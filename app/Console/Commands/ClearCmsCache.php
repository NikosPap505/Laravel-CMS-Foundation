<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCmsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all CMS-related cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        clear_cms_cache();
        
        $this->info('CMS cache cleared successfully!');
        
        return 0;
    }
}