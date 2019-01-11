@extends('home.base')

@section('body')
<input type="hidden" name="id" id="article_id" value="{{ $article_id }}" >
<div class="content whisper-content leacots-content details-content">
    <div class="cont w1000">
      <div class="whisper-list">
        <div class="item-box">
          <div class="review-version">
          	<div class="form-box">
                <div class="article-cont">
                  <div class="title">
                    <h3>{{$info['title']}}</h3>
                    <p class="cont-info"><span class="data">{{ $info['create_username'] }}</span><span class="types">{{$info['create_time']}}</span></p>
                  </div>
                  	{!! $info['content'] !!}
                  <div class="btn-box">
                    <button class="layui-btn layui-btn-primary">上一篇</button>
                    <button class="layui-btn layui-btn-primary">下一篇</button>
                  </div>
                </div>
                <div id="comment">
                  <comment></comment>
                </div>
             </div>
          	<div id="app">
			         <articles></articles>
			       </div>
          </div>
        </div>
    </div>
</div>

@endsection