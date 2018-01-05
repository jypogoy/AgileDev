<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->session->set('active_menu', 'users');
    }

}

