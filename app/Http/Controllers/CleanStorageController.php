<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CleanStorageController extends Controller
{
    public function __invoke(String $folder)
    {

        if(Storage::disk('local')->exists($folder)) {

            Storage::disk('local')->deleteDirectory($folder);
            
        }
        if(Storage::disk('local')->exists($folder . '.zip')) {

            Storage::disk('local')->delete($folder . '.zip');
        }

        
    }
}
