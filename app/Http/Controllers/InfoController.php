<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InfoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function add(Request $request)
    {
        try {
            $info = new Info();
            $info->description = $request->input('description');

            $info->save();

            return response()->json(['task' => $info, 'message' => 'Created']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ad add failed']);
        }
    }

    public function all()
    {
        return Info::getAds();
    }
}
