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
            'order' => 'username'
        ]);

        $this->view->users = $users;             

        $messages = array();
        $this->flashSession->error("HEY");
        if ($this->flashSession->getMessages() && count($this->flashSession->getMessages()) > 0) {
            foreach ($this->flashSession->getMessages() as $type => $message) {
                $messages[$type] = $message[0];
            }
        }

        $this->view->messages = $messages;
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
        $messages = array();
        
        if ($this->flashSession->getMessages() && count($this->flashSession->getMessages()) > 0) {
            foreach ($this->flashSession->getMessages() as $type => $message) {
                $messages[$type] = $message[0];
            }
        }
        
        $this->view->messages = $messages;
    }

    /**
     * Shows the view to "edit" an existing record.
     */
    public function editAction()
    {
        $this->session->set('active_menu', 'users');
        
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

        return $this->response->redirect("users");
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

