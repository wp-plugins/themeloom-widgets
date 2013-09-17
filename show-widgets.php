<?php 
// ThemeLoom Widgets 
// 
//   - Show posts widget
//   - Show pages widget
//   - Media widget


//define showposts widget
class los_showposts_widget extends WP_Widget {

	function los_showposts_widget() {
		$widget_ops = array('classname' => 'LOSPostsWidget', 'description' => __('Show teasers/list of posts with thumbs, filtered by category.','livingos') );
		$this->WP_Widget('los_show_posts', __('Show Posts', 'livingos'), $widget_ops);
	}

	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Example', 'livingos'), 'cat_id' => NULL, 'dates' =>'on', 'post_formats' => '', 'show_sticky' => 0, 'exclude_cats' =>'', 'num_posts' => 5, 'more_link' => 'More', 'more_url' => home_url(),'thumbs' => 'on', 'tags' => '', 'categories'=> '','author'=>'', 'heading' => 'on', 'columns' => 1, 'content'=>'', 'thumbsize' => apply_filters('themeloom_widgets_defthumbsize','thumbnail'), 'entrytag' => 'h3');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		// outputs the options form on admin
			$title = esc_attr($instance['title']);
?>
		<p><em><?php _e('Show teasers and/or thumbs of posts filtered by category','livingos'); ?></em></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p> <!-- Category-->
			<label for="<?php echo $this->get_field_id('cat_id'); ?>"><?php _e('Category','livingos'); ?></label>
			<select id="<?php echo $this->get_field_id('cat_id'); ?>" name="<?php echo $this->get_field_name('cat_id'); ?>">
				<option value="0"<?php if( $instance['cat_id'] == 0 ) { ?> selected="selected"<?php } ?>><?php _e('ALL','livingos'); ?></option>
				
			<?php
			$cat_lists = get_categories(array('orderby' => 'name', 'order' => 'ASC'));
			foreach($cat_lists as $cat_list) {
				?>
				<option value="<?php echo $cat_list->term_id; ?>"<?php if($cat_list->term_id == $instance['cat_id']) { ?> selected="selected"<?php } ?>><?php echo $cat_list->name.' ('.$cat_list->count.')'; ?></option>
				<?php
			}
			?>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number of Posts:','livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo $instance['num_posts']; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('exclude_cats'); ?>"><?php _e('Exclude Categories (e.g. 34,171,34 ):','livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('exclude_cats'); ?>" name="<?php echo $this->get_field_name('exclude_cats'); ?>" type="text" value="<?php echo $instance['exclude_cats']; ?>" /></label></p>

