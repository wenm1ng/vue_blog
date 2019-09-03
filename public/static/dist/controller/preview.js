/**
 * @Title：预览
 * @Version：1.0
 * @Auth：张吉
 * @Date: 2019/4/28
 * @Time: 14:07
 */
layui.define(['jquery'], function(exports) {"use strict";
	const $ = layui.jquery;	

	let preview = function() {
		this.v = '1.0.1';
	};
	// var layrole = layui.data(layui.setter.role);

	preview.prototype.render = function(opt) {		
		layer.open({
			type: 1,
			shade: 0.8,
			title: false,
			skin: 'myskin',
			area: ['450px', '870px'],
			closeBtn: 1,
			shadeClose:false,
			content: '<img width="450px" height="870px" src="//wbso.ihomebot.cn/operate/default/default-phone.png">',
			success: function (layero) {
				previewContent(opt)
			},
			cancel: function(index, layero){
				layer.closeAll();
			}   
		});
		let removeClass = layui.data(layui.setter.role).role[''+opt+''];
		if(removeClass){
			$.each(removeClass,function(i){
			    $('.'+removeClass[i]).removeClass('layui-hide');
			});
		}
		$('.layui-hide').remove();		
        
	}
   	function previewContent(getContent){
        var html = '<link rel="stylesheet" type="text/css" href="//wbso.ihomebot.cn/operate/default/course/course_intro.css"><div class="introduce"><div id="content" class="content">';
        html += getContent;
        html += '</div></div>';  

        layer.open({
            type: 1,
            shade: 0,
            title: false,
            area: ['375px', '667px'],
            content: html,
            closeBtn: 0,
            yes: function(){
              layer.closeAll();
            }
        });    
    };	
	

	//自动完成渲染
	preview = new preview();
	//暴露方法
	exports("preview", preview);
});
