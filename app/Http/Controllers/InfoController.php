<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

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

    public function addInfo(Request $request)
    {
        $user = Auth::guard('teacher')->user();

        try {
            $info = new Info();
            $info->description = $request->input('description');
            $info->id_group = $user->id_group;

            $info->save();

            return response()->json(['task' => $info, 'message' => 'Объявление успешно добавлено']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка создания объявления']);
        }
    }

    public function infoList()
    {
        $user = Auth::guard('student')->user();
        if (!$user) {
            $user = Auth::guard('teacher')->user();
        }
        $infoList = Info::getAdsList($user->id_group);
        return $infoList;
    }

    public function deleteInfo(Request $request){
        try{
            Info::deleteInfo($request->input('id'));
            return response()->json(['message' => 'Объявление успешно удалено']);
        }catch(\Exception $e){
            return response()->json(['message' => 'Произошла непредвиденная ошибка. Повторите попытку немного позже']);
        }
    }
}
