<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    private $errFieldMsg = array();

    public function indexAction()
    {
        $this->session->set('active_menu', 'users');
    }

    public function newAction()
    {
        
    }

    public function createAction()
    {
        if (!$this->request->isAjax()) {
            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'index'
            ]);
            return;
        }

        $user = new User();
        $user->username = $this->request->getPost("username");
        $user->password = $this->request->getPost("password");

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $errFieldMsg[$message->getField()] = $message->getMessage();
            }
           
            //$messageProvider = $di->get('MessageProvider');
            //$messageProvider->setMessages($errFields);

            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'index'
            ]);
            
            return;
        }
        
        // $this->response->redirect("users");
    }

    
}

