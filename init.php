<?php
// Register and load the widget
function acpw_posts_category_load_widget() {
    register_widget( 'acpw_posts_category_widget' );
}
add_action( 'widgets_init', 'acpw_posts_category_load_widget' );
 
// Creating the widget 
class acpw_posts_category_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
	 
		// Base ID of your widget
		'acpw_posts_category_widget', 
		 
		// Widget name will appear in UI
		__('Advance Category Posts Widget', 'advance-category-posts-widget'), 
		 
		// Widget description
		array( 'description' => __( 'Smart widget that shows posts from the selected category.', 'advance-category-posts-widget' ), ) 
		);
	}
 
	// Creating widget front-end
	public function widget( $args, $instance ) {
		$instance['title'] = ( ! empty( $instance['title'] ) ) ? strip_tags( $instance['title'] ) : '';
		$title = apply_filters( 'widget_title', $instance['title'] );
		 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		$slides = ! empty( $instance['slides'] ) ? $instance['slides'] : '1';
		$category = ! empty( $instance['category'] ) ? $instance['category'] : '1';
		$show_arrow = '0';
		$show_pagination = '0';
		$direction = '0';

		if( isset( $instance['show_arrow'] ) && $instance['show_arrow']!='' )
		{
			$show_arrow = $instance['show_arrow'];
		}
		if( isset( $instance['show_pagination'] ) && $instance['show_pagination']!='' )
		{
			$show_pagination = $instance['show_pagination'];
		}
		if( isset( $instance['direction'] ) && $instance['direction']!='' )
		{
			$direction = $instance['direction'];
		}
		$ran = rand(1,100); $ran++;
		if( $direction == '1' )
		{
			$direction = 'true';
		}
		else{
			$direction = 'false';
		}
		$days = ( ! empty( $instance['days'] ) ) ? strip_tags( $instance['days'] ) : '1';
		$option = ( ! empty( $instance['option'] ) ) ? strip_tags( $instance['option'] ) : 'list';
		$timeoption = ( ! empty( $instance['time-option'] ) ) ? strip_tags( $instance['time-option'] ) : 'day';
		$current = ( ! empty( $instance['current'] ) ) ? strip_tags( $instance['current'] ) : current_time( 'mysql' );

		if($option=='slide')
		{
			// This is where you run the code and display the output
			echo '<div id="sync1-'.esc_attr($ran).'" class="owl-carousel owl-theme">';
				global $post;
		    	$catquery = new WP_Query( 'cat='.$category.'&posts_per_page='.$slides ); ?>
				<?php while($catquery->have_posts()) : $catquery->the_post(); 
					$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), apply_filters('acpw_image_size','post-slider-thumb-size' )); 
					if(isset( $feat_image_url ) && $feat_image_url!='' )
					{
						$feat_image_url = $feat_image_url[0];
					}
					else{
						$feat_image_url = esc_url(ACPW_FILE_URL).'/assets/image/no-featured-img.png';
					}
					?>
					<div class="item">
						<a href="<?php the_permalink();?>" title="<?php the_title();?>" class="post-thumbnail">
							<img class="carousel-thumb" src="<?php echo esc_url($feat_image_url);?>">
						</a>
						<div class="carousel-title">
                                <?php
                                $category_detail = get_the_category($post->ID);
                                echo '<span class="cat-links">';
                                foreach($category_detail as $cd){
                                echo '<a title="'.get_the_title().'" href="' . esc_url( get_category_link( $cd->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'advance-category-posts-widget' ), $cd->name ) ) . '">' . esc_html( $cd->name ) . '</a>';
                                }
                                echo '</span>';
                                ?>
							<h3 class="title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						</div>
                    </div>
				<?php endwhile;
				    wp_reset_postdata();
				echo '</div>';
				$obj = new Apcw_Plugin_Assets;
				echo $obj->apcw_minify_css('<style>
				#sync1-'.esc_attr($ran).' {
				  .item {
				    background: #0c83e7;
				    padding: 80px 0px;
				    margin: 5px;
				    color: #FFF;
				    -webkit-border-radius: 3px;
				    -moz-border-radius: 3px;
				    border-radius: 3px;
				    text-align: center;
				  }
				}
				.owl-theme {
					.owl-nav {
					    /*default owl-theme theme reset .disabled:hover links */
					    [class*="owl-"] {
					      transition: all .3s ease;
					      &.disabled:hover {
					       background-color: #D6D6D6;
					      }   
					    }
					    
					  }
					}

					//arrows on first carousel
					#sync1-'.esc_attr($ran).'.owl-theme {
					  position: relative;
					  .owl-next, .owl-prev {
					    width: 22px;
					    height: 40px;
					    margin-top: -20px;
					    position: absolute;
					    top: 50%;
					  }
					  .owl-prev {
					    left: 10px;
					  }
					  .owl-next {
					    right: 10px;
					  }
					}
				</style>');
				echo $obj->apcw_minify_js( '<script>
					jQuery(document).ready(function($) {
					  var sync1 = $("#sync1-'.esc_attr($ran).'");
					  var slidesPerPage = 1;
					  var syncedSecondary = true;
					  sync1.owlCarousel({
					    items : 1,
					    slideSpeed : '.apply_filters('posts_category_slider_speed', '5000').',
					    nav: '.$show_arrow.',
					    dots: '.$show_pagination.',
					    rtl : '.$direction.',
					    autoplay: true,
					    loop: true,
					    responsiveRefreshRate : 200,
					  }); });</script>' );
				echo $args['after_widget'];
		}

		if($option=='list')
		{
			// This is where you run the code and display the output
			echo '<div id="sync1-'.esc_attr($ran).'" class="acpw-images-wrap">';
				global $post;
		    	$catquery = new WP_Query( 'cat='.$category.'&posts_per_page='.$slides ); ?>
				<?php while($catquery->have_posts()) : $catquery->the_post(); 
					$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), apply_filters('acpw_image_size','post-slider-thumb-size' )); 
					if(isset( $feat_image_url ) && $feat_image_url!='' )
					{
						$feat_image_url = $feat_image_url[0];
					}
					else{
						$feat_image_url = esc_url(ACPW_FILE_URL).'/assets/image/no-featured-img.png';
					}
					?>
					<div class="item">
						<a href="<?php the_permalink();?>" title="<?php the_title();?>" class="post-thumbnail">
							<img class="post-thumb" src="<?php echo esc_url($feat_image_url);?>">
						</a>
						<div class="post-title">
                                <?php
                                $category_detail = get_the_category($post->ID);
                                echo '<span class="cat-links">';
                                foreach($category_detail as $cd){
                                echo '<a title="'.get_the_title().'" href="' . esc_url( get_category_link( $cd->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'advance-category-posts-widget' ), $cd->name ) ) . '">' . esc_html( $cd->name ) . '</a>';
                                }
                                echo '</span>';
                                ?>
							<h3 class="title"><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						</div>
                    </div>
				<?php endwhile;
				    wp_reset_postdata();
				echo '</div>';
				echo $args['after_widget'];
		}

		if($option=='time')
		{
			$time = preg_split( '([^0-9])', $current );
			$currenttime = current_time( 'mysql' );
			$currenttime = preg_split( '([^0-9])', $currenttime );
			// This is where you run the code and display the output
			echo '<div id="sync1-'.esc_attr($ran).'" class="acpw-images-wrap">';
				global $post;
		    	$catquery = new WP_Query( 'cat='.$category.'&posts_per_page='.$slides );
		    	$sum=0;
		    	for ($i=1; $i <= $catquery->found_posts; $i++) 
		    	{ 
		    		if($i==1)
		    		{
		    			$timearray[$i] = $time[3]; 
		    		}
		    		else{
		    			$a = $timearray[$i-1]+$days;
		    			$timearray[$i] = $a; 
		    		}
		    	}
		    	?>
				<?php
				if( $timeoption == 'hr' )
				{
					$j=1;
					while($catquery->have_posts()) : $catquery->the_post();
						if( $timearray[$j] == $currenttime[3] )
						{ 
							$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), apply_filters('acpw_image_size','post-slider-thumb-size' )); 
							if(isset( $feat_image_url ) && $feat_image_url!='' )
							{
								$feat_image_url = $feat_image_url[0];
							}
							else{
								$feat_image_url = esc_url(ACPW_FILE_URL).'/assets/image/no-featured-img.png';
							}
							?>
							<div class="item">
								<a href="<?php the_permalink();?>" title="<?php the_title();?>" class="post-thumbnail">
									<img class="post-thumb" src="<?php echo esc_url($feat_image_url);?>">
								</a>
								<div class="post-title">
		                                <?php
		                                $category_detail = get_the_category($post->ID);
		                                echo '<span class="cat-links">';
		                                foreach($category_detail as $cd){
		                                echo '<a href="' . esc_url( get_category_link( $cd->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'advance-category-posts-widget' ), $cd->name ) ) . '">' . esc_html( $cd->name ) . '</a>';
		                                }
		                                echo '</span>';
		                                ?>
									<h3 class="title"><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
								</div>
		                    </div>
					<?php
						} 
						$j++;
					endwhile;
				}

				if( $timeoption == 'day' )
				{
					$j=1;
					while($catquery->have_posts()) : $catquery->the_post(); 
						if( $currenttime[2] == $timearray[$j] )
						{
							$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), apply_filters('acpw_image_size','post-slider-thumb-size' )); 
							if(isset( $feat_image_url ) && $feat_image_url!='' )
							{
								$feat_image_url = $feat_image_url[0];
							}
							else{
								$feat_image_url = esc_url(ACPW_FILE_URL).'/assets/image/no-featured-img.png';
							}
							?>
							<div class="item">
								<a title="<?php the_title();?>" href="<?php the_permalink();?>" class="post-thumbnail">
									<img class="post-thumb" src="<?php echo esc_url($feat_image_url);?>">
								</a>
								<div class="post-title">
		                                <?php
		                                $category_detail = get_the_category($post->ID);
		                                echo '<span class="cat-links">';
		                                foreach($category_detail as $cd){
		                                echo '<a href="' . esc_url( get_category_link( $cd->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'advance-category-posts-widget' ), $cd->name ) ) . '">' . esc_html( $cd->name ) . '</a>';
		                                }
		                                echo '</span>';
		                                ?>
									<h3 class="title"><a title="<?php the_title();?>" href="<?php the_permalink();?>"><?php the_title();?></a></h3>
								</div>
		                    </div>
					<?php
						} 
					$j++;
					endwhile;
				}
				wp_reset_postdata();
				echo '</div>';
				echo $args['after_widget'];
		}
	}
         
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'advance-category-posts-widget' );
		}
		if ( isset( $instance[ 'category' ] ) ) {
			$category = $instance[ 'category' ];
		}
		else {
			$category = '1';
		}
		if ( isset( $instance[ 'show_arrow' ] ) ) {
			$show_arrow = $instance[ 'show_arrow' ];
		}
		else {
			$show_arrow = '';
		}
		if ( isset( $instance[ 'show_pagination' ] ) ) {
			$show_pagination = $instance[ 'show_pagination' ];
		}
		else {
			$show_pagination = '';
		}
		if ( isset( $instance[ 'slides' ] ) ) {
			$slides = $instance[ 'slides' ];
		}
		else {
			$slides = '1';
		}
		if ( isset( $instance[ 'direction' ] ) ) {
			$direction = $instance[ 'direction' ];
		}
		else {
			$direction = '';
		}
		$days = ( ! empty( $instance['days'] ) ) ? strip_tags( $instance['days'] ) : '1';
		$option = ( ! empty( $instance['option'] ) ) ? strip_tags( $instance['option'] ) : 'list';
		$timeoption = ( ! empty( $instance['time-option'] ) ) ? strip_tags( $instance['time-option'] ) : 'day';
		$current = ( ! empty( $instance['current'] ) ) ? strip_tags( $instance['current'] ) : current_time( 'mysql' );

		// Widget admin form
		?>
		<div class="apcw-widget">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'advance-category-posts-widget' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'advance-category-posts-widget' ); ?></label> 
		        <select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" class="widefat" style="width:100%;">
		            <?php foreach(get_terms('category','parent=0&hide_empty=0') as $term) { ?>
		            <option <?php selected( $category, $term->term_id ); ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
		            <?php } ?>      
		        </select>
		    </p>

		    <p>
				<div style="margin-bottom:5px;"><h4><?php _e('Post Display Method:','advance-category-posts-widget'); ?></h4></div>
				<label><?php _e('Slider ','advance-category-posts-widget'); ?><input class="option" id="<?php echo $this->get_field_id( 'option' ); ?>" name="<?php echo $this->get_field_name( 'option' ); ?>" type="radio" value="slide" <?php checked($option,'slide');?> /></label>
				<label><?php _e('List ','advance-category-posts-widget'); ?><input class="option" id="<?php echo $this->get_field_id( 'option' ); ?>" name="<?php echo $this->get_field_name( 'option' ); ?>" type="radio" value="list" <?php checked($option,'list');?> /></label>
				<label><?php _e('Time ','advance-category-posts-widget'); ?><input class="option" id="<?php echo $this->get_field_id( 'option' ); ?>" name="<?php echo $this->get_field_name( 'option' ); ?>" type="radio" value="time" <?php checked($option,'time');?> /></label>
			</p>
			
	        <p>
	            <label for="<?php echo esc_attr( $this->get_field_id( 'slides' ) ); ?>"><?php esc_html_e( 'Number of Posts/Slides:', 'advance-category-posts-widget' ); ?></label>
	            <input id="<?php echo esc_attr( $this->get_field_id( 'slides' ) ); ?>" min="1" name="<?php echo esc_attr( $this->get_field_name( 'slides' ) ); ?>" type="number" max="100" value="<?php echo esc_attr( $slides ); ?>"/>
	            <div class="example-text"><?php _e('Total number of posts available in the selected category will be the maximum number of posts/slides.','advance-category-posts-widget');?></div>
	        </p>

	        <div class="slider-options" <?php if($option!='slide') echo "style='display:none'";?>>
			    <p>
		            <input id="<?php echo esc_attr( $this->get_field_id( 'show_arrow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_arrow' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_arrow ); ?>/>
		            <label for="<?php echo esc_attr( $this->get_field_id( 'show_arrow' ) ); ?>"><?php esc_html_e( 'Show Slider Arrows', 'advance-category-posts-widget' ); ?></label>
		        </p>

		       	<p>
		            <input id="<?php echo esc_attr( $this->get_field_id( 'show_pagination' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_pagination' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_pagination ); ?>/>
		            <label for="<?php echo esc_attr( $this->get_field_id( 'show_pagination' ) ); ?>"><?php esc_html_e( 'Show Slider Pagination', 'advance-category-posts-widget' ); ?></label>
		        </p>

		        <p>
		            <input id="<?php echo esc_attr( $this->get_field_id( 'direction' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'direction' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $direction ); ?>/>
		            <label for="<?php echo esc_attr( $this->get_field_id( 'direction' ) ); ?>"><?php esc_html_e( 'Change Direction', 'advance-category-posts-widget' ); ?></label>
		            <div class="example-text"><?php _e( "Enabling this will change slider direction from 'right to left' to 'left to right'.","blossomthemes-toolkit" );?></div>
		        </p>
	    	</div>
	    	
	    	<div class="time-options" <?php if($option!='time') echo "style='display:none'";?>>
	    		<p>
		    		<div style="margin-bottom:5px;"><h4><?php _e('Post Changes Every:','advance-category-posts-widget');?></h4></div>
		    		<label><input class="widefat" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" value="<?php echo esc_attr($days);?>" /></label>
		    		<select id="<?php echo $this->get_field_id( 'time-option' ); ?>" name="<?php echo $this->get_field_name( 'time-option' ); ?>"  required>
					    <option value="">--Please choose an option--</option>
					    <option value="day" <?php selected($timeoption,'day') ?>>Day</option>
					    <option value="hr" <?php selected($timeoption,'hr') ?>>Hour</option>
					</select>
	    		</p>
		    	<div class="example-text"><?php _e('New Post from the selected category will appear after every selected number of days/hours.','advance-category-posts-widget');?></div>
	    		<input type="hidden" id="<?php echo $this->get_field_id( 'current' ); ?>" name="<?php echo $this->get_field_name( 'current' ); ?>" value="<?php echo esc_attr($current);?>">
	    	</div>
    	</div>
		<?php 
	}
     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';
		$instance['show_arrow'] = ( ! empty( $new_instance['show_arrow'] ) ) ? strip_tags( $new_instance['show_arrow'] ) : '';
		$instance['show_pagination'] = ( ! empty( $new_instance['show_pagination'] ) ) ? strip_tags( $new_instance['show_pagination'] ) : '';
		$instance['slides'] = ( ! empty( $new_instance['slides'] ) ) ? strip_tags( $new_instance['slides'] ) : '1';
		$instance['direction'] = ( ! empty( $new_instance['direction'] ) ) ? strip_tags( $new_instance['direction'] ) : '';
		$instance['days'] = ( ! empty( $new_instance['days'] ) ) ? strip_tags( $new_instance['days'] ) : '';
		$instance['option'] = ( ! empty( $new_instance['option'] ) ) ? strip_tags( $new_instance['option'] ) : '';
		$instance['time-option'] = ( ! empty( $new_instance['time-option'] ) ) ? strip_tags( $new_instance['time-option'] ) : '';
		$instance['current'] = current_time( 'mysql' );
		
		return $instance;
	}
} // Class acpw_posts_category_widget ends here