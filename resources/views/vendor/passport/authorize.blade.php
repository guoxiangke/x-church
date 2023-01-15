@section('title', config('app.name') . '-' . 'Authorization')

<x-wechat-layout>
    <div class="page">
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-safe-success weui-icon_msg"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title">Authorization Request</h2>
            <p class="weui-msg__desc"><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>
            @if (count($scopes) > 0)
            <div class="weui-msg__custom-area">
              <p><strong>This application will be able to:</strong></p>
              <ul class="weui-list-tips">
                @foreach ($scopes as $scope)
                <li role="option" class="weui-list-tips__item">{{ $scope->description }}</li>
                @endforeach
              </ul>
            </div>
            @endif
        </div>
        <div class="weui-msg__opr-area">
            <p class="weui-btn-area">
                <form method="post" action="{{ route('passport.authorizations.approve') }}">
                    @csrf

                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">

                    <button type="submit" role="button" class="weui-btn weui-btn_primary">Authorize</button>
                </form>

            </p>
        </div>
    </div>
</div>

</x-wechat-layout>
