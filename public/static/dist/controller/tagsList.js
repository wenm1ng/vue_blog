/**
 * @Title：tags标签
 * @Version：1.0
 * @Auth：张吉
 * @Date: 2019/4/18
 * @Time: 14:07
 */
layui.define(['admin','jquery'], function(exports) {"use strict";
	const $ = layui.jquery,admin = layui.admin;	

	let tagsList = function() {
		this.v = '1.0.1';
	};
	layui.link("../dist/style/tags.css");
	var html = '<div id="tags_data">'+
  					'<div class="filter_select" id="filter_select">'+
      					'<div class="container">'+
          					'<div class="box"></div>'+
      					'</div>'+
  					'</div>'+
				'</div>';
	var tags_c_id = [];
	var new_tags_c_id = [];			
	var fisrtdata;
	var thirddata;
	var onid = '';
	var onname = '';
	var urlBak = '';
	var old_tags = '';
	var title = '';
	tagsList.prototype.render = function(opt) {
		//设置默认初始化参数
		urlBak = opt.url;
		old_tags = opt.old_tags;
		tags_c_id = layui.data(layui.setter.tableName).tags_c_id;

		if(old_tags){
			title = '选择标签&nbsp;&nbsp;&nbsp;&nbsp;【第三方标签】：'+old_tags;
		}else{
			title = '选择标签';
		}

	    layer.open({
			type: 1,
			title: title,
			shade: false,
			maxmin: true,
			btn: ['保存'],
			area: ['60%', '50%'],
			content: html,
			success:function(){
				//弹出层后加载数据			
				level();
				$(".layui-btn-sm").each(function(){
				  var smbtn=$(this).attr("data-id");
				  for( var i=0;i<tags_c_id.length; i++){
				     if(tags_c_id[i]==smbtn){
				        $(this).removeClass("layui-btn-primary");
				     }
				    }
				});
				for( var i=0;i<tags_c_id.length; i++){
				    for (var j = 0; j < fisrtdata.length; j++) {
				      if(tags_c_id[i]==fisrtdata[j].id){
				      	
				        level(3,fisrtdata[j].id,fisrtdata[j].tag_name);
				       	return false
				      }
				    }
				}
				onclickContent();
				
			},
			yes:function(){
				onid = '';
				onname = '';
				new_tags_c_id = [];
				$(".layui-btn-sm").each(function(){
					if(!$(this).hasClass("layui-btn-primary")){
						if($(this).attr("data-id")){
							onid += $(this).attr("data-id")+',';
							new_tags_c_id.push($(this).attr("data-id"));
						}
						if($(this).attr("data-name")){
							onname += $(this).attr("data-name")+',';
						}
					}
				});
				if(onid.length > 0 ){
					onid = onid.substring(0,onid.length - 1);
				}
				if(onname.length > 0 ){
					onname=onname.substring(0,onname.length-1);
				}	      
				$('#tags').val(onname);
				$('[name="tags"]').val(onid);
				// var laysearch = layui.data(layui.setter.tableName);
			    layui.data(layui.setter.tableName, {
			          key: 'tags_c_id'
			          ,value: new_tags_c_id
			    });				
				layer.closeAll();
			}      
	    });

		onclickContent();
        
	}

	function level(level,level_id,name){
		if(level==undefined || level_id==undefined){
			var level=0;
			var level_id=0;
		}
		admin.req({
			type: "post",
			dataType:"json",
			async:false,
			url: urlBak + "level=" + level + "&parent_id=" + level_id,
			success: function(result){
				var data=result.data;				
				if(level!=0){
					thirddata=data;
					category_select(data,name);//第一个类别
				} else{
					init_list(data);//整个列表
					fisrtdata=data[0].list;

				}
			}
		});
		onclickContent();
	}

	function init_list(data){
console.log(data)		
		var list='';
		for(var i=0;i<data.length;i++){
			if(i==0){
				var dtid='firstid';
			}else{
				var dtid='';
			}
			var tags="";
			if(data[i].name=="年龄段"||data[i].name=="场景"){
				tags="duoxuan"
			} else {
				tags="danxuan"
			}
			list+='<dl id="'+dtid+'"' + 'class="' + tags+ '"><dt>'+data[i].name+'</dt><dd>';			
			for(var j = 0; j < data[i].list.length; j++){
				var listid = data[i].list[j].id;
				var listName = data[i].list[j].tag_name;
				if(i==0){
					list+='<span class="layui-btn layui-btn-sm layui-btn-primary getleveldata" data-id="'+listid +'" data-name="'+ listName +'">'+listName+'</span>';
				}else{
					list+='<span class="layui-btn layui-btn-sm layui-btn-primary" data-id="'+ listid +'" data-name="'+ listName +'">'+listName+'</span>';
				}

			}
			list+='</dd></dl>';
		}
		$('.box').html(list);
	}
	function category_select(data,name){//插入三级分类
		var list='';
		list+='<dl id="secondlevel"><dt>'+name+'</dt><dd>';

		for(var i=0;i<data.length;i++){
			var listid=data[i].id;
			var listName=data[i].tag_name;
			var classname="layui-btn-primary";
			for(var k=0; k< tags_c_id.length;k++){
				if(listid==tags_c_id[k]){
				classname="";
				}
			}

			list+='<span class="layui-btn layui-btn-sm '+ classname+'" data-id="'+ listid +'" data-name="'+listName+'">'+listName+'</span>';
		}
		list+='</dd></dl>';
		$('#secondlevel').remove();
		$('#firstid').append(list);
	}
	function onclickContent(){		
		//多选
		$("#filter_select").unbind().on("click",".duoxuan dd span" ,function () {			
			$(this).toggleClass("layui-btn-primary");
		})
		//单选
		$("#filter_select").on("click",".danxuan dd span" ,function () {
			if( !$(this).hasClass("layui-btn-primary")){
				$(this).addClass('layui-btn-primary');
				if($(this).hasClass("getleveldata")) {
					$('#secondlevel').remove();
				}
			} else {
				$(this).removeClass('layui-btn-primary').siblings().addClass('layui-btn-primary');
				if($(this).hasClass("getleveldata")){
					var id=$(this).attr("data-id");
					var name=$(this).text();
					level(3,id,name);
				}
			}
		})		
	}
	//自动完成渲染
	tagsList = new tagsList();
	//暴露方法
	exports("tagsList", tagsList);
});
