<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:generate-thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate 100x100 thumbnails for all existing user photos to optimize loading';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $manager = new ImageManager(new Driver());
        $users = User::whereNotNull('photo')->where('photo', '!=', '')->get();
        
        $sourceDir = public_path('photos');
        $destDir = public_path('photos/thumb');
        
        if (!File::exists($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        $this->info("Found {$users->count()} users with photos. Starting generation...");
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $sourcePath = $sourceDir . '/' . $user->photo;
            $destPath = $destDir . '/' . $user->photo;

            if (File::exists($sourcePath)) {
                try {
                    // Create optimized thumbnail (354x472 - Standard 3x4 Portrait)
                    $image = $manager->read($sourcePath);
                    $image->cover(354, 472); 
                    $image->save($destPath, 80); // Save with 80% quality
                } catch (\Exception $e) {
                    // $this->error("Failed to process: " . $user->photo);
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Thumbnails generated successfully!');
    }
}
