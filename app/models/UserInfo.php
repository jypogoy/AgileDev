<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class UserInfo extends \Phalcon\Mvc\Model
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
    public $first_name;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $last_name;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $job_title;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=false)
     */
    public $location;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    public $phone;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    public $skype;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("agiledev");
        $this->setSource("user_info");
        $this->belongsTo('username', '\User', 'username', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_info';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserInfo[]|UserInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
