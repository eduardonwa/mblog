<?php

namespace App\Services;

use Illuminate\Filesystem\FilesystemManager;

class MediaRemover
{
    protected FilesystemManager $filesystem;

    public function __construct(FilesystemManager $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
public function removeAllPublicStorageFiles(): void
{
    $disk = $this->filesystem->disk('public');
    $allFiles = $disk->allFiles('/');

    if (empty($allFiles)) {
        dump('No se encontraron archivos para borrar.');
    } else {
        dump('Archivos encontrados:', $allFiles);
    }

    foreach ($allFiles as $file) {
        $deleted = $disk->delete($file);
        dump("Intentando borrar: $file - " . ($deleted ? 'OK' : 'FALLÓ'));
    }
    
    // Opcional: eliminar directorios vacíos
    $allDirs = $disk->allDirectories('/');

    if (empty($allDirs)) {
        dump('No se encontraron directorios para borrar.');
    } else {
        dump('Directorios encontrados:', $allDirs);
    }

    foreach ($allDirs as $dir) {
        $deleted = $disk->deleteDirectory($dir);
        dump("Intentando borrar directorio: $dir - " . ($deleted ? 'OK' : 'FALLÓ'));
    }
}
}