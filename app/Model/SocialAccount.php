<?php
App::uses('AppModel', 'Model');
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
	
	public function updateInsertAccount($token, $token_secret, $account_info){
		
		//登録用データを編集
		//token: IDとして使用
		$member_data['token']= $token;
		//secret: パスワードとして使用
		$member_data['secret']= $token_secret;
		//ログイン元サイトの種類: Twitterでは"TW"
		$member_data['social_type']= Configure::read('Twitter.social_type');
		
		Debugger::dump($account_info);
		
		
		//フルネーム
		$member_data['name']= $account_info->name;
		//スクリーンネーム
		$member_data['screen_name']= $account_info->screen_name;
		//プロフィール用画像URL
		$member_data['profile_image_url']= $account_info->profile_image_url;
		//アカウントURL
		$member_data['url']= $account_info->url;
		//e-mailアドレス
		$member_data['email']= $account_info->email;

		//引数からのtokenがDBへ登録済みの既存ユーザーかを確認
		$account_exsisting_data = $this->findByToken($token);
		//DBが取得できた場合（既存ユーザーの場合）
		if($account_exsisting_data){
			//取得したIDを更新データへセット
			$member_data['id'] = $account_exsisting_data['SocialAccount']['id'];
		//DBが取得できない場合（新規ユーザーの場合）	
		}else{
			//処理なし
		}
		$this->create();
		$this->save(Array("SocialAccount" => $member_data));
	}
}
