<?php

	class socialhub_facebook extends socialhub {

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

      $Config = rex_config::get('socialhub');
      $Config = $Config['facebook']['page'];

      foreach($Config as $pKey => $page) {
        try {
          $response = $fb->get('/'.ucfirst($page).'/posts','1621100048122788|nEgB5hcEU04NF1z6Y_3OBbSdhxc');
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
          $newPost->setTable('socialhub_facebook');
          $newPost->setWhere(array('post_id'=>$fb_post['id']));
          $newPost->select();
          if($newPost->getRows() === 0) {
            $newPost->reset();
            $newPost->setTable('socialhub_facebook');
            $newPost->setValue('post_id',$fb_post['id']);
            $newPost->setValue('message',$fb_post['message']);
            $newPost->setValue('name',$fb_post['from']['name']);
            $newPost->setValue('query',json_encode($fb_post));

            try {
              $newPost->insert();
            } catch (rex_sql_exception $e) {
              echo rex_view::warning($e->getMessage());
            }
          }
        }
      }
		}


    /**
     * Creates a socialhub_facebook instance.
     *
     * @param int $DBID
     *
     * @return static Returns a socialhub_facebook instance
     */
    public static function factory() {
      $class = static::getFactoryClass();
      return new $class();
    }
	}
?>