<?php
/*
 * NowFeaturingWidget - Class for handling the Now Featuring Widget
 *
 * extends: WP_Widget
 * 
 */
class NowFeaturingWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
							'classname'   => 'now_featuring',
							'description' => 'Include featured posts or pages on your sidebar in a variety of ways.'
						);
		$control_ops = array(
							'width'   => 300,
							'height'  => 350,
							'id_base' => 'now_featuring'
						);
		$this->WP_Widget( 'now_featuring', 'Now Featuring', $widget_ops, $control_ops );
	}

	/*
	 * register_widget - To register this widget with Wordpress
	 * 
	 */
	public static function register_widget() {
		register_widget( 'NowFeaturingWidget' );
	}

	/*
	 * widget - Outputs the content of the widget
	 * 
	 */
	public function widget( $args, $instance ) {
		
		$defaults = array(
			'nf_title' 				=> '',
			'nf_widget_type'		=> '',
			'nf_post_type' 			=> 'page',
			'nf_which'				=> '',
			'nf_how'				=> '',
			'nf_category_id' 		=> '',
			'nf_tag_id'				=> '',
			'nf_selection_id_list' 	=> '',
			'nf_list_limit' 		=> '5',
			'nf_thumbnail_width'	=> '100%',
			'nf_transition'			=> 'fade',
			'nf_autoslide'			=> true,
			'nf_wait_time'			=> '7000',
			'nf_randomize'			=> true,
			'nf_include_title'		=> true,
			'nf_include_excerpt'	=> true,
		);
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		
		$title = $instance['nf_title'];
		$widget_type = $instance['nf_widget_type'];
        $post_type = $instance['nf_post_type'];
		$which = $instance['nf_which'];
		$how = $instance['nf_how'];
        $category_id = $instance['nf_category_id'];
		$tag_id = $instance['nf_tag_id'];
		$selection_id_list = $instance['nf_selection_id_list'];
		$list_limit = $instance['nf_list_limit'];
		$thumbnail_width = $instance['nf_thumbnail_width'];
		$transition = $instance['nf_transition'];
		$autoslide = $instance['nf_autoslide'];
		$wait_time = $instance['nf_wait_time'];
		$randomize = $instance['nf_randomize'];
		$include_title = $instance['nf_include_title'];
		$include_excerpt = $instance['nf_include_excerpt'];
		
        if ( array_key_exists('before_widget', $args) ){
          	echo $args['before_widget'];
        }
		if($title != ''){
			echo $args['before_title'] . apply_filters( 'widget_title', $title ). $args['after_title'];
		}
		
		// Get the content from the shortcode
		$widget_content = do_shortcode('[now_featuring widget_type="' . $widget_type . '" post_type="'. $post_type . '" which="' . $which .
											'" how="' . $how . '" category_id="' . $category_id . '" tag_id="' . $tag_id .
											'" selection_id_list="' . $selection_id_list . 
											'" transition="' . $transition . '" autoslide="' . $autoslide .
											'" wait_time="' . $wait_time . '" randomize="' . $randomize .
											'" list_limit="' . $list_limit . '" thumbnail_width="' . $thumbnail_width .
											'" include_title="' . $include_title . '" include_excerpt="' . $include_excerpt .'"]');
		
		echo $widget_content;

        if ( array_key_exists('after_widget', $args) ){
        	echo $args['after_widget'];
        }
	}

	/*
	 * form - Outputs the admin options form for the widget
	 * 
	 */
	public function form( $instance ) {
		
		$defaults = array(
			'nf_title' 				=> '',
			'nf_widget_type'		=> '',
			'nf_post_type' 			=> 'page',
			'nf_which'				=> '',
			'nf_how'				=> '',
			'nf_category_id' 		=> '',
			'nf_tag_id'				=> '',
			'nf_selection_id_list' 	=> '',
			'nf_list_limit' 		=> '5',
			'nf_thumbnail_width'	=> '100%',
			'nf_transition'			=> 'fade',
			'nf_autoslide'			=> true,
			'nf_wait_time'			=> '7000',
			'nf_randomize'			=> true,
			'nf_include_title'		=> true,
			'nf_include_excerpt'	=> true,
		);
		$instance = wp_parse_args( ( array ) $instance, $defaults );
		
		$title = strip_tags($instance['nf_title']);
		$widget_type = strip_tags($instance['nf_widget_type']);
        $post_type = strip_tags($instance['nf_post_type']);
		$which = strip_tags($instance['nf_which']);
		$how = strip_tags($instance['nf_how']);
        $category_id = strip_tags($instance['nf_category_id']);
		$tag_id = strip_tags($instance['nf_tag_id']);
		$selection_id_list = strip_tags($instance['nf_selection_id_list']);
		$list_limit = strip_tags($instance['nf_list_limit']);
		$thumbnail_width = strip_tags($instance['nf_thumbnail_width']);
		$transition = strip_tags($instance['nf_transition']);
		$autoslide = strip_tags($instance['nf_autoslide']);
		$wait_time = strip_tags($instance['nf_wait_time']);
		$randomize = strip_tags($instance['nf_randomize']);
		$include_title = strip_tags($instance['nf_include_title']);
		$include_excerpt = strip_tags($instance['nf_include_excerpt']);
		
        $category_options = "";
        $categories = get_categories();
        foreach ($categories as $key => $cat){
			$selected = "";
			if($cat->term_id  == $category_id){
				$selected = 'selected';
			}
			$cat_name = self::get_string_limited( $cat->name );
			if( $cat->name != '' ){
				$category_options .= "<option value='" . $cat->term_id . "' " . $selected . ">" . $cat_name . "</option>";
			}
        }     
        
		$tag_options = "";
		$tags = get_tags();
        foreach ($tags as $tag){
			$selected = "";
			if($tag->term_id  == $tag_id){
				$selected = 'selected';
			}
			$tag_name = self::get_string_limited( $tag->name );
			if( $tag->name != '' ){
				$tag_options .= "<option value='" . $tag->term_id . "' " . $selected . ">" . $tag_name . "</option>";
			}
        }     
		
		$post_options = array();
		$page_options = array();
		$which_options = "";
		$which_ones_options = "";
		
		$args = array(
			'post_type' => array('post', 'page'),
			'has_password' => false,
			'posts_per_page' => 500,
			'orderby' => 'name',
        	'order' => 'asc',
			'post_status' => 'publish'
		);
		
		$selections = array();
		if($selection_id_list){
			$selections = preg_split('/,/', $selection_id_list);
		}
		
		// sort out the post/page dropdown options
		$wp_query = new WP_Query( $args );
		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) {
				$wp_query->the_post();
				$selected = '';
				
				$this_id = get_the_ID();
				$this_title = self::get_string_limited( get_the_title() );
				if( $this_id == $which ){
					$selected = ' selected';
				}
				
				// for json & js to change the options of the select when post_type changed
				if(get_post_type( get_the_ID() ) == 'post'){
					if( $post_type == 'post' ){
						if( in_array($this_id, $selections) ){
							$which_ones_options .= "<option value='" . $this_id . "' selected>" . $this_title . "</option>";
						}else{
							$which_ones_options .= "<option value='" . $this_id . "'>" . $this_title . "</option>";
						}
						$which_options .= "<option value='" . $this_id . "'$selected>" . $this_title . "</option>";
					}
					array_push($post_options, array('id' => $this_id, 'title' => $this_title) );
				}else{
					if( $post_type == 'page' ){
						if( in_array($this_id, $selections) ){
							$which_ones_options .= "<option value='" . $this_id . "' selected>" . $this_title . "</option>";
						}else{
							$which_ones_options .= "<option value='" . $this_id . "'>" . $this_title . "</option>";
						}
						$which_options .= "<option value='" . $this_id . "'$selected>" . $this_title . "</option>";
					}
					array_push($page_options, array('id' => $this_id, 'title' => $this_title) );
				}
			}
		}
		wp_reset_postdata();
		
		$post_options_json = json_encode( $post_options );
		$page_options_json = json_encode( $page_options );
		
		$which_input_class = 'nf_widget_fields_hidden';
		if($widget_type == 'Single'){
			$which_input_class= 'nf_widget_fields';
		}
		
		$how_input_class = 'nf_widget_fields_hidden';
		if($widget_type == 'Slider' || $widget_type == 'List'){
			$how_input_class= 'nf_widget_fields';
		}

		$category_input_class = 'nf_widget_fields_hidden';
		if($how == 'category'){
			$category_input_class= 'nf_widget_fields';
		}
		
		$tag_input_class = 'nf_widget_fields_hidden';
		if($how == 'tag'){
			$tag_input_class= 'nf_widget_fields';
		}
		
		$selection_list_input_class = 'nf_widget_fields_hidden';
		if($how == 'selection'){
			$selection_list_input_class= 'nf_widget_fields';
		}
		$slider_input_class = 'nf_widget_fields_hidden';
		if($widget_type == 'Slider'){
			$slider_input_class= 'nf_widget_fields';
		}
		
		$list_inputs_class = 'nf_widget_fields_hidden';
		if($widget_type == 'List'){
			$list_inputs_class= 'nf_widget_fields';
		}
		
		?>
		<div id="nf_widget_fields">
			<p id="title_input">
				<label for="<?php echo $this->get_field_id( 'nf_title' ); ?>">Title:</label>
				<input id="<?php echo $this->get_field_id( 'nf_title' ); ?>" name="<?php echo $this->get_field_name( 'nf_title' ); ?>" type="text" value="<?php echo $title; ?>"> 
			</p>
			
			<div id="widget_type_input">
				<p id="widget_type">
					<label for="<?php echo $this->get_field_id( 'nf_widget_type' ); ?>">Widget type:</label>
					<select name="<?php echo $this->get_field_name( 'nf_widget_type' ); ?>" onchange="nf_switch_widget_form(this, 'nf_widget_type');">
						<option value=""></option>
						<option value="Single" <?php if($widget_type == 'Single') echo ' selected' ?>>Single</option>
						<option value="Slider"<?php if($widget_type == 'Slider') echo ' selected' ?>>Slider</option>
						<option value="List"<?php if($widget_type == 'List') echo ' selected' ?>>List</option>
					</select>
				</p>
			</div>
			
			<div id="post_type_input">
				<p id="post_type">
					<label for="<?php echo $this->get_field_id( 'nf_post_type' ); ?>">Post type:</label>
					<select name="<?php echo $this->get_field_name( 'nf_post_type' ); ?>" onchange="nf_switch_widget_form(this, 'nf_post_type');">
						<option value="page" <?php if($post_type == 'page') echo ' selected' ?>>Page</option>
						<option value="post"<?php if($post_type == 'post') echo ' selected' ?>>Post</option>
					</select>
				</p>
			</div>
			
			<div id="which_input" class="<?php echo $which_input_class; ?>">
				<p id="which">
					<script type="text/javascript">
						var post_options = '<?php echo $post_options_json; ?>';
						var page_options = '<?php echo $page_options_json; ?>';
					</script>
					<label for="<?php echo $this->get_field_id( 'nf_which' ); ?>">Which:</label>
					<select name="<?php echo $this->get_field_name( 'nf_which' ); ?>" id="<?php echo $this->get_field_name( 'nf_which' ); ?>" class="nf_which_one_select">
						<?php echo $which_options; ?>
					</select>
				</p>
			</div>
		
			<div id="how_input" class="<?php echo $how_input_class; ?>">
				<p id="how">
					<label for="<?php echo $this->get_field_id( 'nf_how' ); ?>">How?</label>
					<select name="<?php echo $this->get_field_name( 'nf_how' ); ?>" onchange="nf_switch_widget_form(this, 'nf_how');">
						<option value=""></option>
						<option value="category" <?php if($how == 'category') echo 'selected' ?>>by Category</option>
						<option value="tag"<?php if($how == 'tag') echo ' selected' ?>>by Tag</option>
						<option value="selection"<?php if($how == 'selection') echo ' selected' ?>>by Selection</option>
					</select>
				</p>
			</div>
			
			<div id="category_input" class="<?php echo $category_input_class; ?>">
				<p id="category">
					<label for="<?php echo $this->get_field_id( 'nf_category_id' ); ?>">Category:</label>
					<select name="<?php echo $this->get_field_name( 'nf_category_id' ); ?>">
						<option value=""></option>
						<?php echo $category_options; ?>
					</select>
				</p>
			</div>
			
			<div id="tag_input" class="<?php echo $tag_input_class; ?>">
				<p id="tag">
					<label for="<?php echo $this->get_field_id( 'nf_tag_id' ); ?>">Tag:</label>
					<select name="<?php echo $this->get_field_name( 'nf_tag_id' ); ?>">
						<option value=""></option>
						<?php echo $tag_options; ?>
					</select>
				</p>
			</div>
			
			<div id="selection_input" class="<?php echo $selection_list_input_class; ?>">
				<p id="selection">
					<label for="<?php echo $this->get_field_id( 'nf_selection_id_list' ); ?>[]">Which ones?</label>
					<select name="<?php echo $this->get_field_name( 'nf_selection_id_list' ); ?>[]" size="7" multiple="multiple">
						<?php echo $which_ones_options; ?>
					</select>
				</p>
			</div>
			
			<div id="slider_inputs" class="<?php echo $slider_input_class; ?>">
				<p id="transition">
					<label for="<?php echo $this->get_field_id( 'nf_transition' ); ?>">Transition:</label>
					<select name="<?php echo $this->get_field_name( 'nf_transition' ); ?>">
						<option value="fade"<?php if($transition == 'fade') echo ' selected' ?>>Fade</option>
						<option value="slide"<?php if($transition == 'slide') echo ' selected' ?>>Slide</option>
					</select>
				</p>
				<p id="autoslide">
					<label for="<?php echo $this->get_field_id( 'nf_autoslide' ); ?>">Slide automaticaly?</label>
					<select name="<?php echo $this->get_field_name( 'nf_autoslide' ); ?>">
						<option value="true"<?php if($autoslide == 'true') echo ' selected' ?>>Yes</option>
						<option value="false"<?php if($autoslide == 'false') echo ' selected' ?>>No</option>
					</select>
				</p>
				<p id="wait_time">
					<label for="<?php echo $this->get_field_id( 'nf_wait_time'); ?>">Wait time (ms):</label>
					<input id="<?php echo $this->get_field_id( 'nf_wait_time' ); ?>" name="<?php echo $this->get_field_name( 'nf_wait_time' ); ?>" type="text" value="<?php echo $wait_time; ?>"> 
				</p>
				<p id="randomize">
					<label for="<?php echo $this->get_field_id( 'nf_randomize' ); ?>">Randomize?</label>
					<select name="<?php echo $this->get_field_name( 'nf_randomize' ); ?>">
						<option value="true"<?php if($randomize == 'true') echo ' selected' ?>>Yes</option>
						<option value="false"<?php if($randomize == 'false') echo ' selected' ?>>No</option>
					</select>
				</p>
			</div>
			
			<div id="list_inputs" class="<?php echo $list_inputs_class; ?>">
				<p id="list_limit">
					<label for="<?php echo $this->get_field_id( 'nf_list_limit'); ?>">Limit:</label>
					<input id="<?php echo $this->get_field_id( 'nf_list_limit' ); ?>" name="<?php echo $this->get_field_name( 'nf_list_limit' ); ?>" type="text" value="<?php echo $list_limit; ?>"> 
				</p>
				<p id="list_thumbnail_width">
					<label for="<?php echo $this->get_field_id( 'nf_thumbnail_width'); ?>">Thumbnail width:</label>
					<input id="<?php echo $this->get_field_id( 'nf_thumbnail_width' ); ?>" name="<?php echo $this->get_field_name( 'nf_thumbnail_width' ); ?>" type="text" value="<?php echo $thumbnail_width; ?>">
					<br />Include % or px.
				</p>
			</div>
			
			<div id="all_types_input">
				<p id="include_title">
					<label for="<?php echo $this->get_field_id( 'nf_include_title' ); ?>">Include title?</label>
					<select name="<?php echo $this->get_field_name( 'nf_include_title' ); ?>">
						<option value="Yes"<?php if($include_title == 'Yes') echo ' selected' ?>>Yes</option>
						<option value="No"<?php if($include_title == 'No') echo ' selected' ?>>No</option>
					</select>
				</p>
				
				<p id="include_excerpt">
					<label for="<?php echo $this->get_field_id( 'nf_include_excerpt' ); ?>">Include excerpt?</label>
					<select name="<?php echo $this->get_field_name( 'nf_include_excerpt' ); ?>">
						<option value="Yes"<?php if($include_excerpt == 'Yes') echo ' selected' ?>>Yes</option>
						<option value="No"<?php if($include_excerpt == 'No') echo ' selected' ?>>No</option>
					</select>
				</p>
			</div>
		</div>
		<?php 
	}

	/*
	 * update - Processes widget options on save
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		
        $instance = $old_instance;
		$instance['nf_title'] = ( ! empty( $new_instance['nf_title'] ) ) ? strip_tags( $new_instance['nf_title'] ) : $old_instance['nf_title'];
		$instance['nf_widget_type'] = ( ! empty( $new_instance['nf_widget_type'] ) ) ? strip_tags( $new_instance['nf_widget_type'] ) : $old_instance['nf_widget_type'];
		$instance['nf_post_type'] = ( ! empty( $new_instance['nf_post_type'] ) ) ? strip_tags( $new_instance['nf_post_type'] ) : $old_instance['nf_post_type'];
		$instance['nf_which'] = ( ! empty( $new_instance['nf_which'] ) ) ? strip_tags( $new_instance['nf_which'] ) : $old_instance['nf_which'];
		$instance['nf_how'] = ( ! empty( $new_instance['nf_how'] ) ) ? strip_tags( $new_instance['nf_how'] ) : $old_instance['nf_how'];
        $instance['nf_category_id'] = ( ! empty( $new_instance['nf_category_id'] ) ) ? strip_tags( $new_instance['nf_category_id'] ) : $old_instance['nf_category_id'];
		$instance['nf_tag_id'] = ( ! empty( $new_instance['nf_tag_id'] ) ) ? strip_tags( $new_instance['nf_tag_id'] ) : $old_instance['nf_tag_id'];
		$instance['nf_transition'] = ( ! empty( $new_instance['nf_transition'] ) ) ? strip_tags( $new_instance['nf_transition'] ) : $old_instance['nf_transition'];
		$instance['nf_autoslide'] = ( ! empty( $new_instance['nf_autoslide'] ) ) ? strip_tags( $new_instance['nf_autoslide'] ) : $old_instance['nf_autoslide'];
		$instance['nf_wait_time'] = ( ! empty( $new_instance['nf_wait_time'] ) ) ? strip_tags( $new_instance['nf_wait_time'] ) : $old_instance['nf_wait_time'];
		$instance['nf_randomize'] = ( ! empty( $new_instance['nf_randomize'] ) ) ? strip_tags( $new_instance['nf_randomize'] ) : $old_instance['nf_randomize'];
		$instance['nf_list_limit'] = ( ! empty( $new_instance['nf_list_limit'] ) ) ? strip_tags( $new_instance['nf_list_limit'] ) : $old_instance['nf_list_limit'];
		$instance['nf_thumbnail_width'] = ( ! empty( $new_instance['nf_thumbnail_width'] ) ) ? strip_tags( $new_instance['nf_thumbnail_width'] ) : $old_instance['nf_thumbnail_width'];
		$instance['nf_include_title'] = ( ! empty( $new_instance['nf_include_title'] ) ) ? strip_tags( $new_instance['nf_include_title'] ) : $old_instance['nf_include_title'];
		$instance['nf_include_excerpt'] = ( ! empty( $new_instance['nf_include_excerpt'] ) ) ? strip_tags( $new_instance['nf_include_excerpt'] ) : $old_instance['nf_include_excerpt'];
		
		if(is_array($new_instance['nf_selection_id_list'])){
			$selection_id_list = join(',', $new_instance['nf_selection_id_list']);
			$instance['nf_selection_id_list'] = $selection_id_list;
		}
		
		return $instance;
	}
	
	/*
	 *	get_string_limited
	 */ 
	public static function get_string_limited( $string, $limit = 50 ){
		
		if( strlen( $string ) > $limit){
			$string = substr ( $string , 0, $limit) . '...';
		}
		return $string;
	}
}

?>