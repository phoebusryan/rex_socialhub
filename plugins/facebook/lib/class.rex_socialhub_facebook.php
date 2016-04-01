<?php
	class rex_socialhub_facebook {
		
		public static function getEntriesByTimeline() {
			//Start - get all timelines from the database
				$sql = rex_sql::factory();
				$timelines = $sql->getArray('SELECT `user_id` FROM `'.rex::getTablePrefix().'socialhub_facebook_timeline`');
				unset($sql);
				
				if (empty($timelines)) {
					return false;
				}
			//End - get all timelines from the database

			//Start - get all accounts from the database
				$sql = rex_sql::factory();
				$accounts = $sql->getArray('SELECT * FROM `'.rex::getTablePrefix().'socialhub_facebook_account` ORDER BY `id` ASC');
				unset($sql);
				
				if (empty($accounts)) {
					return false;
				}
			//End - get all accounts from the database
			
			//Start - get entries by timeline from twitter
				foreach ($timelines as $timeline) {
					$fb = new Facebook\Facebook([
						'app_id' => $accounts[0]['app_id'],
						'app_secret' => $accounts[0]['app_secret'],
						'default_graph_version' => 'v2.5',
						'default_access_token' => $accounts[0]['app_id'].'|'.$accounts[0]['app_secret']
					]);
					
					try {
						$response = $fb->get('/'.$timeline['user_id'].'/posts');
					} catch(Facebook\Exceptions\FacebookResponseException $e) {
						echo rex_view::error('Graph returned an error: ' . $e->getMessage());
						
					} catch(Facebook\Exceptions\FacebookSDKException $e) {
						echo rex_view::error('Facebook SDK returned an error: ' . $e->getMessage());
					}
					
					if(empty($response)) return;
					
					$response = json_decode($response->getBody(), true);
					
					foreach($response['data'] as $post) {
						list ($userID, $postID) = explode('_', $post['id']);
						
						$newPost = rex_sql::factory();
						$newPost->setTable(rex::getTablePrefix().'socialhub_entry_timeline');
						$newPost->setWhere(['post_id' => $postID]);
						$newPost->select();
						if ($newPost->getRows() === 0) {
							$newPost->reset();
							$newPost->setTable(rex::getTablePrefix().'socialhub_entry_timeline');
							$newPost->setValue('source', 'facebook');
							$newPost->setValue('post_id', $postID);
							$newPost->setValue('message', $post['message']);
							$newPost->setValue('author_id', $userID);
							$newPost->setValue('created_time', date('Y-m-d H:i:s', strtotime($post['created_time'])));
							$newPost->setValue('query', '/'.$timeline['user_id'].'/posts');
							
							try {
								$newPost->insert();
							} catch (rex_sql_exception $e) {
								echo rex_view::warning($e->getMessage());
							}
						}
					}
				}
			//End - get entries by timeline from twitter
		}
	}
?>