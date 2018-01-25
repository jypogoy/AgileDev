<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ProjectsController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->session->set('active_menu', 'projects');
        $this->session->set('is_edit', false);
        $this->session->set('page', 1);

        $itemsPerPage = 10;
        $currentPage = 1;
        $projects = array();

        if ($this->request->isPost()) {
            $this->persistent->parameters = array();
            $keyword = $this->request->getPost("keyword");
            if (count($keyword) > 0) {
                $params = array();
                $params["conditions"] = "name LIKE '%" . $keyword . "%'";
                $this->persistent->parameters = $params;
            } else {
                $this->persistent->parameters = null;
            }   
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "date_created";

        if ($this->session->get('page') !== null) {
            if ($this->request->getQuery('page', 'int') !== null) {
                $currentPage = $this->request->getQuery('page', 'int');
                $this->session->set('page', $currentPage);
            } else {
                $currentPage = $this->session->get('page');
            }
        } else {
            $currentPage = $this->request->getQuery('page', 'int');
            $this->session->set('page', $currentPage);
        }

        try {
            // The data set to paginate
            $projects = Project::find($parameters);
            
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
        
        if (count($projects) == 0) {
            $this->flash->notice("The search did not find any project.");

            // $this->dispatcher->forward([
            //     "controller" => "projects",
            //     "action" => "index"
            // ]);
            
            return;
        }

        // Create a Model paginator, show 10 rows by page starting from $currentPage
        $paginator = new Paginator([
            'data' => $projects,
            'limit'=> $itemsPerPage,
            'page' => $currentPage
        ]);

        // Get the paginated results
        $page = $paginator->getPaginate();
        $totalItems = count($projects);
        $start = ($page->current - 1) * 10 + 1;
        $end = $totalItems;

        if ($itemsPerPage < $page->items) {
            $end = $itemsPerPage * $page->current;
            if ($end > $totalItems) {
                $end = $totalItems;
            }
        }

        $this->view->page = $page;
        $this->view->start = $start;
        $this->view->end = $end;
        $this->view->totalItems = $totalItems;
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
                    'controller' => "projects",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $project->id;
            $this->view->name = $project->name;

            $this->tag->setDefault("id", $project->id);
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
            $is_saveNew = $this->request->getPost('saveNew');

            if ($is_saveNew) {
                $this->response->redirect('projects/new');
            } else {
                $this->response->redirect('projects');
            }            

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
                'controller' => "projects",
                'action' => 'index'
            ]);

            return;
        }

        try {
            $id = $this->request->getPost("id");
            $project = Project::findFirstById($id);

            if (!$project) {
                $this->flash->error("Project does not exist " . $id);

                $this->dispatcher->forward([
                    'controller' => "projects",
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
                    'controller' => "projects",
                    'action' => 'edit',
                    'params' => [$project->id]
                ]);

                return;
            }

            $this->flashSession->success("Project was updated successfully.");
            $this->response->redirect('projects');

        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
            $this->dispatcher->forward(['controller' => 'project', 'action' => 'edit']);
            return;
        }      
    }

    public function deleteAction($id)
    {
        $project = Project::findFirstById($id);
        if (!$project) {
            $this->flashSession->error("Project was not found.");
            $this->response->redirect('projects');

            return;
        }

        if (!$project->delete()) {

            foreach ($project->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "projects",
                'action' => 'index'
            ]);

            return;
        }

        $this->session->set('page', 1);
        $this->flashSession->success("Project was deleted successfully.");
        $this->response->redirect('projects');
    }
}

