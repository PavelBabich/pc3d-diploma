<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use App\Models\Contacts;

class ContactsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function contactsList()
    {
        try {
            $admin = Admin::all();
            $contacts = Contacts::all();
            $user = Auth::guard('student')->user();
            if ($user) {
                $teacherId = Teacher::getTeacherId($user->id_group);
                $teacher = Teacher::findOrFail($teacherId);
                return response()->json([$admin, $contacts, $teacher]);
            }
            return response()->json([$admin, $contacts]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Возникла непредвиденная ошибка. Повторите попытку немного позже']);
        }
    }
}