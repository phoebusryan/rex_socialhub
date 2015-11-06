<?php
	class rex_socialhub_instagram extends rex_socialhub {
		private $url;
		
		private function getDataByHashtag($hashtag, $nextID = false) {
			if ($nextID && $nextID != 0) {
				$url = 'https://api.instagram.com/v1/tags/'.$hashtag.'/media/recent?count=100&client_id='.$this->settings['client_id'].'&min_tag_id='.$next_id;
			} else {
				$url = 'https://api.instagram.com/v1/tags/'.$hashtag.'/media/recent?count=100&client_id='.$this->settings['client_id'];
			}
		}
		
	}
?>