<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{

    // Executed before every found action.
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $flash = array();

        foreach ($this->flashSession->getMessages() as $type => $message) {
            if (!isset($flash[$type])) {
                $flash[$type] = $message;
            }
            $flash[$type][] = $message;
        }

        $this->view->messages = $flash;
    }

    // Executed after every found action.
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        
    }

}
