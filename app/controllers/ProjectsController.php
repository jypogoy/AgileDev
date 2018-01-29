<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ProjectsController extends \Phalcon\Mvc\Controller
{

    const ITEMS_PER_PAGE = 10;    

    public function indexAction()
    {
        $this->session->set('active_menu', 'projects');
        $this->session->set('is_edit', false);    
        $this->persistent->parameters = null;    

        $itemsPerPage = self::ITEMS_PER_PAGE;
        $currentPage = 1;
        $projects = array();
        $keyword = null;
        $sortField = 'name';
        $sortOrder = 'ASC';

        if ($this->request->isPost()) {
            
            $keyword = $this->request->getPost("keyword");

            if (strlen($keyword) > 0) {
                $this->session->set('page', 1);      
                if ($this->session->get('keyword') != $keyword) {
                    $this->session->set('keyword', $keyword);
                }          
            } else {                
                $this->session->set('keyword', null);
            }   
        } else {
            if (strlen($keyword) > 0) {
                $this->session->set('keyword', $keyword);                
            } else {
                if ($this->session->get('keyword') != null) {
                    $keyword = $this->session->get('keyword');
                }
            }
        }

        $params = array();
        $params["conditions"] = "name LIKE '%" . $keyword . "%'";
        $this->persistent->parameters = $params;

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = $sortField . ' ' . $sortOrder;        

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

        // Create a Model paginator, show number of rows by page starting from $currentPage
        $paginator = new Paginator([
            'data' => $projects,
            'limit'=> $itemsPerPage,
            'page' => $currentPage
        ]);

        // Get the paginated results
        $page = $paginator->getPaginate();
        $totalItems = count($projects);
        $start = ($page->current - 1) * $itemsPerPage + 1;
        $end = $totalItems;

        if ($itemsPerPage < $page->items) {
            $end = $itemsPerPage * $page->current;
            if ($end > $totalItems) {
                $end = $totalItems;
            }
        }

        $this->view->keyword = $keyword;
        $this->view->sortField = $sortField;
        $this->view->sortOrder = $sortOrder;
        $this->view->page = $page;
        $this->view->start = $start;
        $this->view->end = $end;
        $this->view->totalItems = $totalItems;

        if (count($projects) == 0) {
            $this->flash->notice("The search did not find any project.");
            return;
        }

        $pages = self::pagination($currentPage, $page->total_pages);
        $this->view->pages = $pages;
    }

    function pagination($c, $m) 
    {
        $current = $c;
        $last = $m;
        $delta = 1;
        $left = $current - $delta;
        $right = $current + $delta + 1;
        $range = array();
        $rangeWithDots = array();
        $l = -1;

        for ($i = 1; $i <= $last; $i++) 
        {
            if ($i == 1 || $i == $last || $i >= $left && $i < $right) 
            {
                array_push($range, $i);
            }
        }

        for($i = 0; $i<count($range); $i++) 
        {
            if ($l != -1) 
            {
                if ($range[$i] - $l === 2) 
                {
                    array_push($rangeWithDots, $l + 1);
                } 
                else if ($range[$i] - $l !== 1) 
                {
                    array_push($rangeWithDots, '...');
                }
            }
            
            array_push($rangeWithDots, $range[$i]);
            $l = $range[$i];
        }

        return $rangeWithDots;
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

            $this->persistent->parameters = null;    
            $this->session->remove('keyword');   
            
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

