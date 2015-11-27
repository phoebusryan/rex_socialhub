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