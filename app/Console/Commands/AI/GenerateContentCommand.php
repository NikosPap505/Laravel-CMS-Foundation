<?php

namespace App\Console\Commands\AI;

use App\Services\AI\AIService;
use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Category;
use Exception;

class GenerateContentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:generate 
                            {type : Content type (blog, meta, tags, title)}
                            {--topic= : Topic for content generation}
                            {--title= : Title for meta description generation}
                            {--content= : Content for tag/meta generation}
                            {--tone=professional : Content tone}
                            {--word-count=800 : Word count for blog posts}
                            {--count=5 : Number of suggestions to generate}
                            {--save : Save generated blog post to database}
                            {--category= : Category for saved blog post}
                            {--status=draft : Status for saved blog post}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI-powered content (blog posts, meta descriptions, tags, titles)';

    protected AIService $aiService;

    /**
     * Create a new command instance.
     */
    public function __construct(AIService $aiService)
    {
        parent::__construct();
        $this->aiService = $aiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->aiService->isAvailable()) {
            $this->error('AI service is not available. Please check your configuration.');
            return Command::FAILURE;
        }

        $type = $this->argument('type');

        try {
            switch ($type) {
                case 'blog':
                    return $this->generateBlogPost();
                
                case 'meta':
                    return $this->generateMetaDescription();
                
                case 'tags':
                    return $this->generateTags();
                
                case 'title':
                    return $this->generateTitles();
                
                default:
                    $this->error("Unknown content type: {$type}");
                    $this->line("Available types: blog, meta, tags, title");
                    return Command::FAILURE;
            }
        } catch (Exception $e) {
            $this->error('AI generation failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Generate a blog post.
     */
    protected function generateBlogPost(): int
    {
        $topic = $this->option('topic');
        if (!$topic) {
            $topic = $this->ask('What topic should the blog post be about?');
        }

        if (!$topic) {
            $this->error('Topic is required for blog post generation.');
            return Command::FAILURE;
        }

        $this->info("Generating blog post about: {$topic}");
        $this->line('This may take a moment...');

        $options = [
            'tone' => $this->option('tone'),
            'word_count' => (int) $this->option('word-count'),
            'target_audience' => 'blog readers',
        ];

        $result = $this->aiService->generateBlogPost($topic, $options);

        // Display the generated content
        $this->newLine();
        $this->line('<fg=green>═══════════════════════════════════════════════════════════════════════════════</>');
        $this->line('<fg=green>                                 GENERATED BLOG POST</>');
        $this->line('<fg=green>═══════════════════════════════════════════════════════════════════════════════</>');
        $this->newLine();
        
        $this->line('<fg=cyan>Title:</>');
        $this->line($result['title']);
        $this->newLine();
        
        $this->line('<fg=cyan>Excerpt:</>');
        $this->line($result['excerpt']);
        $this->newLine();
        
        $this->line('<fg=cyan>Meta Description:</>');
        $this->line($result['meta_description']);
        $this->newLine();
        
        $this->line('<fg=cyan>Tags:</>');
        $this->line(implode(', ', $result['tags']));
        $this->newLine();
        
        $this->line('<fg=cyan>Content:</>');
        $this->line($result['content']);
        $this->newLine();
        
        $this->line('<fg=green>═══════════════════════════════════════════════════════════════════════════════</>');

        // Ask if user wants to save the post
        if ($this->option('save') || $this->confirm('Would you like to save this blog post to the database?', true)) {
            return $this->saveBlogPost($result);
        }

        return Command::SUCCESS;
    }

    /**
     * Generate meta description.
     */
    protected function generateMetaDescription(): int
    {
        $title = $this->option('title');
        $content = $this->option('content');

        if (!$title) {
            $title = $this->ask('Enter the title for meta description generation:');
        }

        if (!$title) {
            $this->error('Title is required for meta description generation.');
            return Command::FAILURE;
        }

        $this->info("Generating meta description for: {$title}");

        $metaDescription = $this->aiService->generateMetaDescription($title, $content);

        $this->newLine();
        $this->line('<fg=green>Generated Meta Description:</>');
        $this->line($metaDescription);
        $this->newLine();
        $this->line('<fg=yellow>Character count: ' . strlen($metaDescription) . '/155</>');

        return Command::SUCCESS;
    }

    /**
     * Generate tags.
     */
    protected function generateTags(): int
    {
        $content = $this->option('content');

        if (!$content) {
            $content = $this->ask('Enter content for tag generation (or file path):');
        }

        if (!$content) {
            $this->error('Content is required for tag generation.');
            return Command::FAILURE;
        }

        // Check if content is a file path
        if (file_exists($content)) {
            $content = file_get_contents($content);
        }

        $this->info('Generating tags for provided content...');

        $options = [
            'max_tags' => (int) $this->option('count'),
        ];

        $tags = $this->aiService->generateTags($content, $options);

        $this->newLine();
        $this->line('<fg=green>Generated Tags:</>');
        foreach ($tags as $tag) {
            $this->line("• {$tag}");
        }

        return Command::SUCCESS;
    }

    /**
     * Generate title suggestions.
     */
    protected function generateTitles(): int
    {
        $topic = $this->option('topic');

        if (!$topic) {
            $topic = $this->ask('Enter topic for title generation:');
        }

        if (!$topic) {
            $this->error('Topic is required for title generation.');
            return Command::FAILURE;
        }

        $this->info("Generating title suggestions for: {$topic}");

        $options = [
            'count' => (int) $this->option('count'),
            'tone' => $this->option('tone'),
        ];

        $titles = $this->aiService->generateTitleSuggestions($topic, $options);

        $this->newLine();
        $this->line('<fg=green>Generated Title Suggestions:</>');
        foreach ($titles as $index => $title) {
            $this->line(($index + 1) . ". {$title}");
        }

        return Command::SUCCESS;
    }

    /**
     * Save generated blog post to database.
     */
    protected function saveBlogPost(array $postData): int
    {
        try {
            $categoryId = $this->getCategoryId();
            $status = $this->option('status') ?? 'draft';

            $post = Post::create([
                'title' => $postData['title'],
                'slug' => \Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'body' => $postData['content'],
                'meta_description' => $postData['meta_description'],
                'category_id' => $categoryId,
                'status' => $status,
                'published_at' => $status === 'published' ? now() : null,
                'user_id' => 1, // Assuming admin user
            ]);

            // Sync tags if they exist
            if (!empty($postData['tags'])) {
                $tagIds = [];
                foreach ($postData['tags'] as $tagName) {
                    $tag = \App\Models\Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
                $post->tags()->sync($tagIds);
            }

            $this->info("Blog post saved successfully with ID: {$post->id}");
            $this->line("Status: {$post->status}");
            $this->line("URL: /blog/{$post->slug}");

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error('Failed to save blog post: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Get category ID for the blog post.
     */
    protected function getCategoryId(): ?int
    {
        $categoryName = $this->option('category');

        if (!$categoryName) {
            $categories = Category::pluck('name', 'id')->toArray();
            
            if (empty($categories)) {
                $this->warn('No categories found. Creating default category.');
                $category = Category::create([
                    'name' => 'General',
                    'slug' => 'general',
                ]);
                return $category->id;
            }

            $this->line('Available categories:');
            foreach ($categories as $id => $name) {
                $this->line("  {$id}. {$name}");
            }

            $categoryId = $this->ask('Select category ID (or press enter for first available)');
            
            if (!$categoryId) {
                return array_key_first($categories);
            }

            if (isset($categories[$categoryId])) {
                return (int) $categoryId;
            }
        }

        // Find or create category by name
        $category = Category::where('name', $categoryName)->first();
        if (!$category) {
            $category = Category::create([
                'name' => $categoryName,
                'slug' => \Str::slug($categoryName),
            ]);
            $this->info("Created new category: {$categoryName}");
        }

        return $category->id;
    }
}
