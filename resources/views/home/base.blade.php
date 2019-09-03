<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="X-CSRF-TOKEN" content="{{csrf_token()}}">
    <title>小明博客</title>
    <link rel="icon" href="/res/img/xiaoming.jpg" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="/res/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/res/css/main.css">
    <!-- <script src="/js/jquery-2.1.4.min.js"></script> -->
    @yield('style')
</head>
<body>
    <div class="header">
        <div class="menu-btn">
          <div class="menu" id="menu"></div>
        </div>
        <h1 class="logo">
          <a href="/">
            <span>MYBLOG</span>
            <img src="/res/img/logo.png">
          </a>
        </h1>
        <div class="nav" id="web_title">
          <a href="/" class="article">文章</a>
          <a href="/whisper" class="whisper">微语</a>
          <a href="/message" class="message">留言</a>
          <a href="/about" class="about">关于</a>
        </div>
        <ul class="layui-nav header-down-nav" id="title">
          <li class="layui-nav-item"><a href="/" class="article">文章</a></li>
          <li class="layui-nav-item"><a href="whisper.html" class="whisper">微语</a></li>
          <li class="layui-nav-item"><a href="leacots.html" class="message">留言</a></li>
          <li class="layui-nav-item"><a href="/about" class="about">关于</a></li>
        </ul>
        <p class="welcome-text">
          欢迎来到<span class="name">小明</span>博客~&nbsp;&nbsp;
          <!-- <a href="#" >登录</a>
          <img src="/images/qq.jpg" alt="" style="margin-bottom:7%"> -->
        </p>
    </div>
@yield('body')



<script src="{{ asset('js/app.js') }}"></script>
</body>
<!-- <script src="https://unpkg.com/vue/dist/vue.js"></script> -->
  <!-- import JavaScript -->
  <!-- <script src="https://unpkg.com/element-ui/lib/index.js"></script> -->
@yield('script')
<script>
    // console.log(window.axios.defaults.headers.common['X-CSRF-TOKEN']);
    href = window.location.href;
    arr = href.split('/');
    if(arr[3] == ''){
      $('.article').addClass('active');
    }else{
      $('.'+arr[3]).addClass('active');
    }
</script>
</html>