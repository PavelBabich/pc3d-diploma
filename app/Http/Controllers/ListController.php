<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function studentList(Request $request)
    {
        if (!$request->get('id')) {
            $user = Auth::guard('student')->user();
            if (!$user) {
                $user = Auth::guard('teacher')->user();
            }
            $groupId = $user->id_group;
        } else {
            $groupId = $request->get('id');
        }

        $studentList = Student::getStudentList($groupId);

        $group = Student::getGroupName($groupId);

        return response()->json(['students' => $studentList, 'groupId' => $groupId, 'group' => $group]);
    }

    public function groupList()
    {
        return Student::getGroupList();
    }

    public function deleteGroup(Request $request)
    {
        try {
            Student::deleteGroup($request->input('id'));
            return response()->json(['message' => 'Группа успешно удалена']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Произошла непредвиденная ошибка. Повторите попытку немного позже']);
        }
    }

    public function deleteStudent(Request $request)
    {
        try {
            Student::deleteStudent($request->input('id'));
            return response()->json(['message' => 'Студент успешно удален']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Произошла непредвиденная ошибка. Повторите попытку немного позже']);
        }
    }

    public function deleteTeacher(Request $request)
    {
        try {
            Teacher::deleteTeacher($request->input('id'));
            return response()->json(['message' => 'Преподаватель успешно удален']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Произошла непредвиденная ошибка. Повторите попытку немного позже']);
        }
    }
}
