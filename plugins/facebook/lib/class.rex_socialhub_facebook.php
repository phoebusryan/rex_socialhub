<?php
	class rex_socialhub_facebook {
		
		public static function cron() {

      $fb = new Facebook\Facebook([
        'app_id' => '1621100048122788',
        'app_secret' => '1b4cff4760b0e705f01eded295b1f53d',
        'default_graph_version' => 'v2.2',
      ]);

      $Config = rex_config::get('socialhub');
      if(empty($Config['facebook']['page'])) {
        echo rex_view::warning(rex_i18n::msg('socialhub_facebook_no_page'));
        return;
      }
      $Config = $Config['facebook']['page'];

      foreach($Config as $pKey => $page) {
        try {
          $response = $fb->get('/'.ucfirst($page).'/posts','1621100048122788|nEgB5hcEU04NF1z6Y_3OBbSdhxc');
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo rex_view::error('Graph returned an error: ' . $e->getMessage());
          
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo rex_view::error('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if(empty($response)) return;

        $Body = json_decode($response->getBody(),1);
        // print_r($Body);
        foreach($Body['data'] as $key => $fb_post) {
          $newPost = rex_sql::factory();
          $newPost->setTable(rex::getTablePrefix().'socialhub_facebook');
          $newPost->setWhere(array('post_id'=>$fb_post['id']));
          $newPost->select();
          if($newPost->getRows() === 0) {
            $newPost->reset();
            $newPost->setTable(rex::getTablePrefix().'socialhub_facebook');
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
	}
?>