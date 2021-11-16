<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadsController extends Controller
{
    public function __invoke($file)
    {
        if (file_exists($file)) {
            return response()->download($file, time().'.zip', array('Content-Type: application/octet-stream','Content-Length: '. filesize($file)))->deleteFileAfterSend(true);
        } else {
            return ['status'=>'file does not exist'];
        }
    }
}