		<!--dates-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['dates'], 'on' ); ?> id="<?php echo $this->get_field_id( 'dates' ); ?>" name="<?php echo $this->get_field_name( 'dates' ); ?>" />
		<label for="<?php echo $this->get_field_id('dates'); ?>"><?php _e('Show dates','livingos'); ?></label> </p>
		
		
		<!--author-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['author'], 'on' ); ?> id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>" />
		<label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Show author','livingos'); ?></label> </p>
		
		<!--tags-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['tags'], 'on' ); ?> id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" />
		<label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Show tags','livingos'); ?></label> </p>
		
		<!--categories-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['categories'], 'on' ); ?> id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ); ?>" />
		<label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Show categories','livingos'); ?></label> </p>
		
		<!--thumbs-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['thumbs'], 'on' ); ?> id="<?php echo $this->get_field_id( 'thumbs' ); ?>" name="<?php echo $this->get_field_name( 'thumbs' ); ?>" />
		<label for="<?php echo $this->get_field_id('thumbs'); ?>"><?php _e('Show post thumbnails','livingos'); ?></label> <br />
		
		</p>
		
		<!--post formats-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['post_formats'], 'on' ); ?> id="<?php echo $this->get_field_id( 'post_formats' ); ?>" name="<?php echo $this->get_field_name( 'post_formats' ); ?>" />
		<label for="<?php echo $this->get_field_id('post_formats'); ?>"><?php _e('Show post formats','livingos'); ?></label> <br />
		
		</p>
		
		<!--sticky-->
		<p>
		<input class="checkbox" type="checkbox" value="1" <?php checked( $instance['show_sticky'], '1' ); ?> id="<?php echo $this->get_field_id( 'show_sticky' ); ?>" name="<?php echo $this->get_field_name( 'show_sticky' ); ?>" />
		<label for="<?php echo $this->get_field_id('show_sticky'); ?>"><?php _e('Enable Sticky Posts','livingos'); ?></label> <br />
		
		</p>
	
		<!--headings-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['heading'], 'on' ); ?> id="<?php echo $this->get_field_id( 'heading' ); ?>" name="<?php echo $this->get_field_name( 'heading' ); ?>" />
		<label for="<?php echo $this->get_field_id('heading'); ?>"><?php _e('Show post heading','livingos'); ?></label>
		<label for="<?php echo $this->get_field_id('entrytag'); ?>"> - <?php _e('Tag','livingos'); ?></label>
		<select id="<?php echo $this->get_field_id('entrytag'); ?>" name="<?php echo $this->get_field_name('entrytag'); ?>">
			<option value="h2"<?php if( $instance['entrytag'] == 'h2' ) { ?> selected="selected"<?php } ?>><?php _e('h2','livingos'); ?></option>
			<option value="h3"<?php if( $instance['entrytag'] == 'h3' ) { ?> selected="selected"<?php } ?>><?php _e('h3','livingos'); ?></option>
			<option value="h4"<?php if( $instance['entrytag'] == 'h4' ) { ?> selected="selected"<?php } ?>><?php _e('h4','livingos'); ?></option>	
			<option value="h5"<?php if( $instance['entrytag'] == 'h5' ) { ?> selected="selected"<?php } ?>><?php _e('h5','livingos'); ?></option>				
		</select>
		</p>
		<!--content-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['content'], 'on' ); ?> id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" />
		<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Show post excerpt','livingos'); ?></label> </p>

		<p><label for="<?php echo $this->get_field_id('more_link'); ?>"><?php _e('More Link Text','livingos'); ?><input class="widefat" id="<?php echo $this->get_field_id('more_link'); ?>" name="<?php echo $this->get_field_name('more_link'); ?>" type="text" value="<?php echo $instance['more_link']; ?>" /></label>
		<label for="<?php echo $this->get_field_id('more_url'); ?>"><?php _e('More Link url','livingos'); ?><input class="widefat" id="<?php echo $this->get_field_id('more_url'); ?>" name="<?php echo $this->get_field_name('more_url'); ?>" type="text" value="<?php echo $instance['more_url']; ?>" /></label>
		</p>
		
		<p> <!-- thumbsize-->
			<label for="<?php echo $this->get_field_id('thumbsize'); ?>"><?php _e('Image Size','livingos'); ?></label><br />
			<select id="<?php echo $this->get_field_id('thumbsize'); ?>" name="<?php echo $this->get_field_name('thumbsize'); ?>">
				<option value="thumbnail"<?php if( $instance['thumbsize'] == 'thumbnail' ) { ?> selected="selected"<?php } ?>><?php _e('Thumbnail','livingos'); ?></option>
				<option value="medium"<?php if( $instance['thumbsize'] == 'medium' ) { ?> selected="selected"<?php } ?>><?php _e('Medium','livingos'); ?></option>
				<option value="large"<?php if( $instance['thumbsize'] == 'large' ) { ?> selected="selected"<?php } ?>><?php _e('Large','livingos'); ?></option>
				
				<?php
					//find additional defined image sizes
					global $_wp_additional_image_sizes;

					foreach( $_wp_additional_image_sizes as $key => $value) {
						
						echo '<option value="'.$key.'" ';
						if( $instance['thumbsize'] == $key ) { echo ' selected="selected" '; }
						echo '>'. $key . ' ('. $value['width'] .'x'. $value['height'] .')</option>';
					}
				?>
			</select><br />	
			<em><?php _e("Thumb, medium and large images are set in Settings>Media. Other sizes may be available here depending on the theme.",'livingos');?></em>
			
		</p>		
	
		<p> <!-- layout-->
			<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Layout','livingos'); ?></label>
			<select id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>">
				<option value="1"<?php if( $instance['columns'] == 1 ) { ?> selected="selected"<?php } ?>><?php _e('Default','livingos'); ?></option>
				<option value="2"<?php if( $instance['columns'] == 2 ) { ?> selected="selected"<?php } ?>>2 <?php _e('Columns','livingos'); ?></option>
				<option value="3"<?php if( $instance['columns'] == 3 ) { ?> selected="selected"<?php } ?>>3 <?php _e('Columns','livingos'); ?></option>
				<option value="4"<?php if( $instance['columns'] == 4 ) { ?> selected="selected"<?php } ?>>4 <?php _e('Columns','livingos'); ?></option>	
				<option value="5"<?php if( $instance['columns'] == 5 ) { ?> selected="selected"<?php } ?>>5 <?php _e('Columns','livingos'); ?></option>
				<option value="6"<?php if( $instance['columns'] == 6 ) { ?> selected="selected"<?php } ?>>6 <?php _e('Columns','livingos'); ?></option>					
			</select>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$new_instance['num_posts'] = intval($new_instance['num_posts']);
		$new_instance['columns'] = intval($new_instance['columns']);
		$new_instance['more_url'] = esc_url( $new_instance['more_url'] );
		$new_instance['more_link'] = esc_html( $new_instance['more_link'] );
		$new_instance['thumbsize'] = esc_html( $new_instance['thumbsize'] );
		$new_instance['entrytag'] = $new_instance['entrytag'];
		$new_instance['exclude_cats'] = $new_instance['exclude_cats'];
		if (! isset($new_instance['post_formats'])) $new_instance['post_formats']='';
		if (! isset($new_instance['content'])) $new_instance['content']='';
		if (! isset($new_instance['dates'])) $new_instance['dates']='';
		if (! isset($new_instance['thumbs'])) $new_instance['thumbs']='';
		if (! isset($new_instance['heading'])) $new_instance['heading']='';
		if (! isset($new_instance['tags'])) $new_instance['tags']='';
		if (! isset($new_instance['categories'])) $new_instance['categories']='';	
		if (! isset($new_instance['author'])) $new_instance['author']='';
		if (! isset($new_instance['show_sticky'])) $new_instance['show_sticky']='';
		return $new_instance;
	}

	function widget($args, $instance) {

		// outputs the content of the widget
		$args['title'] = $instance['title'];
		$args['cat_id'] = $instance['cat_id'];
		$args['num_posts'] = $instance['num_posts'];
		$args['more_link'] = $instance['more_link'];
		$args['more_url'] = $instance['more_url'];
		if ($instance['more_url']=='') {
			$args['more_url']= get_category_link( $instance['cat_id'] );
		}else{
			$args['more_url']=$instance['more_url'];
			
		}
		$args['entrytag'] = $instance['entrytag'];
		$args['post_type'] ="";
		$args['columns'] = $instance['columns'];
		$args['thumbs'] = isset( $instance['thumbs'] ) ? $instance['thumbs'] : false;
		$args['thumbsize'] = $instance['thumbsize'];
		$args['dates'] = isset( $instance['dates'] ) ? $instance['dates'] : false;
		$args['heading'] = isset( $instance['heading'] ) ? $instance['heading'] : false;
		$args['author'] = isset( $instance['author'] ) ? $instance['author'] : false;
		$args['tags'] = isset( $instance['tags'] ) ? $instance['tags'] : false;
		$args['categories'] = isset( $instance['categories'] ) ? $instance['categories'] : false;
		$args['show_sticky'] = isset( $instance['show_sticky'] ) ? $instance['show_sticky'] : false;
		$args['content'] = isset( $instance['content'] ) ? $instance['content'] : false;
		$args['post_formats'] = isset( $instance['post_formats'] ) ? $instance['post_formats'] : false;
		$args['post_class'] = 'los-custom-post los-widget'; 
		$args['exclude_cats'] = $instance['exclude_cats'];
		echo los_showPosts($args);
	}

}

