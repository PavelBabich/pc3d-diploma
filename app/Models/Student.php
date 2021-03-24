<?php

namespace App\Models;

use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\DB;

class Student extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'name', 'surname', 'patronymic', 'group', 'phone', 'email',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function getGroupId($groupName)
    {
        $groupObj = DB::select('select id from groups where group_name = ?', [$groupName]);
        foreach ($groupObj as $group) {
            return $group->id;
        }
    }

    public static function getGroupName($groupId)
    {
        $groupObj = DB::select('select group_name from groups where id = ?', [$groupId]);
        foreach ($groupObj as $group) {
            return $group->group_name;
        }
    }

    public static function getPracticeId($practiceName)
    {
        $practiceObj = DB::select('select id from practice where practice_name = ?', [$practiceName]);
        foreach ($practiceObj as $practice) {
            return $practice->id;
        }
    }

    public static function getPracticeName($practiceId)
    {
        $practiceObj = DB::select('select practice_name from practice where id = ?', [$practiceId]);
        foreach ($practiceObj as $practice) {
            return $practice->practice_name;
        }
    }

    public static function getStudentList($groupId)
    {
        return DB::select('select * from students where id_group = ?', [$groupId]);
    }

    public static function createGroup($groupName)
    {
        return DB::table('groups')->insertGetId(
            ['group_name' => $groupName]
        );
    }

    public static function createPractice($practiceName)
    {
        return DB::table('practice')->insertGetId(
            ['practice_name' => $practiceName]
        );
    }
}
