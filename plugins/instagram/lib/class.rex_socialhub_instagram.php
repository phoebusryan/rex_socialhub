<?php
	class rex_socialhub_instagram {
	
		public static function getEntriesByTimeline() {
			/*
			//Start - get all timelines from the database
				$sql = rex_sql::factory();
				$timelines = $sql->getArray('SELECT `user_id`, `instagram_next_id` FROM `'.rex::getTablePrefix().'socialhub_instagram_timeline`');
				unset($sql);
				
				if (empty($timelines)) {
					return false;
				}
			//End - get all timelines from the database
			
			//Start - get all accounts from the database
				$sql = rex_sql::factory();
				$accounts = $sql->getArray('SELECT * FROM `'.rex::getTablePrefix().'socialhub_instagram_account` ORDER BY `id` ASC');
				unset($sql);
				
				if (empty($accounts)) {
					return false;
				}
			//End - get all accounts from the database
			
			//Start - get entries by timeline from instagram
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
			//End - get entries by timeline from instagram
			*/
		}
		
		public static function getEntriesByHashtag() {
			//Start - get all hashtags from the database
				$sql = rex_sql::factory();
				$hashtags = $sql->getArray('SELECT `hashtag`, `instagram_next_id` FROM `'.rex::getTablePrefix().'socialhub_instagram_hashtag`');
				unset($sql);
				
				if (empty($hashtags)) {
					return false;
				}
			//End - get all hashtags from the database
			
			//Start - get all accounts from the database
				$sql = rex_sql::factory();
				$accounts = $sql->getArray('SELECT * FROM `'.rex::getTablePrefix().'socialhub_instagram_account` ORDER BY `id` ASC');
				unset($sql);
				
				if (empty($accounts)) {
					return false;
				}
			//End - get all accounts from the database
				
			//Start - get entries by hashtag from instagram
				foreach ($hashtags as $hashtag) {
					if ($hashtag['instagram_next_id'] != 0) {
						$url = 'https://api.instagram.com/v1/tags/'.$hashtag['hashtag'].'/media/recent?count=100&client_id='.$accounts[0]['client_id'].'&min_tag_id='.$hashtag['instagram_next_id'];
					} else {
						$url = 'https://api.instagram.com/v1/tags/'.$hashtag['hashtag'].'/media/recent?count=100&client_id='.$accounts[0]['client_id'];
					}
					
					$response = self::curlURL($url);
					$response = json_decode($response);
					
					foreach ($response->data as $data) {
						$sql = rex_sql::factory();
						$sql->setTable(rex::getTablePrefix().'socialhub_entry_hashtag');
						
						$sql->setValue('source', 'instagram');
						$sql->setValue('source_id', $data->id);
						$sql->setValue('caption', urlencode($data->caption->text));
						
						if (!empty($data->images->standard_resolution->url)) {
							$sql->setValue('image', $data->images->standard_resolution->url);
						}
						
						$sql->setValue('created_time', date('Y-m-d H:i:s',$data->created_time));
						$sql->setValue('author_id', $data->user->id);
						$sql->setValue('author_name', $data->user->username);
						$sql->setValue('query', $hashtag['hashtag']);
						
						try {
							$sql->insert();
						} catch (rex_sql_exception $e) {
							echo rex_view::warning($e->getMessage());
						}
						
						unset($sql);
					}
					
					//Start - update next_id
						$sql = rex_sql::factory();
						$sql->setTable(rex::getTablePrefix().'socialhub_instagram_hashtag');
						$sql->setWhere('hashtag = "'.addslashes($hashtag['hashtag']).'"');
						$sql->setValue('instagram_next_id', $response->pagination->min_tag_id);
						
						try {
							$sql->update();
						} catch (rex_sql_exception $e) {
							echo rex_view::warning($e->getMessage());
						}
						
						unset($sql);
					//End - update next_id
					
				}
			//End - get entries by hashtag from instagram
		}
		
		private static function curlUrl($url) {
			$ch = curl_init();
			curl_setopt_array($ch, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => 2
			]);
			
			$result = curl_exec($ch);
			curl_close($ch);
			
			return $result;
		}


		/*
    public function timeline() {
      $sql = rex_sql::factory();
      $sql->setTable($this->table);
      $sql->select();
      $entry = '';
      while($sql->hasNext()) {

        $caption = urldecode($sql->getValue('message'));
        $caption = preg_replace('/((https*|www\.)[^\s]+)/is','<a href="$1" target="_blank" title="link to $1">$1</a>',$caption);
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
			$Accounts = rex_config::get('socialhub','instagram');
			$Accounts = $Accounts['accounts'];

			if(!$Accounts) {
        echo rex_view::error(rex_i18n::msg('socialhub_instagram_no_account'));
        return;
      }

			foreach($Accounts as $token => $account) {
				$response = $Hub->curlURL('https://api.instagram.com/v1/users/self/feed?&access_token='.$token);
				$response = json_decode($response);

        if(empty($response->data)) {
          echo rex_view::error(rex_i18n::msg('socialhub_instagram_no_response'));
          continue;
        }
				foreach($response->data as $key => $data) {
					$sql = rex_sql::factory();
          $sql->setTable($Hub->table);
          $sql->setWhere(array('post_id'=>substr($data->link,(strpos($data->link,self::$post_dir)+strlen(self::$post_dir)),-1)));
          $sql->select();
          if($sql->getRows() === 0) {
            $sql->reset();
          	$sql->setTable($Hub->table);
						$sql->setValue('post_id', substr($data->link,(strpos($data->link,self::$post_dir)+strlen(self::$post_dir)),-1));
						$sql->setValue('message', urlencode($data->caption ? addslashes($data->caption->text) : ''));
						$sql->setValue('image', $data->images->standard_resolution->url);
						$sql->setValue('name', $data->user->id);
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
	}
?>