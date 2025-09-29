<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\MenuItem;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Truncate tables
        DB::table('menu_items')->truncate();
        DB::table('posts')->truncate();
        DB::table('pages')->truncate();
        DB::table('categories')->truncate();

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Categories
        $category1 = Category::create([
            'name' => 'Digital Marketing',
            'slug' => 'digital-marketing',
        ]);

        $category2 = Category::create([
            'name' => 'SEO Best Practices',
            'slug' => 'seo-best-practices',
        ]);

        $category3 = Category::create([
            'name' => 'Web Design',
            'slug' => 'web-design',
        ]);

        // Pages
        Page::create([
            'title' => 'About Web-Verse Solutions',
            'slug' => 'about-us',
            'content' => '<h2>Η Αποστολή Μας</h2><p>Στη Web-Verse Solutions, η αποστολή μας είναι να βοηθήσουμε τις επιχειρήσεις να πλοηγηθούν στον συνεχώς μεταβαλλόμενο ψηφιακό κόσμο. Συνδυάζουμε τη δημιουργικότητα με την τεχνολογία για να προσφέρουμε μετρήσιμα αποτελέσματα που οδηγούν στην ανάπτυξη.</p><h3>Η Ομάδα Μας</h3><p>Αποτελούμαστε από μια ομάδα παθιασμένων ειδικών στο marketing, το design και την ανάπτυξη. Είμαστε εδώ για να μετατρέψουμε τις ιδέες σας σε ψηφιακές επιτυχίες.</p><ul><li>Στρατηγική & Ανάλυση</li><li>Δημιουργικός Σχεδιασμός</li><li>Τεχνική Υλοποίηση</li></ul>',
            'meta_title' => 'About Us | Web-Verse Solutions',
            'meta_description' => 'Learn about the mission and team behind Web-Verse Solutions, a leading digital marketing agency dedicated to your success.',
        ]);

        Page::create([
            'title' => 'Our Digital Marketing Services',
            'slug' => 'services',
            'content' => '<h2>Τι Προσφέρουμε</h2><p>Παρέχουμε ένα ολοκληρωμένο πακέτο ψηφιακών υπηρεσιών, σχεδιασμένο για να καλύψει κάθε ανάγκη της επιχείρησής σας.</p><h3>Search Engine Optimization (SEO)</h3><p>Βελτιστοποιούμε το site σας για να κατακτήσετε τις πρώτες θέσεις στα αποτελέσματα της Google και να αυξήσετε την οργανική σας επισκεψιμότητα.</p><h3>Web Design & Development</h3><p>Σχεδιάζουμε και κατασκευάζουμε μοντέρνα, γρήγορα και φιλικά προς τις κινητές συσκευές websites που μετατρέπουν τους επισκέπτες σε πελάτες.</p><h3>Social Media Marketing</h3><p>Δημιουργούμε και διαχειριζόμαστε καμπάνιες στα social media που χτίζουν το brand σας και αυξάνουν την αλληλεπίδραση με το κοινό σας.</p>',
            'meta_title' => 'Digital Marketing Services | Web-Verse Solutions',
            'meta_description' => 'Explore our wide range of digital marketing services, including SEO, Web Design, and Social Media Marketing.',
        ]);

        // Posts
        Post::create([
            'title' => '5 Tips to Improve Your Google Ranking in 2025',
            'slug' => '5-tips-google-ranking-2025',
            'category_id' => $category2->id,
            'featured_image' => 'posts/placeholder.jpg',
            'excerpt' => 'SEO is constantly evolving. In this article, we explore five actionable tips that can help you climb the search engine rankings and attract more organic traffic this year.',
            'body' => '<h3>1. Focus on User Experience (UX)</h3><p>Google\'s algorithm increasingly prioritizes websites that offer a great user experience. This means fast loading times, easy navigation, and mobile-friendliness are no longer optional.</p><h3>2. Create High-Quality, Relevant Content</h3><p>Content is still king. Your goal should be to create the best, most comprehensive answer to your user\'s query. Use headings, lists, and images to make your content engaging and easy to read.</p><p><strong>Remember:</strong> Quality over quantity!</p>',
            'meta_title' => '5 Actionable Tips for Better Google Rankings in 2025',
            'meta_description' => 'Discover 5 proven SEO tips, including focusing on user experience and creating quality content, to improve your website\'s ranking on Google.',
        ]);

        Post::create([
            'title' => 'The Ultimate Guide to Social Media Strategy',
            'slug' => 'ultimate-guide-social-media-strategy',
            'category_id' => $category1->id,
            'featured_image' => 'posts/placeholder.jpg',
            'excerpt' => 'A successful social media presence doesn\'t happen by accident. It requires a well-thought-out strategy. This guide will walk you through the essential steps.',
            'body' => '<h2>Define Your Goals</h2><p>What do you want to achieve with social media? Brand awareness, lead generation, or customer support? Your goals will define your strategy.</p><h2>Know Your Audience</h2><p>Which platforms do your ideal customers use? What kind of content do they engage with? Research is key to connecting with the right people.</p>',
            'meta_title' => 'The Ultimate Guide to Creating a Social Media Strategy',
            'meta_description' => 'Learn how to build a successful social media strategy from scratch. Define your goals, understand your audience, and create engaging content.',
        ]);

        // Menu Items
        MenuItem::create(['name' => 'Home', 'link' => '/', 'order' => 1]);
        MenuItem::create(['name' => 'About Us', 'link' => '/about-us', 'order' => 2]);
        MenuItem::create(['name' => 'Services', 'link' => '/services', 'order' => 3]);
        MenuItem::create(['name' => 'Blog', 'link' => '/blog', 'order' => 4]);
        MenuItem::create(['name' => 'Contact', 'link' => '/contact', 'order' => 5]);
    }
}
