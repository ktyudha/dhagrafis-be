<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'permissions' => Permission::all(['uuid', 'name'])->groupBy(function (Permission $permission, int $key) {
                $str = explode(' ', $permission['name']);
                return end($str);
            })
        ]);
    }
}
