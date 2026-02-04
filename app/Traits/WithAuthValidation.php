<?php

namespace App\Traits;

trait WithAuthValidation
{
    protected function loginRules()
    {
        return [
            'email' => 'required|email:dns',
            'password' => 'required',
        ];
    }
    protected function loginMessages()
    {
        return [
            'email.required' => 'email perlu di isi',
            'email.email' => 'format email salah',
            'password.required' => 'password perlu di isi',
        ];
    }
    protected function registerRules()
    {
        return [
            'username' => 'required',
            'email' => 'required|email:dns|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ];
    }
    protected function registerMessages()
    {
        return [
            'username.required' => 'username perlu di isi',
            'email.required' => 'email perlu di isi',
            'email.email' => 'format email salah',
            'email.unique' => 'email sudah terdaftar',
            'password.required' => 'password perlu di isi',
            'password.min' => 'password kurang panjang',
            'password.confirmed' => 'password tidak sama',
        ];
    }
    protected function validateLogin()
    {
        return $this->validate(
            $this->loginRules(),
            $this->loginMessages()
        );
    }
    protected function validateRegister()
    {
        return $this->validate(
            $this->registerRules(),
            $this->registerMessages()
        );
    }
}
