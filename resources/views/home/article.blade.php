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
                    @if(empty($before_id))
                    <span style="margin-right:5px">上一篇没有了</span>
                    @else
                    <a href="/article/{{$before_id}}" class="layui-btn layui-btn-primary">上一篇</a>
                    @endif
                    @if(empty($after_id))
                    <span style="margin-left:5px">下一篇没有了</span>
                    @else
                    <a href="/article/{{$after_id}}" class="layui-btn layui-btn-primary">下一篇</a>
                    @endif
                  </div>
                </div>

             </div>
          	<div id="app">
              <comments></comments>
			        <articles></articles>
			      </div>
          </div>
        </div>
    </div>
</div>

@endsection