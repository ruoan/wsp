<?php
App::uses('AppModel', 'Model');
App::import('Model','User');

/**
 * SocialAccount Model
 *
 * @property User $User
 */
class SocialAccount extends AppModel {


//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
     * Twitterユーザー情報アカウント登録更新
     */
	public function insertUpdateTWAccount($user_info, $token, $token_secret, $account_info){
		
		//Model: Userの生成
		$user= new User;
		
		//登録更新用ユーザー情報を取得
		//token: IDとして使用
		$socialAccount_info['token']= $token;
		//secret: パスワードとして使用
		$socialAccount_info['secret']= $token_secret;
		//ログイン元サイトの種別: Twitterでは"TW"
		$socialAccount_info['social_type']= Configure::read('Twitter.social_type');
		//フルネーム
		$socialAccount_info['name']= $account_info->name;
		//スクリーンネーム
		$socialAccount_info['screen_name']= $account_info->screen_name;
		//プロフィール用画像URL
		$socialAccount_info['profile_image_url']= $account_info->profile_image_url;
		//アカウントURL
		$socialAccount_info['url']= $account_info->url;

		//引数からのtokenがDBへ登録済みの既存ユーザーかを確認
		$exsisting_account_info = $this->findByToken($token);
		
		//既存ユーザーの場合(既存データあり)
		if($exsisting_account_info){
			//Userの情報更新
			//User_idの取得、idへセット
			$user_account_info['id'] = $exsisting_account_info['SocialAccount']['user_id'];
			
			//ipのセット
			$user_account_info['ip'] = $user_info['ip'];
			//Userの作成
			$user->create();
			$user->save(Array("User" => $user_account_info));
			//取得したIDを更新データへセット
			$socialAccount_info['id'] = $exsisting_account_info['SocialAccount']['id'];
			
		//新規ユーザーの場合(既存データなし)
		}else{
			//Usersの情報取得
			//ipのセット
			$user_account_info['ip'] = $user_info['ip'];
			
			//Userの作成
			$user->create();
			$user->save(Array("User" => $users_data));
			//作成された新規idを取得、セット
			$inserted_id = $user->getLastInsertID();
			$socialAccount_info['user_id'] = $inserted_id;
		}
		$this->create();
		$this->save(Array("SocialAccount" => $socialAccount_info));
	}
}
?>