<?php

class ProjectsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->session->set('active_menu', 'projects');
    }

    public function searchAction()
    {

    }

    public function newAction()
    {
        $this->session->set('is_edit', false);
    }

    public function showAction()
    {

    }

    public function editAction()
    {
        $this->session->set('is_edit', true);
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->response->redirect('projects');
            return;
        }

        $project = new Project();
        $project->name = $this->request->getPost('name');
        $project->description = $this->post->getPost('description');

        if (!$project->save()) {
            foreach ($project->getMessages() as $value) {
                $this->flash->error($message);
            }
            
            $this->dispatcher->forward(['controller' => 'projects', 'action' => 'new']);
            return;
        }

        $this->flashSession->success('New project was created successfully.');
        $this->response->redirect('projects');
        
        return;
    }

    public function saveAction()
    {

    }

    public function deleteAction()
    {
        
    }
}

