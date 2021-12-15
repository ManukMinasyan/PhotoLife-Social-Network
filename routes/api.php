<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'api.v1.'], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::post('login', 'Auth\LoginController@login');
        Route::post('register', 'Auth\RegisterController@register');
        Route::post('password/reset', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    });

    Route::middleware('auth:api')->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('/auth', 'MemberController@getAuthMember');
            Route::post('/auth', 'MemberController@updateAuthMember');
            Route::patch('/auth/password', 'MemberController@updateAuthMemberPassword');
            Route::patch('/auth/privacy', 'MemberController@updateAuthMemberPrivacy');
            Route::get('/auth/notifications', 'MemberController@getNotifications');
            Route::patch('/auth/notifications', 'MemberController@markNotificationsAsRead');
            Route::get('/auth/bookmarked-posts', 'MemberController@getBookmarkedPosts');
            Route::patch('/auth/follow-requests/{follow_request}/confirm', 'MemberController@confirmFollowRequest');
            Route::delete('/auth/follow-requests/{follow_request}/confirm', 'MemberController@deleteFollowRequest');

            Route::post('/logout', 'MemberController@logout');
            Route::get('/{username}', 'MemberController@show');
            Route::patch('/{username}/follow', 'MemberController@follow');
            Route::get('/{username}/posts', 'PostsController@postsByUsername');
        });

        Route::group(['prefix' => 'explorer'], function () {
            Route::get('/posts', 'ExplorerController@getPosts');
        });

        Route::group(['prefix' => 'messenger', 'namespace' => 'Messenger'], function () {
            Route::post('/{username}/conversations/start', 'MessengerController@startConversation');
            Route::get('/conversations', 'MessengerController@getConversations');
            Route::post('/conversations/send/message', 'MessengerController@sendMessage');
            Route::get('/conversations/{conversation_id}', 'MessengerController@getConversation');
            Route::get('/conversations/{conversation_id}/messages', 'MessengerController@getMessages');
            Route::delete('/conversations/{conversation_id}', 'MessengerController@deleteConversation');
        });

        Route::get('/posts/by-popularity', 'PostsController@postsByPopularity');
        Route::apiResources([
            'posts' => 'PostsController',
        ]);
        Route::post('/posts/{post}/like', 'PostsController@like');
        Route::post('/posts/{post}/bookmark', 'PostsController@bookmark');
        Route::post('/posts/{post}/comments/store', 'CommentsController@store');
        Route::post('/comments/{comment}/like', 'CommentsController@like');

        Route::get('search/{query}', 'SearchController@index');

        Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            Route::get('types', 'ReportController@getReportTypes')->name('types');
            Route::post('post/{post}/{report_type}', 'ReportController@postReport')->name('post');
        });

        Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
            Route::get('/', 'PagesController@index');
            Route::get('/{alias}', 'PagesController@show');
        });

        Route::get('/quotes/random', 'QuotesController@getRandom')->name('quotes.get-random');
    });
});
