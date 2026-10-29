<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'weixin' => [
        'client_id' => env('WEIXIN_KEY'),
        'client_secret' => env('WEIXIN_SECRET'),
        'redirect' => env('APP_URL', 'https://abc.dev') . env('WEIXIN_REDIRECT_URI'),
        // 这一行配置非常重要，必须要写成这个地址。
        'auth_base_uri' => 'https://open.weixin.qq.com/connect/qrconnect',
    ],
    
    'xbot' => [
        'endpoint' => env('XBOT_ENDPOINT'),
        'token' => env('XBOT_TOKEN'),
    ],

    // 永不止息 app 签发签到票据（HS256）的共享密钥，与 worker 的 XCHURCH_TICKET_SECRET 一致
    'ybzx' => [
        'ticket_secret' => env('YBZX_TICKET_SECRET'),
        // 不在微信里打开签到码时跳去的 app 下载页
        'download_url' => env('YBZX_DOWNLOAD_URL', 'https://app.51chat.net'),
    ],

    'laravelpassport' => [
      'client_id' => env('LARAVELPASSPORT_CLIENT_ID'),  
      'client_secret' => env('LARAVELPASSPORT_CLIENT_SECRET'),  
      'redirect' => env('LARAVELPASSPORT_REDIRECT_URI'),
      'host' => env('LARAVELPASSPORT_HOST'),
      'userinfo_uri' => env('LARAVELPASSPORT_URLINFO_URI'),
    ],
    
    'textbee' => [
        'api_key' => env('TEXTBEE_API_KEY'),
        'devices_id' => env('TEXTBEE_DEVICES_ID'),
        'recipient' => env('TEXTBEE_RECIPIENT'),
    ],
    
    'sms' => [
        'endpoint' => env('SMS_ENDPOINT'),
        'token' => env('SMS_ACCESS_TOKEN'),
    ],

];
