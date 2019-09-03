<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Config;

class ArticleController extends BaseController
{
    //文章列表
	public function index(Request $request){
		if(empty($request->input('limit')) || empty($request->input('page'))){		        	
			comm_return_error("缺少参数");
        }

        $limit = $request->input('limit');
        $page = $request->input('page');


        $start = ($page-1)*$limit>0?($page-1)*$limit:0;
		
		$list = DB::table('article')
		->select(DB::raw('article_id,title,`key`,read_num,create_username,status,content,TRIM(BOTH "/public" FROM img) as img,update_time,comment_num'))
		// ->where('status','=',1)
		->orderBy('article_id','DESC')
		->offset($start)
		->limit($limit)
		->get()->toArray();

		$list = obj_to_array($list);

		$count = DB::table('article')->count();
		
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

		$result = DB::table('article')->where('article_id',$id)->update($data);

		if($result != 1) comm_return_error('失败');

    	// Module_Log::getInstance()->log($_SESSION['user_id'],$request->input('')['now_url'],json_encode($data));
		
		comm_return_data();
	}

	//保存文章 新增 | 编辑
	public function save_info(Request $request){
		if(empty($request->input('img')) || empty($request->input('key')) || empty($request->input('title')) || empty($request->input('content'))){
			comm_return_error('参数缺失');
		}

		$id = $request->input('id');

		// print_r($request->all());exit;
		//将临时文件保存在图片保存目录
		$name = trim(strrchr($request->input('img'),'/'),'/');
		$path_name = Config::get('constants.article_dir').'/'.$name;

		is_dir(Config::get('constants.article_dir')) or mkdir(Config::get('constants.article_dir'),777,true);

		$result = rename($request->input('img'),$path_name);
		
		if(!$result){
			comm_return_error('文件丢失');
		}

		// if(file_exists($request->input('img'))) unlink($request->input('img'));

		$data = array();
		$data['title'] = $request->input('title');
		$data['key'] = $request->input('key');
		$data['img'] = '/public'.(trim($path_name,'.'));
		$data['content'] = $request->input('content');

		if(empty($id)){
			//新增
			$data['create_time'] = date('Y-m-d H:i:s');
			$data['update_time'] = date('Y-m-d H:i:s');
			DB::table('article')->insert($data);
		}else{
			//编辑
			$data['update_time'] = date('Y-m-d H:i:s');
			DB::table('article')->where('article_id','=',$id)->update($data);
		}

		comm_return_data();
	}

	//获取文章详细信息
	public function get_info(Request $request){
		if(empty($request->input('id'))){
			comm_return_error('参数错误');
		}

		$info = DB::table('article')->select('article_id','title','key','content','img')->where('article_id','=',$request->input('id'))->first();

		$info = obj_to_array($info);

		$info['img'] = str_replace('/public', '.', $info['img']);

		comm_return_data($info);
	}

	//评论列表
	public function comment_list(Request $request){
		if(empty($request->input('id'))){
			comm_return_error('参数错误');
		}

		$id = $request->input('id');

		$list = DB::table('article_comment as a')
		->select(DB::raw('blog_a.article_id,blog_a.user_id,blog_a.user_name,blog_a.comment_id,blog_a.content,(case blog_a.to_user_id when 1 then 1 else 2 end) as to_myself,blog_a.create_time,blog_c.content as reply_content'))
		->leftJoin('article_comment as c',function($join){
			$join->on('c.link_comment_id','=','a.comment_id')
			->where('c.user_id','=',1);
		})
		->where('a.article_id','=',$id)
		->where('a.user_id','<>',1)
		->get()->toArray();
		

		$list = obj_to_array($list);

		comm_return_data($list);
	}

	//回复留言
	public function comment_save(Request $request){
		$id = $request->input('id');
		$content = $request->input('content');
		$article_id = $request->input('article_id');
		if(empty($id) || empty($content) || empty($article_id)){
			comm_return_error('参数错误');
		}

		$data['content']         = $content;
		$data['article_id']      = $article_id;
		$data['to_user_id']      = $request->input('user_id');
		$data['to_user_name']    = $request->input('user_name');
		$data['link_comment_id'] = $id;
		$data['is_reply']        = 1;
		$data['user_id']         = 1;
		$data['user_name']       = '文明';
		$data['create_time']     = date('Y-m-d H:i:s');
		$result = DB::table('article_comment')->insert($data);

		if($result != 1) comm_return_error('失败');

		comm_return_data();
	}
}
