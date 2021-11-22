<?php 

namespace App\Http\Controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

class ZipController extends Controller
{

    public function __invoke(String $folder)
    {

        
        $zip_file = $folder . '.zip';
        $path = storage_path('app') .'/'. $folder;
        
        $zip = new \ZipArchive();
        $zip->open(storage_path( 'app/' .  $zip_file) , \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();

                // extracting filename with substr/strlen
                $relativePath = substr($filePath, strlen($path) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }
        
        $zip->close();
        if(Storage::disk('local')->exists($zip_file)){
            Storage::disk('local')->deleteDirectory($folder);
            return response(['zip'=> $zip_file] , 200);
        }
        return response()->json(['message'=>'error please try again'], 500);

        
    }
}
