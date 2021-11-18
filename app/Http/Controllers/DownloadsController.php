<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function __invoke($file)
    {
        $file = 'app/' . $file ;
        $file = storage_path($file);
        if (file_exists($file)) {
            return response()->download($file , time().'.zip', array('Content-Type: application/octet-stream','Content-Length: '. filesize($file)))->deleteFileAfterSend(true);
        } else {
            return view('errors.404');       
        }
    }
}
