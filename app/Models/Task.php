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
        if (! DB::select('select id_task from solution_task where id_student = ?', [$studentId])) {
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

    public static function deleteAnswer($studentId)
    {
        DB::table('solution_task')->where('id_student', $studentId)->delete();
    }

    public static function getExistPc($studentId)
    {
        return DB::select('select * from correct_pc where id_student = ?', [$studentId]);
    }

    public static function sendPc($motherId, $cpuId, $ramId, $caseId, $powerSupplyId, $graphicsId, $studentId)
    {
        $pcObj = Task::getExistPc($studentId);
        if (!$pcObj) {
            DB::table('correct_pc')->insert(
                [
                    'id_case' => $caseId,
                    'id_cpu' => $cpuId,
                    'id_graphics' => $graphicsId,
                    'id_motherboard' => $motherId,
                    'id_power_supply' => $powerSupplyId,
                    'id_ram' => $ramId,
                    'id_student' => $studentId
                ]
            );
        } else {
            foreach ($pcObj as $pc) {
                $idPc = $pc->id;
            }
            DB::table('correct_pc')->where('id', $idPc)->update(
                [
                    'id_case' => $caseId,
                    'id_cpu' => $cpuId,
                    'id_graphics' => $graphicsId,
                    'id_motherboard' => $motherId,
                    'id_power_supply' => $powerSupplyId,
                    'id_ram' => $ramId,
                    'id_student' => $studentId
                ]
            );
        }
    }

    public static function deleteAnswerPc($studentId)
    {
        DB::table('correct_pc')->where('id_student', $studentId)->delete();
    }

    public static function getAccessTask($studentId, $taskId)
    {
        return DB::select(
            'select * from access_task where id_student = :student and id_task = :task',
            ['student' => $studentId, 'task' => $taskId]
        );
    }

    public static function deleteTask($taskId){
        DB::table('tasks')->where('id', $taskId)->delete();
    }
}