//the shortcode
add_shortcode( 'los_showposts', 'los_showposts_shortcode' );

//the shortcode generator
function los_showposts_shortcode( $attr ){
	
	//set some defaults
	$args = array( 
		'title' => '',
		'before_widget' => '',
		'after_widget' => '',
		'cat_id' => 0,
		'exclude_cats' => '',
		'post_type' => '',
		'parent_id' => 0,
		'num_posts' => 5,
		'columns' => 2,
		'thumbs' => true,
		'thumbsize' => 'widget',
		'entrytag' => 'h3',
		'author' => false,
		'categories' => false,
		'tags' => false,
		'heading' => true,
		'dates' => false,
		'content' => true,
		'post_formats' => false,
		'more_link' => '',
		'post_class'=> 'los-custom-post',
		'show_sticky' => 0
	);
	
	if( isset($attr['thumbs'])) {
		$attr['thumbs'] = ($attr['thumbs'] == "false") ? false : true;
	}
	
	if( isset($attr['dates'])) {
		$attr['dates'] = ($attr['dates'] == "false") ? false : true;
	}
	
	if( isset($attr['heading'])) {
		$attr['heading'] = ($attr['heading'] == "false") ? false : true;
	}
	
	if( isset($attr['author'])) {
		$attr['author'] = ($attr['author'] == "false") ? false : true;
	}
	
	if( isset($attr['content'])) {
		$attr['content'] = ($attr['content'] == "false") ? false : true;
	}
	
	if( isset($attr['post_formats'])) {
		$attr['post_formats'] = ($attr['post_formats'] == "false") ? false : true;
	}
	
	//apply defaults where attributes not present
	$args = shortcode_atts( $args, $attr );
	
	//generate evenst list
	return los_showPosts($args);
}

