<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');
App::import('Lib', 'twitteroauth');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class LoginsController extends AppController {

	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Logins';

	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array();

	public function twitter() {
		$consumer_key = 'b7crCjiIs1pHYwK1e1i21A';
		$consumer_secret = 'pcG4pWnxzTnj2eEndgKek7XWYbjxgMpIaQxWbr0gqs';
		$oauth_callback = Router::url('logins/twitter_callback', true);

		$connection = new TwitterOAuth($consumer_key, $consumer_secret);
		$request_token = $connection -> getRequestToken($oauth_callback);
		$token = $request_token['oauth_token'];
		$this->Session->write('twitter.token_secret', $request_token['oauth_token_secret']);
		
		switch ($connection->http_code) {
			case 200 :
				/* Build authorize URL and redirect user to Twitter. */
				$url = $connection->getAuthorizeURL($token);
				$this->redirect($url);
				break;
			default :
				/* Show notification if something went wrong. */
				echo 'Could not connect to Twitter. Refresh the page or try again later.';
		}
		
		
		
		
		
	}

}
?>