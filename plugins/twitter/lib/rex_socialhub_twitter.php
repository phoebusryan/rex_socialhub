<?php

  class rex_socialhub_twitter extends rex_socialhub {

    public static $url = 'https://twitter.com';
    protected $table = 'rex_socialhub_twitter';

    private $counter = 0;


    protected function __construct() {
      $this->plugin = 'twitter';
      parent::__construct();
    }


    public static function cron() {

    }


    public static function loadHashtags() {
      $Hub = self::factory();
      foreach($Hub->getHashtags() as $hashtag => $next_id)
        $Hub->getDataByHashtag($hashtag,$next_id);
    }


    private function getDataByHashtag($hashtag, $nextID = false) {

      $Accounts = rex_config::get('rex_socialhub','twitter');

      $connection = new TwitterOAuth($Accounts['consumer_token'], $Accounts['consumer_access_token'], $Accounts['access_token'], $Accounts['secret_token']);
      if($next_id != 0) {
        $response = $connection->get("search/tweets",['q'=>'#'.$hashtag,'since_id'=>$next_id]);
      } else {
        $response = $connection->get("search/tweets",['q'=>'#'.$hashtag]);
      }

      $response = $this->curlURL($url);
      $response = json_decode($response);

      print_r($response);
    }


    /**
     * Creates a rex_socialhub_twitter instance.
     *
     * @param int $DBID
     *
     * @return static Returns a rex_socialhub_twitter instance
     */
    public static function factory() {
      $class = static::getFactoryClass();
      return new $class();
    }
  }
?>