<?php

class MessageProvider 
{

    private $messages;

    public function setMessages($errorMessages){
        $messages = $errorMessages;
    }

    /**
     * Prints message for a specific element.
     */
    public function messages($name) 
    {
        return $this->messages[$name];
    }

}