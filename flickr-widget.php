<?php
/*
	FLICKR WIDGET
*/
class ThemeLoomFlickrWidget extends WP_Widget
{
	
	/* This is the constructor for the Widget, here we setup some variables that define the widget's name and description and add any actions or filters that the widget will use. */
	
	function ThemeLoomFlickrWidget() {
		
		/* Here we define widget options to add a description to the widget in the WordPress Widget Administration page */
		$widget_ops = array('description' => __('This widget displays a flickr badge.', 'livingos') );

		/* Here we pass some arguments to the WP_Widget constructor so that we can override some Widget properties.
			The first parameter allows us to override the id_base of the widget.
			The second parameter allows us to pass in the internationalized title of the widget.
			The third parameter lets us pass in some additional options (such as an internationalized description of the widget). */
			
		parent::WP_Widget( 'ThemeLoomFlickrWidget', __('Flickr Widget','livingos'), $widget_ops);
		
		/* This function checks if this widget is currently added to any sidebars. If your widget requires external JavaScript or CSS, you should only include it if the widget is actually active. Otherwise, you'll be slowing down page loads by including these external files, when they aren't even being used! */
		
		 if (is_active_widget(false, false, $this->id_base) )
		 {
			//add_action( 'template_redirect', array($this, 'WidgetCss') );
		}
		
	}
	
	/* This function renders the form that lets the user setup your widget's settings. You can let your users customize the title of your widget, toggle features on or off, etc... It is very easy to add some settings fields, and since WP_Widgets can be used multiple times each instance of your widget can have different settings. This function takes in one parameter, which is an array of the previously saved settings. If the user has just dragged a new instance of the widget to one of their sidebars, this will be an empty array. */
	
	function form($instance) {
	
		/* wp_parse_args allows us to set up default values for our widget settings. The first argument is the $instance array (which will be empty if this is a new instance), the second argument is an array of the default values for our settings. When a setting hasn't be seen by the user, the default will be used. */
		$instance = wp_parse_args((array) $instance, array('title'=>'flickr','userID'=>'','numimages'=>4,'size'=>'s', 'source'=>'user', 'userTag'=>''));
	
		/* Here we render the form that lets the user set the widget settings. 
		
		Notice that when we render the id and name attribute of each field, we call the functions $this->get_field_id('title') and $this->get_field_name('title') respectively. These functions turn the id: 'title' into 'widget-b2templatewidget-1-title' and the name: 'title' into 'widget-b2templatewidget[1][title]'. This tells WordPress what widget we want to save the settings for, and which instance of the widget we are working with.	
					
		The current value of each setting for this widget is stored as an array element in the $instance variable. So to display the title setting in the text field we use:
		
			value="''.esc_attr($instance['title']).'" 
			
			The attribute_escape function simply removes any backslashes that might have been added to the data before it was added to the database. */
		$locale = get_locale();
			echo '
			<p>
			<label for="'. $this->get_field_id('title').'">'.__('Widget Title','livingos').'</label>
			<input type="text" id="'. $this->get_field_id('title').'" name="'. $this->get_field_name('title').'" value="'.esc_attr($instance['title']).'" class="widefat" />
			</p>
			
			<p>
			<label for="'. $this->get_field_id('userID').'">'.__('User or Group ID','livingos').'</label>
			<input type="text" id="'. $this->get_field_id('userID').'" name="'. $this->get_field_name('userID').'" value="'.esc_attr($instance['userID']).'" class="widefat" />
			<small>Your ID is not your user name. You can use <a href="http://idgettr.com/">IDGettr</a> to find your ID</small>
			</p>
			
			<p>
			<label for="'. $this->get_field_id('source').'">'.__('Source : ','livingos').'</label><br />
			<input type="radio" name="'.$this->get_field_name('source').'" value="user" '.checked($instance['source'],"user",false).' /> User 
			<input type="radio" name="'.$this->get_field_name('source').'" value="group" '.checked($instance['source'],"group",false).' /> Group <br />
			<input type="radio" name="'.$this->get_field_name('source').'" value="user_tag" '.checked($instance['source'],"user_tag",false).' /> User Tag
			<input type="radio" name="'.$this->get_field_name('source').'" value="group_tag" '.checked($instance['source'],"group_tag",false).' /> Group Tag <br />
			<input type="radio" name="'.$this->get_field_name('source').'" value="all_tag" '.checked($instance['source'],"all_tag",false).' /> Everyone Tag
			</p>
			<p>
			<label for="'. $this->get_field_id('userTag').'">'.__('Tag','livingos').'</label>
			<input type="text" id="'. $this->get_field_id('userTag').'" name="'. $this->get_field_name('userTag').'" value="'.esc_attr($instance['userTag']).'" class="widefat" />
			</p>
			
			
			<p>
			<label for="'. $this->get_field_id('numimages').'">'.__('Number of Images','livingos').'</label>
			<input type="text" id="'. $this->get_field_id('numimages').'" name="'. $this->get_field_name('numimages').'" value="'.esc_attr($instance['numimages']).'" class="widefat" />
			
			</p>
			
			<p>
			<label for="'. $this->get_field_id('size').'">'.__('Image size : ','livingos').'</label><br />
			<input type="radio" name="'.$this->get_field_name('size').'" value="s" '.checked($instance['size'],"s",false).' /> Square
			<input type="radio" name="'.$this->get_field_name('size').'" value="t" '.checked($instance['size'],"t",false).' /> Thumbnail
			<input type="radio" name="'.$this->get_field_name('size').'" value="m" '.checked($instance['size'],"m",false).' /> Medium
			</p>
			
			
			';
			
						
			/* And that's all! The Save, Delete, and Close buttons are automatically added by the WP_Widget class. */
	}
	
