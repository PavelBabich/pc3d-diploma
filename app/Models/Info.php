<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\DB;

class Info extends Model
{
    protected $table = 'ads';
    
    public static function getAdsList($groupId)
    {
        return DB::select('select id, description, created_at from ads where id_group = ? order by id desc', [$groupId]);
    }

    public static function deleteInfo($infoId){
        DB::table('ads')->where('id', $infoId)->delete();
    }
}
