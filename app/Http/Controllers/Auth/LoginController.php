<?php

namespace App\Http\Controllers\Auth;

use App\User;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

// googleAuth使用のため追加
use Laravel\Socialite\Facades\Socialite;

// /login /logoutのルーティングで呼び出される
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    // 最大ログイン失敗回数と制限時間設定
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ログアウト後の処理を記述する際はloggedOutメソッドをこのコントローラーに記述
    // useでインポートしているAuthenticatesUsersクラスに既に定義されているためオーバーライド（上書き）となる
    // オーバーライド：親クラスで定義されたメソッドを、子クラスで再定義し上書きすること


    // google認証へのリダイレクト
    public function redirectToProvider(string $provider)
    {
        // リダイレクト先はGoogle
        // Configで設定したGoogle-ID、Seacretを参照している？
        return Socialite::driver($provider)->redirect();
    }

    // googleからの返答後のリダイレクト
    public function handleProviderCallback(Request $request, string $provider)
    {
        // Socialiteは外部プロバイダ用のとして、認証情報(ユーザーネームやパス)を取得できる
        // Google認証を行ったユーザーモデルを取得
        // この時SocialiteクラスはGoogle側に問い合わせを行っている
        $providerUser = Socialite::driver($provider)->stateless()->user();

        // 上記モデルと一致するUserモデルを返す
        $user = User::where('email', $providerUser->getEmail())->first();

        // Google認証したユーザー情報とUserテーブルの情報に一致したものがあれば…
        if ($user) {
            // 上記モデルでログイン(第2引数trueでログイン状態を維持)(rememberMeトークン)
            $this->guard()->login($user, true);

            // sendLoginResponseはLaravelに組み込まれたログイン関係のメソッド
            // 定義元では'/'にリダイレクトする設定
            return $this->sendLoginResponse($request);
        }

        // 一致するUserがいない(未登録)であればGoogleからのログインルートへリダイレクト
        // route()で{}に指定したパラメータはリクエスト(クエリ)パラメータとしてURLに含まれる
        // ↓の場合"register/google?email=foobar@gmail.com&token=xxx"のようにGetメソッドが実行される
        return redirect()->route('register.{provider}', [
            'provider' => $provider,
            'email' => $providerUser->getEmail(),
            'token' => $providerUser->token,
        ]);
    }
}
