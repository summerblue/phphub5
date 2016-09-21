<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Cache;
use DB;

class Role extends EntrustRole
{
    protected $fillable = ['name', 'display_name', 'description'];

    public static function boot() {
        parent::boot();

        static::saving(function($model) {
            Cache::forget('all_assigned_roles');
            Cache::forget('all_roles');
        });
    }

    public static function addRole($name, $display_name = null, $description = null)
    {
        $role = Role::query()->where('name', $name)->first();
        if (!$role) {
            $role = new Role(['name' => $name]);
        }
        $role->display_name = $display_name;
        $role->description  = $description;
        $role->save();

        return $role;
    }

    public function allUsers()
    {
        return $this->belongsToMany(User::class);
    }

    public static function relationArrayWithCache()
    {
        return Cache::remember('all_assigned_roles', $minutes = 60, function () {
            return DB::table('role_user')->get();
        });
    }

    public static function rolesArrayWithCache()
    {
        return Cache::remember('all_roles', $minutes = 60, function () {
            return DB::table('roles')->get();
        });
    }
}
