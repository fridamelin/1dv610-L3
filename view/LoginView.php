<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	public static $keepUsername = '';
	private $message = '';

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		
		if(isset($_SESSION['username']))
		{
			return $this->generateLogoutButtonHTML($this->message);
		}else
		{
			return $this->generateLoginFormHTML($this->message);
		}		
	}
	
	public function login(){
		 $random = password_hash("Password", PASSWORD_DEFAULT);
		if(isset($_POST[self::$name]) || isset($_POST[self::$password]))
		{
			$response = '';

			if($_POST[self::$name] == 'Admin' && password_verify($_POST[self::$password], $random))
			{
				if(!isset($_SESSION['username']) && !isset($_SESSION['password']))
				{
					$this->message = "Welcome";
				} 
		$_SESSION['username'] = $_POST[self::$name];
		$_SESSION['password'] = $random;

				if(isset($_POST[self::$keep]))
				{
					$this->keepUserLoggedIn(); 
					$this->message = "Welcome and you will be remembered";
				}	
				$response = $this->generateLogoutButtonHTML($this->message);
				return $response;
			} 
			else 
			{
				$this->message = "Wrong name or password";
			}

			if ($_POST[self::$password] == '')
			{
				$this->getRequestUserName();
				$this->message = "Password is missing";
			}
			
			if ($_POST[self::$name] == '')
			{
				$this->message = "Username is missing";
			}
		} else if (isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]))
			{
				if($_COOKIE[self::$cookieName] == 'Admin' && password_verify("Password", $_COOKIE[self::$cookiePassword]))
				{
					if(!isset($_SESSION['username']) && !isset($_SESSION['password']))
					{
						$this->message = "Welcome back with cookie";
					} 
				$_SESSION['username'] = $_COOKIE[self::$cookieName];
				$_SESSION['password'] = $_COOKIE[self::$cookiePassword];
				} else 
				{
					$this->message = "Wrong information in cookies";
					setcookie(self::$cookieName, '', time()-3600);
					setcookie(self::$cookiePassword, '', time()-3600);
				}
			}
		if(isset($_POST[self::$logout]))
		{
			if(isset($_SESSION['username']) && isset($_SESSION['password']))
			{
				$this->message = "Bye bye!";
			}
			session_unset();
			setcookie(self::$cookieName, '', time()-3600);
			setcookie(self::$cookiePassword, '', time()-3600);
		}

		if(isset($_SESSION['username']))
		{			
			 $this->generateLogoutButtonHTML($this->message);
		}
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
				return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
				return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message .  '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . self::$keepUsername . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	private function getRequestUserName() {
		$input = $_POST[self::$name];
		return self::$keepUsername = $input;
		}	
	
	private function keepUserLoggedIn(){
		setcookie(self::$cookieName, $_SESSION['username'], time()+3600);
		setcookie(self::$cookiePassword, $_SESSION['password'], time()+3600);
		
	}
}