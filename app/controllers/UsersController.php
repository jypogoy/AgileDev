<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->session->set('active_menu', 'users');
        $this->persistent->parameters = null;
    }

    public function newAction()
    {

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
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'new'
            ]);
            
            return;
        }
        
        // $this->response->redirect("users");
    }
}

