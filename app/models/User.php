<?php

class User extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=45, nullable=false)
     */
    public $username;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $date_created;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $last_login;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("agiledev");
        $this->setSource("user");
        $this->hasMany('username', 'Task', 'created_by', ['alias' => 'Task']);
        $this->hasMany('username', 'TaskAssignee', 'username', ['alias' => 'TaskAssignee']);
        $this->hasMany('username', 'UserContact', 'username', ['alias' => 'UserContact']);
        $this->hasMany('username', 'UserInfo', 'username', ['alias' => 'UserInfo']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
