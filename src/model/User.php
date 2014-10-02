<?php

/**
 * Represents the User of the application.
 * 
 * @author Svante Arvedson
 */
class User
{
    /**
     * @var string The users username
     */
    private $username;
    
    /**
     * @var string          The Users password
     */
    private $password;
    
	/**
     * @var string $salt    Salt for crypting password
     */
    private $salt;

	/**
	 * Constructor method for this class
     * The user register is located in a file
	 *
	 * @throws InvalidUsernameException 	If $username isn't valid
     * @throws InvalidPasswordException     If $password isn't valid
     * @throws InvalidArgumentException     If $salt isn't valid
     * 
	 * @param string $username	The username provided by the user
     * @param string $password  The password provided by the user
     * @param string $salt      Salt used to crypt the password
	 */
	public function __construct($username, $password, $salt) 
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
        
	    $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
	}

	/**
	 * Returns $this->username
	 * 
	 * @return string 	$this->username
	 */
	public function getUsername()
	{
		return $this->username;
	}

    /**
     * Returns $this->password
     * 
     * @return string   $this->password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns $this->salt
     * 
     * @return satring  $this->salt
     */
    public function getSalt()
    {
        return $this->salt;
    }
}