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
		//token
		$member_data['token']= $token;
		//
		$member_data['secret']= $secret;
		//
		$member_data['social_type']= Configure::read('Twitter.social_type');
		//
		$member_data['name']= $account_info['name'];
		//
		$member_data['screen_name']= $account_info['screen_name'];
		//
		$member_data['profile_image_url']= $account_info['profile_image_url'];
		//
		$member_data['url']= $account_info['url'];
		//
		$member_data['email']= $account_info['email'];
		
		
		$account_exsisting_data = $this->findByToken($token);
		if($account_exsisting_data){
			$member_data['id'] = $account_exsisting['SocialAccount']['id'];
			
		}else{
			
			
			
		}
		$this->create();
		$this->save(Array("SocialAccount" => $member_data));
		
		
		
	}
	
	
	
	
	
	
	
	
	
}
