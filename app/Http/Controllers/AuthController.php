<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post('http://carmeer.com/oauth/token', ['form_params' => [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => 'W6F6uuNLRuuXiWQlM6iSDYsNtpRbe6hSNI5DEs0j',
                'username' => $request->email,
                'password' => $request->password
            ]]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() == 400) {
                return response()->json('invalid Request', $e->getCode());
            } else if ($e->getCode() == 401) {
                return response()->json('your credential are incorrect', $e->getCode());
            }
            return response()->json('something went wrong on server', $e->getCode());
        }
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)

        ]);
    }
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('logged out successfully', 200);
    }
}
