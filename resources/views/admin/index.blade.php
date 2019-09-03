<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>金币组-管理后台</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/layui/css/layui.css" media="all"> 
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <!-- 富文本编辑器 -->
    <script type="text/javascript" charset="utf-8" src="/static/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/static/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/static/ueditor/lang/zh-cn/zh-cn.js"></script> 


</head>

<body>
    <div id="LAY_app"></div>
    <script src="/static/layui/layui.js"></script>
    <script type="text/javascript" charset="utf-8" src="/static/dist/controller/excel.js"></script> 
    <script>
        layui.config({
            base: '/static/dist/'
            ,version: new Date().getTime()
            // ,version: 1.4
        }).use(['index','admin'], function(){
            var $ = layui.$
            ,admin = layui.admin;

            admin.req({
                type:"get",
                url:layui.setter.api+'checklogin',
                dataType:'json',
                done:function(res){
                }
            });
        });
    </script>
</body>

</html>


