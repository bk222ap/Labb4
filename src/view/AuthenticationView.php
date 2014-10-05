<?php

/**
 * View for authentication module
 * 
 * @see HTMLView.php    Parent class
 * @author Svante Arvedson
 */
class AuthenticationView extends HTMLView
{
    /**
     * @var string $NameLoginButton     The name attribute of the login button
     */
    private static $NameLoginButton = 'login';
	
	 /**
     * @var string $NameRegisterButton     The name attribute of the register button
     */
    private static $NameRegisterButton = 'register';
	
	  /**
     * @var string $NameRegisterMeButton    The name attribute of the register Me button
     */
    private static $NameRegisterMeButton = 'registerMe';
    
    /**
     * @var string $NameLogoutButton    The name attribute of the logout button
     */
    private static $NameLogoutButton = 'logout';
    
    /**
     * @var string $NamePassword        The name attribute of the password input
     */
    private static $NamePassword = 'password';

     /**
     * @var string $RegisterPassword        The name attribute of the password input
     */
    private static $RegisterPassword = 'registerPassword';
    
     /**
     * @var string $RepeatPassword        The name attribute of the password input
     */
    private static $RepeatPassword = 'repeatPassword';

    /**
     * @var string $NameUsername        The name attribute of the username input
     */
    private static $NameUsername = 'username';
    
    /**
     * @var string $RegisterUsername        The name attribute of the username input
     */
    private static $RegisterUsername = 'registerUsername';
    
    /**
     * @var string $NameSaveCredentials The name attribute of the save credential checkbox
     */
    private static $NameSaveCredentials = 'saveCredentials';
    
    /**
     * @var string $placeErrorMessage   The cookie name for error message
     */
    private static $placeErrorMessage = 'AuthenticationView::ErrorMessage';
    
    /**
     * @var string $placeLastUsernameInput The cookie name for last username input
     */
    private static $placeLastUsernameInput = 'AuthenticationView::LastUsernameInput';
    
    /**
     * @var string $placeSavedPassword  The cookie name for saved credential password
     */
    private static $placeSavedPassword = 'AuthenticationView::SavedPassword';
    
    /**
     * @var string $placeSavedUsername  The cookie name for saved credential username
     */
    private static $placeSavedUsername = 'AuthenticationView::SavedUsername';
    
    /**
     * @var string $placeSuccessMessage The cookie name for successmessage
     */
    private static $placeSuccessMessage = 'AuthenticationView::SuccessMessage';

    /**
     * @var AuthenticationModel $model  An instance of AuthenticationModel class
     */
    private $model;
    
    /**
     * @var CookieService $cookieService An instance of CookieService class
     */
    private $cookieService;

