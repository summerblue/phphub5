<?php

/*
 * 申请 access_token 或者刷新 access_token.
 */
$router->post('oauth/access_token', 'OauthController@issueAccessToken');

/*
 *  此分组下路由 需要通过 login-token 方式认证的 access token
 */
$router->group(['middleware' => 'oauth2:user'], function ($router) {

    // 发布内容单独设置频率限制
    $router->group([
        'middleware' => 'api.throttle',
        'limit'      => config('api.rate_limits.publish.limits'),
        'expires'    => config('api.rate_limits.publish.expires'),
    ], function ($router) {
        $router->post('topics', 'TopicsController@store');
        $router->post('replies', 'RepliesController@store');
    });

    $router->group([
        'middleware' => 'api.throttle',
        'limit'      => config('api.rate_limits.access.limits'),
        'expires'    => config('api.rate_limits.access.expires'),
    ], function ($router) {
        // Users
        $router->get('me', 'UsersController@me');
        $router->put('users/{id}', 'UsersController@update');

        // Topics
        $router->delete('topics/{id}', 'TopicsController@destroy');
        $router->post('topics/{id}/vote-up', 'TopicsController@voteUp');
        $router->post('topics/{id}/vote-down', 'TopicsController@voteDown');

        // Notifications
        $router->get('me/notifications', 'NotificationController@index');
        $router->get('me/notifications/count', 'NotificationController@unreadMessagesCount');
    });
});

/*
 * 此分组下路由 同时支持两种认证方式获取的 access_token
 */
$router->group([
    'middleware' => ['oauth2', 'api.throttle'],
    'limit'      => config('api.rate_limits.access.limits'),
    'expires'    => config('api.rate_limits.access.expires'),
], function ($router) {
    $router->get('topics/{id}', 'TopicsController@show');
    //Topics
    $router->get('topics', 'TopicsController@index');
    $router->get('user/{id}/votes', 'TopicsController@indexByUserVotes');
    $router->get('user/{id}/topics', 'TopicsController@indexByUserId');

    //Web Views
    $router->get('topics/{id}/web_view', [
        'as' => 'topic.web_view',
        'uses' => 'TopicsController@showWebView'
    ]);
    $router->get('topics/{id}/replies/web_view', [
        'as' => 'replies.web_view',
        'uses' => 'RepliesController@indexWebViewByTopic'
    ]);
    $router->get('users/{id}/replies/web_view', [
        'as' => 'users.replies.web_view',
        'uses' => 'RepliesController@indexWebViewByUser'
    ]);

    $router->get('categories', 'CategoriesController@index');

    //Replies
    $router->get('topics/{id}/replies', 'RepliesController@indexByTopicId');
    $router->get('users/{id}/replies', 'RepliesController@indexByUserId');

    //Users
    $router->get('users/{id}', 'UsersController@show');

    //Adverts
    $router->get('adverts/launch_screen', 'LaunchScreenAdvertsController@index');
});