//function to do widget work	
function los_showPosts($args = array()) {
	
	$defaults = array( 
		'title' => '',
		'before_widget' => '',
		'after_widget' => '',
		'cat_id' => 0,
		'exclude_cats' => '',
		'post_type' => '',
		'parent_id' => 0,
		'num_posts' => 5,
		'columns' => 2,
		'thumbs' => true,
		'thumbsize' => 'widget',
		'entrytag' => 'h3',
		'author' => false,
		'categories' => false,
		'tags' => false,
		'heading' => true,
		'dates' => false,
		'content' => true,
		'post_formats' => false,
		'more_link' => '',
		'post_class'=> 'los-custom-post',
		'show_sticky' => 0
	);
	$args = wp_parse_args( $args, $defaults );
	
	$output = "";
	
	//upgrades for old users
	if (!isset($args['post_class'])) $args['post_class'] = 'los-custom-post los-widget';
	if (!isset( $args['entrytag'])) $args['entrytag'] = 'h3';
	
	///begin widget
	$output .= $args['before_widget']; 
    if ( $args['title'] ){
        $output .= $args['before_title'] . esc_html( $args['title'] ) . $args['after_title'];
		
	}
	$output .= '<div class="content-box clearfix">';
	
	
	//need formats?
	$showFormats = $args['post_formats'];
	
	// Set the arguments of the get_posts function 
	if ( $args['post_type'] == "pages" ) {
		// Set the arguments of the get_posts function 
		$get_posts_args = array(
			'post_parent' => $args['parent_id'],
			'posts_per_page' => $args['num_posts'],
			'post_type' => 'page',
			'orderby' => 'menu_order',
			'order' => 'ASC'
			
		);
	} else {
	
		
		$get_posts_args = array(
			'cat' => $args['cat_id'],
			'posts_per_page' => $args['num_posts'],
			'ignore_sticky_posts' => !$args['show_sticky']
		);
		
		// check for excluded cats
		if ( $args['exclude_cats'] != '') {
			$exc_cats = split(',', $args['exclude_cats']);
			
			$get_posts_args['category__not_in'] = $exc_cats;
		}
		
		
	}
	
	/** Actually get the post */
	
	//$los_posts = get_posts($get_posts_args);
	$los_posts = new WP_Query( $get_posts_args );	

	/** Loop through the posts */
	$i = 1;
	$layout ='clear';
	switch ( $args['columns'] ){
		case 2 :
			$layout ='one-half';
			break;
		case 3 :
			$layout ='one-third';
			break;
		case 4 :
			$layout ='one-fourth';
			break;
		case 5 :
			$layout ='one-fith';
			break;
		case 6 :
			$layout ='one-sixth';
			break;
	}
	
	
	
	global $post;
	
	// The Loop
	while ( $los_posts->have_posts() ) :
		$los_posts->the_post();

		$format_style = "";
		
		//post formats ?
		if ( $showFormats) {
			
			//post format
			$format_style = get_post_format( $post->ID );
			if ( $format_style == '' ) {
				$format_style = "format format-standard ";
			} else {
				$format_style = "format format-". $format_style . ' ';
			}
		} 
		
		// start post div
		$output .= 	'<div class="' . $args['post_class'] . ' ' . $format_style;
		if ( has_post_thumbnail() && $args['thumbs'] ) { 
			$output .= 'has-post-image '; 
		} else { 
			$output .= 'no-post-image '; 
		} 
		$output .=  $layout; 
		
		if ( $i % $args['columns'] == 0) $output .= ' last';
		if ( $i % $args['columns'] == 1) $output .= ' clear';
		$output .= '" >'; //end class and div
		
		//post thumbs
		if(has_post_thumbnail() && $args['thumbs']){ 
			$output .=	'<div class="entry-thumb ';
			if ( !( $args['heading'] || $args['dates'] || $args['content'] ) ) { 
				$output .= 'thumbwide';
			} 
			$output .= '"><a href="' . get_permalink() . '" title="' . the_title('','',false) . '" rel="bookmark">' . get_the_post_thumbnail($post->ID, $args['thumbsize'] ,array('title'	=> trim(strip_tags( $post->post_title )))) . '</a></div>';
		}
		if( $args['heading']){ 
		    $output .= '<' . $args['entrytag'] . ' class="entry-title"><a href="' . get_permalink() .'" title="' . the_title('','',false) . '" rel="bookmark">'. the_title('','',false) .'</a></' . $args['entrytag'] . '>';
		} 
		if( $args['dates']){ 
			$output .=  '<div class="entry-date"><span class="theday">' . get_the_time('d') . '</span> <span class="themonth">' . get_the_time('M') . '</span> <span class="theyear">' . get_the_time('Y') . '</span></div>';
		}
		if( $args['author']){ 
			$output .=  '<div class="entry-author">'. __('by ','livingos') . get_the_author_link().'</div>';
		}
		if( $args['content']){ 
			$output .=  '<div class="entry-content">';
			$output .= 	get_the_excerpt();
			$output .= 	'</div>';
		}
		
		if ($args['tags'] || $args['categories'] ) $output .= '<div class="entry-meta">';
		if( $args['tags']){ 
			$tags_list = get_the_tag_list( '', ', ' );
			if ( $tags_list != "" ){
				$output .=	'<div class="entry-tags">';
				$output .=	 sprintf( __( 'Tagged: %s', 'livingos' ),  $tags_list ); 
				$output .=	'</div>';
			}
		}
		if( $args['categories']){ 
			$tags_list = get_the_tag_list( '', ', ' );
			if ( count( get_the_category() ) ){
				$output .=	'<div class="entry-categories">';
				$output .=	 sprintf( __( 'In: %s', 'livingos' ),  get_the_category_list( ', ' ) ); 
				$output .=	'</div>';
			}
		}
		if ($args['tags'] || $args['categories'] ) $output .= '</div>';
		
		//add extras for this post if you like
		$extra_content = "";
		$output .=  apply_filters('livingos_widgets_after_post', $extra_content, $post->ID);
		
		//post
		$output .= '</div>';
		
		$i++;
	endwhile;
	
	//add link to see all category posts
	if ($args['more_link'] != ''){
		$output .= '<div class="los-content-link-btn"><a href="'. esc_url( $args['more_url'] ) .'"  >'. esc_html( $args['more_link'] ).' <span class="meta-nav">&rarr;</span></a></div>';
    }
	
	//close content-box div
	$output .= '</div>';

	//end widget
	$output .= $args['after_widget']; 
	
	//reset wp query
	 wp_reset_postdata();
	 
	return $output;
}
?><?php 
//define showpages widget
class los_showpages_widget extends WP_Widget {

