<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;

class FileController extends BaseController
{
    //上传图片
    public function upload_img(Request $request){
    	if($request->hasFile('file')){
			//获取上传文件的后缀
	        $suffix=$request->file('file')->getClientOriginalExtension();
	        //重定义名字
	        $name=md5(time()+rand(1,99999));
	        //完整文件名:
	        $name=$name.'.'.$suffix;
	        //传入$data
	        $data['img0']=$name;

	        is_dir(Config::get('constants.temp_dir')) or mkdir(Config::get('constants.temp_dir'),777,true);

	        //把上传的文件移动到指定的目录下
	        $request->file('file')->move(Config::get('constants.temp_dir'),$name);
	        //实例化图像处理类
	        // $image=new ImageManager();
	        // //缩放
	        // $image->make(Config::get('constants.temp_dir').'/'.$name)->resize(100,100)->save(Config::get('constants.temp_dir').'/s_'.$name);
	        $result = Config::get('constants.temp_dir').'/'.$name;
	        comm_return_data($result);
		}
    }
	
}
