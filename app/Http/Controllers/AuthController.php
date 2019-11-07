<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends ApiController
{
    /**
     * Login to the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $credentials = request(['username', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->ResponseUnauthorized();
        }

        return $this->ResponseWithSuccess(["token" => $token]);
    }
}