	static $text_domain = "livingos";  //allows class to be used in plugin or theme
	
	function los_showpages_widget() {
		$widget_ops = array('classname' => 'LOSPagesWidget', 'description' => __('Show teasers/list of pages with thumbs.','livingos') );
		$this->WP_Widget('los_show_pages', __('Show Pages','livingos'), $widget_ops);
	}

	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Example', 'livingos'), 'parent_id' => NULL, 'num_posts' => 4, 'more_link' => 'More', 'more_url' => home_url(),'thumbs' => 'on', 'heading' => 'on', 'columns' => 1, 'content'=>'','thumbsize' => apply_filters('themeloom_widgets_defthumbsize','thumbnail'), 'entrytag'=>'h3'  );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		// outputs the options form on admin
			$title = esc_attr($instance['title']);
?>
		<p><em>This widget allows you to show thumbs and/or teasers for pages. Set the parent page option to show child pages.</em></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p> <!-- parent-->
			<label for="<?php echo $this->get_field_id('parent_id'); ?>"><?php _e('Parent Page','livingos'); ?></label><br />
			<select id="<?php echo $this->get_field_id('parent_id'); ?>" name="<?php echo $this->get_field_name('parent_id'); ?>">
			<?php 
			$pages = get_pages('parent=0'); 
			foreach ($pages as $pagg) {
			?>
				<option value="<?php echo $pagg->ID; ?>"<?php if($pagg->ID == $instance['parent_id']) { ?> selected="selected"<?php } ?>><?php echo $pagg->post_title; ?></option>
			<?php
			}
			?>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number of Pages to show:','livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo $instance['num_posts']; ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id('more_link'); ?>"><?php _e('More Link Text','livingos'); ?><input class="widefat" id="<?php echo $this->get_field_id('more_link'); ?>" name="<?php echo $this->get_field_name('more_link'); ?>" type="text" value="<?php echo $instance['more_link']; ?>" /></label>
		<label for="<?php echo $this->get_field_id('more_url'); ?>"><?php _e('More Link url','livingos'); ?><input class="widefat" id="<?php echo $this->get_field_id('more_url'); ?>" name="<?php echo $this->get_field_name('more_url'); ?>" type="text" value="<?php echo $instance['more_url']; ?>" /></label>
		</p>
		
