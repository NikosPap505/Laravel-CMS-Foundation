<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsIntegration extends BaseIntegration
{
    protected string $name = 'google_analytics';
    protected string $description = 'Connect to Google Analytics 4 for website traffic and user behavior insights';
    protected string $category = 'analytics';
    protected array $requiredFields = [
        'property_id' => [
            'type' => 'text',
            'label' => 'Property ID',
            'description' => 'Your GA4 Property ID (e.g., 123456789)',
            'placeholder' => '123456789',
            'required' => true
        ],
        'credentials_json' => [
            'type' => 'textarea',
            'label' => 'Service Account Credentials',
            'description' => 'Service account credentials JSON (download from Google Cloud Console)',
            'placeholder' => '{"type": "service_account", "project_id": "...", ...}',
            'rows' => 8,
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://analyticsdata.googleapis.com/v1beta';

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

    public function testConnection(array $config): bool
    {
        try {
            $propertyId = $config['property_id'] ?? $this->config['property_id'];
            $credentials = $config['credentials_json'] ?? $this->config['credentials_json'];

            // Get access token
            $accessToken = $this->getAccessToken($credentials);

            if (!$accessToken) {
                return false;
            }

            // Test with a simple report request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post("{$this->baseUrl}/properties/{$propertyId}:runReport", [
                'dateRanges' => [
                    ['startDate' => '7daysAgo', 'endDate' => 'today']
                ],
                'dimensions' => [
                    ['name' => 'date']
                ],
                'metrics' => [
                    ['name' => 'sessions']
                ]
            ]);

            if ($response->successful()) {
                $this->log('info', 'Connection test successful');
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
            throw new \Exception('Failed to connect to Google Analytics');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        // Get property information
        $propertyInfo = $this->getPropertyInfo();

        $this->log('info', 'Successfully connected to Google Analytics', ['property' => $propertyInfo['displayName']]);

        return [
            'property' => $propertyInfo,
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

            $this->log('info', 'Disconnected from Google Analytics');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Google Analytics', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'get_audience_report',
            'get_traffic_report',
            'get_conversion_report',
            'get_device_report',
            'get_geographic_report',
            'get_real_time_data'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'get_audience_report':
                return $this->getAudienceReport($data);
            case 'get_traffic_report':
                return $this->getTrafficReport($data);
            case 'get_conversion_report':
                return $this->getConversionReport($data);
            case 'get_device_report':
                return $this->getDeviceReport($data);
            case 'get_real_time_data':
                return $this->getRealTimeData($data);
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            // Get various reports
            if ($options['sync_traffic'] ?? true) {
                $results['traffic'] = $this->getTrafficReport($options);
            }

            if ($options['sync_audience'] ?? true) {
                $results['audience'] = $this->getAudienceReport($options);
            }

            if ($options['sync_conversions'] ?? true) {
                $results['conversions'] = $this->getConversionReport($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'traffic_data_points' => count($results['traffic']['rows'] ?? []),
                'audience_data_points' => count($results['audience']['rows'] ?? []),
                'conversion_data_points' => count($results['conversions']['rows'] ?? [])
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function getAccessToken(string $credentialsJson): ?string
    {
        try {
            $credentials = json_decode($credentialsJson, true);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $this->createJWT($credentials)
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            return null;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to get access token', ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function createJWT(array $credentials): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
        $now = time();
        $payload = json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600
        ]);

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = '';
        openssl_sign($base64Header . '.' . $base64Payload, $signature, $credentials['private_key'], 'SHA256');
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    protected function getPropertyInfo(): array
    {
        $accessToken = $this->getAccessToken($this->config['credentials_json']);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->get("https://analyticsadmin.googleapis.com/v1beta/properties/{$this->config['property_id']}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get property information');
    }

    protected function getTrafficReport(array $options = []): array
    {
        $accessToken = $this->getAccessToken($this->config['credentials_json']);
        $propertyId = $this->config['property_id'];

        $dateRange = $options['date_range'] ?? ['startDate' => '7daysAgo', 'endDate' => 'today'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/properties/{$propertyId}:runReport", [
            'dateRanges' => [$dateRange],
            'dimensions' => [
                ['name' => 'date'],
                ['name' => 'country']
            ],
            'metrics' => [
                ['name' => 'sessions'],
                ['name' => 'users'],
                ['name' => 'pageviews'],
                ['name' => 'bounceRate']
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('traffic_report', $data, 1800); // Cache for 30 minutes
            return $data;
        }

        throw new \Exception('Failed to get traffic report: ' . $response->body());
    }

    protected function getAudienceReport(array $options = []): array
    {
        $accessToken = $this->getAccessToken($this->config['credentials_json']);
        $propertyId = $this->config['property_id'];

        $dateRange = $options['date_range'] ?? ['startDate' => '7daysAgo', 'endDate' => 'today'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/properties/{$propertyId}:runReport", [
            'dateRanges' => [$dateRange],
            'dimensions' => [
                ['name' => 'age'],
                ['name' => 'gender'],
                ['name' => 'userType']
            ],
            'metrics' => [
                ['name' => 'activeUsers'],
                ['name' => 'newUsers'],
                ['name' => 'sessions']
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('audience_report', $data, 3600);
            return $data;
        }

        throw new \Exception('Failed to get audience report: ' . $response->body());
    }

    protected function getConversionReport(array $options = []): array
    {
        $accessToken = $this->getAccessToken($this->config['credentials_json']);
        $propertyId = $this->config['property_id'];

        $dateRange = $options['date_range'] ?? ['startDate' => '7daysAgo', 'endDate' => 'today'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/properties/{$propertyId}:runReport", [
            'dateRanges' => [$dateRange],
            'dimensions' => [
                ['name' => 'eventName']
            ],
            'metrics' => [
                ['name' => 'eventCount'],
                ['name' => 'conversions']
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('conversion_report', $data, 1800);
            return $data;
        }

        throw new \Exception('Failed to get conversion report: ' . $response->body());
    }

    protected function getDeviceReport(array $options = []): array
    {
        $accessToken = $this->getAccessToken($this->config['credentials_json']);
        $propertyId = $this->config['property_id'];

        $dateRange = $options['date_range'] ?? ['startDate' => '7daysAgo', 'endDate' => 'today'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/properties/{$propertyId}:runReport", [
            'dateRanges' => [$dateRange],
            'dimensions' => [
                ['name' => 'deviceCategory'],
                ['name' => 'operatingSystem'],
                ['name' => 'browser']
            ],
            'metrics' => [
                ['name' => 'sessions'],
                ['name' => 'users']
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('device_report', $data, 3600);
            return $data;
        }

        throw new \Exception('Failed to get device report: ' . $response->body());
    }

    protected function getRealTimeData(array $options = []): array
    {
        $accessToken = $this->getAccessToken($this->config['credentials_json']);
        $propertyId = $this->config['property_id'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/properties/{$propertyId}:runRealtimeReport", [
            'dimensions' => [
                ['name' => 'country'],
                ['name' => 'deviceCategory']
            ],
            'metrics' => [
                ['name' => 'activeUsers']
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->cache('realtime_data', $data, 60); // Cache for 1 minute
            return $data;
        }

        throw new \Exception('Failed to get real-time data: ' . $response->body());
    }
}
