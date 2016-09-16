<?php
// Main plugin class - be mindful of namespace
class cpt_shortcode {
	
	private $errors = array();
	
	public function initialize() {
		if ( !is_admin() ) {
			add_shortcode( 'cpt_list',  array('cpt_shortcode', 'handle_cpt_list') );
		}
	}

	// RECORD ERROR
	private function error($message) {
		$errors[] = __($message);
	}
	
	// HANDLE LIST SHORTCODE -DEFAULTS
	function handle_cpt_list( $atts ) {
		extract( shortcode_atts( array(
			'post_type' 		=> 'posts',
			'posts_per_page' 	=> 10,
			'category'			=> '',
			'taxonomy'			=> '',
			'slug'				=> '',
			'use_single_blocks' => 'false',
			'title_links'		=> 'false',
			'link_to_file'		=> 'false',
			'attachment_to_use'	=>	0,
			'show_thumbs' 		=> 'false',
			'show_post_content' => 'true',
			'read_more_link'	=> 'false',
			'list_title' 		=> 'true',
			'file_extension' 	=> 'true',
			'thumb_link' 		=> 'false',
			'thumb_height' 		=> 'false',
			'thumb_width' 		=> 'false',
			'order_posts_by' 	=> 'false',
			'which_order' 		=> 'ASC',
			'wrap_with'			=> 'false',
			'show_date'			=> 'false',
			'images_only'		=>	'false',
			'images_only_num'	=>	1,
			'excerpt_only'		=>	'false', //CONTRIBUTION FROM http://craigwaterman.com
			'show_taxonomies'	=> 'false',
			'filter_content'	=> 'false',	// CONTRIBUTION FROM David Szego
			'meta_key'			=> '',
			'legacy'			=> 'true'			
		), $atts ) );
		
		if ( post_type_exists( $post_type ) ) {
			
			$post_type_object = get_post_type_object( $post_type );
			
			if ( $post_type_object->publicly_queryable ) {
				
	/*
		How meta value works.
		
		When using meta_value, CPT List will override the default order_posts_by and query for a meta_key. 
		Using meta_value we can grab the value for both a-z and 1-9 chars.
		
	*/
	// ORDER BY META VALUE 		
	if ($meta_key != '') {	
	
		// Set Default query
		$cpt_query = new WP_Query( array( 'post_type' => $post_type , 'posts_per_page' => $posts_per_page, 'orderby' => 'meta_value', 'order' => $which_order, 'meta_key' => $meta_key) );
		
			// Check to see if a taxonomy is being used as well		
			if ($taxonomy != '') {					
							
				$cpt_query = new WP_Query( array( 'post_type' => $post_type , 'posts_per_page' => $posts_per_page, $taxonomy => $slug, 'orderby' => 'meta_value', 'order' => $which_order, 'meta_key' => $meta_key) );
							
			} elseif
			 
				// If taxonomy is not used then check to see if a category has been used as well
				($category != ''){
								
					$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page, 'category_name' => $category, 'orderby' => 'meta_value', 'order' => $which_order, 'meta_key' => $meta_key) );
						
				}
		 
	} else {
				
				// Taxonomy support by Justin Greer
				if ($taxonomy != '') {					
					
					if($order_posts_by!='false') {
					
					$cpt_query = new WP_Query( array( 'post_type' => $post_type , 'posts_per_page' => $posts_per_page, $taxonomy => $slug, 'orderby' => $order_posts_by, 'order' => $which_order ) );
					
					} else {
						
						$cpt_query = new WP_Query( array( 'post_type' => $post_type , 'posts_per_page' => $posts_per_page, $taxonomy => $slug ) );
							
						}
					
					}else{
					
					// END IF TAXONOMY
				
					if($category == ''){
						
						if($order_posts_by!='false'){
							
							$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page, 'orderby' => $order_posts_by, 'order' => $which_order ) );
						
							}else{
							
							$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page ) );
							
							}
							
						}else{
						
						if($order_posts_by!='false'){
							
							$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page, 'category_name' => $category, 'orderby' => $order_posts_by, 'order' => $which_order )  );
							
							}else{
							
							$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page, 'category_name' => $category ) );
							
							}
					
						}
				}// End of else for taxonomy
				
	}// END OF ELSE FOR META
	 
				if ( $cpt_query->posts ) {
					
					$list_title_to_print = ''; //create cpt list title
					
					if($list_title == 'true'){
					
						$list_title_to_print = '<h2 class="cpt-list-title">' . ucfirst($post_type_object->name) .'</h2>';
					
					}else{
					
						if($list_title != 'false'){
					
							$list_title_to_print = ucfirst($list_title);
					
						}
					
					}
					
					$pre_list = '<div class="cpt-list-wrapper cpt-list-wrapper-' . $post_type_object->name . '">' . $list_title_to_print . ''; //list wrapper before
					
					$aft_list = '</div>'; //list wrapper after
					
					$pre_item = ''; //list item wrapper before
					
					$btw_item = ''; //list item between title and content
					
					$aft_item = ''; //list item wrapper after
					
					if($use_single_blocks == 'false'){
					
						$pre_list .= '<dl class="cpt-list cpt-list-' . $post_type_object->name . ' cpt-list-cat-' . $category . '">';
					
						$aft_list = '</dl>' . $aft_list;
					
					}else{
					
						$pre_list .= '<ul class="cpt-list cpt-list-' . $post_type_object->name . '">';
					
						$aft_list = '</ul>' . $aft_list;
					
					}
					
					$output = $pre_list;
					
					$first = true;
					
					$total = count($cpt_query->posts);
					
					$count = 0;
					
					foreach ( $cpt_query->posts as $post ) :
					
						$count++; //keep track of current cpt list item
						
						$ext_class = 'cpt_item '; //extend class, used for each cpt list item, starting with a generic cpt list item identifier
						
						if($first){
					
							$ext_class .= 'first '; //if it's the first post add class of first
					
							$first = false;	
					
						}
					
						if($count == $total){
					
							$ext_class .= 'last '; //if it's the last post add class of last	
					
						}						
					
						$ext_title_class = 'cpt_item_title '; //extend list item title class, starting with a generic cpt list item title identifier
					
						$pre_title = '<h3 class="' . $ext_title_class . '">'; //cpt list item title wrap before
					
						$aft_title = '</h3>'; //cpt list item title wrap after
						
						$more_links_to = ''; //cpt list item read more link
						
						if($title_links != 'false'){
					
							$extension = '';
					
							$link_title_to = $title_links;
					
							if($title_links == 'true'){
					
								if($link_to_file != 'false'){
					
									if($link_to_file == 'true'){
					
										$args = array(
											'post_type' => 'attachment',
											'numberposts' => null,
											'post_status' => null,
											'post_parent' => $post->ID
										);
										
										$attachments = get_posts($args);
										
										if ($attachments) {
										
											if($attachments[$attachment_to_use]){
										
												$attachment = $attachments[$attachment_to_use];
										
											}else{
										
												$attachment = $attachments[0];
										
											}
										
											$link_title_to = wp_get_attachment_url($attachment->ID, false);
										
											if($file_extension != 'false'){
										
												if($file_extension == 'true'){
										
													$extension = explode('.',$link_title_to);
										
													$extension = $extension[count($extension)-1];
										
												}else{
										
													$extension = str_replace("'","\\'",str_replace(' ','-',$file_extension));	
										
												}
										
											}
										
										}else{
										
											$link_title_to = 'javascript:void(0)'; // Changed to prevent jump - Was #
										
										}
									
									}else{
									
										$link_title_to = $link_to_file;
									
									}
								
								}else{
								
									$link_title_to = get_permalink($post->ID);
								
								}
							
							}
							
							$pre_title .= '<a href="' . $link_title_to . '" class="cpt_item_title_link ' . $extension . '">';
							
							$aft_title = '</a>' . $aft_title;
							
							$more_links_to = $link_title_to;
						
						}else{
					
								$more_links_to = get_permalink($post->ID);
					
						}
					
						$thumb_html = ''; //cpt list item thumbnail
					
						if($show_thumbs == 'true'){
					
							if ( function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID) ) {
					
							 	$thumbnails = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
					
								if (!$thumbnails[0]){
					
									$thumb_html = '';
					
								}else{
					
									$alt = '' . attribute_escape($post->post_title) .'';
					
									$title = '' . attribute_escape($post->post_title) . '';							
					
									if(get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true)){
					
										$alt = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
					
										$title = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
					
									}
					
									if(get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_title', true)){
					
										$title = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_title', true);
					
										if($alt==''){
					
											$alt = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_title', true);	
					
										}
					
									}
					
									$thumb_size_html = 'style=" ';
					
									if($thumb_height != 'false'){
					
										$thumb_size_html .= 'height:' . $thumb_height . 'px; ';
					
									}
					
									if($thumb_width != 'false'){
					
										$thumb_size_html .= 'width:' . $thumb_width . 'px; ';
					
									}
									$thumb_size_html .= ' "';
					
									$thumb_html = '<img src="' . $thumbnails[0] . '" border="0" class="attachment-post-thumbnail wp-post-image" title="' . $title . '" alt="' . $alt . '" ' . $thumb_size_html . ' />';
									
									if($thumb_link != 'false'){
									
										$link_thumb_to = '';
									
										switch($thumb_link){
									
											case 'true':
									
											if(!$link_title_to){
									
												$link_title_to = get_permalink($post->ID);
									
											}
									
											$link_thumb_to = $link_title_to;
									
											break;
									
											case 'post_index':
									
											$link_thumb_to = '#' . intval($count-1);
									
											break;
									
											case 'id':
									
											$link_thumb_to = get_post_thumbnail_id($post->ID);
									
											break;
									
											case 'src':
									
											$link_thumb_to = $thumbnails[0];
									
											break;
									
											default:
									
											$link_thumb_to = $thumb_link;
									
											break;
									
										}
									
										$thumb_html = '<a href="' . $link_thumb_to . '" class="cpt_item_thumb_link">' . $thumb_html . '</a>';
									
									}
								}
								
							}
							
						}
						
						$content_html = '';
						
						if($show_post_content == 'true'){
						
							$content_html = $post->post_content;
						
							$content_html = ( $excerpt_only == 'true' ) ? $post->post_excerpt : $post->post_content; //http://craigwaterman.com
							
							// Override the defualt and use filter on content - David Szego
							if ($filter_content == 'true'){
							
								$content_html = apply_filters('the_content', $post->post_content);    
                                                
       							$content_html = ( $excerpt_only == 'true' ) ? apply_filters('the_content', $post->post_excerpt) : apply_filters('the_content', $post->post_content); 
							 
							}
						
						}else if($show_post_content == 'false'){
						
						}else{
							
							$content_html = $show_post_content;	
						
						}
						
						
							
								// ADDED BY JUSTIN GREER 8/21/2011 - ABILITY TO SHOW ONLY IMAGES FROM THE POST
								if ($images_only == "true"){
									
									  	$num = $images_only_num;
							
										global $more;
							
										$more = 1;
							
										$link = get_permalink();
							
										$content = $post->post_content;
							
										$count = substr_count($content, '<img');
							
										$start = 0;
							
										for($i=1;$i<=$count;$i++) {
							
										$imgBeg = strpos($content, '<img', $start);
							
										$post = substr($content, $imgBeg);
							
										$imgEnd = strpos($post, '>');
							
										$postOutput = substr($post, 0, $imgEnd+1);
							
										$postOutput = preg_replace('/width="([0-9]*)" height="([0-9]*)"/', '',$postOutput);;
							
										$image[$i] = $postOutput;
							
										$start=$imgEnd+1;
							
									}
							
								$content_html = $postOutput;									
							
									}else if($images_only == 'false'){
							
						}else{
							
							$content_html = $images_only;	
						
						}
							
									// ADDED BY JUSTIN GREER - 08/21/2011
									$show_date1= '';
							
										if($show_date == 'true'){
							
											$show_date1 = $post->post_date;
							
										}else if($show_date == 'false'){
											
										}else{
							
									$show_date1 = $show_date;	
							
								}
									

						if($use_single_blocks == 'false'){
							
							$pre_item = '<dt class="' . $ext_class . '">';
							
							$btw_item = '</dt><dd class="cpt_item_content">';
							
							$aft_item = '</dd>';
						
						}else{
							
							$open_wrap_html = '';
							
							$close_wrap_html = '';
							
							if($wrap_with!='false'){
							
								$open_wrap_html = '<'.$wrap_with.'>';
								
								$wrap_close_tag = explode(' ',$wrap_with);
								
								$close_wrap_html = '</'.$wrap_close_tag[0].'>';	
							}
							
							$pre_item = '<li class="' . $ext_class . '">'.$open_wrap_html;
							
							$btw_item = '<div class="cpt_item_content">';
							
							$aft_item = '</div>'.$close_wrap_html.'</li>';
						}

						// Show Taxonomies support by Chris Sigler
						$tax_output = '';
						
						if ($show_taxonomies != 'false'){
							
							$show_taxonomies = explode(',', $show_taxonomies);
							
							$taxes=get_taxonomies(array('public' => true, '_builtin'=>false),'object');
							
							foreach ($show_taxonomies as $to_show){
								
								$to_show = trim($to_show);

								$assigned = get_the_terms( $post->ID, $to_show );
								
								if (!empty($assigned) && !isset($assigned->errors)){

									$tax_output .= "<div><label>";

									if (count($assigned) > 1){
									
										$tax_output .= $taxes[$to_show]->labels->name;
										
									}else{
									
										$tax_output .= $taxes[$to_show]->labels->singular_name;
									}

									$tax_output .= ":</label> ";

									foreach ($assigned as $a){
									
										$tax_output .= $a->name . ', ';
									
									} // end foreach assignment

									$tax_output = rtrim($tax_output, ', '); // clean off trailing comma. it's dirty, i know

									$tax_output .= "</div>";

								} // end if there are assignments

							} // end foreach taxonomies in shortcode
							
							$tax_output = "<div class='show_taxonomies'>" . $tax_output . "</div>";
							
						}
						// END OF SHOW TAXONOMIES

						$more_link = '';
						
						if($read_more_link != 'false'){
						
							if($read_more_link == 'true'){
						
								$more_link = '<a href="' . $more_links_to . '">Read More...</a>';	
						
							}else{
						
								$more_link = '<a href="' . $more_links_to . '">' . $read_more_link . '</a>';
							}
						
							$aft_item = $more_link . $aft_item;
						}
						
							$output .= $pre_item;
							
							// Shane Lilge
							if (!empty($post->post_title)) {
								$output .= $pre_title . $post->post_title . $aft_title;
							}

							$output .= $tax_output; // ADDED BY CHRIS SIGLER 12/09/2011 - display assigned taxonomy values						
						
							if ($show_date != 'false'){
								
								$output .= $pre_title . $show_date1 . $aft_title;
								
								}
						
							$output .= $btw_item;
							if ($legacy === 'true'){
								$output .= '<span class="cpt-featured-image">'.$thumb_html.'</span><span class="cpt-content">'.$content_html.'</span>';
							}else{
								$output .= '<div class="cpt-featured-image">'.$thumb_html.'</div>';
								$output .= '<div class="cpt-content">'.$content_html.'</div>';
							}
							$output .= $aft_item;
					
					endforeach;
					
					$output .= $aft_list;
				
				} else {
					
					$output .= '<p>' . $post_type_object->labels->not_found . '</p>';
				
				}
			
			} else {
			
				$output = '<p>Post Type <em>{$post_type}</em> is not publicly queriable.</p>';
			}
			
			// debugging code
			// $output = '<pre>' . print_r($post_type_object,true) . '</pre>';
		
		} else {
		
			$output = '<p>Post Type <em>{$post_type}</em> does not exist.</p>';
		
		}
		
		return $output;
	
	}
	

	// SHOW MESSAGES
	private function showMessages() {
	
		if(!$errors) return;
	
		?>
		
		<div class="updated wp-cpt-shortcode-updated error"><p><strong><?php e('The following errors were reported:') ?></strong></p>
			
			<?php foreach($this->errors as $err) {
			
			print '<p>' . __($err) . '</p>';
			
			} ?>
		
		</div>
	
	<?php }

}