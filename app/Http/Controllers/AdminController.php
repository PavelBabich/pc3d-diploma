<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;



class AdminController extends Controller
{

    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::guard('admin')->user();
    }

    public function profile()
    {
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
                $path = $file->move($destinationPath, $fileName);
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
