<?php

/*
 * NowFeaturingShortcodes - Class for handling the Now Featuring shortcodes
 */
class NowFeaturingShortcodes {

	function __construct() {}

	/*
 	 * now_featuring - Where the rendering of the slider content happens
 	 * 
  	 */
	public static function now_featuring( $instance, $content) {
		
		$defaults = array(
			'title' 			=> '',
			'widget_type' 		=> '',
			'post_type' 		=> '',
			'which'				=> '',
			'how'				=> '',
			'category_id' 		=> '',
			'tag_id'	 		=> '',
			'selection_id_list' => '',
			'list_limit'		=> 5,
			'thumbnail_width'	=> '100%',
			'transition'		=> 'fade',
			'autoslide'			=> true,
			'wait_time'			=> '7000',
			'randomize'			=> true,
			'include_title'		=> 'Yes',
			'include_excerpt'	=> 'Yes'
		);
		$instance = wp_parse_args( $instance, $defaults );

		$widget_type = $instance['widget_type'];
        $post_type = $instance['post_type'];
		
		$widget_content = '';
		if( $widget_type == 'Single' ){
			$widget_content = self::render_single( $instance );
		}elseif( $widget_type == 'Slider' ){
			$widget_content = self::render_slider( $instance );
		}elseif( $widget_type == 'List' ){				
			$widget_content = self::render_list( $instance );
		}else{
			$widget_content = 'Nothing to do.';
		}

		return $widget_content;
	}
	
	/*
 	 * render_single - Where the rendering of the single content
 	 * 
  	 */
	public static function render_single( $now_featuring_widget ) {
		
        $which = $now_featuring_widget['which'];
		$include_title = $now_featuring_widget['include_title'];
		$include_excerpt = $now_featuring_widget['include_excerpt'];
		
		if(!$which || $which == ''){
			return '<p>Nothing selected to show.</p>';
		}
		
		$post = get_post( $which );
		if(!$post){
			return '<p>Selection not found.</p>';
		}
		
		setup_postdata( $post );
		$title = get_the_title( $post->ID );
		$permalink = get_permalink( $post->ID );
		$thumbnail = self::get_thumbnail( $which, $title );
		$excerpt = '';
		if($include_excerpt == 'Yes'){
			$the_excerpt = get_the_excerpt();
			$excerpt = "<span>$the_excerpt</span>";
		}
		if($include_title == 'Yes'){
			$title = "<a href='$permalink' class='nf_title'>$title</a>";
		}
		$widget_content = "<div id='nf_single'>
							    $title
								<a href='$permalink'>$thumbnail</a>
								$excerpt
							</div>";
		
		return $widget_content;
	}
	
	/*
 	 * render_slider - Where the rendering of the slider content
 	 * 
  	 */
	public static function render_slider( $now_featuring_widget ) {
		
		$post_type = $now_featuring_widget['post_type'];
		$how = $now_featuring_widget['how'];
        $category_id = $now_featuring_widget['category_id'];
		$tag_id = $now_featuring_widget['tag_id'];
		$selection_id_list = $now_featuring_widget['selection_id_list'];
		$transition = $now_featuring_widget['transition'];
		$autoslide = $now_featuring_widget['autoslide'];
		$wait_time = $now_featuring_widget['wait_time'];
		$randomize = $now_featuring_widget['randomize'];
		$include_title = $now_featuring_widget['include_title'];
		$include_excerpt = $now_featuring_widget['include_excerpt'];
		
		$date = new DateTime();
		$slider_id = substr($date->getTimestamp(), 0, 4);
		
		$args = self::get_wp_query_args( $now_featuring_widget );
		if( !is_array($args) && preg_match('/^Error/', $args )){
			return $args;
		}
		$wp_query = new WP_Query( $args );
		
		$style = '';
		if($include_excerpt == 'Yes'){
			$style = "<style>.nf_flex_direction_nav a { top: 30%; }</style>";
		}else{
			$style = "<style>.nf_flex_direction_nav a { top: 40%; }</style>";
		}

		$widget_content = "";
		if ( $wp_query->have_posts() ) {
			
				$widget_content = "
<script type='text/javascript'>
var now_featuring_slider_" . $slider_id . " = function($) {
	$('#now_featuring_slider_" . $slider_id . "').addClass('nf_flexslider');
	var theslider = $('#now_featuring_slider_" . $slider_id . "').nf_flexslider({
					'animation': '" . $transition ."', // fade, slide
					'direction': 'horizontal', // horizontal, vertical
					'slideshow': " . $autoslide . ",
					'slideshowSpeed': " . $wait_time . ", // ms
					'randomize': " . $randomize . ",
					'controlNav': false,
					'pauseOnAction': true,
					'pauseOnHover': true,
					'prevText': '&#60;',
					'nextText': '&#62;'
	});
};
var timer_now_featuring_slider_" . $slider_id . " = function() {
	var slider = !window.jQuery ? window.setTimeout(timer_now_featuring_slider_" . $slider_id . ", 100) : !jQuery.isReady ? window.setTimeout(timer_now_featuring_slider_" . $slider_id . ", 1) : now_featuring_slider_" . $slider_id . "(window.jQuery);
};
timer_now_featuring_slider_" . $slider_id . "();
</script>
$style";
			
			$widget_content .= '<div class=".nf_flexslider" id="now_featuring_slider_' . $slider_id . '"><ul class="slides">';
			while ( $wp_query->have_posts() ) {
				$wp_query->the_post();
				$title = get_the_title();
				$permalink = get_permalink( get_the_ID() ); 
				$thumbnail = self::get_thumbnail( get_the_ID(), $title );
				$excerpt = '';
				if($include_excerpt == 'Yes'){
					$the_excerpt = get_the_excerpt();
					$excerpt = "<span>$excerpt</span>";
				}
				if($include_title == 'Yes'){
					$title = "<a href='$permalink' class='nf_title nf_flex_header'>$title</a>";
				}
				
				$widget_content .= "<li>
										$title
										<a href='$permalink'>$thumbnail</a>
										$the_excerpt
									</li>
									";
			}
			$widget_content .= '</ul></div>';
		}else{
			$widget_content = "<p><b>No pages found for the Now Featuring Slider Widget</b></p>";
		}
		wp_reset_postdata();
		
		return $widget_content;
	}
	
