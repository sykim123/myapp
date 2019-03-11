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
Route::get('/', 'WelcomeController@index');

Route::get('mail', function (){
    $article=App\Article::with('user')->find(1);

    return Mail::send(
        ['text' => 'emails.articles.created-text'],
        compact('article'),
        function ($message) use ($article) {
            $message->from('syoung8818@gmail.com', 'sy');
            $message->to(['syoung8818@gmail.com', 'sykim@yongsanzip.com']);
            $message->subject('새 글이 등록되었습니다 -'. $article->title);
            $message->cc('sykim@yongsanzip.com');
            $message->attach(storage_path('coco.jpeg'));
        }
    );
});

Route::get('markdown', function () {
    $text =<<< EOT
#마크 다운 예제1

이 문서는 [마크다운][1]으로 썼습니다. 화면에는 HTML로 변환되어 출력됩니다.

##순서 없는 목록

- 첫 번째 항목
- 두 번째 항목[^1]

[1] : http://daringfireball.net/projects/markdown

[^1] : 두 번째 항목_ http://google.com
EOT;
    return app(ParsedownExtra::class)->text($text);

});

Route::get('docs/{file?}', 'DocsController@show');

Route::get('docs/images/{image}', 'DocsController@image')->where('image', '\pL-\pN\._-]+-img-[0-9]{2}.jpeg');

Route::get('auth/login', function() {
    $credentials = [
        'email' => 'john@example.com',
        'password' => 'password'
    ];

    if(! auth()->attempt($credentials)) {
        return '로그인 정보가 정확하지 않습니다.';
    }
    return redirect('protected');
});

Route::get('protected',['middleware'=>'auth', function() {
    dump(session()->all());

    return '어서오세요' . auth()->user()->name;
}]);

Route::get('auth/logout', function() {
    auth()->logout();

    return '또 봐요~';
});

Route::resource('articles', 'ArticlesController');
Auth::routes();



/*DB::listen(function ($query){
    var_dump($query->sql);
});*/



Route::get('/home', 'HomeController@index')->name('home');
