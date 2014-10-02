<?php

/**
 * Represents a temporary user of the application
 * 
 * @author Svante Arvedson
 */
class TempUser
{
    /**
     * @var integer $lengthOfPassword   The length of a temporary password
     */
    public static $lengthOfUncryptPass = 12;
    
    /**
     * @var string $username    Users username
     */
    private $username;
    
    /**
     * @var string $password    Users password
     */
    private $password;
    
    /**
     * @var string $salt    Salt for crypting the password
     */
    private $salt;
    
    /**
     * @var string $endTimestamp   Time when TempUser stops being valid
     */
    private $expirationTime;
    
    /**
     * @var string $IP  The temporary users IP address
     */
    private $IP;
     
     /**
      * @var string $browser    The temporary users IP address   
      */
    private $browser; 
    
    /**
     * @param string $username  The users username
     * 
     * @throws InvalidUsernameException If provided username isn't valid
     * @throws InvalidPasswordException If $password isn't valid
     * @throws InvalidArgumentException If parameters isn't valid
     * 
     * @return void
     */
    public function __construct($username, $password, $salt, $expirationTime, $IP, $browser)
    {
        if (!is_string($username) || $username == '')
        {
            throw new InvalidUsernameException('Unvalid username');
        }
        if (!is_string($password) || $password == '')
        {
            throw new InvalidPasswordException('Unvalid password');
        }
        if (!is_string($salt) || $salt == '')
        {
            throw new InvalidArgumentException('Unvalid salt');
        }
        if ($expirationTime <= time())
        {
            throw new InvalidArgumentException('Unvalid timestamp');
        }
        if (!is_string($IP) || $IP == '')
        {
            throw new InvalidArgumentException('Unvalid IP-address');
        }
        if (!is_string($browser) || $browser == '')
        {
            throw new InvalidArgumentException('Unvalid browser information');
        }
        
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->expirationTime = $expirationTime;
        $this->IP = $IP;
        $this->browser = $browser;
    }
    
    /**
     * Returns $this->username
     * 
     * @return string $this->username
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Returns $this->password
     * 
     * @return string $this->password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns $this->salt
     * 
     * @return string $this->salt
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * Returns $this->expirationTime
     * 
     * @return integer $this->expirationTime
     */
    public function getExpirationTime()
    {
        return $this->expirationTime;
    }
    
    /**
     * Returns $this->IP
     * 
     * @return string $this->IP
     */
    public function getIP()
    {
        return $this->IP;
    }
    
    /**
     * Returns $this->browser
     * 
     * @return string $this->browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }   
}