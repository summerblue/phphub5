<?php
# ------------------ Page Route ------------------------
Route::get('/', 'PagesController@home')->name('home');
Route::get('/about', 'PagesController@about')->name('about');
Route::get('/search', 'PagesController@search')->name('search');
Route::get('/feed', 'PagesController@feed')->name('feed');
Route::get('/wiki', 'PagesController@wiki')->name('wiki');
Route::get('/sitemap', 'PagesController@sitemap');
Route::get('/sitemap.xml', 'PagesController@sitemap');
Route::get('/hall_of_fames', 'PagesController@hallOfFames')->name('hall_of_fames');
Route::get('/composer', 'PagesController@composer')->name('composer');

Route::get('/roles/{id}', 'RolesController@show')->name('roles.show');

# ------------------ User stuff ------------------------

Route::get('/users/drafts', 'UsersController@drafts')->name('users.drafts');
Route::get('/users/{id}/replies', 'UsersController@replies')->name('users.replies');
Route::get('/users/{id}/topics', 'UsersController@topics')->name('users.topics');
Route::get('/users/{id}/articles', 'UsersController@articles')->name('users.articles');
Route::get('/users/{id}/votes', 'UsersController@votes')->name('users.votes');
Route::get('/users/{id}/following', 'UsersController@following')->name('users.following');
Route::get('/users/{id}/followers', 'UsersController@followers')->name('users.followers');

Route::get('/users/{id}/refresh_cache', 'UsersController@refreshCache')->name('users.refresh_cache');
Route::get('/users/{id}/access_tokens', 'UsersController@accessTokens')->name('users.access_tokens');
Route::get('/access_token/{token}/revoke', 'UsersController@revokeAccessToken')->name('users.access_tokens.revoke');
Route::post('/users/regenerate_login_token', 'UsersController@regenerateLoginToken')->name('users.regenerate_login_token');

Route::post('/users/follow/{id}', 'UsersController@doFollow')->name('users.doFollow');
Route::get('/users/{id}/edit_email_notify', 'UsersController@editEmailNotify')->name('users.edit_email_notify');
Route::post('/users/{id}/update_email_notify', 'UsersController@updateEmailNotify')->name('users.update_email_notify');
Route::get('/users/{id}/edit_password', 'UsersController@editPassword')->name('users.edit_password');
Route::patch('/users/{id}/update_password', 'UsersController@updatePassword')->name('users.update_password');
Route::get('/users/{id}/edit_social_binding', 'UsersController@editSocialBinding')->name('users.edit_social_binding');

Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/{id}', 'UsersController@show')->name('users.show');
Route::get('/users/{id}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{id}', 'UsersController@update')->name('users.update');
Route::get('/users/{id}/edit_avatar', 'UsersController@editAvatar')->name('users.edit_avatar');
Route::patch('/users/{id}/update_avatar', 'UsersController@updateAvatar')->name('users.update_avatar');

Route::get('/notifications/unread', 'NotificationsController@unread')->name('notifications.unread');
Route::get('/notifications', 'NotificationsController@index')->name('notifications.index');
Route::get('/notifications/count', 'NotificationsController@count')->name('notifications.count');

Route::get('/messages', 'MessagesController@index')->name('messages.index');
Route::get('/messages/to/{id}', 'MessagesController@create')->name('messages.create');
Route::post('/messages', 'MessagesController@store')->name('messages.store');
Route::get('/messages/{id}', 'MessagesController@show')->name('messages.show');
Route::put('/messages/{id}', 'MessagesController@update')->name('messages.update');

Route::get('/email-verification-required', 'UsersController@emailVerificationRequired')->name('email-verification-required');
Route::post('/users/send-verification-mail', 'UsersController@sendVerificationMail')->name('users.send-verification-mail');

# ------------------ Authentication ------------------------

Route::get('/login', 'Auth\AuthController@oauth')->name('login');
Route::get('/auth/login', 'Auth\AuthController@signin')->name('auth.login');
Route::post('/auth/login', 'Auth\AuthController@postLogin')->name('auth.login');
Route::get('/login-required', 'Auth\AuthController@loginRequired')->name('login-required');
Route::get('/admin-required', 'Auth\AuthController@adminRequired')->name('admin-required');
Route::get('/user-banned', 'Auth\AuthController@userBanned')->name('user-banned');
Route::get('/signup', 'Auth\AuthController@create')->name('signup');
Route::post('/signup', 'Auth\AuthController@createNewUser')->name('signup');
Route::get('/logout', 'Auth\AuthController@logout')->name('logout');
Route::get('/oauth', 'Auth\AuthController@getOauth');

