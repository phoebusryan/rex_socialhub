<?php

  class rex_socialhub_twitter extends rex_socialhub {

    public static $url = 'https://twitter.com';
    protected $table = 'rex_socialhub_twitter';

    private $counter = 0;


    protected function __construct() {
      // require_once '/Applications/MAMP/htdocs/redaxo5/redaxo/src/addons/rex_socialhub/plugins/twitter/vendor/twitteroauth/autoload.php';
      $this->plugin = 'twitter';
      parent::__construct();
    }


    public static function cron() {
      $Hub = self::factory();
      $Accounts = rex_config::get('rex_socialhub','twitter');
      $Accounts = $Accounts['accounts'];

      if(!$Accounts) return;

      foreach($Accounts as $token => $account) {
        $connection = new Abraham\TwitterOAuth\TwitterOAuth($account['consumer_token'], $account['consumer_secret_token'], $account['access_token'], $account['secret_token']);
        $response = $connection->get("statuses/user_timeline",['user_id'=>'244714769']);

        print_r($response);
      }
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