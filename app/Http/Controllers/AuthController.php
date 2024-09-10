<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Auth;

class AuthController extends Controller
{


    public function redirectToGoogle()
    {
        $config = config('services.google');

        $query = http_build_query([
            'response_type' => $config['response_type'],
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect'],
            'scope' => $config['scope'],
            // 'scopes' => $config['scopes'],
        ]);
        

        return redirect('https://accounts.google.com/o/oauth2/auth?' . $query);
    }

    public function handleGoogleCallback($code)
   {
    $code= $code;

    // return $code;
 

    $user = Socialite::driver($code)->stateless()->user();

    // dd($user);
    
    $authUser = User::firstOrCreate([
        'provider_id' => $user->getId(),
        // 'id' => $user->getId(),
    ], [
        'email' => $user->getEmail(),
        'name' => $user->getName(),
        'provider_name' => $code,
        'provider_id' => $user->getId(),
        'avatar' => $user->getavatar(),
        'password' => '',
    ]);

   
    Auth::login($authUser);

    return redirect('dashboard');
}




}
