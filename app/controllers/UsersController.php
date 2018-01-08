<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->session->set('active_menu', 'users');
        $this->persistent->parameters = null;
    }

    public function createAction()
    {
        $this->flashSession->success("User was created successfully.");

        $this->response->redirect("users");
    }
}

