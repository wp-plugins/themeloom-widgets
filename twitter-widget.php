<?php
// LivingOS Theme Framework       
// ========================
// Simple Twitter Widget to show most recent tweets
// Uses javascript to load asynchronously

//define twitter widget
class los_twitter_widget extends WP_Widget {
	
	function los_twitter_widget() {
		$widget_ops = array('classname' => 'LOSTwitterWidget', 'description' => __('Show latest twitter tweets.','livingos') );
		$this->WP_Widget('los_twitter_widget', __('Twitter Widget','livingos'), $widget_ops);
		
		//load js only if widget active
		if ( is_active_widget(false, false, $this->id_base) )
            add_action( 'wp_footer', array(&$this, 'los_twitface_scripts') );
		
	}

	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Example', 'livingos'), 'num_posts' => 5, 'twittername' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		// outputs the options form on admin
		$title = esc_attr($instance['title']);
?>		
		<p><em>Show your latest tweets from your twitter account:</em></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('twittername'); ?>"><?php _e('Twitter Name:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('twittername'); ?>" name="<?php echo $this->get_field_name('twittername'); ?>" type="text" value="<?php echo $instance['twittername']; ?>" /></label></p>	
		<p><label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number of Tweets:', 'livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo $instance['num_posts']; ?>" /></label></p>

		
		<?php
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$new_instance['num_posts'] = intval($new_instance['num_posts']);
		$new_instance['twittername'] = wp_strip_all_tags( $new_instance['twittername'] );
		
		return $new_instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget
		$args['title'] = apply_filters('widget_title', $instance['title']);
		$args['num_posts'] = $instance['num_posts'];
		
		//start widget
		echo $args['before_widget']; 
		if ( $args['title'] )
			echo $args['before_title'] . '<a href="http://www.twitter.com/'. $instance['twittername'] . '" target="_blank">' . esc_html( $args['title'] ) . '</a>' . $args['after_title'];
		?>
		<script type="text/javascript"> 
			jQuery(document).ready(function() { 
			// When the document is loaded (jQuery function)
			// Call the Twitter API to retrieve the last 10 tweets in JSON format
			jQuery('#tweet').html('<div id="tweet-loading"><img src="<?php echo get_template_directory_uri() ;?>/base/lib/images/ajax-loader.gif" width="24" height="24" /><small>waiting for twitter...</small></div>');
			jQuery.getJSON("http://api.twitter.com/1/statuses/user_timeline.json?include_rts=true&screen_name=<?php echo $instance['twittername'];?>&count=<?php echo $instance['num_posts']; ?>&callback=?", function(tweetdata) {		
						
			//jQuery.getJSON("http://twitter.com/statuses/user_timeline.json?include_rts=true&screen_name=<?php echo $instance['twittername'];?>&count=<?php echo $instance['num_posts']; ?>&callback=?", function(tweetdata) {		
					jQuery('#tweet').html('<ul id="tweet-list"></ul>');
					// Grab a reference to the ul element which will display the tweets
					var tl = jQuery("#tweet-list");
					// For each item returned in tweetdata
					jQuery.each(tweetdata, function(i,tweet) {
						// Append the info in li tags to the ul, converting any links to HTML <a href=.. code and convert the tweeted date
						// to a more readable Twitter format
						tl.append("<li>&ldquo;" + urlToLink(tweet.text) + "&rdquo;&ndash; <em>" + relTime(tweet.created_at) + "</em></li>");
					});
				});
			});	
		</script>
		
		<?php
		
		echo '<div id="tweet" >';	
		
		
		echo '</div>';
		echo '<div id="tweet-follow"><a href="http://www.twitter.com/'. $instance['twittername'] . '" target="_blank">'. __('follow on twitter &rarr;', 'livingos').'</a></div>';
		//end widget
		echo $args['after_widget'];		
		
	}
	
	//fucntion to create javascript to load tweet asynchronously
	function los_twitface_scripts() {
   ?>
	   <script type="text/javascript"> 
	   // Converts any links in text to their HTML <a href=""> equivalent
	   // converts hashtags and usernames
			function urlToLink(text) {
			  var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
			  text = text.replace(exp,"<a href='$1'>$1</a>"); 
			  text = text.replace(/(^|\s)@(\w+)/g, '$1@<a href="http://www.twitter.com/$2" target="_blank">$2</a>');
			  text = text.replace(/(^|\s)#(\w+)/g, '$1#<a href="http://search.twitter.com/search?q=%23$2" target="_blank">$2</a>');
			  return text;
			}

			// Takes a time value and converts it to "from now" and then returns a relevant text interpretation of it
			function relTime(time_value) {
				time_value = time_value.replace(/(\+[0-9]{4}\s)/ig,"");
				var parsed_date = Date.parse(time_value);
				var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
				var timeago = parseInt((relative_to.getTime() - parsed_date) / 1000);			
				if (timeago < 60) return 'less than a minute ago';
				else if(timeago < 120) return 'about a minute ago';
				else if(timeago < (45*60)) return (parseInt(timeago / 60)).toString() + ' minutes ago';
				else if(timeago < (90*60)) return 'about an hour ago';
				else if(timeago < (24*60*60)) return 'about ' + (parseInt(timeago / 3600)).toString() + ' hours ago';
				else if(timeago < (48*60*60)) return '1 day ago';
				else return (parseInt(timeago / 86400)).toString() + ' days ago';
			}


			</script>
   <?php

	}
}

//add_action('widgets_init', create_function('', 'return register_widget("los_twitter_widget");'));
?>