<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeixinController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\EventEnrollController;

use App\Http\Livewire\PageEventHelperByEnrollment;
use App\Http\Livewire\PageEventHelperByContact;
use App\Models\Contact;
use App\Models\User;
use App\Models\Social;
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

Route::get('/', function () {
    return view('welcome');
});

// 永不止息 app 的 Universal Links / App Links：系统相机扫 /e/、/s/ 签到码时直接打开 app
Route::get('/.well-known/apple-app-site-association', function () {
    return response()->json([
        'applinks' => [
            'apps' => [],
            'details' => [[
                'appID' => 'L833M8JQWM.app.yongbuzhixi.com',
                'appIDs' => ['L833M8JQWM.app.yongbuzhixi.com'],
                'paths' => ['/e/*', '/s/*'],
                'components' => [['/' => '/e/*'], ['/' => '/s/*']],
            ]],
        ],
    ]);
});
Route::get('/.well-known/assetlinks.json', function () {
    return response()->json([[
        'relation' => ['delegate_permission/common.handle_all_urls'],
        'target' => [
            'namespace' => 'android_app',
            'package_name' => 'app.yongbuzhixi.com',
            // 正式签名（CN=Liangyou Lingting）的 SHA-256
            'sha256_cert_fingerprints' => ['37:9B:11:E3:B5:EC:ED:67:A1:D9:08:32:DD:93:B0:C2:AF:2E:07:7C:48:66:2E:86:AE:41:22:A7:4B:C7:7C:65'],
        ],
    ]]);
});
Route::get('/weui/success', function () {
    return view('weui.success');
});
Route::get('/weui/error', function () {
    return view('weui.warn');
});

Route::get('/unsubscribe/{contact}', function (Contact $contact) {
    if($contact->status!=0) {
        $contact->update(['status'=>0]);
        $text = 'Unsubscribe Successful';
    }else{
        $text = 'You will no longer receive email marketing from this list.';
    }
    return [$text];
})->name('unsubscribe')->middleware('signed');



// 1.用户访问403 跳转登录 http://oauth2client.test/user/profile
Route::get('/auth', function () {
    return Socialite::driver('laravelpassport')->redirect();
})->name('login');

// 登录成功后，TODO：需要跳转之前访问的403页面
Route::get('/auth/callback', function () {
    // 获取用户信息，存储用户、登陆、然后再次跳转。
    $socialUser = Socialite::driver('laravelpassport')->user();//stateless()
    // dd($socialUser);
    $socialUser = $socialUser->user;//Array
    $avatar = $socialUser['avatar'];
    $socialEmail = $socialUser['email'];
    $wxid = $socialUser['wxid'];
    $socialType = 0;//weixin
    $name = $socialUser['nickname'] ?: $socialUser['name'];
    if($user = Auth::user()){
        //用户已登录，执行绑定！
    }else{
        // 未登录，执行登录！
        $user = User::whereMeta('wxid', $wxid)->first()?:User::where('email', $socialEmail)->first();
        if(!$user){
            $user = User::create([
                'name' => $name,
                'email' => $socialEmail,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(8)),
                'remember_token' => Str::random(10),
                'profile_photo_path' => $avatar,
            ]);
        }
        $user->setMeta('wxid', $wxid);

        //执行登录！
        Auth::loginUsingId($user->id, true);//自动登入！
    }
    // 
    $user->update([
        // 'name' => $name,//$socialUser['nickname'],
        'profile_photo_path' => $avatar,
    ]);

    // 执行绑定！
    $social = Social::firstOrCreate([
        'social_id' => $wxid,
        'user_id'   => $user->id,
        'type'      => $socialType,
    ]);
    //
    if($social->updated_at->diffInDays(now()) > 1) {
        // $social->name = $name;
        $social->avatar = $avatar;
        if($social->isDirty()){
            $social->save();
        }
    }
    return Redirect::intended('dashboard');
});


// 跳转到/events/{event}/check-in-out 再登录认证，为了纪录新人从哪里event来的。
Route::get('/s/{service:hashid}',  [CheckInController::class, 'serviceRedirectToEvent'])->name('service.checkin');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


Route::get('/devotional', function () {
    $eventEnrolls = User::whereNot('profile_photo_path')->get();
    $now = now()->setTimezone('Asia/Shanghai');
    $date = $now->format('md');
    $dateY = $now->format('Y');
    $dateN = $now->format('n');
    $dateJ = $now->format('j');
    $url = "https://mbcotc.david777.net/devotional/CN/{$date}.html";
    $html = file_get_contents($url);
    $pattern = '/<div class="theme-default-content" vp-content>([\s\S]*?)<\/div>/';
    preg_match($pattern, $html, $matches);
    $content = $matches[1];
    $systemName = '天普市國語浸信會靈修';
    $title = "{$dateN}月{$dateJ}日｜{$systemName}";
    return view('devotional',compact('eventEnrolls','content','title','systemName','dateY'));
})->name('devotional');

    // Route::resources([
    //     'organization'      => OrganizationController::class,
    //     'services'     => ServiceController::class,
    //     'events'        => EventController::class,
    //     // 'rrules' => 'RruleController', //except create!!! create from order
    // ]);


    // /services/{service}/check-in-out
    // /events/{event}/check-in-out
    // https://github.com/mtvs/eloquent-hashids
    // Route::get('/s/{service:hashid}',  [CheckInController::class, 'serviceCheck'])->name('service.checkin');
    Route::get('/e/{event:hashid}',  [CheckInController::class, 'eventCheck'])->name('event.checkin');

    Route::get('/e/status/{eventEnroll}',  [EventEnrollController::class, 'status'])->name('event.status');
    
    // 报名人数更新 /event_enrolls/{{$enrollId}}/update
    Route::get('/event_enrolls/{eventEnroll}/counts', [EventEnrollController::class, 'counts'])->name('event_enrolls.counts');
    // 取消报名？ /event_enrolls/{{$enrollId}}/cancel
    Route::get('/event_enrolls/{eventEnroll}/cancel', [EventEnrollController::class, 'cancel'])->name('event_enrolls.cancel');

    Route::get('/e/{event:hashid}/enrollment', PageEventHelperByEnrollment::class)->name('helper.by.enrollment');
    // 辅助报名：录入的联系人Contact，没有微信，如何为他们签到？
    Route::get('/e/{event:hashid}/contacts',   PageEventHelperByContact::class )->name('helper.by.contacts');

});