	/*
 	 * render_list - Where the rendering of the list content
 	 * 
  	 */
	public static function render_list( $now_featuring_widget ) {
		
		$post_type = $now_featuring_widget['post_type'];
		$list_limit = $now_featuring_widget['list_limit'];
		$include_title = $now_featuring_widget['include_title'];
		$include_excerpt = $now_featuring_widget['include_excerpt'];
		$thumbnail_width = $now_featuring_widget['thumbnail_width'];
		
		$args = self::get_wp_query_args( $now_featuring_widget );
		if( !is_array($args) && preg_match('/^Error/', $args )){
			return $args;
		}
		$wp_query = new WP_Query( $args );
		
		$widget_content = "";
		if ( $wp_query->have_posts() ) {
			$widget_content .= '<div class="nf_list" id="now_featuring_list">';
			$count = 0;
			while ( $wp_query->have_posts() ) {
				if( $count == $list_limit ){
					break;
				}
				$wp_query->the_post();
				$title = get_the_title();
				$permalink = get_permalink( get_the_ID() );
				$thumbnail = self::get_thumbnail( get_the_ID(), $title, $thumbnail_width );
				$excerpt = '';
				if($include_excerpt == 'Yes'){
					$the_excerpt = get_the_excerpt();
					$excerpt = "<span>$the_excerpt</span>";
				}
				if($include_title == 'Yes'){
					$title = "<a href='$permalink' class='nf_title'>$title</a>";
				}
				
				$widget_content .= "<div class='nf_list_item'>
										$title
										<a href='$permalink'>$thumbnail</a>
										$excerpt
										<div class='clear'></div>
									</div>";
				$count++;
			}
			$widget_content .= '</div>';
		}else{
			$slider_content = "<p><b>No pages found for the Now Featuring List Widget</b></p>";
		}
		wp_reset_postdata();
		
		return $widget_content;
	}
	
	/*
 	 * get_thumbnail 
 	 * 
  	 */
	public static function get_thumbnail( $id, $title, $thumbnail_width = 0 ) {
		
		$post_thumbnail_id = get_post_thumbnail_id( $id );
		$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
		$title = strip_tags( $title );
		$style = '';
		if( preg_match('/^\d+$/', $thumbnail_width) && ($thumbnail_width != 0) ){
			$style = "style='width: " . $thumbnail_width . "%;'";
		}else if( $thumbnail_width != 0 ){
			$style = "style='width: $thumbnail_width;'";
		}
		$thumbnail = "<img src='$post_thumbnail_url' alt='$title' title='$title' $style />";
		return $thumbnail;
	}
	
	/*
 	 * get_wp_query_args 
 	 * 
  	 */
	public static function get_wp_query_args( $now_featuring_widget ) {
		
		$how = $now_featuring_widget['how'];
        $post_type = $now_featuring_widget['post_type'];
		
		$args = array();
		if($how == 'category'){
			$category_id = $now_featuring_widget['category_id'];
			if(!$category_id){
				return "Error: No category selected";
			}
			$args = array(
						'post_type' => array( $post_type ),
						'has_password' => false,
						'posts_per_page' => 50,
						'orderby' => 'name',
						'order' => 'asc',
						'cat' => $category_id
					);
		}elseif($how == 'tag'){
			
			$tag_id = $now_featuring_widget['tag_id'];
			if(!$tag_id){
				return "Error: No tag selected";
			}
			$args = array(
						'post_type' => array( $post_type ),
						'has_password' => false,
						'posts_per_page' => 50,
						'orderby' => 'name',
						'order' => 'asc',
						'tag_id' => $tag_id
					);
		}elseif($how == 'selection'){
			$selection_id_list = $now_featuring_widget['selection_id_list'];
			$selections = preg_split('/,/', $selection_id_list);
			if(!$selection_id_list){
				return "Error: Nothing selected";
			}
			$args = array(
						'post_type' => array( $post_type ),
						'has_password' => false,
						'posts_per_page' => 50,
						'orderby' => 'name',
						'order' => 'asc',
						'post__in' => $selections 
					);
		}
		return $args;
	}
}

?>