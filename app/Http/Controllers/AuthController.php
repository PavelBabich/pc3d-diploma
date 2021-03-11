<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function registerStudent(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string',
            'surname' => 'required|string',
            'patronymic' => 'required|string',
            'group' => 'required|string',
            'practice' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:students|unique:teachers|unique:admin',
            'password' => 'required|confirmed',
        ]);

        $groupId = Student::getGroupId($request->input('group'));

        $practiceId = Student::getPracticeId($request->input('practice'));


        try {
            $student = new Student();
            $student->name = $request->input('name');
            $student->surname = $request->input('surname');
            $student->patronymic = $request->input('patronymic');
            $student->id_group = $groupId;
            $student->id_practice = $practiceId;
            $student->phone = $request->input('phone');
            $student->email = $request->input('email');
            $plainPassword = $request->input('password');
            $student->password = app('hash')->make($plainPassword);

            $student->save();

            return response()->json(['user' => $student, 'message' => 'Created'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User Registration Failed'], 409);
        }
    }

    public function registerTeacher(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'surname' => 'required|string',
            'patronymic' => 'required|string',
            'group' => 'required|string',
            'practice' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:students|unique:teachers|unique:admin',
            'password' => 'required|confirmed',
        ]);

        $groupId = Teacher::getGroupId($request->input('group'));

        $practiceId = Teacher::getPracticeId($request->input('practice'));

        try {
            $teacher = new Teacher();
            $teacher->name = $request->input('name');
            $teacher->surname = $request->input('surname');
            $teacher->patronymic = $request->input('patronymic');
            $teacher->id_group = $groupId;
            $teacher->id_practice = $practiceId;
            $teacher->phone = $request->input('phone');
            $teacher->email = $request->input('email');
            $plainPassword = $request->input('password');
            $teacher->password = app('hash')->make($plainPassword);

            $teacher->save();

            return response()->json(['teacher' => $teacher, 'message' => 'Created'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User Registration Failed'], 409);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentails = $request->only(['email', 'password']);


        $token = Auth::shouldUse('admin');
        $token = Auth::attempt($credentails);
        $role = 'admin';

        if (!$token) {

            $token = Auth::shouldUse('student');
            $token = Auth::attempt($credentails);
            $role = 'student';

            if (!$token) {
                $token = Auth::shouldUse('teacher');
                $token = Auth::attempt($credentails);
                $role = 'teacher';

                if (!$token) {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            }
        }

        return $this->respondWithToken($token, $role);
    }

    public function logout()
    {
        Auth::guard('student')->logout(true);

        return response()->json(['message' => 'Successfully logged out']);
    }
}
