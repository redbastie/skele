<?php

namespace Redbastie\Skele\Components;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Lukeraymonddowning\Honey\Traits\WithHoney;

class RegisterComponent extends Component
{
    use WithHoney;

    public $routeUri = '/register';
    public $routeName = 'register';
    public $routeMiddleware = 'guest';

    public function render()
    {
        return view('auth.register');
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ];
    }

    public function register()
    {
        $validated = $this->validate();

        if (!$this->honeyPasses()) {
            return;
        }

        $user = User::create($validated);

        Auth::login($user, true);

        return $this->redirectAfter();
    }

    public function redirectAfter()
    {
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
