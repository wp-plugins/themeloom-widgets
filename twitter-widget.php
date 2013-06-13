<?php
// LivingOS Theme Framework       
// ========================
// Simple Twitter Widget to show most recent tweets
// Uses new API and php based on StormTwitter

require('lib/LosTwitter.class.php');


/* implement getTweets */
function los_getTweets($count = 20, $username = false, $options = false) {
 
 $config = array(
    'key' => '',
    'secret' => '',
    'token' => '',
    'token_secret' => '',
    'screenname' => '',
    'cache_expire' => 3600      
  );
  
  $config = array_merge($config, $options);
  
  if ($config['cache_expire'] < 60) $config['cache_expire'] = 3600;
 
  
  $obj = new LosTwitter($config);
  $res = $obj->getTweets($count, $username, $options);
  update_option('tdf_last_error',$obj->st_last_error);
  return $res;
  
}

// This calculates a relative time, e.g. "1 minute ago"
function los_relativeTime($time)
    {
        $second = 1;
        $minute = 60 * $second;
        $hour = 60 * $minute;
        $day = 24 * $hour;
        $month = 30 * $day;

        $delta = time() - $time;

        if ($delta < 1 * $minute)
        {
            return $delta == 1 ? "one second ago" : $delta . " seconds ago";
        }
        if ($delta < 2 * $minute)
        {
          return "a minute ago";
        }
        if ($delta < 45 * $minute)
        {
            return floor($delta / $minute) . " minutes ago";
        }
        if ($delta < 90 * $minute)
        {
          return "an hour ago";
        }
        if ($delta < 24 * $hour)
        {
          return floor($delta / $hour) . " hours ago";
        }
        if ($delta < 48 * $hour)
        {
          return "yesterday";
        }
        if ($delta < 30 * $day)
        {
            return floor($delta / $day) . " days ago";
        }
        if ($delta < 12 * $month)
        {
          $months = floor($delta / $day / 30);
          return $months <= 1 ? "one month ago" : $months . " months ago";
        }
        else
        {
            $years = floor($delta / $day / 365);
            return $years <= 1 ? "one year ago" : $years . " years ago";
        }
    }

//define twitter widget
class los_twitter_widget extends WP_Widget {
	