    /**
     * @param AuthenticationModel $model    An instance of the AuthenticationModel class
     * @return void
     */
    public function __construct(AuthenticationModel $model)
    {
        $this->model = $model;
        $this->cookieService = new CookieService();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if ($this->userPressedLogin())
            {
                $this->addLastUsernameInput($this->getUsername());
            }
        }
    }

    /**
     * Saves an error message in a cookie
     * 
     * @param string $message   The error message
     * @return void
     */
    public function addErrorMessage($message)
    {
        $this->cookieService->saveCookie(self::$placeErrorMessage, $message);
    }
    
    /**
     * Saves a success message in a cookie
     * 
     * @param string $message   The success message
     * @return void
     */
    public function addSuccessMessage($message)
    {
        $this->cookieService->saveCookie(self::$placeSuccessMessage, $message);
    }

    /**
     * Creates the HTML page
     * 
     * @return string   The HTML page
     */
    public function createHTML()
    {
        $body = '';
        $title = '';
        $date = ucfirst(utf8_encode(strftime('%A den %#d %B ' . utf8_decode('år') . ' %Y. Klockan ' . utf8_decode('är') . ' [%H:%M:%S].')));
        
        if ($this->model->isUserAuthenticated($this->getIP(), $this->getBrowserInfo()))
        {
            $title .= $this->model->getUser()->getUsername() . ' är inloggad';
            $body .= $this->createAuthenticatedBody();
        }
        else if($this->userPressedRegister()){
            $title .= "Register";
            $body .= $this->createRegisterBody();
        }
        else
        {
            $title .= 'Ej inloggad';
            $body .= $this->createUnauthenticatedBody();
        }
        
        $body .= '<p>' . $date . '</p>
            </div>';
        
        $this->setBody($body);
        $this->setTitle($title);
    }

    /**
     * Checks if users credentials are saved
     * 
     * @return boolean  TRUE if credentials are saved
     */
    public function credentialsIsSaved()
    {
        return $this->cookieService->cookieIsset(self::$placeSavedUsername) && $this->cookieService->cookieIsset(self::$placeSavedPassword);
    }

    /**
     * Getter for inputfield for password
     * 
     * @return string   The user submitted password
     */
    public function getPassword()
    {

        return $_POST[self::$NamePassword];
    }


    /**
     * Getter for inputfield for Register Password
     * 
     * @return string   The user submitted Register Password
     */
    public function getRegisterPassword()
    {
        
            return $_POST[self::$RegisterPassword];
                
    }

    /**
     * Getter for inputfield for Repeat Password
     * 
     * @return string   The user submitted reapeated Password
     */
    public function getRepeatPassword()
    {
       
            return $_POST[self::$RepeatPassword];
        
    }

    /**
     * Getter for saved credentials in cookies
     * 
     * @return array    The credentials saved in cookies
     */
    public function getSavedCredentials()
    {
        $username = $this->cookieService->loadCookie(self::$placeSavedUsername);
        $password = $this->cookieService->loadCookie(self::$placeSavedPassword);
        return array($username, $password);
    }
    
    /**
     * Getter for inputfield for username
     * 
     * @return string   The user submitted username
     */
    public function getUsername()
    {
        return $_POST[self::$NameUsername];
    }

    /**
     * Getter for inputfield for RegisterUsername
     * 
     * @return string   The user submitted RegisterUsername
     */
    public function getRegisterUsername()
    {
        
            return $_POST[self::$RegisterUsername];
        
    }

    /**
     * Destorys cookies with saved credentials
     * 
     * @return void
     */
    public function removeCredentials()
    {
        $this->cookieService->unsetCookie(self::$placeSavedUsername);
        $this->cookieService->unsetCookie(self::$placeSavedPassword);
    }
    
    /**
     * Saves credentials in cookies
     * 
     * @param string $username  The users username
     * @param string $password  The users password
     * @return void
     */
    public function saveCredentials($username, $password)
    {
        $this->cookieService->saveCookie(self::$placeSavedUsername, $username, time()+$this->model->getExpirationOfTempUser());
        $this->cookieService->saveCookie(self::$placeSavedPassword, $password, time()+$this->model->getExpirationOfTempUser());
    }
    
    /**
     * Checks if user choosed "remember me"
     * 
     * @return boolean  TRUE if user choosed "remember me"
     */
    public function userWantsToSaveCredentials()
    {
        return isset($_POST[self::$NameSaveCredentials]);
    }

    /**
     * Checks if user clicked login button
     * 
     * @return boolean  TRUE if user clicked login button
     */
    public function userPressedLogin()
    {
        return isset($_POST[self::$NameLoginButton]);
    }
	
	   /**
     * Checks if user clicked register button
     * 
     * @return boolean  TRUE if user clicked register button
     */
    public function userPressedRegister()
    {
        return isset($_POST[self::$NameRegisterButton]);
    }
	
	  /**
     * Checks if user clicked registerMe button
     * 
     * @return boolean  TRUE if user clicked register button
     */
    public function userPressedRegisterMe()
    {
        return isset($_POST[self::$NameRegisterMeButton]);
    }

    /**
     * Checks if userclicked logout button
     * 
     * @return boolean  TRUE if user clicked logout button
     */
    public function userPressedLogout()
    {
        return isset($_POST[self::$NameLogoutButton]);
    }

    /**
     * Saves last username input in a cookie
     * 
     * @param string $lastInput Users last username input
     * @return void
     */
    private function addLastUsernameInput($lastInput)
    {
        $this->cookieService->saveCookie(self::$placeLastUsernameInput, $lastInput);
    }
    
    /**
     * Creates HTML content for an authenticated user
     * 
     * @return string   HTML
     */
    private function createAuthenticatedBody()
    {
        $errorMessage = $this->getErrorMessage();
        $successMessage = $this->getSuccessMessage();
        $lastUsernameInput = $this->getLastUsernameInput();

        $body = '
            <div id="main">
                <h1>Laboration 2 - ba222ec</h1>
                <h2>' . $this->model->getUser()->getUsername() . ' är inloggad</h2>' . "\n";
        
        if ($successMessage != '')
        {
            $body .= '<p class="success">' . $successMessage . '</p>';
        }
                
        $body .='<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">
                    <input type="submit" name="' . self::$NameLogoutButton . '" value="Logga ut" />
                </form>' . "\n";
                
        return $body;
    }
    
    /**
     * Creates HTML content for an unauthenticated user
     * 
     * @return string   HTML
     */
    private function createUnauthenticatedBody()
    {
        $errorMessage = $this->getErrorMessage();
        $successMessage = $this->getSuccessMessage();
        $lastUsernameInput = $this->getLastUsernameInput();
        
        $body = '
            <div id="main">
                <h1>Laboration 2 - ba222ec</h1>
                <h2>Ej inloggad</h2>' . "\n";
        
        if ($successMessage != '')
        {
            $body .= '<p class="success">' . $successMessage . '</p>';
        }
                    
        $body .='<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">
                    <fieldset>
                        <legend>Logga in:</legend>' . "\n";

        // If there is an error massage
        if ($errorMessage != '')
        {
            $body .= '<p class="error">' . $errorMessage . '</p>' . "\n";
        }
            
        $body .= '<span class="row">
                    <label for="' . self::$NameUsername . '">Användarnamn: </label>
                    <input id="' . self::$NameUsername . '" name="' . self::$NameUsername . 
                       '" type="text" autofocus="autofocus" value="' . $lastUsernameInput . '" />
                 </span>
                 <span class="row">
                      <label for="' . self::$NamePassword . '">Lösenord: </label>
                      <input id="' . self::$NamePassword . '" name="' . self::$NamePassword . '" type="password" />
                 </span>
                 <span class="row">
                      <label for="' . self::$NameSaveCredentials . '">
                          Håll mig inloggad: <input id="' . self::$NameSaveCredentials . '" name="' . self::$NameSaveCredentials . '" type="checkbox">
                      </label>
                 </span>
                 <span class="row">
                    <input type="submit" name="' . self::$NameLoginButton . '" value="Logga in" />
                    <input type="submit" name="' . self::$NameRegisterButton . '" value="Register" />
                 </span>
                 </fieldset>
            </form>' . "\n";
            
        return $body;
    }
    
	 private function createRegisterBody()
    {
       $errorMessage = $this->getErrorMessage();
       $successMessage = $this->getSuccessMessage();

        $body = '
            <div id="main">
                <h1>Laboration 4 - bk222ap</h1>
                <h2>Registrera en ny användare</h2>' . "\n";
        
       if ($successMessage != '')
       {
            $body .= '<p class="success">' . $successMessage . '</p>';
        }

		$body .='<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">
		                    <fieldset>
		                        <legend>Logga in:</legend>' . "\n";

                
        $body .='<span class="row">
                    <label for="' . self::$NameUsername . '">Användarnamn: </label>
                    <input id="' . self::$NameUsername . '" name="' . self::$RegisterUsername . 
                       '" />
                 </span>
                 <span class="row">
                      <label for="' . self::$NamePassword . '">Lösenord: </label>
                      <input id="' . self::$NamePassword . '" name="' . self::$RegisterPassword . '" type="password" />
                 </span>
                 
                  <span class="row">
                      <label for="' . self::$NamePassword . '">Repetera Lösenordet: </label>
                      <input id="' . self::$NamePassword . '" name="' . self::$RepeatPassword . '" type="password" />
                 </span>
                 <span class="row">
                    <input type="submit" name="' . self::$NameRegisterMeButton . '" value="Registrera Mig" />
                 </span>
                  </form>' . "\n";
                
        return $body;
    }

    /**
     * Gets last username input from cookie
     * 
     * @return string   Last username input
     */
    private function getLastUsernameInput()
    {
        return $this->cookieService->loadOnceCookie(self::$placeLastUsernameInput);
    }
    
    /**
     * Gets error message fron cookie
     * 
     * @return string   Error message
     */
    private function getErrorMessage()
    {
        return $this->cookieService->loadOnceCookie(self::$placeErrorMessage);
    }
    
    /**
     * Gets success message from cookie
     * 
     * @return string   Success message
     */
    private function getSuccessMessage()
    {
        return $this->cookieService->loadOnceCookie(self::$placeSuccessMessage);
    }
}