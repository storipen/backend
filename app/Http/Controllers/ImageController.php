<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class ImageController extends Controller
{
    //
    public function image($fileName)
    {
        $uploadimage = env('APP_DIRECTORYIMAGE', 'default_value');
        $path = $uploadimage. $fileName;
        return Response::download($path);
    }
}
