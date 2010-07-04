<?php
/*
Plugin Name: Picasa for Wordpress
Plugin URI: http://wordpress.org/extend/plugins/picasa-for-wordpress/
Description: Embed a Flash player widget based on a selected Picasa album.
Author: Pierre Sudarovich
Version: 1.6
Author URI: http://www.monblogamoua.fr/
*/

define('picasa', 'picasa-for-wordpress/lang/picasa');

if(function_exists('load_plugin_textdomain')) load_plugin_textdomain(picasa);

if (version_compare($wp_version, '2.8', '>=')) {
	class Picasa_Widget extends WP_Widget {

		function Picasa_Widget() {
			$widget_ops = array('classname' => 'widget_picasa', 'description' => __("Embed a Flash player widget based on a selected Picasa album.",picasa) );
			$this->WP_Widget('slideshow', __('SlideShow',picasa), $widget_ops);
		}

		function widget( $args, $instance ) {
			extract($args);
			$title = apply_filters('widget_title', empty($instance['title']) ? __('SlideShow',picasa) : $instance['title']);
			$RGB = empty( $instance['RGB'] ) ? "0x000000" : str_replace("#","",$instance['RGB']);
			$feed = $instance['feed'];
			$feed=str_replace("%3A",":",$feed);
			$feed=str_replace("%2F","/",$feed);
			$posQuest=strpos($feed,"/user/");
			$feed=substr($feed,$posQuest);
			$feed=str_replace('=',"%3D",$feed);
			$feed=str_replace('&amp;',"&",$feed);
			$feed=str_replace('&',"%26",$feed);
			$width =  empty( $instance['width'] ) ? "210" : $instance['width'];
			$unit = $instance['unit'];
			$autoplay = (empty( $instance['autoplay'] ) || $instance['autoplay']=='0') ? '&amp;noautoplay=1' : '';
			$RegisteredOnly = (empty( $instance['registered_only'] ) || $instance['registered_only']=='0') ? '0' : '1';
			$caption = ($instance['caption']=="1") ? "&amp;captions=1" : "";

			if($feed!="") {
				if ($RegisteredOnly =='0' || (is_user_logged_in() && $RegisteredOnly =='1')) { ?>
					<?php echo $before_widget; ?>
						<?php echo $before_title . $title . $after_title; ?>
					<object type="application/x-shockwave-flash" data="http://picasaweb.google.com/s/c/bin/slideshow.swf" width="<?php echo $width;?><?php if($unit=="%") echo "%";?>" height="100" id="<?php echo $this->get_field_id('title'); ?>">
						<param name="flashvars" value="host=picasaweb.google.com&amp;RGB=<?php echo $RGB;?><?php echo $autoplay;?><?php echo $caption;?>&amp;feed=http://picasaweb.google.com/data/feed/api<?php echo $feed;?>"/>
						<param name="movie" value="http://picasaweb.google.com/s/c/bin/slideshow.swf"/>
					</object>
					<script type="text/javascript">
					//<![CDATA[
					if(typeof window.addEventListener != 'undefined') {	//.. gecko, safari, konqueror and standard
					window.addEventListener('load', PicasaLoaded, false);
					}
					else if(typeof document.addEventListener != 'undefined') {	//.. opera 7
					document.addEventListener('load', PicasaLoaded, false);
					}
					else if(typeof window.attachEvent != 'undefined') {	//.. win/ie
					window.attachEvent('onload', PicasaLoaded);
					}
					function PicasaLoaded() {
						var flash=document.getElementById('<?php echo $this->get_field_id('title'); ?>');
						flash.height = Math.round(flash.offsetWidth/1.4);
					}
					//]]>
					</script>
					<?php if (function_exists("current_user_can")) {
						if(current_user_can('edit_themes'))
						echo '<div align="center"><a href="'.get_bloginfo('wpurl').'/wp-admin/widgets.php">'. __('Manage SlideShow',picasa).'</a></div>';
						}
					?>
					<?php
					echo $after_widget;
				}
			}
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['RGB'] = strip_tags($new_instance['RGB']);
			$instance['feed'] = strip_tags($new_instance['feed']);
			$instance['width'] = strip_tags($new_instance['width']);
			$instance['unit'] = strip_tags($new_instance['unit']);
			$instance['autoplay'] = strip_tags($new_instance['autoplay']);
			$instance['registered_only'] = strip_tags($new_instance['registered_only']);
			$instance['caption'] = strip_tags($new_instance['caption']);
			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title = strip_tags($instance['title']);

			$options = $newoptions = get_option('widget_picasa');
			$newoptions = $options;

		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_picasa', $options);
		}
		$feed = empty($instance['feed']) ? "" : $instance['feed'];
		$RGB = empty($instance['RGB']) ? "" : $instance['RGB'];
		$width = empty($instance['width']) ? "100" : $instance['width'];
		$unit= empty($instance['unit']) ? "%" : $instance['unit'];
		$autoplay = empty($instance['autoplay']) ? "" : $instance['autoplay'];
		$caption = empty($instance['caption']) ? "" : $instance['caption'];
		$registered_only = empty($instance['registered_only']) ? "" : $instance['registered_only'];?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',picasa); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
		name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/></label></p>
		<p><label for="<?php echo $this->get_field_id('feed'); ?>"><?php _e('RSS feed:',picasa); ?> <input class="widefat" id="<?php echo $this->get_field_id('feed'); ?>" 
		name="<?php echo $this->get_field_name('feed'); ?>" value="<?php echo esc_attr($feed); ?>"/></label></p>
		<p><label for="<?php echo $this->get_field_id('RGB'); ?>"><?php _e('RGB:',picasa); ?> <input type="text" name="<?php echo $this->get_field_name('RGB'); ?>" 
		id="<?php echo $this->get_field_id('RGB'); ?>" value="<?php echo esc_attr($RGB); ?>" size="8" maxlength="7"/></label></p>
		<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:',picasa); ?> <input type="text" name="<?php echo $this->get_field_name('width'); ?>" 
		id="<?php echo $this->get_field_id('width'); ?>" style="width: 35px;" value="<?php echo esc_attr($width); ?>"/></label>
		<select name="<?php echo $this->get_field_name('unit'); ?>" id="<?php echo $this->get_field_id('unit'); ?>">
			<option value="px"<?php selected( $unit, 'px' ); ?>>px</option>
			<option value="%"<?php selected( $unit, '%' ); ?>>%</option>
		</select></p>
		<p><label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Autoplay',picasa); ?> <input type="checkbox" value="1" 
		name="<?php echo $this->get_field_name('autoplay'); ?>" id="<?php echo $this->get_field_id('autoplay'); ?>"<?php if($autoplay=='1') echo 'checked="checked"';?>/></label></p>
		<p><label for="<?php echo $this->get_field_id('registered_only'); ?>"><?php _e('Registered users only',picasa); ?> <input type="checkbox" value="1" 
		name="<?php echo $this->get_field_name('registered_only'); ?>" id="<?php echo $this->get_field_id('registered_only'); ?>"<?php if($registered_only=='1') echo 'checked="checked"';?>/></label></p>
		<p><label for="<?php echo $this->get_field_id('caption'); ?>"><?php _e('Show Captions',picasa); ?> <input type="checkbox" value="1" 
		name="<?php echo $this->get_field_name('caption'); ?>" id="<?php echo $this->get_field_id('caption'); ?>"<?php if($caption=='1') echo 'checked="checked"';?>/></label></p>
		<?php
		}
	}

	function register_My_Picasa_Widget(){
		register_widget('Picasa_Widget');
	}
	add_action('init', 'register_My_Picasa_Widget', 1);
}
else {
	define('PICASA_MAX', 10);

	function wp_widget_picasa( $args, $number = 1 ) {
		extract( $args );
		$options = get_option( 'widget_picasa' );

		$title = empty( $options[$number]['title'] ) ? "&nbsp;" : $options[$number]['title'];
		$RGB = empty( $options[$number]['RGB'] ) ? "0x000000" : str_replace("#","",$options[$number]['RGB']);
		$feed = $options[$number]['feed'];
		$feed=str_replace("%3A",":",$feed);
		$feed=str_replace("%2F","/",$feed);
		$posQuest=strpos($feed,"/user/");
		$feed=substr($feed,$posQuest);
		$feed=str_replace('=',"%3D",$feed);
		$feed=str_replace('&amp;',"&",$feed);
		$feed=str_replace('&',"%26",$feed);
		$width =  empty( $options[$number]['width'] ) ? "210" : $options[$number]['width'];
		$unit = $options[$number]['unit'];
		$autoplay = (empty( $options[$number]['autoplay'] ) || $options[$number]['autoplay']=='0') ? '&amp;noautoplay=1' : '';
		$RegisteredOnly = (empty( $options[$number]['registered_only'] ) || $options[$number]['registered_only']=='0') ? '0' : '1';
		$caption = ($options[$number]['caption']=="1") ? "&amp;captions=1" : "";
		if($feed!="") {
			if ($RegisteredOnly =='0' || (is_user_logged_in() && $RegisteredOnly =='1')) { ?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; //max-results	start-index
			?>
		<object type="application/x-shockwave-flash" data="http://picasaweb.google.com/s/c/bin/slideshow.swf" 
		width="<?php echo $width;?><?php if($unit=="%") echo "%";?>" height="100" id="picasa_player_<?php echo $number;?>">
			<param name="flashvars" value="host=picasaweb.google.com&amp;RGB=<?php echo $RGB;?><?php echo $autoplay;?><?php echo $caption;?>&amp;feed=http://picasaweb.google.com/data/feed/api<?php echo $feed;?>"/>
			<param name="movie" value="http://picasaweb.google.com/s/c/bin/slideshow.swf"/>
		</object>
		<script type="text/javascript">
		//<![CDATA[
		if(typeof window.addEventListener != 'undefined') {	//.. gecko, safari, konqueror and standard
		window.addEventListener('load', PicasaLoaded, false);
		}
		else if(typeof document.addEventListener != 'undefined') {	//.. opera 7
		document.addEventListener('load', PicasaLoaded, false);
		}
		else if(typeof window.attachEvent != 'undefined') {	//.. win/ie
		window.attachEvent('onload', PicasaLoaded);
		}
	function PicasaLoaded() {
		var flash=document.getElementById('picasa_player_<?php echo $number;?>');
		flash.height = Math.round(flash.offsetWidth/1.4);
	}
		//]]>
		</script>
		
		<?php if (function_exists("current_user_can")) {
			if(current_user_can('edit_themes'))
			echo '<div align="center"><a href="'.get_bloginfo('wpurl').'/wp-admin/widgets.php">'. __('Manage SlideShow',picasa).'</a></div>';
			}
		 ?>	
		<?php echo $after_widget; ?>
	<?php
	}
		}
	}

	function wp_widget_picasa_control($number) {
		$options = $newoptions = get_option('widget_picasa');
		$newoptions = $options;
		if ( $_POST["picasa-submit-$number"] ) {
			$newoptions[$number]['title'] = strip_tags(stripslashes($_POST["picasa-title-$number"]));
			$newoptions[$number]['feed'] = stripslashes($_POST["picasa-feed-$number"]);
			$newoptions[$number]['RGB'] = strip_tags( stripslashes( $_POST["picasa-RGB-$number"] ) );
			$newoptions[$number]['width'] = stripslashes( $_POST["picasa-width-$number"] );
			$newoptions[$number]['unit'] =  $_POST["picasa-unit-$number"] ;
			$newoptions[$number]['autoplay'] = $_POST["picasa-autoplay-$number"] ;
			$newoptions[$number]['caption'] = $_POST["picasa-caption-$number"] ;
			$newoptions[$number]['registered_only'] = $_POST["picasa-registered_only-$number"] ;
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_picasa', $options);
		}
		$title = attribute_escape($options[$number]['title']);
		$feed = attribute_escape( $options[$number]['feed'] );
		$RGB = attribute_escape( $options[$number]['RGB'] );
		$width = empty( $options[$number]['width'] ) ? "100" : attribute_escape( $options[$number]['width']);
		$unit = empty( $options[$number]['unit'] ) ? "%" : attribute_escape( $options[$number]['unit']);
		$autoplay = attribute_escape( $options[$number]['autoplay'] );
		$caption = attribute_escape( $options[$number]['caption'] );
		$registered_only = attribute_escape( $options[$number]['registered_only'] );	?>

		<p><label for="picasa-title"><?php _e('Title:',picasa); ?>
		<input style="width: 250px;" id="picasa-title-<?php echo $number; ?>" name="picasa-title-<?php echo $number; ?>" 
		type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="picasa-feed-<?php echo "$number"; ?>"><?php _e('RSS feed:',picasa); ?>
		<input style="width:100%;" id="picasa-feed-<?php echo $number; ?>" 
		name="picasa-feed-<?php echo $number; ?>" value="<?php echo $feed; ?>"/></label></p>
		<p><label for="picasa-RGB-<?php echo $number; ?>"><?php _e('RGB:',picasa); ?>
		<input type="text" name="picasa-RGB-<?php echo $number; ?>" id="picasa-RGB-<?php echo $number; ?>" value="<?php echo $RGB; ?>"/>
		</label></p>
		<p><label for="picasa-width-<?php echo $number; ?>"><?php _e('Width:',picasa); ?>
		<input type="text" name="picasa-width-<?php echo $number; ?>" id="picasa-width-<?php echo $number; ?>" style="width: 35px;" 
		value="<?php echo $width; ?>" /></label> <select name="picasa-unit-<?php echo $number; ?>" 
		id="picasa-unit-<?php echo $number; ?>">
			<option value="px"<?php selected( $unit, 'px' ); ?>>px</option>
			<option value="%"<?php selected( $unit, '%' ); ?>>%</option>
		</select></p>
		<p><label for="picasa-autoplay-<?php echo $number; ?>"><?php _e('Autoplay',picasa); ?> <input type="checkbox" value="1" 
		name="picasa-autoplay-<?php echo $number; ?>" 
		id="picasa-autoplay-<?php echo $number; ?>"<?php if($autoplay=='1') echo 'checked="checked"';?>/></label></p>
		<p><label for="picasa-registered_only-<?php echo $number; ?>"><?php _e('Registered users only',picasa); ?> <input type="checkbox" value="1" 
		name="picasa-registered_only-<?php echo $number; ?>" 
		id="picasa-registered_only-<?php echo $number; ?>"<?php if($registered_only=='1') echo 'checked="checked"';?>/></label></p>
		<p><label for="picasa-caption-<?php echo $number; ?>"><?php _e('Show Captions',picasa); ?> <input type="checkbox" value="1" 
		name="picasa-caption-<?php echo $number; ?>" 
		id="picasa-caption-<?php echo $number; ?>"<?php if($caption=='1') echo 'checked="checked"';?>/></label></p>
		<input type="hidden" id="picasa-submit-<?php echo $number; ?>" name="picasa-submit-<?php echo $number; ?>" value="1" />
		<?php
	}

	function widget_picasa_setup() {
		$options = $newoptions = get_option('widget_picasa');
		if ( isset($_POST['picasa-number-submit']) ) {
			$number = (int) $_POST['picasa-number'];
			if ( $number > PICASA_MAX ) $number = PICASA_MAX;
			if ( $number < 1 ) $number = 1;
			$newoptions['number'] = $number;
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_picasa', $options);
			widget_picasa_register($options['number']);
		}
	}

	function widget_picasa_page() {
		$options = $newoptions = get_option('widget_picasa'); ?>
		<div class="wrap">
			<form method="POST">
				<h2>Picasa SlideShow Widgets</h2>
				<p style="line-height: 30px;"><?php _e('How many Picasa SlideShow widgets would you like ?',picasa); ?>
				<select id="picasa-number" name="picasa-number" value="<?php echo $options['number']; ?>">
					<?php for ( $i = 1; $i <= PICASA_MAX; ++$i ) 
					echo "<option value='$i' ".($options['number']==$i ? "selected='selected'" : '').">$i</option>"; ?>
				</select>
				<span class="submit"><input type="submit" name="picasa-number-submit" id="picasa-number-submit" 
				value="<?php _e('Save',picasa); ?>" /></span></p>
			</form>
		</div>
	<?php
	}

	function widget_picasa_register() {
		global $wp_version;
		$options = get_option('widget_picasa');
		$number = $options['number'];
		if ( $number < 1 ) $number = 1;
		if ( $number > PICASA_MAX ) $number = PICASA_MAX;
		for ($i = 1; $i <= PICASA_MAX; $i++) {
			$name = array('Picasa SlideShow %s', null, $i);
			if ( function_exists( 'wp_register_sidebar_widget' ) ){
				$id = "picasa-$i"; // Never never never translate an id
				$dims = array('width' => 520, 'height' => 300);
				$class = array( 'classname' => 'wp_widget_picasa' );
				$name = sprintf('Picasa SlideShow %d', $i);
				wp_register_sidebar_widget($id, $name, $i <= $number ? 'wp_widget_picasa' : /* unregister */ '', $class, $i);
				wp_register_widget_control($id, $name, $i <= $number ? 'wp_widget_picasa_control' : /* unregister */ '', $dims, $i);
			}
			else{
				register_sidebar_widget($name, $i <= $number ? 'wp_widget_picasa' : /* unregister */ '', $i);
				register_widget_control($name, $i <= $number ? 'wp_widget_picasa_control' : /* unregister */ '', 520, 300, $i);
			}
		}
		add_action('sidebar_admin_setup', 'widget_picasa_setup');
		add_action('sidebar_admin_page', 'widget_picasa_page');
	}
	function picasa_on_plugins_loaded() {
		widget_picasa_register();
	}

	if (function_exists("add_action")) {
		add_action("plugins_loaded","picasa_on_plugins_loaded");
		}
}
?>