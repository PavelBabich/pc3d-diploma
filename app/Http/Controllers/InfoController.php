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

    public function add(Request $request)
    {
        $groupId = Teacher::getGroupId($request->input('group'));

        try {
            $info = new Info();
            $info->description = $request->input('description');
            $info->id_group = $groupId;

            $info->save();

            return response()->json(['task' => $info, 'message' => 'Объявление успешно добавлено']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка создания объявления']);
        }
    }

    public function all()
    {
        $user = Auth::guard('student')->user();
        if (!$user) {
            $user = Auth::guard('teacher')->user();
        }
        $infoList = Info::getAds($user->id_group);
        return $infoList;
    }
}
