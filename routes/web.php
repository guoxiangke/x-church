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
    $socialUser = $socialUser->user;
    $avatar = $socialUser['avatar'];
    $socialEmail = $socialUser['email'];
    $wxid = $socialUser['wxid'];
    // 如果已登陆
    if($user = Auth::user()){
        // 执行绑定！
    }else{
        // 未登录，执行登录！
        $user = User::whereMeta('wxid', $wxid)->first()?:User::where('email', $socialEmail)->first();
        if(!$user){
            $user = User::create([
                'name' => $socialUser['nickname'],
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
    $user->update([
        'name' => $socialUser['nickname'],
        'profile_photo_path' => $avatar,
    ]);
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
    
    $date = now()->format('md');
    $dateY = now()->format('Y');
    $dateN = now()->format('n');
    $dateJ = now()->format('j');
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
    
    // 报名人数更新 /event_enrolls/{{$enrollId}}/update
    Route::get('/event_enrolls/{eventEnroll}/counts', [EventEnrollController::class, 'counts'])->name('event_enrolls.counts');
    // 取消报名？ /event_enrolls/{{$enrollId}}/cancel
    Route::get('/event_enrolls/{eventEnroll}/cancel', [EventEnrollController::class, 'cancel'])->name('event_enrolls.cancel');

    Route::get('/e/{event:hashid}/enrollment', PageEventHelperByEnrollment::class)->name('helper.by.enrollment');
    // 辅助报名：录入的联系人Contact，没有微信，如何为他们签到？
    Route::get('/e/{event:hashid}/contacts',   PageEventHelperByContact::class )->name('helper.by.contacts');

});