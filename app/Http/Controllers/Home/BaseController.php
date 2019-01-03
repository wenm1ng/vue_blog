<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Config;

class BaseController extends Controller
{
	public $limit;
	public function __construct(){
		// parent::__construct();
		$this->limit = Config::get('constants.limit_num');
	}

	public function obj_to_array($list){
		return json_decode(json_encode($list),true);
	}
}