Route::get('/auth/oauth', 'Auth\AuthController@oauth')->name('auth.oauth');
Route::get('/auth/callback', 'Auth\AuthController@callback')->name('auth.callback');
Route::get('/verification/{token}', 'Auth\AuthController@getVerification')->name('verification');

# ------------------ Categories ------------------------

Route::get('categories/{id}', 'CategoriesController@show')->name('categories.show');

# ------------------ Site ------------------------

Route::get('/sites', 'SitesController@index')->name('sites.index');

# ------------------ Replies ------------------------

Route::post('/replies', 'RepliesController@store')->name('replies.store')->middleware('verified_email');
Route::delete('replies/delete/{id}', 'RepliesController@destroy')->name('replies.destroy')->middleware('auth');

# ------------------ Topic ------------------------
Route::get('/topics', 'TopicsController@index')->name('topics.index');
Route::get('/topics/create', 'TopicsController@create')->name('topics.create')->middleware('verified_email');
Route::post('/topics', 'TopicsController@store')->name('topics.store')->middleware('verified_email');
Route::get('/topics/{id}/edit', 'TopicsController@edit')->name('topics.edit');
Route::patch('/topics/{id}', 'TopicsController@update')->name('topics.update');
Route::delete('/topics/{id}', 'TopicsController@destroy')->name('topics.destroy');
Route::post('/topics/{id}/append', 'TopicsController@append')->name('topics.append');

# ------------------ User Topic Actions ------------------------

Route::group(['before' => 'auth'], function () {
    Route::post('/topics/{id}/upvote', 'TopicsController@upvote')->name('topics.upvote');
    Route::post('/topics/{id}/downvote', 'TopicsController@downvote')->name('topics.downvote');
    Route::post('/replies/{id}/vote', 'RepliesController@vote')->name('replies.vote');
    Route::post('/attentions/{id}', 'AttentionsController@createOrDelete')->name('attentions.createOrDelete');
});

# ------------------ Admin Route ------------------------

Route::group(['before' => 'manage_topics'], function () {
    Route::post('topics/recommend/{id}', 'TopicsController@recommend')->name('topics.recommend');
    Route::post('topics/pin/{id}', 'TopicsController@pin')->name('topics.pin');
    Route::delete('topics/delete/{id}', 'TopicsController@destroy')->name('topics.destroy');
    Route::post('topics/sink/{id}', 'TopicsController@sink')->name('topics.sink');
});

Route::group(['before' => 'manage_users'], function () {
    Route::post('users/blocking/{id}', 'UsersController@blocking')->name('users.blocking');
});

Route::post('/upload_image', 'TopicsController@uploadImage')->name('upload_image')->middleware('auth');

// Health Checking
Route::get('heartbeat', function () {
    return Category::first();
});

Route::get('/github-api-proxy/users/{username}', 'UsersController@githubApiProxy')->name('users.github-api-proxy');
Route::group(['middleware' => ['auth', 'admin_auth']], function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

# ------------------ Blogs ------------------------

Route::get('/blogs', 'BlogsController@index')->name('blogs.index');
Route::get('/blogs/create', 'BlogsController@create')->name('blogs.create')->middleware('verified_email');
Route::post('/blogs', 'BlogsController@store')->name('blogs.store')->middleware('verified_email');
Route::get('/blogs/{id}/edit', 'BlogsController@edit')->name('blogs.edit');
Route::patch('/blogs/{id}', 'BlogsController@update')->name('blogs.update');

Route::post('/blogs/{blog}/subscribe', 'BlogsController@subscribe')->name('blogs.subscribe');
Route::post('/blogs/{blog}/unsubscribe', 'BlogsController@unsubscribe')->name('blogs.unsubscribe');

// Article
Route::get("/articles/create", "ArticlesController@create")->name('articles.create')->middleware('verified_email');
Route::patch("/topics/{id}/transform", "ArticlesController@transform")->name('articles.transform');
Route::post("/articles", "ArticlesController@store")->name('articles.store')->middleware('verified_email');
Route::get("/articles/{id}/edit", "ArticlesController@edit")->name('articles.edit');

Route::get('/topics/{id}/{slug?}', 'TopicsController@show')->name('topics.show');
Route::get('/articles/{id}/{slug?}', "TopicsController@show")->name('articles.show');

# ------------------ ShareLinks ------------------------

Route::get('/links/share', 'ShareLinksController@createLink')->name('share_links.create');
Route::get('/share_links/{id}/edit', 'ShareLinksController@edit')->name('share_links.edit');

# ------------------ Wild Card ------------------------

Route::get('{name}', 'PagesController@wildcard')->name('wildcard');