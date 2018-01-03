<?php

class DashboardController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->session->set('active_menu', 'dashboard');
    }

}

