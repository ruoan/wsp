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
App::import('Lib', 'facebook');

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
    
    private $session_key = array(
        'twitter_token'         => 'twitter.token',
        'twitter_token_secret'  => 'twitter.token_secret',
        'twitter_access_token'  => 'twitter.access_token'
    );

	public function twitter() {
		//変数定義
		$consumer_key = Configure::read('Twitter.consumer_key');
		$consumer_secret = Configure::read('Twitter.consumer_secret');
		$oauth_callback = Router::url('/logins/twitter_callback', true);

		//TwitterAPI接続OAuthオブジェクト生成
		$connection = new TwitterOAuth($consumer_key, $consumer_secret);

		//Twitter未認証request_token取得
		$request_token = $connection->getRequestToken($oauth_callback);
		$token = $request_token['oauth_token'];
		$this->Session->write($this->session_key['twitter_token'], $token);
		$this->Session->write($this->session_key['twitter_token_secret'], $request_token['oauth_token_secret']);

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
		//変数定義
		$consumer_key = Configure::read('Twitter.consumer_key');
		$consumer_secret = Configure::read('Twitter.consumer_secret');

		//接続先url取得
		//$url = $connection->getAuthorizeURL($token);

		//リクエストとセッションが一致しない場合のエラー処理
		if (isset($this->request['url']['oauth_token']) && 
		      $this->Session->read($this->session_key['twitter_token']) !== 
		      $this->request['url']['oauth_token']) {
			$this->Session->delete($this->session_key['twitter_token']);
			$this->Session->delete($this->session_key['twitter_token_secret']);
		}

		//Twitter接続オブジェクト生成
		$connection = new TwitterOAuth(
		                      $consumer_key, 
		                      $consumer_secret, 
		                      $this->Session->read($this->session_key['twitter_token']), 
		                      $this->Session->read($this->session_key['twitter_token_secret']));

		//accesstokenの取得
		$access_token = $connection->getAccessToken($this->request['url']['oauth_verifier']);
		$this->Session->write($this->session_key['twitter_access_token'], $access_token);
		$this->Session->delete($this->session_key['twitter_token']);
		$this->Session->delete($this->session_key['twitter_token_secret']);

		//DB処理
		//基本情報の取得
		$connection = new TwitterOAuth(
		                      $consumer_key, 
		                      $consumer_secret, 
		                      $access_token['oauth_token'],
		                      $access_token['oauth_token_secret']);
		$user_name = $connection->get('account/verify_credentials');
        
        Debugger::dump($user_name);

	}

	public function facebook() {
		//変数定義
		$app_id = '296251410509103';
		$app_secret = '4d5c7b8a532fd03eb0f0f20306c962a3';
		$callback = Router::url('/logins/facebook_callback', true);

		//facebookAPI接続OAuthオブジェクト生成
		$connection = new facebook(array('appId' => $app_id, 'secret' => $app_secret));

		//リダイレクト
		$url = $connection->getLoginUrl(array('redirect_uri' => $callback, 'scope' => 'email,publish_actions'));
		$this->redirect($url);

	}

	public function facebook_callback() {
		$app_id = '296251410509103';
		$app_secret = '4d5c7b8a532fd03eb0f0f20306c962a3';
		$connection = new facebook(array('appId' => $app_id, 'secret' => $app_secret));
		$user = $connection->getUser();

		if ($user) {
			$user_profile = $connection->api('/me');
			$this->Session->write('user_profile', $user_profile);

		} else {

		}

		$this->redirect('/timelines/index');

	}

}
?>