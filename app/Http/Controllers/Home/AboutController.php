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


	public function article(Request $request){		
		
		//获取文章详情
		$info = DB::table('article')->where('article_id','=',$request->id)->get()->toArray();
		$info = $this->obj_to_array($info);
		// print_r($info);
		//上一篇
		$before_id = DB::table('article')->where('article_id','<',$request->id)->value('article_id');

		$after_id = DB::table('article')->where('article_id','>',$request->id)->value('article_id');

		return view('home.article',['info'=>$info[0],'article_id'=>$request->id,'before_id'=>$before_id,'after_id'=>$after_id]);
		
	}

	//回复&评论
	public function reply(Request $request){
		$data['article_id'] = $request->article_id;
		$data['link_comment_id'] = $request->link_comment_id;
		$data['content'] = $request->content;
		$data['to_user_id'] = $request->to_user_id;
		$data['to_user_name'] = $request->to_user_name;
		$data['is_reply'] = $request->is_reply;
		$data['user_id'] = 0;
		$arr = explode('.',$_SERVER['REMOTE_ADDR']);
		$str = '';
		foreach ($arr as $key => $val) {
			$str .= strrev($val);
		}
		$data['user_name'] = '游客'.$str;
		$data['create_time'] = date('Y-m-d H:i:s');

		$result = DB::table('article_comment')->insertGetId($data);

		if($result){
			$data['comment_id'] = $result;
			$data['child'] = array();
			return response()->json(['status'=>1,'msg'=>'操作成功','data'=>$data]);
		}
	}
}