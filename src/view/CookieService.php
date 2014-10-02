<?php

/**
 * Service class for using cookies
 * 
 * @author Svante Arvedson
 */
class CookieService
{
    /**
     * Checks if a cookie is set
     * 
     * @param string $key   Name of the cookie
     * @return boolean  TRUE if cookie is set
     */
    public function cookieIsset($key)
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * Gets content is a cookie
     * 
     * @param string $key Name of the cookie
     * @return string   The cookie content or an empty string
     */
    public function loadCookie($key)
    {
        if (isset($_COOKIE[$key]))
        {
            return $_COOKIE[$key];
        }
        else
        {
            return '';
        }
    }
    
    /**
     * Gets value of the cookie and then unsets the cookie
     * 
     * @param string $key   Name of the cookie
     * @return string   The cookie content if or an empty string
     */
    public function loadOnceCookie($key)
    {
        if (isset($_COOKIE[$key]))
        {
            $content = $_COOKIE[$key];
            $this->unsetCookie($key);
            return $content;
        }
        else
        {
            return '';
        }
    }
    
    /**
     * Creates a cookie
     * 
     * @param string $key   Name of the cookie
     * @param string $value Content in cookie
     * @param string $expire Expiration time of cookie
     * @return void
     */
    public function saveCookie($key, $value, $expire = -1)
    {
        setcookie($key, $value, $expire);
    }

    /**
     * Unsets a cookie
     * 
     * @param string $key   Name of the cookie
     * @return void
     */
    public function unsetCookie($key)
    {
        setcookie($key, '', time()-3600);
    } 
}