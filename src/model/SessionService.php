<?php

/**
 * This class contains methods for handling session
 * 
 * @author Svante Arvedson
 */
class SessionService
{
	/**
	 * Return value stored in session
	 * 
	 * @param string $key	The key to get the session variable
	 * @return mixed		The value in the variable
	 */
	public function load($key)
	{
		return $_SESSION[$key];
	}
	
	/**
	 * Check if a session variable is set
	 * 
	 * @param string $key	The key to the variable to check
	 * @return bool			TRUE if variable is set, else FALSE
	 */
	public function issetVar($key)
	{
		return isset($_SESSION[$key]);
	}
		
	/**
	 * Sets a session variable
	 * 
	 * @param string $key	The key to the assigned variable
	 * @param mixed $value	The value stored in the variable
	 * @return void
	 */
	public function save($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Unsets a session variable
	 * 
	 * @param string $key	The key to unset
	 * @return void
	 */
	public function remove($key)
	{
		unset($_SESSION[$key]);
	}
}