<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ObjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function mother()
    {
        $type = 'text/plain';
        $headers = ['Content-Type' => $type];
        $path = 'objects/Motherboard/Motherboard.mtl';

        $response = new BinaryFileResponse($path, 200, $headers);

        return $response;
    }

    public function body()
    {
        $type = 'text/plain';
        $headers = ['Content-Type' => $type];
        $path = 'objects/Body/Body.mtl';

        $response = new BinaryFileResponse($path, 200, $headers);

        return $response;
    }
}
