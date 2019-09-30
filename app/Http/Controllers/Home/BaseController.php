<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Config;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
	public $limit;
	public function __construct(){
		// parent::__construct();
		DB::connection()->enableQueryLog(); // 开启查询日志
		$this->limit = Config::get('constants.limit_num');
	}

	public function obj_to_array($list){
		return json_decode(json_encode($list),true);
	}
}