<?php
	class rex_socialhub_twitter extends rex_socialhub {
		
//		public static $url = 'https://twitter.com';
//		public static $search_url = 'https://twitter.com/search?q=%23';
//		protected $table = 'rex_socialhub_twitter_entries';

//		private $counter = 0;

//		protected function __construct() {
//			$this->plugin = 'twitter';
//			parent::__construct();
//		}
		
		/*
		public function timeline() {
			$sql = rex_sql::factory();
			$sql->setTable($this->table);
			$sql->select();
			$entry = '';
			
			while($sql->hasNext()) {
				$caption = urldecode($sql->getValue('message'));
				$caption = preg_replace('/((https*|www\.)[^\s]+)/is','<a href="$1" target="_blank" title="link to $1">$1</a>',$caption);
				$caption = preg_replace('/\@([^ :]*)/is','<a href="'.(self::$url).'/$1" target="_blank" title="link to $1">@$1</a>',$caption);
				$caption = preg_replace('/\#([0-9a-zA-Z_\-]+)/is','<a href="'.(self::$search_url).'$1" target="_blank" title="Hashtag-Suche auf '.ucfirst($sql->getValue('source')).': '.self::$search_url.'$1">#$1</a>',$caption);
				
				$fragment = new rex_fragment();
				$fragment->setVar('id',$sql->getValue('id')); 
				$fragment->setVar('visible',$sql->getValue('visible'));
				$fragment->setVar('highlight',$sql->getValue('highlight'));
				$fragment->setVar('source',$this->plugin); 
				$fragment->setVar('source_id',$sql->getValue('post_id'));
				$fragment->setVar('caption',$caption,false);
				$fragment->setVar('image',$sql->getValue('image'));
				
				$entry .= $fragment->parse('frontend/hastags.php');
				
				$sql->next();
			}
			
			return $entry;
		}
		
		public static function cron() {
			$Hub = self::factory();
			$Accounts = rex_config::get('socialhub','twitter');
			$Accounts = $Accounts['accounts'];
			
			if(!$Accounts) return;
			
			foreach($Accounts as $token => $account) {
				$connection = new Abraham\TwitterOAuth\TwitterOAuth($account['consumer_token'], $account['consumer_secret_token'], $account['access_token'], $account['secret_token']);
				$response = $connection->get("statuses/user_timeline",['user_id'=>'244714769']);
				
				foreach($response as $key => $data) {
					$sql = rex_sql::factory();
					$sql->setTable($Hub->table);
					$sql->setWhere(array('post_id'=>$data->id));
					$sql->select();
					if($sql->getRows() === 0) {
						$sql->reset();
						$sql->setTable($Hub->table);
						$sql->setValue('post_id', $data->id);
						$sql->setValue('message', urlencode($data->text));
						
						if($data->entities && !empty($data->entities->media)) {
							$sql->setValue('image', $data->entities->media[0]->media_url);
						}
						
						$sql->setValue('author', $data->user->id);
						$sql->setValue('query', json_encode($data));
						$sql->setValue('visible', '1');
						
						try {
							$sql->insert();
						} catch (rex_sql_exception $e) {
							echo rex_view::warning($e->getMessage());
						}
					}
				}
			}
		}
		*/
		
		public static function getEntriesByTimeline() {
			
			
			
			//merge function cron() and timeline() into this
		}
		
		public static function getEntriesByHashtag() {
			//Start - get all hashtags from the database
				$sql = rex_sql::factory();
				$hashtags = $sql->getArray('SELECT `hashtag`, `twitter_next_id` FROM `'.rex::getTablePrefix().'socialhub_twitter_hashtag` ORDER BY `hashtag` ASC');
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
				$countEntries = 0;
				
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
							$sql->setTable(rex::getTablePrefix().'socialhub_entries');
							
							$sql->setValue('source', 'twitter');
							$sql->setValue('source_id', $data->id);
							$sql->setValue('caption', urlencode($data->text ? addslashes($data->text) : ''));
							
							if ($data->entities && !empty($data->entities->media)) {
								$sql->setValue('image', $data->entities->media[0]->media_url);
							}
							
							$sql->setValue('created_time', date('Y-m-d H:i:s',strtotime($data->created_at)));
							$sql->setValue('user_id', $data->user->id);
							$sql->setValue('query', $hashtag['hashtag']);
							$sql->setValue('visible', ((!empty($data->entities->media)) ? '1' : '0'));
							
							try {
								$sql->insert();
								$countEntries++;
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
			
			return $countEntries;
		}
	}
?>