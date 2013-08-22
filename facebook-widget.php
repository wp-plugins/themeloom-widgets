<?php
/* ThemeLoom Widgets - A Fcebook Widget
 * ====================================
 * - adds a simple facebook widget for accessing page feed
 */
 
/*
 *	Facebook Page Feed Widget
 */
class ThemeLoom_Facebook_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'tl_fb_widget', // Base ID
			'Facebook Widget', // Name
			array( 'description' => __( 'Displays your Facebook page feed on your site.', 'livingos' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
	
		$pageinfo = livingos_get_fb_page_info( $instance['page_id'], $instance['auth_token'], 3600 );
		 
		// outputs the content of the widget
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . '<a href="' . $pageinfo->link . '">'. $title . '</a>' . $args['after_title'];
		
	
		//get feed contents
		echo livingos_facebook_feed( $instance );
		
		echo $args['after_widget'];
	}

 	public function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Facebook Page Feed', 'livingos'), 'app_id' => '', 'app_sc' => '', 'page_id' => '', 
			'auth_token' => '', 'num_posts' => 3, 'post_meta' => 'on', 'page_title' => 'on', 'show_likes'=> 'on', 'follow_message' => __('Join us on Facebook','livingos')  );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		extract( $instance );
		
		?>
		<p><em><?php _e('Show your latest posts from your Facebook page:','livinogs'); ?></em></p>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Widget Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<h3><?php _e('Facebook API Keys','livingos'); ?></h3>
		<p><?php _e('This widget requires a Facebook API key. Get one here by creating an app:  <a href="https://developers.facebook.com/apps">Create App</a>','livingos') ?></p>
		<p><?php _e('When creating an application for this widget, you just need basic settings.','livingos') ?></p>
		<p>
		<label for="<?php echo $this->get_field_name( 'app_id' ); ?>"><?php _e( 'App ID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'app_id' ); ?>" name="<?php echo $this->get_field_name( 'app_id' ); ?>" type="text" value="<?php echo esc_attr( $app_id ); ?>" />
		<label for="<?php echo $this->get_field_name( 'app_sc' ); ?>"><?php _e( 'App Secret:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'app_sc' ); ?>" name="<?php echo $this->get_field_name( 'app_sc' ); ?>" type="text" value="<?php echo esc_attr( $app_sc ); ?>" />
		
		</p>
		<h3><?php _e('What to Show','livingos'); ?></h3>
		<p>
		<label for="<?php echo $this->get_field_name( 'page_id' ); ?>"><?php _e( 'Page ID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'page_id' ); ?>" name="<?php echo $this->get_field_name( 'page_id' ); ?>" type="text" value="<?php echo esc_attr( $page_id ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_name( 'num_posts' ); ?>"><?php _e( 'Number of items to show:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" type="text" value="<?php echo esc_attr( $num_posts ); ?>" />
		</p>
		<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['post_meta'], 'on' ); ?> id="<?php echo $this->get_field_id( 'post_meta' ); ?>" name="<?php echo $this->get_field_name( 'post_meta' ); ?>" />
		<label for="<?php echo $this->get_field_id('post_meta'); ?>"><?php _e('Show post headers','livingos'); ?></label> </p>
		<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['page_title'], 'on' ); ?> id="<?php echo $this->get_field_id( 'page_title' ); ?>" name="<?php echo $this->get_field_name( 'page_title' ); ?>" />
		<label for="<?php echo $this->get_field_id('page_title'); ?>"><?php _e('Show page title','livingos'); ?></label> </p>
		<p>
		<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['show_likes'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_likes' ); ?>" name="<?php echo $this->get_field_name( 'show_likes' ); ?>" />
		<label for="<?php echo $this->get_field_id('show_likes'); ?>"><?php _e('Show Likes count','livingos'); ?></label> </p>
		<p>
		<label for="<?php echo $this->get_field_name( 'follow_message' ); ?>"><?php _e( 'Follow button message:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'follow_message' ); ?>" name="<?php echo $this->get_field_name( 'follow_message' ); ?>" type="text" value="<?php echo esc_attr( $follow_message ); ?>" />
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['app_id'] = ( ! empty( $new_instance['app_id'] ) ) ? strip_tags( $new_instance['app_id'] ) : '';
		$instance['app_sc'] = ( ! empty( $new_instance['app_sc'] ) ) ? strip_tags( $new_instance['app_sc'] ) : '';
		$instance['page_id'] = ( ! empty( $new_instance['page_id'] ) ) ? strip_tags( $new_instance['page_id'] ) : '';
		$instance['num_posts'] = ( ! empty( $new_instance['num_posts'] ) ) ? strip_tags( $new_instance['num_posts'] ) : '';
		$instance['post_meta'] =  $new_instance['post_meta'] ;
		$instance['page_title'] =  $new_instance['page_title'] ;
		$instance['show_likes'] =  $new_instance['show_likes'] ;
		$instance['follow_message'] = ( ! empty( $new_instance['follow_message'] ) ) ? strip_tags( $new_instance['follow_message'] ) : '';
		
		//get access token
		$instance['auth_token'] = livingos_get_fb_access_token( $instance['app_id'], $instance['app_sc'] );
		
		return $instance;
	}
}

/*
 * Get access token
 */
function livingos_get_fb_access_token( $appid, $appsc ) {
	
	//Retrieve access token, now required for even public pages
	$authToken = livingos_fetchUrl("https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id={$appid}&client_secret={$appsc}");	
	
	return $authToken;
}

/*
 * Get page info
 */
function livingos_get_fb_page_info( $page_id, $auth_token, $cache_expire = 3600 ){
	
	//Page info : check cache
	$cachename = "fbpage-" . md5( $page_id . $auth_token );
	$pageinfo = get_transient( $cachename );
	
	if ( empty( $pageinfo ) ){
	   
	    //cache empty
		
		//get page details
		$json_object = livingos_fetchUrl( "https://graph.facebook.com/{$page_id}?{$auth_token}" );
		
		$pageinfo = json_decode($json_object);
		
		if ( isset($pageinfo) ) {
		  set_transient($cachename, $pageinfo, $cache_expire );
		}
	
	}
	
	return $pageinfo;
}

/*
 * Get page fee
 */
function livingos_get_fb_page_feed( $page_id, $auth_token, $num_posts, $cache_expire = 3600 ){
	
	//Page Feed : check cache
	$cachename = "fbfeed-" . md5( $page_id . $auth_token . $num_posts );
	$feedarray = get_transient( $cachename );
	
	if ( empty( $feedarray ) ){
	   
	    //cache empty
		
		//get page feed
		$json_object = livingos_fetchUrl( "https://graph.facebook.com/{$page_id}/feed?limit={$num_posts}&{$auth_token}" );
		$feedarray = json_decode($json_object);
		
		if ( isset($feedarray) ) {
		  set_transient($cachename, $feedarray, $cache_expire );
		}
	
	}
	
	return $feedarray;
}
 
/*
 * Main function for showing feed
 */
 function livingos_facebook_feed( $args = array() ) {
	
	$output = "";
	
	$defaults = array( 
		'page_id' => '', 
		'auth_token' => '',
		'num_posts' => 3,
		'post_meta' => true,
		'page_title' => true,
		'show_likes' => true,
		'follow_message' => __('Join us on Facebook','livingos'),
		'cache_expire' => 60
	);
	$args = wp_parse_args( $args, $defaults );
	
	//Page info
	$pageinfo = livingos_get_fb_page_info( $args['page_id'], $args['auth_token'], $args['cache_expire']);
	
	
	// show page info
	if ( $args['page_title'] ) {
		$output .= "<div class=\"fb-pageinfo\">";
		$output .= "<a href=\"{$pageinfo->link}\" >{$pageinfo->name}</a>";
		if ( $args['show_likes'] )
			$output .= "<a href=\"{$pageinfo->link}\" class=\"fb-like-btn\" ><span class=\"fb-likes\">$pageinfo->likes</span></a>";
		$output .= "</div>";
	}
	
	// get feed
	$feedarray = livingos_get_fb_page_feed( $args['page_id'], $args['auth_token'], $args['num_posts'], $args['cache_expire'] );
	
	// process feed 
	$output .= "<ul class=\"facebook-feed\">";
	
	foreach ( $feedarray->data as $feed_data )
	{
		// Get the description of item or the message of the one who posted the item
		$message = isset( $feed_data->message ) ? trim( $feed_data->message ) : null;
		$message = preg_replace('/\n/', '<br />', $message);

		// Get the description of item or the message of the one who posted the item
		$description = isset( $feed_data->description ) ? trim( $feed_data->description ) : null;
		// Turn urls into links and replace new lines with <br />
		$description = preg_replace(array('/((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}\/\S*)/', '/\n/'), array("<a href='$1'>\\1</a>", '<br />'), $description);
				
		// Get the description of item or the message of the one who posted the item
		$story = isset( $feed_data->story ) ? trim($feed_data->story ) : null;
		$story = preg_replace('/\n/', '<br />', $story);
		
	
		//date
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		//$pub_date = date( $date_format . ' ' . $time_format, strtotime( $feed_data->created_time ) );
		$pub_date = los_relativeTime( strtotime( $feed_data->created_time ));
		
		// images
		if ( isset($feed_data->picture) ) {
			$img = "<img src='". htmlentities($feed_data->picture) ."'  />\n";
		}
		
		// links
		if (!empty($feed_data->link)) {
					$link = $feed_data->link;
					//Check whether it links to facebook or somewhere else
					$facebook_str = 'facebook.com';
					if(stripos($link, $facebook_str) !== false) {
						$link_text = __('View on Facebook','livingos');
					} else {
						$link_text = __('View Link','livingos');
					}
				}
		
		
		
		// start output generation
		$output .= "<li class=\"{$feed_data->type}\" >";
		
								
		switch ( $feed_data->type ) {
			case 'link':
				if ( $args['post_meta'] ) {
					$output .= "<p class=\"fb-meta\"><strong>{$feed_data->from->name}</strong> " . __('shared a link:','livingos');
					$output .= "</br><span class=\"fb-pubdate\">{$pub_date}</span></p>\n";
				}
				$output .= "<p class=\"content\">". $message ."</p>\n";
				$output .= "<a class=\"fb-link-preview clearfix\" href=\"{$link}\" title=\"{$link_text}\" >";
				$output .= "{$img}<h4>{$feed_data->name}</h4><p>{$feed_data->description}</p>";
				$output .= '</a>';
				break;
			case 'status':
				if ( $args['post_meta'] ) {
					$output .= "<p class=\"fb-meta\"><strong>{$feed_data->from->name}</strong>";
					$output .= "</br><span class=\"fb-pubdate\">{$pub_date}</span>";
					$output .= "</p>\n";
				}
				if ( $message != null  )
					$output .= "<p class='message'>". $message ."</p>\n";
				else if ( $story != null )
					$output .= "<p class='message'>". $story ."</p>\n";
				
				break;
			default:
				if ( $args['post_meta'] ) {
					$output .= "<p class=\"fb-meta\"><strong>{$feed_data->from->name}</strong>";
					$output .= "</br><span class=\"fb-pubdate\">{$pub_date}</span>";
					$output .= "</p>\n";
				}
				if ( $message != null  )
					$output .= "<p class='message'>". $message ."</p>\n";
				else if ( $story != null )
					$output .= "<p class='message'>". $story ."</p>\n";
				
		
		}
		
		$output .= "</li>";
	}
	$output .= "</ul>";
	$output .= "<a class=\"fb-pagelink\" href=\"{$pageinfo->link}\" >" . $args['follow_message'] . "</a>";

	
	return $output;
 }
 
 
 
 

/*
 * fetch url
 */
if(!function_exists('livingos_fetchUrl')){
function livingos_fetchUrl($url){

 //Can we use cURL?
    if(is_callable('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $feedData = curl_exec($ch);
        curl_close($ch);
    //If not then use file_get_contents
    } elseif ( ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE ) {
        $feedData = @file_get_contents($url);
    //Or else use the WP HTTP API
    } else {
        if( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC. '/class-http.php' );
        $request = new WP_Http;
        $result = $request->request($url);
        $feedData = $result['body'];
    }
    
    return $feedData;

}
}




?>