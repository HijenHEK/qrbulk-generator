<?php

namespace App\Http\Controllers;

use App\Jobs\CleanStorage;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

class QrCodeBulkGenerator extends Controller
{
    public function __invoke(Request $request)
    {

        $data = $request->validate([
            'qr_code_names_list' => 'required|array'
        ]);
        
        $options = new QROptions([
            'version'    => 5,
            'outputType' => 'png',
            'eccLevel'   => QRCode::ECC_L,
        ]);
        
        $folder = time() . rand(0 , 1000);
        $path = storage_path('app') .'/'. $folder;
        File::makeDirectory( $path, $mode = 0755, true, true);

        foreach($data['qr_code_names_list'] as $code) {
            
            
            // invoke a fresh QRCode instance
            $qrcode = new QRCode($options);
            
            // and dump the output

            $filename = preg_replace("([^\w\s\d\-_~,;\[\]\(\)])" , "" ,  $code);

            $qrcode->render($code,  $path .'/' . $filename .'.png');
            
        }
        CleanStorage::dispatch($folder)
                    ->delay(now()->addMinutes(10));
        
        return response()->json([
            'folder'=> $folder 
        ],200);
        
        
        
        
    }
}