		<!--thumbs-->
		<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['thumbs'], 'on' ); ?> id="<?php echo $this->get_field_id( 'thumbs' ); ?>" name="<?php echo $this->get_field_name( 'thumbs' ); ?>" />
		<label for="<?php echo $this->get_field_id('thumbs'); ?>"><?php _e('Show page thumbnails','livingos'); ?></label><br />
		 </p>
		
		<!--headings-->
		<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['heading'], 'on' ); ?> id="<?php echo $this->get_field_id( 'heading' ); ?>" name="<?php echo $this->get_field_name( 'heading' ); ?>" />
		<label for="<?php echo $this->get_field_id('heading'); ?>"><?php _e('Show headings','livingos'); ?></label> 
		<label for="<?php echo $this->get_field_id('entrytag'); ?>"> - <?php _e('Tag','livingos'); ?></label>
		<select id="<?php echo $this->get_field_id('entrytag'); ?>" name="<?php echo $this->get_field_name('entrytag'); ?>">
			<option value="h2"<?php if( $instance['entrytag'] == 'h2' ) { ?> selected="selected"<?php } ?>><?php _e('h2','livingos'); ?></option>
			<option value="h3"<?php if( $instance['entrytag'] == 'h3' ) { ?> selected="selected"<?php } ?>><?php _e('h3','livingos'); ?></option>
			<option value="h4"<?php if( $instance['entrytag'] == 'h4' ) { ?> selected="selected"<?php } ?>><?php _e('h4','livingos'); ?></option>		
			<option value="h5"<?php if( $instance['entrytag'] == 'h5' ) { ?> selected="selected"<?php } ?>><?php _e('h5','livingos'); ?></option>			
		</select></p>
		<!--content-->
		<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['content'], 'on' ); ?> id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" />
		<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Show page excerpt','livingos'); ?></label> </p>
		
		<p> <!-- thumbsize-->
			<label for="<?php echo $this->get_field_id('thumbsize'); ?>"><?php _e('Image Size','livingos'); ?></label><br />
			<select id="<?php echo $this->get_field_id('thumbsize'); ?>" name="<?php echo $this->get_field_name('thumbsize'); ?>">
				<option value="thumbnail"<?php if( $instance['thumbsize'] == 'thumbnail' ) { ?> selected="selected"<?php } ?>><?php _e('Thumbnail','livingos'); ?></option>
				<option value="medium"<?php if( $instance['thumbsize'] == 'medium' ) { ?> selected="selected"<?php } ?>><?php _e('Medium','livingos'); ?></option>
				<option value="large"<?php if( $instance['thumbsize'] == 'large' ) { ?> selected="selected"<?php } ?>><?php _e('Large','livingos'); ?></option>
				
				<?php
					//find additional defined image sizes
					global $_wp_additional_image_sizes;

					foreach( $_wp_additional_image_sizes as $key => $value) {
						
						echo '<option value="'.$key.'" ';
						if( $instance['thumbsize'] == $key ) { echo ' selected="selected" '; }
						echo '>'. $key . ' ('. $value['width'] .'x'. $value['height'] .')</option>';
					}
				?>
			</select><br />	
			<em><?php _e("Thumb, medium and large images are set in Settings>Media. Other sizes may be available here depending on the theme.",'livingos');?></em>
			
		</p>
		
		<p> <!-- layout-->
			<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Layout','livingos'); ?></label>
			<select id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>">
				<option value="1"<?php if( $instance['columns'] == 1 ) { ?> selected="selected"<?php } ?>><?php _e('Default','livingos'); ?></option>
				<option value="2"<?php if( $instance['columns'] == 2 ) { ?> selected="selected"<?php } ?>>2 <?php _e('Columns','livingos'); ?></option>
				<option value="3"<?php if( $instance['columns'] == 3 ) { ?> selected="selected"<?php } ?>>3 <?php _e('Columns','livingos'); ?></option>
				<option value="4"<?php if( $instance['columns'] == 4 ) { ?> selected="selected"<?php } ?>>4 <?php _e('Columns','livingos'); ?></option>	
				<option value="5"<?php if( $instance['columns'] == 5 ) { ?> selected="selected"<?php } ?>>5 <?php _e('Columns','livingos'); ?></option>
				<option value="6"<?php if( $instance['columns'] == 6 ) { ?> selected="selected"<?php } ?>>6 <?php _e('Columns','livingos'); ?></option>		
							
			</select>
		</p>
		
		<?php
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		
		$new_instance['num_posts'] = intval( $new_instance['num_posts'] );
		$new_instance['more_url'] = esc_url( $new_instance['more_url'] );
		$new_instance['more_link'] = esc_html( $new_instance['more_link'] );
		$new_instance['columns'] = intval( $new_instance['columns'] );
		if (! isset($new_instance['content'])) $new_instance['content']='';
		if (! isset($new_instance['thumbs'])) $new_instance['thumbs']='';
		if (! isset($new_instance['heading'])) $new_instance['heading']='';
		$new_instance['thumbsize'] = $new_instance['thumbsize'];
		$new_instance['entrytag'] = $new_instance['entrytag'];
		return $new_instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget
		$args['title'] = $instance['title'];
		$args['parent_id'] = $instance['parent_id'];
		$args['num_posts'] = $instance['num_posts'];
		$args['more_link'] = $instance['more_link'];
		$args['more_url'] = $instance['more_url'];
		if ($instance['more_url']=='') {
			$args['more_url']=  get_permalink( $instance['parent_id'] );
		}else{
			$args['more_url']=$instance['more_url'];
			
		}
		$args['entrytag'] = $instance['entrytag'];
		$args['columns'] = $instance['columns'];
		$args['thumbsize'] = $instance['thumbsize'];
		$args['thumbs'] = isset( $instance['thumbs'] ) ? $instance['thumbs'] : false;
		$args['dates'] = isset( $instance['dates'] ) ? $instance['dates'] : false;
		$args['heading'] = isset( $instance['heading'] ) ? $instance['heading'] : false;
		$args['content'] = isset( $instance['content'] ) ? $instance['content'] : false;
		$args['post_type'] = 'pages';
		$args['post_formats'] = false;
		$args['post_class'] = 'los-custom-post los-widget'; 
		echo los_showPosts($args);
	}

}

