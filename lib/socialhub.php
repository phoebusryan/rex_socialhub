<?php
	abstract class socialhub {

		protected $plugin = '';
		private $sql = null;

    use rex_factory_trait;

		abstract public function timeline();
		abstract public function findBy();
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
			$this->sql->setTable(rex::getTablePrefix().'socialhub_hashtag');
			$result = $this->sql->getArray('SELECT `hashtag`,`'.$this->plugin.'_next_id` FROM `'.rex::getTablePrefix().'socialhub_hashtags` ORDER BY `hashtag` ASC');
			
			$hashtags = [];
			foreach($result as $row) {
				$hashtags[$row['hashtag']] = $row[$this->plugin.'_next_id'];
			}
			
			return $hashtags;
		}

		public function toggleVisibility() {
			$this->sql->reset();
			$this->sql->setQuery("UPDATE ".$this->table." SET visible = CASE WHEN visible = 1 THEN 0 ELSE 1 END WHERE id = ?",array(rex_get('entry')));
		}

		public function toggleHighlight() {
			$this->sql->reset();
			$this->sql->setQuery("UPDATE ".$this->table." SET highlight = CASE WHEN highlight = 1 THEN 0 ELSE 1 END WHERE id = ?",array(rex_get('entry')));
		}

		public function entries($asArray = true,$where=array()) {
			$this->sql->reset();
			if(!empty($where))
				$this->sql->setWhere($where);
			$select = $this->sql->select();
			$arrEntry = [];
			while($select->hasNext()) {
				$arrEntry[] = $this->json_decode($select->getRow(),$asArray);
				$select->next();
			}
			return $arrEntry;
		}

		private function json_decode($arrData,$asArray = true) {
			$_arrData = [];
			foreach($arrData as $key => $value)
				if(!is_numeric($key)) {
					$arrValue = @json_decode($value,$asArray);
					$_arrData[(strpos($key,'.') !== false?substr($key,strpos($key,'.')+1):$key)] = is_array($arrValue) || is_object($arrValue)?$arrValue:$value;
				}

			return $_arrData;
		}
	}
?>