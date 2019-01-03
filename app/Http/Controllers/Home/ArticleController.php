<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Config;

class ArticleController extends BaseController
{
	public function index(Request $request){
		
		//获取文章详情
		$info = DB::table('article')->where('article_id','=',$request->id)->get()->toArray();
		$info = $this->obj_to_array($info);
		
		if(empty($info)){
			return response()->json(['status'=>0,'msg'=>'暂无数据']);
		}else{
			return response()->json(['status'=>1,'msg'=>'返回成功','data'=>$info[0]]);
		}
		
		
	}


}