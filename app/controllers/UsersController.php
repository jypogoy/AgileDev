<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    /**
     * The start action, it shows the "search" view.
     */
    public function indexAction()
    {
        $this->session->set('active_menu', 'users');      
            
        $users = User::find([
            'order' => 'date_created'
        ]);

        $this->view->users = $users;       
    }

    /**
     * Execute the "search" based on the criteria sent from the "index".
     * Returning a paginator for the results.
     */
    public function searchAction()
    {

    }

    /**
     * Shows the view to create a "new" record.
     */
    public function newAction()
    {
        $this->session->set('is_edit', false);

        $messages = array();
        
        if ($this->flashSession->getMessages() && count($this->flashSession->getMessages()) > 0) {
            foreach ($this->flashSession->getMessages() as $type => $message) {
                $messages[$type] = $message[0];
            }
        }
        
        $this->view->messages = $messages;
    }

    /**
     * Shows the read-only view of an existing record.
     */
    public function showAction($username)
    {
        if (!$this->request->isPost()) {
            $user = User::findFirstByusername($username);
            if (!$user) {
                $this->flash->error("User was not found.");

                $this->dispatcher->forward([
                    'controller' => "users",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->username = $user->username;

            $this->tag->setDefault("username", $user->username);
            $this->tag->setDefault("password", $user->password);
            $this->tag->setDefault("date_created", $user->date_created);
            $this->tag->setDefault("last_login", $user->last_login);
            
        }
    }

    /**
     * Shows the view to "edit" an existing record.
     * @param string $username
     */
    public function editAction($username)
    {
        $this->session->set('is_edit', true);

        if (!$this->request->isPost()) {
            $user = User::findFirstByusername($username);
            if (!$user) {
                $this->flash->error("User was not found.");

                $this->dispatcher->forward([
                    'controller' => "users",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->username = $user->username;

            $this->tag->setDefault("username", $user->username);
            $this->tag->setDefault("password", $user->password);
            $this->tag->setDefault("date_created", $user->date_created);
            $this->tag->setDefault("last_login", $user->last_login);
            
        }
    }

    /**
     * Creates a record based on the data entered in the "new" action.
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->response->redirect("users");
            return;
        }

        $user = new User();
        $user->username = $this->request->getPost("username");
        $user->password = $this->request->getPost("password");

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }
            
            $this->dispatcher->forward(['controller' => "users", 'action' => 'new']);            
            return;
        }
        
        $this->flashSession->success("New user profiles was created successfully.");            
        $this->response->redirect("users");
        
        return false;
    }

    /**
     * Updates a record based on the data entered in the "edit" action.
     */
    public function saveAction()
    {

    }

    /**
     * Deletes an existing record.
     */
    public function deleteAction()
    {

    }
}

