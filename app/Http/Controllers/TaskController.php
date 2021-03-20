<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Teacher;
use DirectoryIterator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TaskController extends Controller
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
        $typeId = Task::getTypeId($request->input('type'));

        try {
            $task = new Task();
            $task->name = $request->input('name');
            $task->id_task_type = $typeId;
            $task->id_group = $groupId;

            $task->save();

            return response()->json(['task' => $task, 'message' => 'Задание успешно добавлено']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка добавления задания']);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->description = $request->input('description');
            $task->help = $request->input('help');

            $task->save();

            return response()->json(['task' => $task, 'message' => 'Задание успешно изменено'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка изменения задания']);
        }
    }

    public function all()
    {
        $user = Auth::guard('student')->user();
        if (!$user) {
            $user = Auth::guard('teacher')->user();
        }
        $taskList = Task::getTaskList($user->id_group);
        return $taskList;
    }

    public function start(Request $request)
    {
        $user = Auth::guard('student')->user();

        //вызов функции начала задания
        //параметры: id студента, id задания, путь к папке с файлами
        try {
            $user = Auth::guard('student')->user();
            $destinationPath = 'docs' . '/' . $user->surname;
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath);
            }

            $respnose = Task::startTask($user->id, $request->input('id'), $destinationPath);
            if (!$respnose) {
                return response()->json(['message' => 'Задание принято к исполнению']);
            } else {
                return response()->json(['message' => $respnose], 424);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error']);
        }
    }

    public function getActive($studentId)
    {
        return Task::getActiveTask($studentId);
    }

    public function sendAnswer(Request $request)
    {
        try {
            $user = Auth::guard('student')->user();
            $pathFiles = Task::getFilePath($user->id);

            $allFiles = $request->allFiles();
            foreach ($allFiles as $file) {
                $fileName = $file->getClientOriginalName();

                $file->move($pathFiles, $fileName);
            }
            return response()->json(['message' => 'Ответ успешно добавлен']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка добавления задания']);
        }
    }

    public function getAnswer($studentId)
    {
        $pathFiles = Task::getFilePath($studentId);
        $dir_path = base_path() . '/public/' . $pathFiles;
        $dir = new DirectoryIterator($dir_path);

        $files = [];
        foreach ($dir as $file) {
            if (!$file->isDot()) {
                $files[] = ['name' => $file->getFilename(), 'path' => $pathFiles . '/' . $file->getFilename()];
            }
        }

        return response($files);
    }

    public function acceptAnswer($studentId)
    {
        try {
            $pathFiles = Task::getFilePath($studentId);
            array_map('unlink', glob($pathFiles . '/*'));

            Task::deleteAnswer($studentId);

            return response()->json(['message' => 'Задание зачтено']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error']);
        }
    }
}
