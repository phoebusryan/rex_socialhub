<?php

	class socialhub_instagram extends socialhub {

		public static $url = 'https://instagram.com';
		public static $post_dir = '/p/';
    public static $search_url = 'https://www.instagram.com/explore/tags/';

    protected $table = 'rex_socialhub_instagram';

		private $counter = 0;

    protected function __construct() {
    	$this->plugin = 'instagram';
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

    public function findBy() {
      
    }
    

		public static function cron() {
			$Hub = self::factory();
			$Accounts = rex_config::get('socialhub','instagram');
			$Accounts = $Accounts['accounts'];

			if(!$Accounts) return;

			foreach($Accounts as $token => $account) {
				$response = $Hub->curlURL('https://api.instagram.com/v1/users/self/feed?&access_token='.$token);
				$response = json_decode($response);

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

		public static function loadHashtags() {
			$Hub = self::factory();
			foreach($Hub->getHashtags() as $hashtag => $next_id)
				$Hub->getDataByHashtag($hashtag,$next_id);
		}
		
		private function getDataByHashtag($hashtag, $nextID = false) {
			$Token = rex_config::get('socialhub','instagram');
			$Token = $Token['access_token'];
			if(!$Token) return;

			if ($nextID && $nextID != 0) {
				$url = 'https://api.instagram.com/v1/tags/'.$hashtag.'/media/recent?count=100&access_token='.$Token.'&min_tag_id='.$nextID;
			} else {
				$url = 'https://api.instagram.com/v1/tags/'.$hashtag.'/media/recent?count=100&access_token='.$Token;
			}

			$response = $this->curlURL($url);
			$response = json_decode($response);

			if($response->pagination && property_exists($response->pagination,'min_tag_id') && $response->pagination->min_tag_id !== 0) {
				$hash_sql = rex_sql::factory();
				$hash_sql->setTable(rex::getTablePrefix().'socialhub_hashtags');
				$hash_sql->setWhere('hashtag = "'.$hashtag.'"');
				$hash_sql->setValue($this->plugin.'_next_id', $response->pagination->min_tag_id);

				try {
	        $hash_sql->update();
	      } catch (rex_sql_exception $e) {
	        echo rex_view::warning($e->getMessage());
	      }
			}
			
			$entries = [];
			if($response->meta->code == 200) {
				$counter = 0;
				
				foreach($response->data as $data) {
					$this->saveHashtagEntry($data,'#'.$hashtag);
				}
			}
		}

		private function saveHashtagEntry($data,$query) {
			global $REX;
			
			$sql = rex_sql::factory();
			$sql->setTable(rex::getTablePrefix().'socialhub_entries');
			
			$sql->setValue('source', $this->plugin);
			$sql->setValue('source_id', substr($data->id,0,strpos($data->id,'_')));
			$sql->setValue('post_id', substr($data->link,(strpos($data->link,self::$post_dir)+strlen(self::$post_dir)),-1));
			$sql->setValue('caption', urlencode($data->caption ? addslashes($data->caption->text) : ''));
			$sql->setValue('image', $data->images->standard_resolution->url);
			$sql->setValue('created_time', date('Y-m-d H:i:s',$data->created_time));
			$sql->setValue('user_id', $data->user->id);
			$sql->setValue('query', addslashes($query));
			$sql->setValue('visible', '1');
			if(!empty($data->videos)) {
				$sql->setValue('video', $data->videos->standard_resolution->url);
			}
			
			try {
        $sql->insert();
      } catch (rex_sql_exception $e) {
        echo rex_view::warning($e->getMessage());
      }
		}

		public function getAccountData($accounts) {
			if(empty($accounts)) return [];

			$arrAccounts = [];
			foreach($accounts as $key => $account) {
				$User = json_decode($this->curlURL('https://api.instagram.com/v1/users/self?access_token='.$account),1);
				if(empty($User['data'])) continue;
				$arrAccounts[$account] = $User['data'];
			}
			if(!empty($arrAccounts))
				$arrAccounts = array_filter($arrAccounts);
			return $arrAccounts;
		}


    /**
     * Creates a socialhub_instagram instance.
     *
     * @param int $DBID
     *
     * @return static Returns a socialhub_instagram instance
     */
    public static function factory() {
      $class = static::getFactoryClass();
      return new $class();
    }
		
	}

?>