?>
<?php
//define media widget
class los_media_widget extends WP_Widget {


	function los_media_widget() {
		$widget_ops = array('classname' => 'LOSMediaWidget', 'description' => __('Show latest posts in category with audio attachments.','livingos') );
		$this->WP_Widget('los_media_widget', __('Show Media Posts','livingos'), $widget_ops);
	}

	function form($instance) {
		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Example', 'livingos'), 'cat_id' => NULL, 'num_posts' => 5, 'more_link' => 'More', 'more_url' => home_url(), 'dates' =>'on','content'=>'' );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		// outputs the options form on admin
		$title = esc_attr($instance['title']);
?>
		<p><em>Show latest posts in category with audio attachments.</em></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p> <!-- Category-->
			<label for="<?php echo $this->get_field_id('cat_id'); ?>"><?php _e('Post Category:','livingos'); ?></label>
			<select id="<?php echo $this->get_field_id('cat_id'); ?>" name="<?php echo $this->get_field_name('cat_id'); ?>">
			<?php
			$cat_lists = get_categories(array('orderby' => 'name', 'order' => 'ASC'));
			foreach($cat_lists as $cat_list) {
				?>
				<option value="<?php echo $cat_list->term_id; ?>"<?php if($cat_list->term_id == $instance['cat_id']) { ?> selected="selected"<?php } ?>><?php echo $cat_list->name.' ('.$cat_list->count.')'; ?></option>
				<?php
			}
			?>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('num_posts'); ?>"><?php _e('Number of Posts:','livingos'); ?> <input class="widefat" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" type="text" value="<?php echo $instance['num_posts']; ?>" /></label></p>
		
		<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['content'], 'on' ); ?> id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" />
		<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Show excerpt','livingos'); ?></label> </p>
		
		<p><label for="<?php echo $this->get_field_id('more_link'); ?>"><?php _e('More Link Text','livingos'); ?><input class="widefat" id="<?php echo $this->get_field_id('more_link'); ?>" name="<?php echo $this->get_field_name('more_link'); ?>" type="text" value="<?php echo $instance['more_link']; ?>" /></label>
		<label for="<?php echo $this->get_field_id('more_url'); ?>"><?php _e('More Link url','livingos'); ?><input class="widefat" id="<?php echo $this->get_field_id('more_url'); ?>" name="<?php echo $this->get_field_name('more_url'); ?>" type="text" value="<?php echo $instance['more_url']; ?>" /></label>
		</p>
		
