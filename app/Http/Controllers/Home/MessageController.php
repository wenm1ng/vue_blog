<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Config;

class MessageController extends BaseController
{
	public function list(Request $request){
		// CREATE TABLE `blog_message` (
		//   `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
		//   `content` TEXT NOT NULL COMMENT '内容',
		//   `user_id` INT(11) NOT NULL DEFAULT '0' COMMENT '评论人id',
		//   `user_name` VARCHAR(60) NOT NULL DEFAULT '' COMMENT '评论人',
		//   `to_user_name` VARCHAR(60) NOT NULL DEFAULT '' COMMENT '回复人',
		//   `create_time` DATETIME NOT NULL COMMENT '创建时间',
		//   `link_comment_id` INT(11) NOT NULL DEFAULT '0' COMMENT '关联评论Id',
		//   PRIMARY KEY (`id`)
		// ) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='留言表'
		$page = $request->input('page');

		$list = DB::table('message')
		->offset(($page-1)*$this->limit)->limit($this->limit)
		->get()->toArray();
		$list = $this->obj_to_array($list);
		
		$count = DB::table('message')->count();

		if(empty($list)){
			return response()->json(['status'=>0,'msg'=>'暂无数据','count'=>0]);
		}else{
			return response()->json(['status'=>1,'msg'=>'返回成功','data'=>$list,'count'=>$count]);
		}
	}
}