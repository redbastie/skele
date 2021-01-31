<?php

namespace Redbastie\Skele\Components;

use Illuminate\Support\Facades\Auth;

class LogoutComponent extends Component
{
    public $routeUri = '/logout';
    public $routeName = 'logout';
    public $routeMiddleware = 'auth';

    public function mount()
    {
        Auth::logout();

        return $this->redirectAfter();
    }

    public function redirectAfter()
    {
        return redirect()->route('index');
    }
}
