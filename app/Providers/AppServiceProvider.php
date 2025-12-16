<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        Gate::define('access-admin', fn(User $user) => in_array($user->role, ['admin']));
        Gate::define('access-kasir', fn(User $user) => in_array($user->role, ['kasir','admin']));
        Gate::define('access-pemilik', fn(User $user) => in_array($user->role, ['pemilik','admin']));
        Gate::define('access-pelanggan', fn(User $user) => in_array($user->role, ['pelanggan','admin']));
        
        // Ensure storage link exists (prevents 403 errors on uploaded files)
        $this->ensureStorageLinkExists();
    }
    
    /**
     * Ensure the storage symbolic link exists.
     * This prevents issues where public/storage is a regular directory instead of a symlink.
     */
    protected function ensureStorageLinkExists(): void
    {
        $publicStoragePath = public_path('storage');
        $targetPath = storage_path('app/public');
        
        // Skip if running in console (artisan commands)
        if (app()->runningInConsole()) {
            return;
        }
        
        // Check if storage link exists and is valid
        if (file_exists($publicStoragePath)) {
            // If it's a directory (not a symlink), it's broken
            if (is_dir($publicStoragePath) && !is_link($publicStoragePath)) {
                // Log warning - needs manual fix via artisan storage:link
                \Log::warning('Storage link is a regular directory, not a symlink. Run: php artisan storage:link');
            }
        } else {
            // Storage link doesn't exist, try to create it
            try {
                Artisan::call('storage:link');
                \Log::info('Storage link created automatically.');
            } catch (\Exception $e) {
                \Log::error('Failed to create storage link: ' . $e->getMessage());
            }
        }
    }
}
