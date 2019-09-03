<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Config;

class WhisperController extends BaseController
{

	public function list_info(Request $request){
		$page = $request->page;
		//获取微语详情
		$list = DB::table('whisper')
		->select(DB::raw('id,date_format(create_time,"%Y-%m-%d") as date,date_format(create_time,"%H:%i") as time,content,img,comment_count'))
		->where('status','=',1)
		->offset(($page-1)*$this->limit)->limit($this->limit)
		->get()->toArray();
		$list = obj_to_array($list);

		foreach ($list as $key => $val) {
			$comment_list = DB::table('whisper_comment')->where('whisper_id','=',$val['id'])->orderBy('id','desc')->get()->toArray();
			$comment_list = obj_to_array($comment_list);
			$list[$key]['comment'] = $comment_list;
		}

		if(empty($list)){
			return response()->json(['status'=>0,'msg'=>'暂无数据','data'=>array()]);
		}else{
			return response()->json(['status'=>1,'msg'=>'返回成功','data'=>$list]);
		}
		
	}

	//微语评论
	public function comment(Request $request){
		$id = $request->id;
		$content = $request->content;

		$arr = explode('.',$_SERVER['REMOTE_ADDR']);
		$str = '';
		foreach ($arr as $key => $val) {
			$str .= strrev($val);
		}
		$user_name = '游客'.$str;

		$data = array(
			'whisper_id' => $id,
			'content' => $content,
			'user_name' => $user_name,
			'create_time' => date('Y-m-d H:i:s')
		);

		DB::table('whisper_comment')->insert($data);

		DB::update("update blog_whisper set comment_count = comment_count + 1 where id = {$id}");

		return response()->json(['status'=>1,'msg'=>'返回成功','data'=>$data]);
	}
}