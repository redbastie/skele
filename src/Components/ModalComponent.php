<?php

namespace Redbastie\Skele\Components;

class ModalComponent extends Component
{
    public $hidden = true;
    protected $listeners = ['show'];

    public function toggle()
    {
        $this->hidden = !$this->hidden;

        $this->emit($this->hidden ? 'bodyScrollUnlock' : 'bodyScrollLock');
    }
}
