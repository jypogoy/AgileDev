<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UserInfoController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for user_info
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'UserInfo', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "username";

        $user_info = UserInfo::find($parameters);
        if (count($user_info) == 0) {
            $this->flash->notice("The search did not find any user_info");

            $this->dispatcher->forward([
                "controller" => "user_info",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $user_info,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a user_info
     *
     * @param string $username
     */
    public function editAction($username)
    {
        if (!$this->request->isPost()) {

            $user_info = UserInfo::findFirstByusername($username);
            if (!$user_info) {
                $this->flash->error("user_info was not found");

                $this->dispatcher->forward([
                    'controller' => "user_info",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->username = $user_info->username;

            $this->tag->setDefault("username", $user_info->username);
            $this->tag->setDefault("first_name", $user_info->first_name);
            $this->tag->setDefault("last_name", $user_info->last_name);
            $this->tag->setDefault("job_title", $user_info->job_title);
            $this->tag->setDefault("location", $user_info->location);
            $this->tag->setDefault("email", $user_info->email);
            $this->tag->setDefault("phone", $user_info->phone);
            $this->tag->setDefault("skype", $user_info->skype);
            
        }
    }

    /**
     * Creates a new user_info
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user_info",
                'action' => 'index'
            ]);

            return;
        }

        $user_info = new UserInfo();
        $user_info->username = $this->request->getPost("username");
        $user_info->firstName = $this->request->getPost("first_name");
        $user_info->lastName = $this->request->getPost("last_name");
        $user_info->jobTitle = $this->request->getPost("job_title");
        $user_info->location = $this->request->getPost("location");
        $user_info->email = $this->request->getPost("email", "email");
        $user_info->phone = $this->request->getPost("phone");
        $user_info->skype = $this->request->getPost("skype");
        

        if (!$user_info->save()) {
            foreach ($user_info->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_info",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("user_info was created successfully");

        $this->dispatcher->forward([
            'controller' => "user_info",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a user_info edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user_info",
                'action' => 'index'
            ]);

            return;
        }

        $username = $this->request->getPost("username");
        $user_info = UserInfo::findFirstByusername($username);

        if (!$user_info) {
            $this->flash->error("user_info does not exist " . $username);

            $this->dispatcher->forward([
                'controller' => "user_info",
                'action' => 'index'
            ]);

            return;
        }

        $user_info->username = $this->request->getPost("username");
        $user_info->firstName = $this->request->getPost("first_name");
        $user_info->lastName = $this->request->getPost("last_name");
        $user_info->jobTitle = $this->request->getPost("job_title");
        $user_info->location = $this->request->getPost("location");
        $user_info->email = $this->request->getPost("email", "email");
        $user_info->phone = $this->request->getPost("phone");
        $user_info->skype = $this->request->getPost("skype");
        

        if (!$user_info->save()) {

            foreach ($user_info->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_info",
                'action' => 'edit',
                'params' => [$user_info->username]
            ]);

            return;
        }

        $this->flash->success("user_info was updated successfully");

        $this->dispatcher->forward([
            'controller' => "user_info",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a user_info
     *
     * @param string $username
     */
    public function deleteAction($username)
    {
        $user_info = UserInfo::findFirstByusername($username);
        if (!$user_info) {
            $this->flash->error("user_info was not found");

            $this->dispatcher->forward([
                'controller' => "user_info",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user_info->delete()) {

            foreach ($user_info->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_info",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("user_info was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "user_info",
            'action' => "index"
        ]);
    }

}
