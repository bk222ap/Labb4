<?php

/**
 * Base view class for the application
 * 
 * @author Svante Arvedson
 */
class HTMLview
{
	/**
	 * @var string	HTML body
	 */
	private $body;
	
	/**
	 * @var string	HTML title
	 */
	private $title;
	
	/**
	 * This function echos the HTML code.
	 *
	 * @throws HTMLException	If $this->title or $this->body isn't set. 
	 * @return void
	 */
	public function echoHTML()
	{
		if ($this->title == null)
		{
			throw new HTMLException('$title must not be null.');
		}
		if ($this->body == null)
		{
			throw new HTMLException('$body must not be null.');
		}
		
		$HTML = '
			<!DOCTYPE html>
			<html lang="sv">
				<head>
					<meta charset="utf-8" />
					<title>' . $this->title . '</title>
					<link rel="stylesheet" href="content/css/site.css" />
				</head>
				<body>'
					. $this->body . "\n" .
				'</body>
			</html>';
		
		echo $HTML;
	}
	
    /**
     * Return IP address for the request
     * 
     * @return string   IP address
     */
    public function getIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * Return info about used browser
     * 
     * @return string   info about used browser
     */
    public function getBrowserInfo()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
    
    /**
     * Redirects to a new page
     * 
     * @return void
     */
    public function redirect($address)
    {
        header('Location: ' . $address);
    }
    
	/**
	 * Setter for $this->body
	 * 
	 * @param string $body	The body content
	 * @return void
	 */
	public function setBody($body)
	{
		$this->body = $body;
	}
	
	/**
	 * Setter for $this->title
	 * 
	 * @param string $title	The title content
	 * @return void
	 */
	public function setTitle($title) 
	{
		$this->title = $title;
	}
}