<?php

class DALTempUser
{
    /**
     * @var string $URL URL to file with users
     */
    private static $URL = 'files/tempUsers';
    
    /**
     * Return all users in an array
     * 
     * @return array All existing users
     */
    public function getAllTempUsers()
    {
        $ret = array();
        $tempUsers = file(self::$URL, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($tempUsers as $tempUser)
        {
            $userElements = explode(',', $tempUser, 6);
            $ret[] = new TempUser($userElements[0], $userElements[1], $userElements[2], $userElements[3], $userElements[4], $userElements[5]);
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
    public function getTempUserByUsername($username)
    {        
        $tempUsers = file(self::$URL, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($tempUsers as $tempUser)
        {
            $userElements = explode(',', $tempUser, 6);
            if ($userElements[0] == $username && $userElements[3] >= time())
            {
                $tempUser = new TempUser($userElements[0], $userElements[1], $userElements[2], $userElements[3], $userElements[4], $userElements[5]);
                return $tempUser;
            }
        }
        
        throw new Exception('$username doesn\'t exist');
    }

    /**
     * Saves a tempUser object
     * 
     * @return void
     */
    public function saveTempUser($tempUser)
    {
        $username = $tempUser->getUsername();
        $password = $tempUser->getPassword();
        $salt = $tempUser->getSalt();
        $expirationTime = $tempUser->getExpirationTime();
        $IP = $tempUser->getIP();
        $browser = $tempUser->getBrowser();
        
        $tempUserString = $username . "," . $password . "," . $salt . "," . $expirationTime . "," . $IP . "," . $browser . "\n";
                    
        // Save temp login in a file
        file_put_contents(self::$URL, $tempUserString, FILE_APPEND);
    }
}