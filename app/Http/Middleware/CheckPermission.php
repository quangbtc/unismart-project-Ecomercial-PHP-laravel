<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;
use App\User;
use App\Permission;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$permission=null)
    {;
    //#1 Lấy Tất cả các quyền của user login và trả về danh sách ID các quyền
    
    $listRoleOfUser=User::find(Auth::id())->roles()->select('roles.id')->pluck('id')->toArray();
    //#2 Lấy ra tất cả các quyền permission của user login
     $listPermissionUser=DB::table('roles')
     ->join('permission_role','roles.id','=','permission_role.role_id')
     ->join('permissions','permissions.id','=','permission_role.permission_id')
     ->whereIn('roles.id', $listRoleOfUser)
     ->select('permissions.*')
     ->get()->pluck('id')->unique();
     //#3 Lây ID màn hình
     $checkPermission=Permission::where('name',$permission)->value('id');
    
        if($listPermissionUser->contains($checkPermission)){
        return $next($request);
        }{
            
            return redirect('/dashboard')->with('status','Bạn không có quyền truy cập tác vụ này');
        }
        
        
    }
}
