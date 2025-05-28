<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ClearStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        File::deleteDirectory(storage_path('app/public'));
        File::makeDirectory(storage_path('app/public'), 0755, true);
        
        $this->info('✅ Todo el contenido de storage/app/public fue eliminado.');
    }
}
