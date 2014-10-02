<?php

/**
 * This class handles the authentication.
 * 
 * @author Svante Arvedson
 */
class AuthenticationController
{
    /**
     * @var AuthenticationModel $model  An instance of AuthenticationModel
     */
    private $model;
    
    /**
     * @var AuthenticationView $view  An instance of AuthenticationView
     */
    private $view;
    
    /**
     * @return void
     */
    public function __construct()
    {
        $this->model = new AuthenticationModel(); 
        $this->view = new AuthenticationView($this->model);
    }
    
	/**
	 * Authenticate a user
     * Redirect when done
	 * 
     * @throws InvalidUsernaeException  If provided username isn't valid
     * @throws InvalidPasswordException If provided password isn't valid
     * @throws LoginException   If user doesn't exist in register
     * @throws Exception If a server error occurs
     * 
	 * @return void
	 */
	public function doLogin()
	{
        $inputUsername = $this->view->getUsername();
        $inputPassword = $this->view->getPassword(); 
        $inputIP = $this->view->getIP();
        $inputBrowser = $this->view->getBrowserInfo();

        try
        {
            if ($this->view->userWantsToSaveCredentials())
            {
                $this->model->loginUser($inputUsername, $inputPassword, $inputIP, $inputBrowser, true);
                $this->view->saveCredentials($this->model->getUser()->getUsername(), $this->model->getTempPassword());
                $this->view->addSuccessMessage('Inloggning lyckades och vi kommer ihåg dig till nästa gång');   
            }
            else
            {
                $this->model->loginUser($inputUsername, $inputPassword, $inputIP, $inputBrowser);
                $this->view->addSuccessMessage('Inloggning lyckades');    
            }
        }
        catch (InvalidUsernameException $e)
        {
            $this->view->addErrorMessage('Användarnamn saknas');
        }
        catch (InvalidPasswordException $e)
        {
            $this->view->addErrorMessage('Lösenord saknas');
        }
        catch (LoginException $e)
        {
            $this->view->addErrorMessage('Felaktigt användarnamn och/eller lösenord');
        }
        catch (Exception $e)
        {
            $this->view->addErrorMessage('Ett fel inträffade när du försökte logga in');
        }

        $this->view->redirect($_SERVER['PHP_SELF']);
	}
	
    /**
     * Authenticate a user woth saved credentials
     * Redirect when done
     * 
     * @throws Exception If an error occurs
     * 
     * @return void
     */
    public function doLoginWithCredentials()
    {
        $inputIP = $this->view->getIP();
        $inputBrowser = $this->view->getBrowserInfo();
        $savedCredentials = $this->view->getSavedCredentials();

        try
        {
            /* $savedCredentials[0] == username
             * $savedCredentials[1] == password */
            $this->model->loginUserWithCredentials($savedCredentials[0], $savedCredentials[1], $inputIP, $inputBrowser);
            $this->view->addSuccessMessage('Inloggning lyckades via cookies');
        }
        catch (Exception $e)
        {
            $this->view->addErrorMessage('Felaktig information i cookies');
            $this->view->removeCredentials();
        }

        $this->view->redirect($_SERVER['PHP_SELF']);
    }
    
	/**
	 * Unauthenticate user
	 * 
	 * @return void
	 */
	public function doLogout()
	{
		$this->model->logoutUser();
		$this->view->removeCredentials();
        $this->view->addSuccessMessage('Du har nu loggat ut');

		$this->view->redirect($_SERVER['PHP_SELF']);
	}
}