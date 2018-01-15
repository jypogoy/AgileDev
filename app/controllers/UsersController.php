<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

        $users = User::find([
            'order' => 'username'
        ]);

        $this->view->users = $users;    

        $this->session->set('active_menu', 'users');        

        $flash = array();
        
        if (count($this->flashSession->getMessages()) > 0) {
            foreach ($this->flashSession->getMessages() as $type => $message) {
                $flash[$type] = $message[0];
            }
        }

        $this->view->flashMessages = $flash;
    }

    public function newAction()
    {
        $flash = array();

        $this->view->flashMessages = $flash;
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
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
                $this->flashSession->error($message);
            }
           
            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'new'
            ]);
            
            return;
        }
        
        $this->flashSession->success("New user profiles was created successfully.");    

        $this->dispatcher->forward([
            'controller' => "users",
            'action' => 'index'
        ]);
    }

    
}

