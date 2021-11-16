<?php

namespace App\Http\Controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        $path = storage_path() .'/'. $folder;
        File::makeDirectory( $path, $mode = 0755, true, true);

        foreach($data['qr_code_names_list'] as $code) {
            
            
            // invoke a fresh QRCode instance
            $qrcode = new QRCode($options);
            
            // and dump the output
            $qrcode->render($code,  $path .'/' . $code .'.png');
            
        }

        
    }
}
