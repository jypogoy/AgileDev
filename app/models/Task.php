<?php

class Task extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=250, nullable=false)
     */
    public $details;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $due_date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $priority_id;

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $parent_task_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $date_created;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $created_by;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("agiledev");
        $this->setSource("task");
        $this->hasMany('id', 'Task', 'parent_task_id', ['alias' => 'Task']);
        $this->belongsTo('priority_id', '\Priority', 'id', ['alias' => 'Priority']);
        $this->belongsTo('parent_task_id', '\Task', 'id', ['alias' => 'Task']);
        $this->belongsTo('created_by', '\User', 'id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'task';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Task[]|Task|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Task|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
