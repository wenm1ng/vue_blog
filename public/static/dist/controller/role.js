/**
 * @Title：权限
 * @Version：1.0
 * @Auth：张吉
 * @Date: 2019/3/19
 * @Time: 14:07
 */
layui.define(['jquery'], function(exports) {"use strict";
	const $ = layui.jquery;	

	let role = function() {
		this.v = '1.0.1';
	};
	// var layrole = layui.data(layui.setter.role);

	role.prototype.render = function(opt) {

		let removeClass = layui.data(layui.setter.role).role[''+opt+''];
		if(removeClass){
			$.each(removeClass,function(i){
			    $('.'+removeClass[i]).removeClass('layui-hide');
			});
		}
		// $('.layui-hide').remove();		
        
	}

	//自动完成渲染
	role = new role();
	//暴露方法
	exports("role", role);
});
