<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{

    // Executed before every found action.
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $messages = $this->flashSession->getMessages();
        
        if (!is_null($messages)) {
            foreach ($messages as $type => $message) {
                echo $type . " : " . $message[0];    
            }  
        }

        $this->view->messages = $messages;
    }

    // Executed after every found action.
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        
    }

}
