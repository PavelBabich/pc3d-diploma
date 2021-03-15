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

    public static function getTask()
    {
        return DB::select('select * from task_view');
    }

    public static function startTask($studentId, $taskId)
    {
        $taskObj = DB::select('select id_task from solution_task where id_user = ?', [$studentId]);
        if (!$taskObj) {
            DB::table('solution_task')->insert(
                ['id_task' => $taskId, 'id_user' => $studentId]
            );
        } else {
            return 'У вас уже есть активное задание';
        }
    }

    public static function getActiveTaks($studentId)
    {
        $taskObj = DB::select('select id_task from solution_task where id_user = ?', [$studentId]);
        foreach ($taskObj as $task) {
            return $task->id_task;
        }
    }

    public static function sendPath($path, $studentId){
        DB::table('solution_task')->where('id_user', $studentId)->update(
            ['path_to_file' => $path]
        );
    }
}
