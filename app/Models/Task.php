<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    public static function getTypeId($taskType)
    {
        $taskObj = DB::select('select id from task_type where type_name = ?', [$taskType]);
        foreach ($taskObj as $task) {
            return $task->id;
        }
    }

    public static function getTaskList($groupId)
    {
        return DB::select('select * from task_view where id_group = ?', [$groupId]);
    }

    public static function startTask($studentId, $taskId, $path)
    {
        $taskObj = DB::select('select id_task from solution_task where id_student = ?', [$studentId]);
        if (!$taskObj) {
            DB::table('solution_task')->insert(
                ['id_task' => $taskId, 'id_student' => $studentId, 'path_to_file' => $path]
            );
        } else {
            return 'У вас уже есть активное задание';
        }
    }

    public static function getActiveTask($studentId)
    {
        return DB::select('select * from task_view where id = (select id_task from solution_task where id_student = ?)', [$studentId]);
    }

    public static function getFilePath($studentId)
    {
        $taskObj = DB::select('select path_to_file from solution_task where id_student = ?', [$studentId]);
        foreach ($taskObj as $task) {
            return $task->path_to_file;
        }
    }

    public static function deleteAnswer($studentId){
        DB::table('solution_task')->where('id_student', $studentId)->delete();
    }
}
