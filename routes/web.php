<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'Home'],function(){
	Route::post('/index','IndexController@index');
	Route::post('/article/index','ArticleController@index');
	Route::get('/article/{id}','ArticleController@article')->where('id','[0-9]+');
	Route::post('/article/reply','ArticleController@reply');
	Route::get('/about','AboutController@index');
	//微语
	Route::get('/whisper',function(){
		return view('home.whisper');
	});
	Route::post('/whisper/list','WhisperController@list_info');//获取微语列表
	Route::post('/whisper/comment','WhisperController@comment');//进行评论
	Route::post('/whisper/comment_list','WhisperController@comment_list');//微语评论列表

	//留言
	Route::get('/message',function(){
		return view('home.message');
	});
	Route::post('/message/list','MessageController@list_info');//获取留言列表

});

Route::get('/',function(){
	return view('home.index');
});

Route::get('/admin',function(){
	return view('admin.index');
});

Route::group(['namespace' => 'Admin'],function(){
	// Route::post('/article/index','ArticleController@index');
	// Route::get('/article/{id}','ArticleController@article')->where('id','[0-9]+');
	// Route::post('/article/reply','ArticleController@reply');
	Route::get('/admin/login','LoginController@login');
	Route::get('/admin/checklogin','AdminController@checkLogin');
	Route::get('/admin/common-menu','CommonController@menu');
	Route::any('/admin/common-menuData','CommonController@menuData');
	Route::post('/admin/common-menu_node_add','CommonController@menu_node_add');//新增一项菜单
	Route::any('/admin/common-menu_node_save','CommonController@menu_node_save');//编辑菜单某一个字段
	//文章******************************************************
	//文章列表
	Route::get('/admin/article-index','ArticleController@index');
	//文章编辑某个字段
	Route::get('/admin/article-save_one','ArticleController@save_one');
	//保存文章
	Route::post('/admin/article-save_info','ArticleController@save_info');
	//上传图片
	Route::post('/admin/file-upload_img','FileController@upload_img');
	//获取文章详细内容
	Route::post('/admin/article-get_info','ArticleController@get_info');
	//获取文章评论列表
	Route::get('/admin/article-comment_list','ArticleController@comment_list');
	//回复评论
	Route::post('/admin/article-comment_save','ArticleController@comment_save');
	//微语******************************************************
	//微语列表
	Route::get('/admin/whisper-index','WhisperController@index');
	//微语编辑某个字段
	Route::any('/admin/whisper-save_one','WhisperController@save_one');
	//保存微语
	Route::post('/admin/whisper-save_info','WhisperController@save_info');
	//获取微语详细内容
	Route::post('/admin/whisper-get_info','WhisperController@get_info');
	//微语评论列表
	Route::get('/admin/whisper-comment_list','WhisperController@comment_list');
	//微语回复评论
	Route::post('/admin/whisper-comment_save','WhisperController@comment_save');
});


Route::get('/404',function(){
	return view('home.404');
});


// Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
//     Route::get('/teams', function () {
//         return view('admin.team');
//     });
//     Route::get('/list_index', function () {
//         return view('admin.index');
//     });
//     Route::get('/games', function () {
//         return view('admin.index');
//     });
//     Route::get('/users', function () {
//         return view('admin.user');
//     });
//     Route::get('/talks', function () {
//         return view('admin.talk');
//     });
//     Route::get('/guesses', function (\Illuminate\Http\Request $request) {
//         return view('admin.guess')->with(['game_id' => $request->input('game'),'team_id' => $request->input('team')]);
//     });

//     Route::get('/game/list', 'GameController@index');
//     Route::post('/game/save', 'GameController@saveGame');
//     Route::post('/game/delete', 'GameController@deleteGame');
//     Route::get('/game/guesses', 'GameController@guesses');
//     Route::post('/game/delete_lottery', 'GameController@deleteLottery');
//     Route::post('/game/save_lottery', 'GameController@saveLottery');

//     Route::get('/team/list', 'GameController@listTeams');
//     Route::post('/team/save', 'GameController@saveTeam');
//     Route::post('/team/delete', 'GameController@deleteTeam');

//     Route::get('/user/list', 'UserController@listUsers');

//     Route::get('/talk/list', 'UserController@listTalks');
//     Route::post('/talk/delete', 'UserController@deleteTalk');
// });

// Route::get('/', 'HomeController@guess');
// Route::get('/test', 'HomeController@test');
// Route::get('/talk', function () {
//     return view('talk');
// });
// Route::get('/talk/list', 'TalkController@talks');
// Route::post('/talk/talk', 'TalkController@talk');

// Route::get('/rules', function () {
//     return view('rules');
// });
// Route::get('/center', 'HomeController@center');
// Route::get('/rank', 'GameController@rank');
// Route::get('/address', function () {
//     return view('address');
// });
// Route::get('/address/load', 'HomeController@loadAddress');
// Route::post('/address/save', 'HomeController@saveAddress');

// Route::get('/lotteries', 'GameController@lotteries');
// Route::get('/lottery', function (\Illuminate\Http\Request $request) {
//     return view('lottery')->with([
//         'game_id' => $request->get('id')
//     ]);
// });
// Route::get('/lottery/draw', 'GameController@drawLottery');

// Route::post('/game/bet', 'GameController@bet');
// Route::get('/history', 'GameController@history');

// Route::get('/welcome', function () {
//     return view('welcome');
// });

// Route::get('/wx/oauth_callback', 'WechatController@wxOAuthCallback');
// Route::get('/wx/serve', 'WechatController@serve');
// Route::get('/check_token', 'WechatController@checkToken');

// Route::get('/code', 'HomeController@code');