<?php

class DALUser
{
    /**
     * @var string $URL URL to file with users
     */
    private static $URL = 'files/Users';
    
    /**
     * Return all users in an array
     * 
     * @return array All existing users
     */
    public function getAllUsers()
    {
        $ret = array();
        $users = file(self::$URL, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($users as $user)
        {
            $userElements = explode(',', $user, 3);
            $ret[] = new User($userElements[0], $userElements[1], $userElements[2]);
        }
        
        return $ret;
    }
    
    /**
     * Return a user
     * 
     * @param string $username  The username of the wanted user
     * 
     * @throws Exception    If no user have $username
     * 
     * @return User The wanted user
     */
    public function getUserByUsername($username)
    {
        $ret;
        $users = file(self::$URL, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($users as $user)
        {
            $userElements = explode(',', $user, 3);
            if ($userElements[0] == $username)
			
            {
                return new User($userElements[0], $userElements[1], $userElements[2]); 
            }
        }
        
        throw new InvalidUsernameException('$username doesn\'t exist');
    }

    public function saveUser($username, $password)
    {
        /*$username = $tempUser->getUsername();
        $password = $tempUser->getPassword();
        $salt = $tempUser->getSalt();
        $expirationTime = $tempUser->getExpirationTime();
        $IP = $tempUser->getIP();
        $browser = $tempUser->getBrowser();*/
        
        $userString = $username . "," . $password . ", \n";
                    
        // Save temp login in a file
        file_put_contents(self::$URL, $userString, FILE_APPEND);
    }
}