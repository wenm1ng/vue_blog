<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Config;

class IndexController extends BaseController
{
	public function index(Request $request){
		//获取文章列表
		$page = $request->input('page');
		$list = DB::table('article')->where('status','=',1)->offset(($page-1)*$this->limit)->limit($this->limit)->get()->toArray();
		// print_r($list);exit;
		$list = $this->obj_to_array($list);
		
		foreach ($list as $key => $val) {
			$list[$key]['content'] = mb_substr(strip_tags($val['content']),0,48);
			$list[$key]['img'] = substr($val['img'],strpos(trim($val['img'],'/'),'/')+2,strlen($val['img'])-1);
		}

		if(empty($list)){
			return response()->json(['status'=>0,'msg'=>'暂无数据']);
		}else{
			return response()->json(['status'=>1,'msg'=>'返回成功','data'=>$list]);
		}
	}
}