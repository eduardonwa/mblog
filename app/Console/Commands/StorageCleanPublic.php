<?php

namespace App\Console\Commands;

use App\Services\MediaRemover;
use Illuminate\Console\Command;

class StorageCleanPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clean-public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Borra todos los archivos y carpetas de storage/app/public';

    /**
     * Execute the console command.
     */
    public function handle(MediaRemover $remover)
    {
        $remover->removeAllPublicStorageFiles();
        $this->info('Archivos y carpetas de storage/app/public eliminados.');
    }
}
