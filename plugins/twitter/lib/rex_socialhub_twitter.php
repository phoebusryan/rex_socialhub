<?php
	class rex_socialhub_twitter extends rex_socialhub {
		
		public static $url = 'https://twitter.com';
		public static $search_url = 'https://twitter.com/search?q=%23';
		protected $table = 'rex_socialhub_twitter';

		private $counter = 0;

		protected function __construct() {
			$this->plugin = 'twitter';
			parent::__construct();
		}

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
		
		public static function loadHashtags() {
			$Hub = self::factory();
			foreach($Hub->getHashtags() as $hashtag => $next_id)
				$Hub->getDataByHashtag($hashtag,$next_id);
		}
		
		private function getDataByHashtag($hashtag, $nextID = false) {
			$Hub = self::factory();
			$Accounts = rex_config::get('socialhub','twitter');
			$Accounts = $Accounts['accounts'][0];
			
			if(!$Accounts) return;
			
			$connection = new Abraham\TwitterOAuth\TwitterOAuth($Accounts['consumer_token'], $Accounts['consumer_secret_token'], $Accounts['access_token'], $Accounts['secret_token']);
			if($next_id != 0) {
				$response = $connection->get("search/tweets",['q'=>'#'.$hashtag,'since_id'=>$next_id]);
			} else {
				$response = $connection->get("search/tweets",['q'=>'#'.$hashtag]);
			}
			
			foreach($response->statuses as $data) {
				$lastId = $data->id;
				$this->saveHashtagEntry($data,$hashtag);
			}
			
			$sql = rex_sql::factory();
			$sql->debugsql = 0;
			$sql->setTable(rex::getTablePrefix().'socialhub_hashtags');
			$sql->setWhere('hashtag = "'.addslashes($hashtag).'"');
			$sql->setValue($this->plugin.'_next_id', $lastId);
			
			try {
				$sql->update();
			} catch (rex_sql_exception $e) {
				echo rex_view::warning($e->getMessage());
			}
		}
		
		private function saveHashtagEntry($data,$query) {
		 if (empty($data->retweeted_status)) {
				$sql = rex_sql::factory();
				$sql->debugsql = 0;
				$sql->setTable(rex::getTablePrefix().'socialhub_entries');
				
				$sql->setValue('source', $this->plugin);
				$sql->setValue('source_id', $data->id);
				$sql->setValue('caption', urlencode($data->text ? addslashes($data->text) : ''));
				
				if ($data->entities && !empty($data->entities->media)) {
					$sql->setValue('image', $data->entities->media[0]->media_url);
				}
				
				$sql->setValue('created_time', date('Y-m-d H:i:s',strtotime($data->created_at)));
				$sql->setValue('user_id', $data->user->id);
				$sql->setValue('query', $query);
				$sql->setValue('visible', ((!empty($data->entities->media)) ? '1' : '0'));
				
				try {
					$sql->insert();
					$this->counter++;
				} catch (rex_sql_exception $e) {
					echo rex_view::warning($e->getMessage());
				}
			}
		}
		
		public static function factory() {
			$class = static::getFactoryClass();
			return new $class();
		}
	}
?>