@extends('home.base')

@section('body')
<div id="app">
	<input type="hidden" id="article_id" value="{{$id}}">
    <article></article>
</div>
@endsection