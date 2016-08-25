<?php

/*
 * This file is part of OAuth 2.0 Laravel.
 *
 * (c) Luca Degasperi <packages@lucadegasperi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Grant Types
    |--------------------------------------------------------------------------
    |
    | Your OAuth2 Server can issue an access token based on different grant
    | types you can even provide your own grant type.
    |
    | To choose which grant type suits your scenario, see
    | http://oauth2.thephpleague.com/authorization-server/which-grant
    |
    | Please see this link to find available grant types
    | http://git.io/vJLAv
    |
    */

    'grant_types' => [

        // accesstoken 过期时间，以返回的时间的准，单位为秒，注意过期时提醒用户重新授权
        // 类似于：
        // 1. http://open.weibo.com/wiki/Oauth2/access_token
        // 2. https://developers.douban.com/wiki/?title=oauth2

        /*
         * 使用 login_token 获取 access_token
         */
        'login_token' => [
            'class'            => \Phphub\OAuth\LoginTokenGrant::class,
            'callback'         => \Phphub\OAuth\LoginTokenVerifier::class.'@verify',
            'access_token_ttl' => (int) env('OAUTH_ACCESS_TOKEN_TTL', 2592000),
        ],

        /*
         * 在用户还未登陆的时候使用，可访问部分资源
         */
        'client_credentials' => [
            'class'            => \League\OAuth2\Server\Grant\ClientCredentialsGrant::class,
            'access_token_ttl' => (int) env('OAUTH_CLIENT_ACCESS_TOKEN_TTL', 2592000),
        ],

        /*
         * 使用此授权方法来更新过期的 Token
         */
        'refresh_token' => [
            'class'             => \League\OAuth2\Server\Grant\RefreshTokenGrant::class,
            'access_token_ttl'  => (int) env('OAUTH_ACCESS_TOKEN_TTL', 2592000),
            'refresh_token_ttl' => (int) env('OAUTH_REFRESH_TOKEN_TTL', 5184000),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Output Token Type
    |--------------------------------------------------------------------------
    |
    | This will tell the authorization server the output format for the access
    | token and the resource server how to parse the access token used.
    |
    | Default value is League\OAuth2\Server\TokenType\Bearer
    |
    */

    'token_type' => League\OAuth2\Server\TokenType\Bearer::class,
    /*
    |--------------------------------------------------------------------------
    | State Parameter
    |--------------------------------------------------------------------------
    |
    | Whether or not the state parameter is required in the query string.
    |
    */

    'state_param' => false,
    /*
    |--------------------------------------------------------------------------
    | Scope Parameter
    |--------------------------------------------------------------------------
    |
    | Whether or not the scope parameter is required in the query string.
    |
    */

    'scope_param' => false,
    /*
    |--------------------------------------------------------------------------
    | Scope Delimiter
    |--------------------------------------------------------------------------
    |
    | Which character to use to split the scope parameter in the query string.
    |
    */

    'scope_delimiter' => ',',
    /*
    |--------------------------------------------------------------------------
    | Default Scope
    |--------------------------------------------------------------------------
    |
    | The default scope to use if not present in the query string.
    |
    */

    'default_scope' => null,
    /*
    |--------------------------------------------------------------------------
    | Access Token TTL
    |--------------------------------------------------------------------------
    |
    | For how long the issued access token is valid (in seconds) this can be
    | also set on a per grant-type basis.
    |
    */

    'access_token_ttl' => 3600,
    /*
    |--------------------------------------------------------------------------
    | Limit clients to specific grants
    |--------------------------------------------------------------------------
    |
    | Whether or not to limit clients to specific grant types. This is useful
    | to allow only trusted clients to access your API differently.
    |
    */

    'limit_clients_to_grants' => false,
    /*
    |--------------------------------------------------------------------------
    | Limit clients to specific scopes
    |--------------------------------------------------------------------------
    |
    | Whether or not to limit clients to specific scopes. This is useful to
    | only allow specific clients to use some scopes.
    |
    */

    'limit_clients_to_scopes' => false,
    /*
    |--------------------------------------------------------------------------
    | Limit scopes to specific grants
    |--------------------------------------------------------------------------
    |
    | Whether or not to limit scopes to specific grants. This is useful to
    | allow certain scopes to be used only with certain grant types.
    |
    */

    'limit_scopes_to_grants' => false,
    /*
    |--------------------------------------------------------------------------
    | HTTP Header Only
    |--------------------------------------------------------------------------
    |
    | This will tell the resource server where to check for the access_token.
    | By default it checks both the query string and the http headers.
    |
    */

    'http_headers_only' => true,

];
