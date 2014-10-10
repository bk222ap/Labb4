<?php

/**
 * @author Svante Arvedson
 */
class UsernameAlreadyExistException extends Exception
{
    /**
     * @param string $message   Error message
     * 
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}