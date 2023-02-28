<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserPermission;
use App\Http\Controllers\Controller;

class UserPermissions extends Controller
{
    public function getUserRoles(): array
    {
        // It can store in db and handle with resource.
        return UserPermission::toArray();
    }
}
