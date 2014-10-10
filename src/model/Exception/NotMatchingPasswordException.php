<?php

/**
 * @author Svante Arvedson
 */
class NotMatchingPasswordException extends Exception
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