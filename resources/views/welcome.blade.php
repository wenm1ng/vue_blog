<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="X-CSRF-TOKEN" content="{{csrf_token()}}">
    <title>首页</title>
    <link rel="stylesheet" type="text/css" href="/res/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/res/css/main.css">
    <script src="/js/jquery-2.1.4.min.js"></script>
</head>
<body>
<div id="app">
    <example></example>fsdfsd
</div>


<script src="{{ asset('js/app.js') }}"></script>
</body>
<!-- <script src="https://unpkg.com/vue/dist/vue.js"></script> -->
  <!-- import JavaScript -->
  <!-- <script src="https://unpkg.com/element-ui/lib/index.js"></script> -->
<script>
    // console.log(window.axios.defaults.headers.common['X-CSRF-TOKEN']);
    $("#menu").click(function(){
        if($("#title").css('display') == 'block'){
            $("#title").toggle(false);
        }else{
            $("#title").toggle(true);
        }
    })
</script>
</html>