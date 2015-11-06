<?php
	class rex_socialhub {
		protected function curlURL($url) {
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
		
		/* todo */
		protected function getHashtags() {
			$sql = new sql();
			$result = $sql->get_array('SELECT `hashtag`,`'.$this->plugin.'_next_id` FROM `'.$REX['TABLE_PREFIX'].'socialhub_hashtags` ORDER BY `hashtag` ASC');
			
			$hashtags = [];
			foreach($result as $row) {
				$hashtags[$row['hashtag']] = $row[$this->plugin.'_next_id'];
			}
			
			return $hashtags;
		}
	}
?>