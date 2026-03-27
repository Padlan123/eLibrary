<?php

namespace App\Traits;

trait WithFlashMessages
{
    public function flashMessage($type, $message, $route, $param = '')
    {
        session()->flash($type, $message);
        $this->reset();
        $this->redirect(route($route, $param));
    }
}
