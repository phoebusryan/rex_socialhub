<?php
	abstract class rex_socialhub {

		private $sql = null;

    use rex_factory_trait;

		abstract public static function cron();

		protected function __construct() {
			$this->sql = rex_sql::factory();
			$this->sql->setTable($this->table);
		}

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

		public function entries($obj = false) {
			$select = $this->sql->select();
			$arrEntry = [];
			while($select->hasNext()) {
				$arrEntry[] = $this->json_decode($select->getRow(),$obj);
				$select->next();
			}
			return $arrEntry;
		}

		private function json_decode($arrData,$obj = false) {
			$_arrData = [];
			foreach($arrData as $key => $value)
				$_arrData[substr($key,strpos($key,'.')+1)] = @json_decode($value,$obj);

			return $_arrData;
		}
	}
?>