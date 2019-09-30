<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Config;

class WhisperController extends BaseController
{
    //微语列表
	public function index(Request $request){
		if(empty($request->input('limit')) || empty($request->input('page'))){		        	
			comm_return_error("缺少参数");
        }

        $limit = $request->input('limit');
        $page = $request->input('page');


        $start = ($page-1)*$limit>0?($page-1)*$limit:0;
		
		$list = DB::table('whisper')
		// ->where('status','=',1)
		->orderBy('id','DESC')
		->offset($start)
		->limit($limit)
		->get()->toArray();

		$list = obj_to_array($list);

		$count = DB::table('whisper')->count();
		
		comm_return_data($list,$count);
	}

	//编辑某个字段
	public function save_one(Request $request){
		$id = $request->input('id');
		$field = $request->input('field');
		$value = $request->input('value');

		if(empty($id)) comm_return_error('参数错误');

        if(empty($field)) comm_return_error('参数错误');

        $data[$field] = $value;

		$result = DB::table('whisper')->where('id',$id)->update($data);

		if($result != 1) comm_return_error('失败');

    	// Module_Log::getInstance()->log($_SESSION['user_id'],$request->input('')['now_url'],json_encode($data));
		
		comm_return_data();
	}

	//保存文章 新增 | 编辑
	public function save_info(Request $request){
		if(empty($request->input('img')) || empty($request->input('content'))){
			comm_return_error('参数缺失');
		}

		$id = $request->input('id');

		// print_r($request->all());exit;
		//将临时文件保存在图片保存目录
		$name = trim(strrchr($request->input('img'),'/'),'/');
		$path_name = Config::get('constants.whisper_dir').'/'.$name;

		is_dir(Config::get('constants.whisper_dir')) or mkdir(Config::get('constants.whisper_dir'),777,true);

		$result = rename($request->input('img'),$path_name);
		
		if(!$result){
			comm_return_error('文件丢失');
		}

		// if(file_exists($request->input('img'))) unlink($request->input('img'));

		$data = array();
		$data['img'] = trim($path_name,'.');
		$data['content'] = $request->input('content');

		if(empty($id)){
			//新增
			$data['create_time'] = date('Y-m-d H:i:s');
			$data['update_time'] = date('Y-m-d H:i:s');
			DB::table('whisper')->insert($data);
		}else{
			//编辑
			$data['update_time'] = date('Y-m-d H:i:s');
			DB::table('whisper')->where('id','=',$id)->update($data);
		}

		comm_return_data();
	}

	//获取文章详细信息
	public function get_info(Request $request){
		if(empty($request->input('id'))){
			comm_return_error('参数错误');
		}

		$info = DB::table('whisper')->select('id','content','img')->where('id','=',$request->input('id'))->first();

		$info = obj_to_array($info);

		$info['img'] = '.'.$info['img'];

		comm_return_data($info);
	}

	//评论列表
	public function comment_list(Request $request){
		if(empty($request->input('id'))){
			comm_return_error('参数错误');
		}

		$id = $request->input('id');

		$list = DB::table('whisper_comment as a')
		->select(DB::raw('blog_a.whisper_id,blog_a.user_name,blog_a.id,blog_a.content,blog_a.create_time,blog_c.content as reply_content'))
		->leftJoin('whisper_comment as c',function($join){
			$join->on('c.link_comment_id','=','a.id')
			->where('c.user_id','=',1);
		})
		->where('a.whisper_id','=',$id)
		->where('a.user_id','<>',1)
		->get()->toArray();
		

		$list = obj_to_array($list);

		comm_return_data($list);
	}

	//回复留言
	public function comment_save(Request $request){
		$id = $request->input('id');
		$content = $request->input('content');
		$whisper_id = $request->input('whisper_id');
		if(empty($id) || empty($content) || empty($whisper_id)){
			comm_return_error('参数错误');
		}

		$info = Db::table('whisper_comment')->where('id','=',$id)->first();

		$info = obj_to_array($info);

		$data['content']         = $content;
		$data['whisper_id']      = $whisper_id;
		$data['link_comment_id'] = $id;
		$data['user_id']         = 1;
		$data['user_name']       = '文明';
		$data['to_user_name']    = $info['user_name'];
		$data['create_time']     = date('Y-m-d H:i:s');
		$result = DB::table('whisper_comment')->insert($data);

		//评论数加1
		Db::update("update blog_whisper set comment_count = comment_count + 1 where id = {$whisper_id}");

		if($result != 1) comm_return_error('失败');

		comm_return_data();
	}
}
