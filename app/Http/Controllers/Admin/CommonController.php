<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CommonController extends BaseController
{
    //获取权限列表
	public function menu(){
		$user_type = session('user_type');

        // echo 1;exit;
        if(empty($user_type)){
            comm_return_error("参数错误");
        }
        
        if($user_type != 1){
        	$where = array(
        		array('a.status','=','1'),
        		array('a.level','=','1'),
        		array('a.type','=','1'),
        		array('a.user_type','=',$user_type)
        	);
            
            $menu = DB::table('admin_role as r')
            ->select('a.icon','a.id','a.jump','a.name','a.parent_id','a.level','a.sort','a.status','a.title')
            ->leftjoin('admin_menu as a','r.menu_id','=','a.id')
            ->where($where)
            ->orderBy('a.sort','ASC')
            ->get()->toArray();
            
            print_r($menu);exit;
            // exit;
            foreach ($menu as $key => $value) {
                $sec_where = array(
                    "ORDER"=>array('a.sort'=>'ASC'),
                    "a.status"=>1,
                    "a.level"=>2,
                    "r.type"=>1,
                    'a.parent_id'=>$value['id'],
                    'r.user_type'=>$user_type
                );                
                $sec_menu = $db->select('admin_role(r)',array('[>]admin_menu(a)'=>array('r.menu_id'=>'id')),$column,$sec_where);
                if(!empty($sec_menu)){
                    foreach ($sec_menu as $k => $v) {
                        $thr_where = array(
                            "ORDER"=>array('a.sort'=>'ASC'),
                            "a.status"=>1,
                            "a.level"=>3,
                            "r.type"=>1,
                            'a.parent_id'=>$v['id'],
                            'r.user_type'=>$user_type
                        );                        
                        $thr_menu = $db->select('admin_role(r)',array('[>]admin_menu(a)'=>array('r.menu_id'=>'id')),$column,$thr_where);;
                        if(!empty($thr_menu)){
                            $sec_menu[$k]['list'] = $thr_menu;
                        }
                    }
                    $menu[$key]['list'] = $sec_menu;
                }
            }
        }else{
            $where = array(
                "ORDER"=>array('sort'=>'ASC'),
                "status"=>1,
                "level"=>1
            );
            $where = array(
            	array('status','=',1),
            	array('level','=',1)
            );
            $column = array('icon','id','jump','name','parent_id','level','sort','status','title'
            );
            $menu = DB::table('admin_menu')
            ->select('icon','id','jump','name','parent_id','level','sort','status','title')
            ->where($where)
            ->get()->toArray();

            $menu = obj_to_array($menu);
            // print_r($menu);exit;
            foreach ($menu as $key => $value) {
            	$sec_where = array(
            		array('status','=',1),
            		array('level','=',2),
            		array('parent_id','=',$value['id'])
            	);
                $sec_menu = DB::table('admin_menu')
                ->select('icon','id','jump','name','parent_id','level','sort','status','title')
                ->where($sec_where)
                ->get()->toArray();
                $sec_menu = obj_to_array($sec_menu);

                if(!empty($sec_menu)){
                    foreach ($sec_menu as $k => $v) {
                    	$thr_where = array(
		            		array('status','=',1),
		            		array('level','=',3),
		            		array('parent_id','=',$v['id'])
		            	);
                        $thr_menu = DB::table('admin_menu')
		                ->select('icon','id','jump','name','parent_id','level','sort','status','title')
		                ->where($thr_where)
		                ->get()->toArray();

                		$thr_menu = obj_to_array($thr_menu);

                        if(!empty($thr_menu)){
                            $sec_menu[$k]['list'] = $thr_menu;
                        }
                    }
                    $menu[$key]['list'] = $sec_menu;
                }
            }                
        }


        comm_return_data($menu);
	}

	public function menuData(Request $request){
        $data = DB::table('admin_menu')->orderBy('parent_id','ASC','sort','ASC')->get()->toArray();
        $data = obj_to_array($data);
        if(empty($data)){
        	comm_return_error('数据异常');
        }else{
        	foreach($data as $k=>$v){
        		!empty($v['jump'])?$v['jump']:'';
        		switch ($v['status']) {
        			case 0:
        				$data[$k]['status_name'] = '隐藏';
        				break;
        			case 1:
        				$data[$k]['status_name'] = '显示';
        				break;
        		}
        	}
        }	
		comm_return_data($data);
	}

	//新增菜单
	public function menu_node_add(Request $request){
		$level = !empty($request->input('level'))?(int)$request->input('level'):'';
		$parent_id = !empty($request->input('id'))?(int)$request->input('id'):-1;
        if($parent_id == -1 || empty($level)) comm_return_error('参数错误');

        $data = array();
        
    	//添加
    	$data['parent_id'] = $parent_id;
    	$data['level'] = $level+1;

    	$id = DB::table('admin_menu')->insertGetId($data);
    	if(empty($id)) comm_return_error('添加失败');

    	$msg = '添加成功，请继续编辑信息';
    	$data['id'] = $id;

    	// Module_Log::getInstance()->log($_SESSION['user_id'],$request->input('')['now_url'],json_encode($data));

		comm_return_data($data,0,0,$msg);
	}

	//编辑菜单某一个内容
	public function menu_node_save(Request $request){
		$id = !empty($request->input('id'))?(int)$request->input('id'):'';
		$status = $request->input('status');
		$field = !empty($request->input('field'))?trim($request->input('field')):'';
		$value = !empty($request->input('value'))?trim($request->input('value')):'';

		if(empty($id)) comm_return_error('参数错误');

		if(!$request->has('status')){
	        if(empty($field)) comm_return_error('参数错误');

	        $data[$field] = $value;
		}else{
	        if(!in_array($status, array(0,1))) comm_return_error('参数错误');

	        $data['status'] = $status;				
		}

		$result = DB::table('admin_menu')->where('id',$id)->update($data);

		if($result != 1) comm_return_error('失败');

    	// Module_Log::getInstance()->log($_SESSION['user_id'],$request->input('')['now_url'],json_encode($data));
		
		comm_return_data($data);
	}
}
