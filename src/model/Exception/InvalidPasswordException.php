<?php

/**
 * @author Svante Arvedson
 */
class InvalidPasswordException extends Exception
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