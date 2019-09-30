<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\SocialAccount;
use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/home';
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function Redirect($provider)
    {
       return Socialite::driver($provider)->redirect();
    }
    public function Callback($provider)
    {
       $providerUser = Socialite::driver($provider)->user();
       $user = $this->createOrGetUser($provider, $providerUser);
       auth()->login($user);
       return redirect()->to('/home');
    }

    public function createOrGetUser($provider, $providerUser)
    {
        $account = SocialAccount::whereProvider($provider)
                                ->whereProviderUserId($providerUser->getId())
                                ->first();
        if(!Empty($account)){
            return $account->user;
        }else{
            $user = User::whereEmail($providerUser->getEmail())->first();
          
            if (!Empty($user)) {
                $user = User::create([
                  'email' => $providerUser->getEmail(),
                  'name' => $providerUser->getName(),
                  'password' => md5(rand(1,10000)),
                ]);
            }
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider
            ]);
            $account->user()->associate($user);
            $account->save();
            return $user;
        }
    }
}
