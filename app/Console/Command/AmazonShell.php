<?php
/**
 * User: ruoan
 * Date: 13/08/04
 * Time: 17:33
 * To change this template use File | Settings | File Templates.
 */

App::import('Lib', 'AmazonECS');

class AmazonShell extends AppShell {
	public function update() {

		try{
			$amazonEcs = new AmazonECS(
				Configure::read('Amazon.access_key_id'),
				Configure::read('Amazon.secret_access_key'),
				Configure::read('Amazon.associate.country_code'),
				Configure::read('Amazon.associate.id'));

			$response = $amazonEcs->responseGroup('Large')->similarityLookup('B0017TZY5Y');
			var_dump($response);

		}catch(Exception $e){
			echo $e->getMessage();
		}

	}
}