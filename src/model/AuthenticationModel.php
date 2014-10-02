<?php

/**
 * Model representing the authentication module
 * 
 * @author Svante Arvedson
 */
class AuthenticationModel
{
    /**
     * @var integer $expirationOfCookies    The valid length of a cookie in seconds
     */
    private static $expirationOfTempUser = 60;
    
    /**
     * @var string $lengthOfSalt
     */
    private static $lengthOfSalt = 8;
    
    /**
     * @var string $placeBrowser    The session name for the browser information
     */
    private static $placeBrowser = 'AuthenticationModel::Browser';
    
    /**
     * @var string $placeIP     The session name for the IP number information
     */
    private static $placeIP = 'AuthenticationModel::IP';
    
    /**
     * @var $placeUser      The session name for the user object
     */
	private static $placeUser = 'AuthenticationModel::User';
    
    /**
     * @var SessionService $sessionService  An instance of SessionService class
     */
    private $sessionService;
    
    /**
     * @var string $tempPassword
     */
    private $tempPassUncrypt;
    
    /**
     * @var DALUser $DALUser
     */
    private $DALUser;
    
    /**
     * @var DALTempUser
     */
    private $DALTempUser;
    
    /**
     * @return void
     */
    public function __construct()
    {
        $this->sessionService = new SessionService();
        $this->DALTempUser = new DALTempUser();
        $this->DALUser = new DALUser();
    }    

    /**
     * Getter for self::$expirationOfTempUser
     * 
     * @return integer self::$expirationOfTempUser
     */
    public function getExpirationOfTempUser()
    {
        return self::$expirationOfTempUser;
    }

    /**
     * Return the User representing the authenticated user
     * 
     * @return User     The authenticated user
     */
    public function getUser()
    {
        return $this->sessionService->load(self::$placeUser);
    }
	
    /**
     * Return $this->tempPassword
     * 
     * @return string $this->tempPassword
     */
    public function getTempPassword()
    {
        return $this->tempPassword;
    }
    
    /**
     * Returns TRUE if the user is authenticated
     * 
     * @return boolean  TRUE if the user is authenticated
     */
    public function isUserAuthenticated($IP, $browser)
    {
        if ($this->sessionService->issetVar(self::$placeUser) && 
            $this->sessionService->load(self::$placeIP) == $IP && 
            $this->sessionService->load(self::$placeBrowser) === $browser)
        {
            return true;
        }
        return false;
    }
    
    /**
     * Authenticates the user
     * 
     * @param string $username  Users username
     * @param string $password  Users password
     * @param boolean $saveCredentials  If user wants to save her credentials
     * 
     * @throws InvalidUsernameException If the provided username isn't valid
     * @throws InvalidPasswordException If the provided password isn't valid
     * @throws LoginException   If user doesn't exist in the register
     * 
     * @return User The authenicated user
     */
	public function loginUser($username, $password, $IP, $browser, $saveCredentials = false)
	{
	    if (!is_string($username) || $username == '')
        {
            throw new InvalidUsernameException('Unvalid username');
        }
        if (!is_string($password) || $password == '')
        {
            throw new InvalidPasswordException('Unvalid password');
        }
        
        try
        {
	        $user = $this->DALUser->getUserByUsername($username);
            
            if ($user->getPassword() == crypt($password, $user->getSalt()))
            {
               if ($saveCredentials)
                {
                    $this->tempPassword = $this->createPassword();
                    $tempSalt = $this->createSalt();
                    $tempCryptPass = crypt($this->tempPassword, $tempSalt);
                    $expirationTime = time() + self::$expirationOfTempUser;

                    $tempUser = new TempUser($username, $tempCryptPass, $tempSalt, $expirationTime, $IP, $browser);
                    $this->DALTempUser->saveTempUser($tempUser);
                }
                               
                $this->sessionService->save(self::$placeUser, $user);
                $this->sessionService->save(self::$placeIP, $IP);
                $this->sessionService->save(self::$placeBrowser, $browser);
                return $user;
            }
            else
            {
                throw new LoginException('Unexisting user');
            }
        }
        catch(Exception $e)
        {
            throw new LoginException('Error during login');
        }
    }
	
    /**
     * Authenticates an user with saved credentials
     * 
     * @param string $username  Users username
     * @param string $password  Users password
     * 
     * @throws Exception    If provided username or password isn't valid
     * @throws LoginException If user doesn't exist in the register
     * 
     * @return User The authenticated user
     */
    public function loginUserWithCredentials($username, $password, $IP, $browser)
    {
        try
        {
            $tempUser = $this->DALTempUser->getTempUserByUsername($username);

            if ($tempUser->getPassword() === crypt($password, $tempUser->getSalt()) &&
                $IP == $tempUser->getIP() &&
                $browser == $tempUser->getBrowser())
            {
                $user = $this->DALUser->getUserByUsername($username);
                $this->sessionService->save(self::$placeUser, $user);
                $this->sessionService->save(self::$placeIP, $IP);
                $this->sessionService->save(self::$placeBrowser, $browser);
                return $user;
            }
            else
            {
                throw new LoginException('Unexisting user');
            }
        }
        catch (Exception $e)
        {
            throw new LoginException('Unexisting user');
        }
    }
    
    /**
     * Unauthenticate the user
     * 
     * @return void
     */
	public function logoutUser()
	{
		$this->sessionService->remove(self::$placeUser);
        $this->sessionService->remove(self::$placeIP);
        $this->sessionService->remove(self::$placeBrowser);
	}

    /**
     * Creates salt for cryptation of password
     * 
     * @return string   Salt for cryptation of password
     */
    private function createSalt()
    {
        return '_' . $this->generateRandomString(self::$lengthOfSalt, false);
    }

    /**
     * Generates a random password
     * 
     * @return string A random password
     */
    private function createPassword()
    {
        return base64_encode($this->generateRandomString(TempUser::$lengthOfUncryptPass));
    }
    
    /**
     * Generate a string with random characters
     * 
     * @return string   Ranom string
     */
    private function generateRandomString($lengthOfString, $allChars = true)
    {
        $validChars;
        
        if ($allChars)
        {
            $validChars = 'abcdefghijklmnopqrstuvxyABCDEFGHIJKLMNOPQRSTUVXY123456789!"#¤%&/()=?@£${[]}\+-*';
        }
        else
        {
            $validChars = 'abcdefghijklmnopqrstuvxyABCDEFGHIJKLMNOPQRSTUVXY123456789';    
        }
        
        $validCharsLength = strlen($validChars);
        $randString = '';
        
        for ($i = 0; $i < $lengthOfString; $i += 1)
        {
            $index = mt_rand(0, $validCharsLength - 1);
            $randString .= substr($validChars, $index, 1);
        }
        
        return $randString;
    }
}