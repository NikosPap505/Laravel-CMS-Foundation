<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwitterIntegration extends BaseIntegration
{
    protected string $name = 'twitter';
    protected string $description = 'Connect to Twitter for social media management and auto-posting';
    protected string $category = 'social';
    protected array $requiredFields = [
        'api_key' => [
            'type' => 'password',
            'label' => 'API Key',
            'description' => 'Your Twitter API Key',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'api_secret' => [
            'type' => 'password',
            'label' => 'API Secret',
            'description' => 'Your Twitter API Secret',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'access_token' => [
            'type' => 'password',
            'label' => 'Access Token',
            'description' => 'Your Twitter Access Token',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'access_token_secret' => [
            'type' => 'password',
            'label' => 'Access Token Secret',
            'description' => 'Your Twitter Access Token Secret',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'bearer_token' => [
            'type' => 'password',
            'label' => 'Bearer Token',
            'description' => 'Your Twitter Bearer Token (for API v2)',
            'placeholder' => 'AAAAAAAAAAAAAAAAAAAAAF7opgEAAAAA0%2BuSeid%2BULvsea4JtiGRiSDSJSI%3DEUifiRBkKG5E2XzMDjRfl76ZC9Ub0wnz4XsNiRVBChTYbJcE3F',
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://api.twitter.com/2';
    protected string $baseUrlV1 = 'https://api.twitter.com/1.1';

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }

    public function getIcon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="4" fill="#1DA1F2"/>
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" fill="white"/>
        </svg>';
    }

    public function testConnection(array $config): bool
    {
        try {
            $bearerToken = $config['bearer_token'] ?? $this->config['bearer_token'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $bearerToken
            ])->get("{$this->baseUrl}/users/me");

            if ($response->successful()) {
                $userData = $response->json();
                $this->log('info', 'Connection test successful', ['username' => $userData['data']['username']]);
                return true;
            }

            $this->log('error', 'Connection test failed', ['status' => $response->status(), 'body' => $response->body()]);
            return false;
        } catch (\Exception $e) {
            $this->log('error', 'Connection test exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function connect(array $config): array
    {
        $errors = $this->validateConfig($config);
        if (!empty($errors)) {
            throw new \Exception('Configuration validation failed: ' . implode(', ', $errors));
        }

        if (!$this->testConnection($config)) {
            throw new \Exception('Failed to connect to Twitter');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        // Get user information
        $userInfo = $this->getUserInfo();

        $this->log('info', 'Successfully connected to Twitter', ['username' => $userInfo['username']]);

        return [
            'user' => $userInfo,
            'sync_status' => 'ready'
        ];
    }

    public function disconnect(): bool
    {
        try {
            $this->connected = false;
            $this->config = [];
            $this->saveConfiguration();
            $this->clearCache();

            $this->log('info', 'Disconnected from Twitter');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Twitter', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'post_tweet',
            'get_tweets',
            'get_followers',
            'get_following',
            'get_mentions',
            'get_analytics',
            'schedule_tweet'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'post_tweet':
                return $this->postTweet($data);
            case 'get_tweets':
                return $this->getTweets($data);
            case 'get_followers':
                return $this->getFollowers($data);
            case 'get_following':
                return $this->getFollowing($data);
            case 'get_mentions':
                return $this->getMentions($data);
            case 'get_analytics':
                return $this->getAnalytics();
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            // Get tweets
            if ($options['sync_tweets'] ?? true) {
                $results['tweets'] = $this->getTweets($options);
            }

            // Get followers
            if ($options['sync_followers'] ?? true) {
                $results['followers'] = $this->getFollowers($options);
            }

            // Get mentions
            if ($options['sync_mentions'] ?? true) {
                $results['mentions'] = $this->getMentions($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'tweets' => $results['tweets']['count'] ?? 0,
                'followers' => $results['followers']['count'] ?? 0,
                'mentions' => $results['mentions']['count'] ?? 0
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function getUserInfo(): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['bearer_token']
        ])->get("{$this->baseUrl}/users/me", [
            'user.fields' => 'id,name,username,public_metrics,verified'
        ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Failed to get user information');
    }

    protected function postTweet(array $data): array
    {
        $tweetData = [
            'text' => $data['text']
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['bearer_token'],
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/tweets", $tweetData);

        if ($response->successful()) {
            $tweet = $response->json();
            $this->log('info', 'Tweet posted successfully', ['tweet_id' => $tweet['data']['id']]);
            return $tweet;
        }

        throw new \Exception('Failed to post tweet: ' . $response->body());
    }

    protected function getTweets(array $options = []): array
    {
        $userId = $options['user_id'] ?? $this->getUserInfo()['id'];
        $maxResults = $options['max_results'] ?? 10;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['bearer_token']
        ])->get("{$this->baseUrl}/users/{$userId}/tweets", [
            'max_results' => $maxResults,
            'tweet.fields' => 'created_at,public_metrics,context_annotations'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('tweets', $data, 900); // Cache for 15 minutes
            return [
                'count' => count($data['data'] ?? []),
                'tweets' => $data['data'] ?? []
            ];
        }

        throw new \Exception('Failed to get tweets: ' . $response->body());
    }

    protected function getFollowers(array $options = []): array
    {
        $userId = $options['user_id'] ?? $this->getUserInfo()['id'];
        $maxResults = $options['max_results'] ?? 100;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['bearer_token']
        ])->get("{$this->baseUrl}/users/{$userId}/followers", [
            'max_results' => $maxResults,
            'user.fields' => 'id,name,username,verified,public_metrics'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('followers', $data, 1800); // Cache for 30 minutes
            return [
                'count' => count($data['data'] ?? []),
                'followers' => $data['data'] ?? []
            ];
        }

        throw new \Exception('Failed to get followers: ' . $response->body());
    }

    protected function getFollowing(array $options = []): array
    {
        $userId = $options['user_id'] ?? $this->getUserInfo()['id'];
        $maxResults = $options['max_results'] ?? 100;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['bearer_token']
        ])->get("{$this->baseUrl}/users/{$userId}/following", [
            'max_results' => $maxResults,
            'user.fields' => 'id,name,username,verified,public_metrics'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('following', $data, 1800);
            return [
                'count' => count($data['data'] ?? []),
                'following' => $data['data'] ?? []
            ];
        }

        throw new \Exception('Failed to get following: ' . $response->body());
    }

    protected function getMentions(array $options = []): array
    {
        $userId = $options['user_id'] ?? $this->getUserInfo()['id'];
        $maxResults = $options['max_results'] ?? 10;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['bearer_token']
        ])->get("{$this->baseUrl}/users/{$userId}/mentions", [
            'max_results' => $maxResults,
            'tweet.fields' => 'created_at,public_metrics,author_id'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('mentions', $data, 600); // Cache for 10 minutes
            return [
                'count' => count($data['data'] ?? []),
                'mentions' => $data['data'] ?? []
            ];
        }

        throw new \Exception('Failed to get mentions: ' . $response->body());
    }

    public function getAnalytics(): array
    {
        $analytics = [
            'followers_count' => $this->getCached('followers_count', 0),
            'following_count' => $this->getCached('following_count', 0),
            'tweets_count' => $this->getCached('tweets_count', 0),
            'mentions_count' => $this->getCached('mentions_count', 0),
            'last_sync' => $this->getCached('last_sync')
        ];

        return $analytics;
    }

    /**
     * Auto-post content to Twitter
     */
    public function autoPost(array $content): array
    {
        try {
            $tweetText = $this->formatContentForTwitter($content);

            if (strlen($tweetText) > 280) {
                $tweetText = substr($tweetText, 0, 277) . '...';
            }

            return $this->postTweet(['text' => $tweetText]);
        } catch (\Exception $e) {
            $this->log('error', 'Auto-post failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function formatContentForTwitter(array $content): string
    {
        $text = $content['title'] ?? '';

        if (isset($content['excerpt'])) {
            $text .= "\n\n" . $content['excerpt'];
        }

        if (isset($content['url'])) {
            $text .= "\n\n" . $content['url'];
        }

        if (isset($content['hashtags'])) {
            $text .= "\n\n" . implode(' ', array_map(fn($tag) => '#' . $tag, $content['hashtags']));
        }

        return $text;
    }
}
