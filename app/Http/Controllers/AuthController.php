<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;


class AuthController extends Controller
{
    /**********************************************************
     * login
     **********************************************************/
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        //try to login
        try {
            $response = $http->post('http://carmeer.com/oauth/token', ['form_params' => [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => 'W6F6uuNLRuuXiWQlM6iSDYsNtpRbe6hSNI5DEs0j',
                'username' => $request->email,
                'password' => $request->password
            ]]);
            //return token on success
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            //return error message on error
            if ($e->getCode() == 400) {
                return response()->json('invalid Request', $e->getCode());
            } else if ($e->getCode() == 401) {
                return response()->json('your credential are incorrect', $e->getCode());
            }
            return response()->json('something went wrong on server', $e->getCode());
        }
    }
    /**********************************************************
     * register
     *********************************************************/
    public function register(Request $request)
    {

        //create new user
        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post('http://carmeer.com/api/users', ['form_params' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() == 400) {
                return response()->json('invalid Request', $e->getCode());
            } else if ($e->getCode() == 401) {
                return response()->json('your credential are incorrect', $e->getCode());
            }
            return response()->json('something went wrong on server', $e->getCode());
        }
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
    /*************************************************************************
     * logout
     *************************************************************************/
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('logged out successfully', 200);
    }
}
