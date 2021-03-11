<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function studentList()
    {
        $user = Auth::guard('student')->user();
        if (!$user) {
            $user = Auth::guard('teacher')->user();
        }

        $studentList = Student::getStudentList($user->id_group);

        $group = Student::getGroupName($user->id_group);

        return response()->json(['students' => $studentList, 'group' => $group]);
    }
}
