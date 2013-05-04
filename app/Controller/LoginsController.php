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
		//変数定義
		$consumer_key = 'b7crCjiIs1pHYwK1e1i21A';
		$consumer_secret = 'pcG4pWnxzTnj2eEndgKek7XWYbjxgMpIaQxWbr0gqs';
		$oauth_callback = Router::url('/logins/twitter_callback', true);

		//TwitterAPI接続OAuthオブジェクト生成
		$connection = new TwitterOAuth($consumer_key, $consumer_secret);

		//Twitter未認証request_token取得
		$request_token = $connection->getRequestToken($oauth_callback);
		$token = $request_token['oauth_token'];
		$this->Session->write('twitter.token', $token);
		$this->Session->write('twitter.token_secret', $request_token['oauth_token_secret']);

		//成功時(case 200)認証済token取得
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

	public function twitter_callback() {
		$consumer_key = 'b7crCjiIs1pHYwK1e1i21A';
		$consumer_secret = 'pcG4pWnxzTnj2eEndgKek7XWYbjxgMpIaQxWbr0gqs';
		$url = $connection->getAuthorizeURL($token);

		if (isset($this->request['oauth_token']) && 
				$this->Session->read('oauth_token') !== $this->request['url']['oauth_token']) {
			$this->Session->delete('twitter.oauth_token');
			$this->Session->delete('twitter.oauth_secret');
		}
		$connection = new TwitterOAuth(
							$consumer_key, 
							$consumer_secret, 
							$this->Session->read('twitter.oauth_token'), 
							$this->Session->read('twitter.oauth_token_secret'));
		$access_token = $connection->getAccessToken($this->request['oauth_verifier']);
		
		$this->Session->write('twitter.access_token', $access_token);
		$this->Session->delete('twitter.oauth_token');
		$this->Session->delete('twitter.oauth_secret');

	}

}
?>