@extends('home.base')
@section('style')
<style>
  .img{
    width: 135px;
  }
</style>
@endsection
@section('body')
<div class="about-content">
    <div class="w1000">
      <div class="item info">
        <div class="title">
          <h3>我的介绍</h3>
        </div>
        <div class="cont">
          <img src="../res/img/study.jpg">
          <div class="per-info">
            <p>
              <span class="name">小明</span><br />
              <span class="age">25岁</span><br />
              <span class="Career">php开发工程师</span><br />
              <span class="interest">爱好旅游,打游戏,跑步,游泳,听音乐,逛技术博客,学习新技术</span>
            </p>
          </div>
        </div>
      </div>
      
      <div class="item tool">
        <div class="title">
          <h3>我的技能</h3>
        </div>
        <div class="layui-fluid">
          <div class="layui-row">
            <div class="layui-col-xs6 layui-col-sm3 layui-col-md3">
              <div class="cont-box">
                <img src="../res/img/php.jpg" class="img">
                <p>95%</p>
              </div>
            </div>
            <div class="layui-col-xs6 layui-col-sm3 layui-col-md3">
              <div class="cont-box">
                <img src="../res/img/js.jpg" class="img">
                <p>90%</p>
              </div>
            </div>
            <div class="layui-col-xs6 layui-col-sm3 layui-col-md3">
              <div class="cont-box">
                <img src="../res/img/css.jpg" class="img">
                <p>90%</p>
              </div>
            </div>
            <div class="layui-col-xs6 layui-col-sm3 layui-col-md3">
              <div class="cont-box">
                <img src="../res/img/redis.jpg" class="img">
                <p>90%</p>
              </div>
            </div>
          </div>
          <div class="layui-row">
            <div class="layui-col-xs6 layui-col-sm3 layui-col-md3">
              <div class="cont-box">
                <img src="../res/img/html.jpg" class="img">
                <p>95%</p>
              </div>
            </div>
            <div class="layui-col-xs6 layui-col-sm3 layui-col-md3">
              <div class="cont-box">
                <img src="../res/img/linux.jpg" class="img">
                <p>80%</p>
              </div>
            </div>
            <div class="layui-col-xs6 layui-col-sm3 layui-col-md3">
              <div class="cont-box">
                <img src="../res/img/vue.jpg" class="img">
                <p>80%</p>
              </div>
            </div>
            <div class="layui-col-xs6 layui-col-sm3 layui-col-md3">
              <div class="cont-box">
                <img src="../res/img/angular.jpg" class="img">
                <p>70%</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="app">
        <foot></foot>
      </div>
    </div>
  </div>
@endsection