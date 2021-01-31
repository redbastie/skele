<?php

namespace Redbastie\Skele\Components;

use Illuminate\Support\Facades\Password;

class PasswordForgotComponent extends Component
{
    public $routeUri = '/password-forgot';
    public $routeName = 'password.forgot';
    public $routeMiddleware = 'guest';
    public $sent;

    public function render()
    {
        return view('auth.password-forgot');
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function sendPasswordResetLink()
    {
        $validated = $this->validate();

        $response = Password::sendResetLink($validated);

        if ($response != Password::RESET_LINK_SENT) {
            $this->addError('email', __($response));

            return;
        }

        $this->sent = __($response);
    }
}
