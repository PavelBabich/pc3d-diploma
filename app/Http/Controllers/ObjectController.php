<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Models\CPU;
use App\Models\ComputerCase;
use App\Models\GraphicsCard;
use App\Models\RAM;
use App\Models\PowerSupply;
use App\Models\Motherboard;
use App\Models\Power_supply;

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

    public function cpu()
    {
        return CPU::all();
    }

    public function mother()
    {
        return Motherboard::all();
    }

    public function ram()
    {
        return RAM::all();
    }
    public function graphics()
    {
        return GraphicsCard::all();
    }
    public function powerSupply()
    {
        return PowerSupply::all();
    }
    public function case()
    {
        return ComputerCase::all();
    }

    // public function mother()
    // {
    //     $type = 'text/plain';
    //     $headers = ['Content-Type' => $type];
    //     $path = 'objects/Motherboard/Motherboard.mtl';

    //     $response = new BinaryFileResponse($path, 200, $headers);

    //     return $response;
    // }

    // public function body()
    // {
    //     $type = 'text/plain';
    //     $headers = ['Content-Type' => $type];
    //     $path = 'objects/Body/Body.mtl';

    //     $response = new BinaryFileResponse($path, 200, $headers);

    //     return $response;
    // }
}