		<!--dates-->
		<p>
		<input class="checkbox" type="checkbox" value="on" <?php checked( $instance['dates'], 'on' ); ?> id="<?php echo $this->get_field_id( 'dates' ); ?>" name="<?php echo $this->get_field_name( 'dates' ); ?>" />
		<label for="<?php echo $this->get_field_id('dates'); ?>"><?php _e('Show dates','livingos'); ?></label> </p>
		
		
		
		<?php
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		if (! isset($new_instance['content'])) $new_instance['content']='';
		if (! isset($new_instance['dates'])) $new_instance['dates']='';
		$new_instance['num_posts'] = intval($new_instance['num_posts']);
		$new_instance['more_url'] = esc_url( $new_instance['more_url'] );
		$new_instance['more_link'] = esc_html( $new_instance['more_link'] );

		return $new_instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget
		$args['title'] = $instance['title'];
		$args['cat_id'] = $instance['cat_id'];
		$args['num_posts'] = $instance['num_posts'];
		$args['more_link'] = $instance['more_link'];
		$args['more_url'] = $instance['more_url'];
		if ($instance['more_url']=='') {
			$args['more_url']= get_category_link( $instance['cat_id'] );
		}else{
			$args['more_url']=$instance['more_url'];
			
		}
		
		$args['dates'] = isset( $instance['dates'] ) ? $instance['dates'] : false;
		$args['content'] = isset( $instance['content'] ) ? $instance['content'] : false;
		
		los_get_media($args);
	}

}
//add_action('widgets_init', create_function('', 'return register_widget("los_media_widget");'));

//function to do widget work	
function los_get_media( $args = array() ) {

	///begin widget
	echo $args['before_widget']; 
    if ( $args['title'] )
        echo $args['before_title'] . $args['title'] . $args['after_title'];

	echo '<div class="content-box">';

	$media_posts_args = array(
		'cat' => $args['cat_id'],
		'numberposts' => $args['num_posts']
	);
	$los_posts = get_posts($media_posts_args);
	global $post;
	
	echo '<ul class="media-list">';
	
	foreach($los_posts as $post) {
		
		// Set the arguments of the get_posts function 
		$get_atts_args = array(
			'post_type' => 'attachment',
			'post_parent' => $post->ID, 
			'post_mime_type' => 'audio/mpeg',
			'numberposts' => -1,
			'post_status' => null
		);
			
		/** Actually get the post */
		$los_atts = get_posts($get_atts_args);
		setup_postdata($post);
		
		echo '<li>';
		?>
		
		<a class="media-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a>
		<?php if( $args['dates']){ ?>
				<span class="entry-date"><span class="theday"><?php the_time('d') ?></span> <span class="themonth"><?php the_time('M') ?></span></span>
			<?php } ?>
		<?php
		
		//see if enclosure tag exists
		$enc = get_post_meta( $post->ID, 'enclosure', true );
		
		$enclosure = explode("\n", $enc);

		//only get the the first element eg, audio/mpeg from 'audio/mpeg mpga mp2 mp3'
		if ( isset($enclosure[2]) ) {
			$t = preg_split('/[ \t]/', trim($enclosure[2]) );
			$file_type = $t[0];
		} else {
			$file_type = "";
		}
		
		$audio_file = trim(htmlspecialchars($enclosure[0]));
				
		//find attachments if no enclosure field
		if ( $los_atts && $audio_file == '' ) {
			foreach( $los_atts as $post ) {
				setup_postdata( $post ); 
				
				if ($audio_file == '') {
					$audio_file = wp_get_attachment_url($post->ID);
					$file_type = 'audio/mpeg';
				}
			}	
		}		
		
		//found audio?
		if ($audio_file != '' && $file_type == 'audio/mpeg') {
			
			//use audio player plugin if installed
			if ( function_exists( "insert_audio_player" ) ) {  
				insert_audio_player( "[audio:".$audio_file."|width=100%]" );  
			} else {
				echo '<a href="'.$audio_file.'" class="media-download" title="'.__('listen/download','livingos').'" >'.apply_filters( 'livingos_media_download_message' , __( 'Listen','livingos' ) ).'</a>';
			}
		
		} else {
		
			echo apply_filters( 'livingos_media_noaudio_message' , __( '[no audio]' ,'livingos' ) );
		}
		if ( $args['content'] ) echo the_excerpt();
		echo '</li>';
	}
	
	echo '</ul>';
	
	//add link to see all category posts
	echo '<div class="los-content-link-btn"><a href="'.$args['more_url'].'" >'.$args['more_link'].' <span class="meta-nav">&rarr;</span></a></div>';
    
	echo '</div>';

	//end widget
	echo $args['after_widget']; 
	
	//reset wp query
	wp_reset_postdata();
	
}
?>