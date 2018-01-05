<?php

class ProjectsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->session->set('active_menu', 'projects');
    }

}

