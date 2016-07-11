<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = ['name', 'display_name', 'description'];

    public static function addPermission($name, $display_name = null, $description = null)
    {
        $permission = Permission::where('name', $name)->first();
        if (!$permission) {
            $permission = new Permission(['name' => $name]);
        }
        $permission->display_name = $display_name;
        $permission->description  = $description;
        $permission->save();

        return $permission;
    }
}
