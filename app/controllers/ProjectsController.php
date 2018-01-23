<?php

class ProjectsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->session->set('active_menu', 'projects');

        $projects = array();

        try {
            $projects = Project::find([
                'order' => 'date_created'
            ]);
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
        
        $this->view->projects = $projects;       
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

    public function editAction($id)
    {
        $this->session->set('is_edit', true);
        if (!$this->request->isPost()) {

            $project = Project::findFirstById($id);
            if (!$project) {
                $this->flash->error("Project was not found");

                $this->dispatcher->forward([
                    'controller' => "project",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $project->id;
            $this->view->name = $project->name;

            $this->tag->setDefault("name", $project->name);
            $this->tag->setDefault("description", $project->description);
            $this->tag->setDefault("date_created", $project->date_created);
            
        }
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->response->redirect('projects');
            return;
        }

        try {
            $project = new Project();
            $project->name = $this->request->getPost('name');
            $project->description = $this->request->getPost('description');
            
            if (!$project->save()) {
                foreach ($project->getMessages() as $message) {
                    $this->flash->error($message);
                }
                
                $this->dispatcher->forward(['controller' => 'projects', 'action' => 'new']);
                return;
            }

            $this->flashSession->success('New project was created successfully.');
            $this->response->redirect('projects');

        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
            $this->dispatcher->forward(['controller' => 'project', 'action' => 'new']);
            return;
        }    

        return;
    }

    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "project",
                'action' => 'index'
            ]);

            return;
        }

        try {
            $id = $this->request->getPost("id");
            $project = Project::findFirstById($id);

            if (!$project) {
                $this->flash->error("Project does not exist " . $project->name);

                $this->dispatcher->forward([
                    'controller' => "project",
                    'action' => 'index'
                ]);

                return;
            }

            $project->name = $this->request->getPost("name");
            $project->description = $this->request->getPost("description");
            

            if (!$project->save()) {

                foreach ($project->getMessages() as $message) {
                    $this->flash->error($message);
                }

                $this->dispatcher->forward([
                    'controller' => "project",
                    'action' => 'edit',
                    'params' => [$project->id]
                ]);

                return;
            }

            $this->flash->success("Project was updated successfully");

            $this->dispatcher->forward([
                'controller' => "project",
                'action' => 'index'
            ]);

        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
            $this->dispatcher->forward(['controller' => 'project', 'action' => 'edit']);
            return;
        }      
    }

    public function deleteAction()
    {
        
    }
}

