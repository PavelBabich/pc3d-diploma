<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;


class TeacherController extends Controller
{
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::guard('teacher')->user();
    }

    public function profile()
    {
        $this->user->id_group = Teacher::getGroupName($this->user->id_group);

        $this->user->id_practice = Teacher::getPracticeName($this->user->id_practice);

        return response()->json(['user' => $this->user], 200);
    }

    public function edit(Request $request)
    {
        try {
            $this->validate($request, [
                'phone' => 'required|string',
                'email' => 'required|email|unique:students|unique:admin',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка изменения данных. Проверьте введенные данные'], 409);
        }

        try {
            $this->user->phone = $request->input('phone');
            $this->user->email = $request->input('email');

            if (!empty($request->input('password'))) {
                $plainPassword = $request->input('password');
                $this->user->password = app('hash')->make($plainPassword);
            }

            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $file = $request->file('photo');
                $destinationPath = 'images';
                $extension = $file->extension();
                $fileName = Str::random(10) . '.' . $extension;
                $file->move($destinationPath, $fileName);
                $path = $destinationPath . '/' . $fileName;
                $this->user->photo = $path;
            }

            $this->user->save();

            return response()->json(['user' => $this->user, 'message' => 'Данные успешно изменены'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка изменения данных'], 409);
        }
    }
}
