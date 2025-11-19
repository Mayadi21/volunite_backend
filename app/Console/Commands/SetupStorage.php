<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SetupStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create necessary storage directories and then run storage:link';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up storage directories...');

        $sourcePath = public_path('initial_assets');
        $destinationPath = storage_path('app/public');

        if (File::isDirectory($sourcePath)) {
            $this->comment('Menyalin assets dari initial_assets ke storage/app/public...');
            File::copyDirectory($sourcePath, $destinationPath);

            $this->info('Penyalinan assets selesai.');
        } else {
            $this->error('Direktori initial_assets tidak ditemukan. Pastikan ada.');
            return 1;
        }

        $this->comment('Membuat symbolic link public/storage...');
        Artisan::call('storage:link', [], $this->output);
        $this->info('Setup Storage Berhasil! File default sudah siap diakses.');
        return 0; 
    }
}
