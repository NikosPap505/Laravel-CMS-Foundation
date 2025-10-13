<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class SyncPermissionsFromDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docs:sync {--assign-admin : Assign admin role to ADMIN_EMAIL user if found}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan Markdown docs for permissions and sync them into the database (Spatie)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Scanning Markdown files for permissions...');

        $base = base_path();
        $paths = [$base];

        $mdFiles = [];
        foreach ($paths as $path) {
            foreach (File::allFiles($path) as $file) {
                $ext = strtolower($file->getExtension());
                // Only scan Markdown files in project root (and not vendor/node_modules/storage). Keep it safe.
                if ($ext === 'md') {
                    $relative = str_replace($base . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    if (preg_match('#^(vendor|node_modules|storage|bootstrap|public|tests)/#', $relative)) {
                        continue;
                    }
                    $mdFiles[] = $file->getPathname();
                }
            }
        }

        if (empty($mdFiles)) {
            $this->warn('No Markdown files found. Nothing to do.');
            return Command::SUCCESS;
        }

        $found = [];
        foreach ($mdFiles as $file) {
            try {
                $contents = File::get($file);
            } catch (\Throwable $e) {
                $this->warn("Could not read: {$file}");
                continue;
            }

            // Extract inline code segments `...`
            if (preg_match_all('/`([^`]+)`/u', $contents, $matches)) {
                foreach ($matches[1] as $snippet) {
                    $slug = trim($snippet);
                    // We only care about permission-like strings (heuristic)
                    if (preg_match('/^(manage|view|create|edit|delete)\s+[a-z0-9 _-]+$/i', $slug)) {
                        $found[] = strtolower($slug);
                    }
                }
            }

            // Also look for bullet lists with backticks optional: - manage posts
            if (preg_match_all('/^-\s+([a-z][a-z ]+)$/im', $contents, $bullets)) {
                foreach ($bullets[1] as $b) {
                    $b = trim($b);
                    if (preg_match('/^(manage|view|create|edit|delete)\s+[a-z0-9 _-]+$/i', $b)) {
                        $found[] = strtolower($b);
                    }
                }
            }
        }

        // Ensure known defaults from our docs if parsing fails to find any
        $defaults = [
            'manage pages',
            'manage menus',
            'manage posts',
            'manage categories',
            'manage users',
        ];

        $unique = array_values(array_unique(array_filter($found)));
        if (count($unique) === 0) {
            $this->warn('No permission slugs detected in docs; using defaults from PERMISSION_GUIDE.md');
            $unique = $defaults;
        }

        // Create permissions
        $this->info('Creating/updating permissions...');
        foreach ($unique as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
            $this->line("  ✓ {$perm}");
        }

        // Roles from docs: admin (all), editor (subset)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
        $this->line('  ✓ Admin role synced');

        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        // Editor gets a safe subset (use whatever exists among unique/defaults)
        $editorAllowed = array_intersect($unique, ['manage posts', 'manage categories', 'manage pages']);
        if (empty($editorAllowed)) {
            $editorAllowed = ['manage posts', 'manage categories', 'manage pages'];
        }
        $editorRole->syncPermissions($editorAllowed);
        $this->line('  ✓ Editor role synced');

        if ($this->option('assign-admin')) {
            $adminEmail = config('app.admin_email');
            if ($adminEmail) {
                $user = User::where('email', $adminEmail)->first();
                if ($user && !$user->hasRole('admin')) {
                    $user->assignRole('admin');
                    $this->line("  ✓ Assigned admin role to {$user->email}");
                }
            }
        }

        $this->newLine();
        $this->info('✓ Docs permissions synced successfully.');
        $this->warn('Note: Logout/login may be required to refresh permission cache.');

        return Command::SUCCESS;
    }
}
