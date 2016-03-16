<?php
	class rex_socialhub_twitter {
		
		public static function getEntriesByTimeline() {
			//Start - get all timelines from the database
				$sql = rex_sql::factory();
				$timelines = $sql->getArray('SELECT `user_id`/*, `twitter_next_id`*/ FROM `'.rex::getTablePrefix().'socialhub_twitter_timeline`');
				unset($sql);
				
				if (empty($timelines)) {
					return false;
				}
			//End - get all timelines from the database
			
			//Start - get all accounts from the database
				$sql = rex_sql::factory();
				$accounts = $sql->getArray('SELECT * FROM `'.rex::getTablePrefix().'socialhub_twitter_account` ORDER BY `id` ASC');
				unset($sql);
				
				if (empty($accounts)) {
					return false;
				}
			//End - get all accounts from the database
			
			//Start - get entries by timeline from twitter
				foreach ($timelines as $timeline) {
					$connection = new Abraham\TwitterOAuth\TwitterOAuth($accounts[0]['consumer_token'], $accounts[0]['consumer_secret_token'], $accounts[0]['access_token'], $accounts[0]['secret_token']);
					$response = $connection->get("statuses/user_timeline",['user_id'=>$timeline['user_id']]); //todo: add since_id
					
					foreach ($response as $r) {
						if (empty($r->in_reply_to_status_id)) {
							$sql = rex_sql::factory();
							$sql->setTable(rex::getTablePrefix().'socialhub_entry_timeline');
							
							$sql->setValue('source', 'twitter');
							$sql->setValue('post_id', $r->id_str);
							$sql->setValue('message', $r->text);
							$sql->setValue('author_id', $r->user->id);
							$sql->setValue('author_name', $r->user->screen_name);
							$sql->setValue('created_time', date('Y-m-d H:i:s', strtotime($r->created_at)));
							
							try {
								$sql->insert();
							} catch (rex_sql_exception $e) {
								echo rex_view::warning($e->getMessage());
							}
							
							unset($sql);
						}
					}
				}
			//End - get entries by timeline from twitter
		}
		
		public static function getEntriesByHashtag() {
			//Start - get all hashtags from the database
				$sql = rex_sql::factory();
				$hashtags = $sql->getArray('SELECT `hashtag`, `twitter_next_id` FROM `'.rex::getTablePrefix().'socialhub_twitter_hashtag`');
				unset($sql);
				
				if (empty($hashtags)) {
					return false;
				}
			//End - get all hashtags from the database
			
			//Start - get all accounts from the database
				$sql = rex_sql::factory();
				$accounts = $sql->getArray('SELECT * FROM `'.rex::getTablePrefix().'socialhub_twitter_account` ORDER BY `id` ASC');
				unset($sql);
				
				if (empty($accounts)) {
					return false;
				}
			//End - get all accounts from the database
			
			//Start - get entries by hashtag from twitter
				foreach ($hashtags as $hashtag) {
					$connection = new Abraham\TwitterOAuth\TwitterOAuth($accounts[0]['consumer_token'], $accounts[0]['consumer_secret_token'], $accounts[0]['access_token'], $accounts[0]['secret_token']);
					if($hashtag['twitter_next_id'] != 0) {
						$response = $connection->get("search/tweets", ['q'=>'#'.$hashtag['hashtag'], 'since_id' => $hashtag['twitter_next_id']]);
					} else {
						$response = $connection->get("search/tweets", ['q'=>'#'.$hashtag['hashtag']]);
					}
					
					foreach ($response->statuses as $data) {
						$lastId = $data->id;
						
						if (empty($data->retweeted_status)) {
							$sql = rex_sql::factory();
							$sql->setTable(rex::getTablePrefix().'socialhub_entry_hashtag');
							
							$sql->setValue('source', 'twitter');
							$sql->setValue('source_id', $data->id);
							$sql->setValue('caption', urlencode($data->text ? addslashes($data->text) : ''));
							
							if (!empty($data->entities->media)) {
								$sql->setValue('image', $data->entities->media[0]->media_url);
							}
							
							$sql->setValue('created_time', date('Y-m-d H:i:s',strtotime($data->created_at)));
							$sql->setValue('author_id', $data->user->id);
							$sql->setValue('author_name', $data->user->screen_name);
							$sql->setValue('query', $hashtag['hashtag']);
							
							try {
								$sql->insert();
							} catch (rex_sql_exception $e) {
								echo rex_view::warning($e->getMessage());
							}
							
							unset($sql);
						}
					}
					
					//Start - update next_id
						$sql = rex_sql::factory();
						$sql->setTable(rex::getTablePrefix().'socialhub_twitter_hashtag');
						$sql->setWhere('hashtag = "'.addslashes($hashtag['hashtag']).'"');
						$sql->setValue('twitter_next_id', $lastId);
						
						try {
							$sql->update();
						} catch (rex_sql_exception $e) {
							echo rex_view::warning($e->getMessage());
						}
						
						unset($sql);
					//End - update next_id
				}
			//End - get entries by hashtag from twitter
		}
	}
?>