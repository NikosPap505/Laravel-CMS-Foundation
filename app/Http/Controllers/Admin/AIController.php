<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AI\AIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class AIController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display AI assistant dashboard.
     */
    public function index()
    {
        $aiAvailable = $this->aiService->isAvailable();
        $features = $aiAvailable ? $this->aiService->getAvailableFeatures() : [];
        $usageStats = $aiAvailable ? $this->aiService->getUsageStats() : [];
        
        return view('admin.ai.index', compact('aiAvailable', 'features', 'usageStats'));
    }

    /**
     * Generate blog post content.
     */
    public function generateBlogPost(Request $request): JsonResponse
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'tone' => 'sometimes|string|in:professional,casual,friendly,authoritative,conversational,technical,creative',
            'word_count' => 'sometimes|integer|min:100|max:3000',
            'target_audience' => 'sometimes|string|max:255',
        ]);

        try {
            if (!$this->aiService->isAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service is not available. Please check your configuration.'
                ], 503);
            }

            $options = [
                'tone' => $request->get('tone', 'professional'),
                'word_count' => $request->get('word_count', 800),
                'target_audience' => $request->get('target_audience', 'blog readers'),
            ];

            $result = $this->aiService->generateBlogPost($request->topic, $options);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Blog post generated successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate blog post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate meta description.
     */
    public function generateMetaDescription(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'sometimes|string',
            'content_type' => 'sometimes|string|in:article,product,page,blog_post',
        ]);

        try {
            if (!$this->aiService->isAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service is not available.'
                ], 503);
            }

            $options = [
                'content_type' => $request->get('content_type', 'article'),
                'max_length' => 155,
            ];

            $metaDescription = $this->aiService->generateMetaDescription(
                $request->title,
                $request->get('content', ''),
                $options
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'meta_description' => $metaDescription,
                    'character_count' => strlen($metaDescription)
                ],
                'message' => 'Meta description generated successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate meta description: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate title suggestions.
     */
    public function generateTitles(Request $request): JsonResponse
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'count' => 'sometimes|integer|min:1|max:10',
            'tone' => 'sometimes|string|in:professional,casual,friendly,authoritative,conversational,technical,creative',
        ]);

        try {
            if (!$this->aiService->isAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service is not available.'
                ], 503);
            }

            $options = [
                'count' => $request->get('count', 5),
                'tone' => $request->get('tone', 'engaging'),
            ];

            $titles = $this->aiService->generateTitleSuggestions($request->topic, $options);

            return response()->json([
                'success' => true,
                'data' => [
                    'titles' => $titles,
                    'count' => count($titles)
                ],
                'message' => 'Title suggestions generated successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate titles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate tags for content.
     */
    public function generateTags(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
            'max_tags' => 'sometimes|integer|min:1|max:20',
        ]);

        try {
            if (!$this->aiService->isAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service is not available.'
                ], 503);
            }

            $options = [
                'max_tags' => $request->get('max_tags', 8),
            ];

            $tags = $this->aiService->generateTags($request->content, $options);

            return response()->json([
                'success' => true,
                'data' => [
                    'tags' => $tags,
                    'count' => count($tags)
                ],
                'message' => 'Tags generated successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate tags: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Improve existing content.
     */
    public function improveContent(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
            'focus' => 'sometimes|string|in:grammar,seo,readability,all',
        ]);

        try {
            if (!$this->aiService->isAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service is not available.'
                ], 503);
            }

            $options = [
                'focus' => $request->get('focus', 'all'),
            ];

            $improvements = $this->aiService->improveContent($request->content, $options);

            return response()->json([
                'success' => true,
                'data' => $improvements,
                'message' => 'Content improvement suggestions generated!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to improve content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyze content for insights.
     */
    public function analyzeContent(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        try {
            if (!$this->aiService->isAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service is not available.'
                ], 503);
            }

            $analysis = $this->aiService->analyzeContent($request->content);

            return response()->json([
                'success' => true,
                'data' => $analysis,
                'message' => 'Content analysis completed!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate social media post.
     */
    public function generateSocialPost(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
            'platform' => 'required|string|in:twitter,facebook,linkedin,instagram',
            'tone' => 'sometimes|string|in:professional,casual,friendly,engaging,promotional',
        ]);

        try {
            if (!$this->aiService->isAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service is not available.'
                ], 503);
            }

            $options = [
                'tone' => $request->get('tone', 'engaging'),
            ];

            $socialPost = $this->aiService->generateSocialMediaPost(
                $request->content,
                $request->platform,
                $options
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'post' => $socialPost,
                    'platform' => $request->platform,
                    'character_count' => strlen($socialPost)
                ],
                'message' => 'Social media post generated successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate social post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get AI service status and configuration.
     */
    public function status(): JsonResponse
    {
        try {
            $available = $this->aiService->isAvailable();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'available' => $available,
                    'features' => $available ? $this->aiService->getAvailableFeatures() : [],
                    'supported_tones' => $available ? $this->aiService->getSupportedTones() : [],
                    'supported_formats' => $available ? $this->aiService->getSupportedFormats() : [],
                    'usage_stats' => $available ? $this->aiService->getUsageStats() : [],
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get AI status: ' . $e->getMessage()
            ], 500);
        }
    }
}
