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
    public $uses = array('SocialAccount');

    private $session_key = array(
        'twitter_token'         => 'twitter.token',
        'twitter_token_secret'  => 'twitter.token_secret',
        'twitter_access_token'  => 'twitter.access_token'
    );
	
	/**
	 * アクション前処理
	 */
	function beforeFilter(){
		//認証不要アクション設定
		$this->Auth->allow();
	}

    /**
     * Twitterログインアクション
     */
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

    /**
     * Twitterコールバックアクション
     */
	public function twitter_callback() {
		//変数定義
		$consumer_key = Configure::read('Twitter.consumer_key');
		$consumer_secret = Configure::read('Twitter.consumer_secret');

		//接続先url取得
		//$url = $connection->getAuthorizeURL($token);

		//リクエストとセッションが一致しない場合のエラー処理
		if (isset($this->request->query['oauth_token']) && 
		      $this->Session->read($this->session_key['twitter_token']) !== 
		      $this->request->query['oauth_token']) {
		    
            //セッションクリア
			$this->Session->delete($this->session_key['twitter_token']);
			$this->Session->delete($this->session_key['twitter_token_secret']);
            //トップページにリダイレクト
            return $this->redirect('/');
		}

		//Twitter接続オブジェクト生成
		$connection = new TwitterOAuth(
		                      $consumer_key, 
		                      $consumer_secret, 
		                      $this->Session->read($this->session_key['twitter_token']), 
		                      $this->Session->read($this->session_key['twitter_token_secret']));

		//AccessTokenの取得
		$access_token = $connection->getAccessToken($this->request['url']['oauth_verifier']);

        //Twitterでのログインに成功した場合
        if(isset($access_token)){
            
			//AccessTokenをセッションにセット
    		$this->Session->write($this->session_key['twitter_access_token'], $access_token);
			//未認証RequestTokenをセッションから削除
    		$this->Session->delete($this->session_key['twitter_token']);
    		$this->Session->delete($this->session_key['twitter_token_secret']);
            
    		//アカウントアップデート
    		//該当のアカウントがない場合は新規登録
    		$updateTwitterAccountRes = $this->updateTwitterAccount(
                      $consumer_key, 
                      $consumer_secret, 
                      $access_token['oauth_token'],
                      $access_token['oauth_token_secret']);
            
			if($updateTwitterAccountRes){
			
	            //ログイン情報セット
	            $user['SocialAccount']['token'] = $access_token['oauth_token'];
				$user['SocialAccount']['secret'] = $access_token['oauth_token_secret'];
	            
	            //Authコンポーネントにてログイン
	            //social_typeが'TW'のものに限定
	            $this->Auth->authenticate = array('Form' => Array('scope' => array('SocialAccount.social_type' => Configure::read('Twitter.social_type'))));
	            //$this->Auth->scope(array(
	            //	'SocialAccount.social_type' => Configure::read('Twitter.social_type')));
	            $this->Auth->login($user);
				//$this->Auth->userScope = array('User.verified' => '1');
				
				$this->redirect('/timelines/index');
			} else {
				return $this->cakeError('error');
			}
        
        } else {
        //Twitterログインエラー処理
            return $this->cakeError('error404');
        }
	}
	
	/**
     * Twitterログイン後アカウント登録更新
     */
    private function updateTwitterAccount($cons_key, $cons_secret, $token, $token_secret){
       //usersデータ取得
       //ipの取得
	   $user_info['ip']= $this->request->clientIp(false);
	   
	   //OAuthコネクション取得
       $connection = new TwitterOAuth($cons_key, $cons_secret, $token, $token_secret);
       $account_info = $connection->get('account/verify_credentials');

		//SocialAccountモデルでレコード更新（追加）
        $this->SocialAccount->insertUpdateTWAccount($user_info, $token, $token_secret, $account_info);
		
		return true;
    }


    /**
     * Facebookログインアクション
     */
	public function facebook() {
		//変数定義
		$app_id = Configure::read('Facebook.apl_id');
		$app_secret = Configure::read('Facebook.apl_secret');
		$callback = Router::url('/logins/facebook_callback', true);

		//facebookAPI接続OAuthオブジェクト生成
		$connection = new facebook(array('appId' => $app_id, 'secret' => $app_secret));

		//リダイレクト
		$url = $connection->getLoginUrl(array('redirect_url' => $callback, 'scope' => 'email,publish_actions'));
		$this->redirect($url);

	}
    /**
     * Facebookコールバックアクション
     */
	public function facebook_callback() {
		$app_id = Configure::read('Facebook.apl_id');
		$app_secret = Configure::read('Facebook.apl_secret');
		$connection = new facebook(array('appId' => $app_id, 'secret' => $app_secret));
		$user = $connection->getUser();

		if ($user) {
			$user_profile = $connection->api('/me');
			$this->Session->write('user_profile', $user_profile);
			facebook_authenticate();
		} else {

		}

		$this->redirect('/timelines/index');

	}
	
	private function facebook_authenticate(){
		
	}
	
	// ログアウト処理
    public function logout() {
		$this->redirect($this->Auth->logout());
    }
}
?>