<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class TaskController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for task
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Task', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $task = Task::find($parameters);
        if (count($task) == 0) {
            $this->flash->notice("The search did not find any task");

            $this->dispatcher->forward([
                "controller" => "task",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $task,
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
     * Edits a task
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $task = Task::findFirstByid($id);
            if (!$task) {
                $this->flash->error("task was not found");

                $this->dispatcher->forward([
                    'controller' => "task",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $task->id;

            $this->tag->setDefault("id", $task->id);
            $this->tag->setDefault("details", $task->details);
            $this->tag->setDefault("due_date", $task->due_date);
            $this->tag->setDefault("priority_id", $task->priority_id);
            $this->tag->setDefault("parent_task_id", $task->parent_task_id);
            $this->tag->setDefault("date_created", $task->date_created);
            $this->tag->setDefault("created_by", $task->created_by);
            
        }
    }

    /**
     * Creates a new task
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "task",
                'action' => 'index'
            ]);

            return;
        }

        $task = new Task();
        $task->details = $this->request->getPost("details");
        $task->dueDate = $this->request->getPost("due_date");
        $task->priorityId = $this->request->getPost("priority_id");
        $task->parentTaskId = $this->request->getPost("parent_task_id");
        $task->dateCreated = $this->request->getPost("date_created");
        $task->createdBy = $this->request->getPost("created_by");
        

        if (!$task->save()) {
            foreach ($task->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "task",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("task was created successfully");

        $this->dispatcher->forward([
            'controller' => "task",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a task edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "task",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $task = Task::findFirstByid($id);

        if (!$task) {
            $this->flash->error("task does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "task",
                'action' => 'index'
            ]);

            return;
        }

        $task->details = $this->request->getPost("details");
        $task->dueDate = $this->request->getPost("due_date");
        $task->priorityId = $this->request->getPost("priority_id");
        $task->parentTaskId = $this->request->getPost("parent_task_id");
        $task->dateCreated = $this->request->getPost("date_created");
        $task->createdBy = $this->request->getPost("created_by");
        

        if (!$task->save()) {

            foreach ($task->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "task",
                'action' => 'edit',
                'params' => [$task->id]
            ]);

            return;
        }

        $this->flash->success("task was updated successfully");

        $this->dispatcher->forward([
            'controller' => "task",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a task
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $task = Task::findFirstByid($id);
        if (!$task) {
            $this->flash->error("task was not found");

            $this->dispatcher->forward([
                'controller' => "task",
                'action' => 'index'
            ]);

            return;
        }

        if (!$task->delete()) {

            foreach ($task->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "task",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("task was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "task",
            'action' => "index"
        ]);
    }

}
