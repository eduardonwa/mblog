<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixStoragePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:fix-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repara permisos de storage/app/public para el usuario actual.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $storagePath = storage_path('app/public');
        $user = get_current_user();

        // cambia permisos recursivamente
        exec("chmod -R 755 {$storagePath}");
        $this->info("Permisos cambiados a 755 para {$storagePath}");
        
        $this->info("Permisos reparados para el usuario: {$storagePath}");
    }
}
