<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Config;

class LoginController extends Controller
{
	public function login(Request $request){
		$username = !empty($request->input('username')) ? trim($request->input('username')) : '';
        $password = !empty($request->input('password')) ? md5(trim($request->input('password'))) : '';

        if(empty($username) || empty($password)){
            comm_return_error('请输入');
        }
        DB::connection()->enableQueryLog();
        $user = DB::table('admin_user')
        ->select('id','nickname','type','status')
        ->where('username','=',$username)
        ->where('password','=',$password)
        ->first();
        
        $user = obj_to_array($user);
        // print_r(DB::getQueryLog());exit;
        if(empty($user)){
            comm_return_error('账号或密码错误');
        }elseif($user['status'] == 2){
            comm_return_error('账号已被禁用');
        }
        // 权限查询
        $roleData = DB::table('admin_role as r')
        ->select('a.plate','a.plate_class')
        ->leftjoin('admin_action as a','r.menu_id','=','a.id')
        ->where('r.type','=',2)
        ->where('user_type','=',$user['type'])
        ->get()->toArray();

        $arr = array();
        foreach ($roleData as $key => $value) {
            $arr[$value["plate"]][] = $value['plate_class'];
        }

        $data = array(
            'nickname' => $user['nickname'],
            'access_token' => md5($user['id'].time()),
            'role' => $arr,
        );

        // ini_set('session.gc_maxlifetime', 100000);
        
        // if(!session_id()){
        //     session_start();
        // }

        session(['access_token' => $data['access_token']]);
        session(['user_id' => $user['id']]);
        session(['user_type' => $user['type']]);
        session()->save();
        comm_return_data($data);
	}
}