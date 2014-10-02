<?php

/**
 * The main contoller for the application.
 * 
 * @author Svante Arvedson
 */
class AppController
{
    /**
     * Configure the application to run in swedish
     * 
     * @return void
     */
    private function configureLocale()
    {
        setlocale(LC_ALL, array('sv_SE', 'swedish_sweden', 'sv'));
        date_default_timezone_set('Europe/Stockholm');
    }
    
	/**
	 * The application runs from here. 
	 *
	 * @return void
	 */
	public function run()
	{
		$this->configureLocale();
        session_start();

        $model = new AuthenticationModel();
        $view = new AuthenticationView($model);
        $controller = new AuthenticationController();

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			
            if ($view->userPressedLogin())
            {
                $controller->doLogin();
            }
            else if ($view->userPressedLogout())
            {
                $controller->doLogout();
            }
		}
		else
		{
            if ($model->isUserAuthenticated($view->getIP(), $view->getBrowserInfo()) || !$view->credentialsIsSaved())
            {
                $view->createHTML();
                $view->echoHTML();
            }
            else
            {
                $controller->doLoginWithCredentials();
            }
		}
	}
}