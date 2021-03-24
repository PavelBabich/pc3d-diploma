<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$router->group(['middleware' => 'CORS'], function ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => 'api'], function () use ($router) {

        $router->post('login', 'AuthController@login');

        $router->post('logout', 'AuthController@logout');

        $router->group(['middleware' => 'auth:admin'], function ($router) {

            $router->post('register/student', 'AuthController@registerStudent');

            $router->post('register/teacher', 'AuthController@registerTeacher');

            $router->get('admin/profile', 'AdminController@profile');

            $router->post('admin/edit', 'AdminController@edit');
        });


        $router->group(['middleware' => 'auth:student'], function ($router) {

            $router->get('student/profile', 'StudentController@profile');

            $router->post('student/edit', 'StudentController@edit');

            $router->post('task/start', 'TaskController@start');

            $router->post('task/send/answer', 'TaskController@sendAnswer');

            $router->get('object/cpu', 'ObjectController@cpu');

            $router->get('object/mother', 'ObjectController@mother');

            $router->get('object/case', 'ObjectController@case');
            
            $router->get('object/power-supply', 'ObjectController@powerSupply');
           
            $router->get('object/graphics', 'ObjectController@graphics');
            
            $router->get('object/ram', 'ObjectController@ram');

            $router->post('task/pc/check', 'TaskController@pcCheck');
        });

        $router->group(['middleware' => 'auth:teacher'], function ($router) {

            $router->get('teacher/profile', 'TeacherController@profile');

            $router->post('teacher/edit', 'TeacherController@edit');

            $router->post('task/add', 'TaskController@add');

            $router->post('task/edit/{id}', 'TaskController@edit');

            $router->post('info/add', 'InfoController@add');

            $router->get('task/get/answer/{id}', 'TaskController@getAnswer');

            $router->post('task/accept/answer/{id}', 'TaskController@acceptAnswer');

            $router->post('task/accept/pc/{id}', 'TaskController@acceptAnswerPc');

            $router->get('task/get/pc/{id}', 'TaskController@getCorrectPc');

            $router->post('task/delete', 'TaskController@delete');
            
            $router->post('info/delete', 'InfoController@delete');
        });

        $router->get('task/exist/pc/{id}', 'TaskController@existPc');

        $router->get('contacts/list', 'ContactsController@contactsList');

        $router->get('student/list', 'GroupController@studentList');

        $router->get('task/all', 'TaskController@all');

        $router->get('task/active/{id}', 'TaskController@getActive');

        $router->get('info/all', 'InfoController@all');
        
        
        $router->options('/{any:.*}', function () {
            return response(['status' => 'success']);
        });
    });
});