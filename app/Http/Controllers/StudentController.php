<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class StudentController extends Controller
{
    private $student;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::guard('student')->user();
    }

    public function profile(Request $requst)
    {

        $this->user->id_group = Student::getGroupName($this->user->id_group);

        $this->user->id_practice = Student::getPracticeName($this->user->id_practice);

        return response()->json(['user' => $this->user], 200);
    }

    public function edit(Request $request)
    {

        $this->validate($request, [
            'phone' => 'required|string',
            'email' => 'required|email',
        ]);

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

            return response()->json(['user' => $this->user, 'message' => 'Created'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Data editing Failed'], 409);
        }
    }
}
