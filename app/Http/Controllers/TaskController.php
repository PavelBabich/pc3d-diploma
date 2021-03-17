<?php

namespace App\Http\Controllers;

use App\Models\Task;
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

        $id_type = Task::getTypeId($request->input('type'));

        try {
            $task = new Task();
            $task->name = $request->input('name');
            $task->id_task_type = $id_type;

            $task->save();

            return response()->json(['task' => $task, 'message' => 'Created']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Task add failed']);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->description = $request->input('description');
            $task->help = $request->input('help');

            $task->save();

            return response()->json(['task' => $task, 'message' => 'Created'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Edit add failed']);
        }
    }

    public function all()
    {
        return Task::getTask();
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
                return response()->json(['message' => 'Task started successfully']);
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
            return response()->json(['message' => 'Answer send successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error']);
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

            return response()->json(['message' => 'Answer accept successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error']);
        }
    }
}
