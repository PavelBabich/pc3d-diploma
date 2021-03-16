<?php

namespace App\Http\Controllers;

use App\Models\Task;
use DirectoryIterator;
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
        //параметры: id студента, id задания
        try {
            $respnose = Task::startTask($user->id, $request->input('id'));
            if (!$respnose) {
                return response()->json(['message' => 'Task started successfully']);
            } else {
                return response()->json(['message' => $respnose]);
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
        $user = Auth::guard('student')->user();
        $destinationPath = 'docs' . '/' . $user->surname;

        $allFiles = $request->allFiles();
        foreach ($allFiles as $file) {
            $fileName = $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);
        }

        try {
            Task::sendFile($destinationPath, $user->id);

            return response()->json(['message' => 'Answer send successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error']);
        }
    }

    public function getAnswer($studentId)
    {
        $dir_path = base_path(). '/public/' . Task::getFilePath($studentId);
        $dir = new DirectoryIterator($dir_path);
        
        $files = [];
        foreach($dir as $file){
            if(!$file->isDot()){
                $files[] = $file->getFilename();
            }
        }

        // $type = 'text/plain';
        // $headers = ['Content-Type' => $type];
        // $path = $dir_path . '/' . $files[0];

        // $response = new BinaryFileResponse($path);

        // return $response;
    }
}
