<?php

	class rex_socialhub_facebook extends rex_socialhub {

    protected $table = 'rex_socialhub_facebook';

    protected function __construct() {
      parent::__construct();
    }

		public static function cron() {
      $fb = new Facebook\Facebook([
        'app_id' => '1621100048122788',
        'app_secret' => '1b4cff4760b0e705f01eded295b1f53d',
        'default_graph_version' => 'v2.2',
      ]);

      try {
        $response = $fb->get('/Sioweb/posts','1621100048122788|nEgB5hcEU04NF1z6Y_3OBbSdhxc');
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }

      $Body = json_decode($response->getBody(),1);
      // print_r($Body);
      foreach($Body['data'] as $key => $fb_post) {
        $newPost = rex_sql::factory();
        $newPost->setTable('rex_socialhub_facebook');
        $newPost->setWhere(array('fid'=>$fb_post['id']));
        $newPost->select();
        if($newPost->getRows() === 0) {
          $newPost->reset();
          $newPost->setTable('rex_socialhub_facebook');
          $newPost->setValue('fid',$fb_post['id']);
          $newPost->setValue('message',$fb_post['message']);
          $newPost->setValue('name',$fb_post['from']['name']);
          $newPost->setValue('author',json_encode($fb_post['from']));
          $newPost->setValue('post_type',$fb_post['type']);
          $newPost->setValue('privacy',json_encode($fb_post['privacy']));
          $newPost->setValue('likes',json_encode($fb_post['likes']));
          $newPost->setValue('count_likes',count($fb_post['likes']));

          try {
            $newPost->insert();
          } catch (rex_sql_exception $e) {
            echo rex_view::warning($e->getMessage());
          }
        }
      }
		}


    /**
     * Creates a rex_sql instance.
     *
     * @param int $DBID
     *
     * @return static Returns a rex_sql instance
     */
    public static function factory() {
      $class = static::getFactoryClass();
      return new $class();
    }
	}
?>