	/* The update function is like a filter for your Widget settings. If there is any manipulation or error detection you need to perform on the settings your user is trying to save it should be done here. For example, below we make sure to strip out any HTML tags that the user may have tried to enter into the title text field, before we save it to the database. */
	
	function update($new_instance, $old_instance) {
		
		$instance['title'] = strip_tags($new_instance['title']); /* If the user tries to type any HTML tags, like <h1>, <p>, or <strong> into the title field, this line will strip it out. */
		$instance['userID'] = $new_instance['userID'];
		$instance['numimages'] = $new_instance['numimages'];
		
		$instance['size'] = $new_instance['size'];
		$instance['source'] = $new_instance['source'];
		$instance['userTag'] = $new_instance['userTag'];
		return $instance;
		
	}


	/*	This function is what actually adds your widget HTML to the page.
		
		The first parameter, $args, is an array of arguments defined by the currently active WordPress theme that defines what HTML elements the widget should be rendered inside (e.g. <div>,<li>), and what HTML element the widget title should be rendered inside (e.g. <strong>, <h3>, <h4>). It's important to echo out these elements exactly as they are in the example below, so that your widget does not break the design of the WordPress theme.
		
		The second parameter, $instance, includes the array of settings (defined above) that were saved for this instance of the widget. */
	
	function widget( $args, $instance ) {
		
		
		echo $args['before_widget'];
		
		/* If the user has set a title for the widget, then we want to display it. */
		if ( $instance['title'] )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		
		echo '<div id="flickr_tab" >';
		
		switch ($instance['source']) {
			case 'user':
				$feed = 'count=' . $instance['numimages'] . '&display=latest&size='.$instance['size'].'&layout=x&source=user&user=' .$instance['userID'];
				break;
			case 'group':
				$feed = 'count=' . $instance['numimages'] . '&display=latest&size='.$instance['size'].'&layout=x&source=group&group=' .$instance['userID'];
				break;
			case 'user_tag':
				$feed = 'count=' . $instance['numimages'] . '&display=latest&size='.$instance['size'].'&layout=x&source=user_tag&user=' .$instance['userID'] .'&tag='.$instance['userTag'] ;
				break;
			case 'group_tag':
				$feed = 'count=' . $instance['numimages'] . '&display=latest&size='.$instance['size'].'&layout=x&source=group_tag&group=' .$instance['userID'] .'&tag='.$instance['userTag'] ;
				break;
			case 'all_tag':
				$feed = 'count=' . $instance['numimages'] . '&display=latest&size='.$instance['size'].'&layout=x&source=all_tag&tag='.$instance['userTag'] ;
				break;
		}
		echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?'. $feed .'"></script>';
		echo '</div>';

		echo '<div class="clear"></div>';
		echo $args['after_widget'];


	}
	
	/* This function attaches the Widget's CSS file to the page. Notice above that it is only run if the widget is currently active. */
	function WidgetCss()
	{
		
	}
	
	
}
?>