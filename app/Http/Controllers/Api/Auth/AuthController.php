<?php

namespace App\Http\Controllers\Api\Auth;

use Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $form = [
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username' => $request->email,
            'password' => $request->password,
        ];

        $request->request->add($form);

        $requestToken = Request::create('oauth/token', 'POST');
        $response = Route::dispatch($requestToken);

        return response()->json(json_decode((string) $response->content(), true), $response->status());
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->revoke();
        });

        return response()->json('Logged out successfully', 200);
    }
}