	function los_twitter_widget() {
		$widget_ops = array('classname' => 'LOSTwitterWidget', 'description' => __('Show latest twitter tweets.','livingos') );
		$this->WP_Widget('los_twitter_widget', __('Twitter Widget','livingos'), $widget_ops);
		
	}

	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Example', 'livingos'), 'num_posts' => 5, 'twittername' => '', 'consumer_key' =>'', 'consumer_secret' => '', 'access_token' => '',  'access_token_secret' => '', 'cache_expire' => 3600  );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		// outputs the options form on admin
		$title = esc_attr($instance['title']);
?>		
		<p><em><?php _e('Show your latest tweets from your twitter account:','livinogs'); ?></em></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<h3>Twitter API credentials</h3>
		<p><?php _e('This widget requires a Twitter API key. Get one here:  <a href="http://dev.twitter.com/apps">http://dev.twitter.com</a>','livingos') ?></p>
		<p><?php _e('When creating an application for this widget, you don\'t need to set a callback location and you only need read access.','livingos') ?></p>
		
		
		<p><label for="<?php echo $this->get_field_id('consumer_key'); ?>"><?php _e('Consumer Key:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('consumer_key'); ?>" name="<?php echo $this->get_field_name('consumer_key'); ?>" type="text" value="<?php echo $instance['consumer_key']; ?>" /></label></p>	
		<p><label for="<?php echo $this->get_field_id('consumer_secret'); ?>"><?php _e('Consumer Secret:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('consumer_secret'); ?>" name="<?php echo $this->get_field_name('consumer_secret'); ?>" type="text" value="<?php echo $instance['consumer_secret']; ?>" /></label></p>	
		<p><label for="<?php echo $this->get_field_id('access_token'); ?>"><?php _e('Access Token:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('access_token'); ?>" name="<?php echo $this->get_field_name('access_token'); ?>" type="text" value="<?php echo $instance['access_token']; ?>" /></label></p>	
		<p><label for="<?php echo $this->get_field_id('access_token_secret'); ?>"><?php _e('Access Token Secret:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('access_token_secret'); ?>" name="<?php echo $this->get_field_name('access_token_secret'); ?>" type="text" value="<?php echo $instance['access_token_secret']; ?>" /></label></p>	
		<h3>Twitter Feed</h3>
		<p><label for="<?php echo $this->get_field_id('twittername'); ?>"><?php _e('Twitter Name:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('twittername'); ?>" name="<?php echo $this->get_field_name('twittername'); ?>" type="text" value="<?php echo $instance['twittername']; ?>" /></label></p>	
		<p><label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number of Tweets:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo $instance['num_posts']; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('cache_expire'); ?>"><?php _e('Cache Expire (s):', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('cache_expire'); ?>" name="<?php echo $this->get_field_name('cache_expire'); ?>" type="text" value="<?php echo $instance['cache_expire']; ?>" /></label></p>
		
		
		<?php
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$new_instance['num_posts'] = intval($new_instance['num_posts']);
		$new_instance['twittername'] = wp_strip_all_tags( $new_instance['twittername'] );
		$new_instance['consumer_key'] = wp_strip_all_tags($new_instance['consumer_key']);
		$new_instance['consumer_secret'] = wp_strip_all_tags( $new_instance['consumer_secret'] );
		$new_instance['access_token'] = wp_strip_all_tags($new_instance['access_token']);
		$new_instance['access_token_secret'] = wp_strip_all_tags( $new_instance['access_token_secret'] );
		$new_instance['cache_expire'] = intval( $new_instance['cache_expire'] );
		if ($new_instance['cache_expire'] < 60) $new_instance['cache_expire'] = 3600;
		return $new_instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget
		$args['title'] = apply_filters('widget_title', $instance['title']);
		$args['num_posts'] = $instance['num_posts'];
		
		$options = array(
			'key' => $instance['consumer_key'],
			'secret' => $instance['consumer_secret'],
			'token' => $instance['access_token'],
			'token_secret' => $instance['access_token_secret'],
			'screenname' => $instance['twittername'],
			'cache_expire' => $instance['cache_expire']      
		  );
		
		$tweets = los_getTweets ($instance['num_posts'], $instance['twittername'], $options);
		
		//start widget
		echo $args['before_widget']; 
		if ( $args['title'] )
			echo $args['before_title'] . '<a href="http://www.twitter.com/'. $instance['twittername'] . '" target="_blank">' . esc_html( $args['title'] ) . '</a>' . $args['after_title'];
				
		echo '<div id="tweet" ><ul id="tweet-list">';	
		if( isset ( $tweets['error'] ) ) {
			echo $tweets['error'];
		} else {
			foreach($tweets as $tweet){
				if( $tweet['text']){
				
					$the_tweet = $tweet['text'];
					
					echo '<li>&ldquo;';

					// i. User_mentions must link to the mentioned user's profile.
					if(is_array($tweet['entities']['user_mentions'])){
						foreach($tweet['entities']['user_mentions'] as $key => $user_mention){
							$the_tweet = preg_replace(
								'/@'.$user_mention['screen_name'].'/i',
								'<a href="http://www.twitter.com/'.$user_mention['screen_name'].'" target="_blank">@'.$user_mention['screen_name'].'</a>',
								$the_tweet);
						}
					}

					// ii. Hashtags must link to a twitter.com search with the hashtag as the query.
					if(is_array($tweet['entities']['hashtags'])){
						foreach($tweet['entities']['hashtags'] as $key => $hashtag){
							$the_tweet = preg_replace(
								'/#'.$hashtag['text'].'/i',
								'<a href="https://twitter.com/search?q=%23'.$hashtag['text'].'&src=hash" target="_blank">#'.$hashtag['text'].'</a>',
								$the_tweet);
						}
					}

					// iii. Links in Tweet text must be displayed using the display_url
					//      field in the URL entities API response, and link to the original t.co url field.
					if(is_array($tweet['entities']['urls'])){
						foreach($tweet['entities']['urls'] as $key => $link){
							$the_tweet = preg_replace(
								'`'.$link['url'].'`',
								'<a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a>',
								$the_tweet);
						}
					}

					echo $the_tweet;


					


					// 4. Tweet Timestamp
					//    The Tweet timestamp must always be visible and include the time and date. e.g., “3:00 PM - 31 May 12”.
					// 5. Tweet Permalink
					//    The Tweet timestamp must always be linked to the Tweet permalink.
					echo '
					<em>&rdquo;&ndash;
						<a href="https://twitter.com/'.$instance['twittername'].'/status/'.$tweet['id_str'].'" target="_blank">
							'.los_relativeTime(strtotime( $tweet['created_at'] )).'
						</a>
					</em>';// -8 GMT for Pacific Standard Time
				} 
				echo '</li>';
			
			}
		}
		echo '</ul></div>';
		echo '<div id="tweet-follow"><a href="http://www.twitter.com/'. $instance['twittername'] . '" target="_blank">'. __('follow on twitter &rarr;', 'livingos').'</a></div>';
		//end widget
		echo $args['after_widget'];		
		
	}
	
}

?>