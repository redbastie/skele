<?php

namespace Redbastie\Skele\Components;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class PasswordResetComponent extends Component
{
    public $routeUri = '/password-reset/{token}/{email}';
    public $routeName = 'password.reset';
    public $routeMiddleware = 'guest';

    public function mount($token, $email)
    {
        $this->model = [
            'token' => $token,
            'email' => $email,
        ];
    }

    public function render()
    {
        return view('auth.password-reset');
    }

    public function rules()
    {
        return [
            'password' => ['required', 'confirmed'],
        ];
    }

    public function resetPassword()
    {
        $validated = $this->validate();

        $response = Password::reset($this->model, function (User $user) use ($validated) {
            $user->update($validated);

            Auth::login($user, true);
        });

        if ($response != Password::PASSWORD_RESET) {
            $this->addError('password', __($response));

            return;
        }

        return $this->redirectAfter();
    }

    public function redirectAfter()
    {
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
