<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\DB;

class Info extends Model
{
    protected $table = 'ads';
    
    public static function getAds()
    {
        return DB::select('select id, description, created_at from ads order by id desc');
    }
}
