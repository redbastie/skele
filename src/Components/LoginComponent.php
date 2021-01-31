<?php

namespace Redbastie\Skele\Components;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginComponent extends Component
{
    public $routeUri = '/login';
    public $routeName = 'login';
    public $routeMiddleware = 'guest';
    public $username = 'email';
    public $attempts = 5;

    public function render()
    {
        return view('auth.login');
    }

    public function rules()
    {
        return [
            $this->username => ['required'],
            'password' => ['required'],
        ];
    }

    public function login()
    {
        $validated = $this->validate();

        if (RateLimiter::tooManyAttempts($this->throttleKey(), $this->attempts)) {
            $this->addError($this->username, __('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]));

            return;
        }
        else if (!Auth::attempt($validated, $this->model('remember'))) {
            RateLimiter::hit($this->throttleKey());

            $this->addError($this->username, __('auth.failed'));

            return;
        }

        return $this->redirectAfter();
    }

    public function redirectAfter()
    {
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function throttleKey()
    {
        return $this->model($this->username) . '|' . request()->ip();
    }
}
