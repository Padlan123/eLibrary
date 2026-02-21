<?php

namespace App\Traits;

trait WithFlashMessages
{
    public function flashMessage($type, $message, $route)
    {
        session()->flash($type, $message);
        $this->redirect(route($route));
    }
}
