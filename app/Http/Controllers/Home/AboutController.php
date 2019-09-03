<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Config;

class AboutController extends BaseController
{
	public function index(Request $request){
		
		//获取文章详情
		$list = DB::table('skill')
		->get()->toArray();
		$list = $this->obj_to_array($list);

		return view('home.about',['list'=>$list]);
		
	}


}