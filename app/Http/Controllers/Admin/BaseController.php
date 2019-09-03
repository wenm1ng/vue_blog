<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use Session;

class BaseController extends Controller
{
	public $limit;
	public function __construct(Request $request){
		$access_token = !empty($request->input('access_token')) ? $request->input('access_token') : '';
		$this->check_login($access_token);
		// parent::__construct();
		$this->limit = Config::get('constants.limit_num');
	}

	public function obj_to_array($list){
		return json_decode(json_encode($list),true);
	}

	public function check_login($access_token){
		// print_r($access_token);
		// echo '********************';
		// print_r(session('access_token'));exit;
		if(empty($access_token) || empty(session('access_token')) || session('access_token') != $access_token){
			$result = array("code"=>1001,'msg'=>'登录失效！请重新登录!');
			echo json_encode($result);
			exit();
		}
		return true;
	}
}