<?php
/**
 * Betheme Child Theme
 *
 * @package Betheme Child Theme
 * @author Muffin group
 * @link https://muffingroup.com
 */

/**
 * Load Textdomain
 */

add_action('after_setup_theme', 'mfn_load_child_theme_textdomain');

function mfn_load_child_theme_textdomain(){
	load_child_theme_textdomain('betheme', get_stylesheet_directory() . '/languages');
	load_child_theme_textdomain('mfn-opts', get_stylesheet_directory() . '/languages');
}

/**
 * Enqueue Styles
 */

function mfnch_enqueue_styles()
{
	// enqueue the parent stylesheet
	// however we do not need this if it is empty
	// wp_enqueue_style('parent-style', get_template_directory_uri() .'/style.css');

	// enqueue the parent RTL stylesheet

	if ( is_rtl() ) {
		wp_enqueue_style('mfn-rtl', get_template_directory_uri() . '/rtl.css');
	}

	// enqueue the child stylesheet

	wp_dequeue_style('style');
	wp_enqueue_style('style', get_stylesheet_directory_uri() .'/style.css');
}
add_action('wp_enqueue_scripts', 'mfnch_enqueue_styles', 101);



// Add your code below this line

add_filter( 'fl_module_upload_regex', function( $regex, $type, $ext, $file ) {
  
  $regex['photo'] = '#(jpe?g|png|gif|bmp|tiff?|webp|svg)#i';
  
  return $regex;

}, 10, 4 );

/**
 * Menu burger (Override by PH)
 */

if (! function_exists('sc_header_burger')) {
	function sc_header_burger($attr){
		extract(shortcode_atts(array(
			'image' 	=> '',
			'icon' 		=> '',
			'desc'		=> '',
			'link'		=> '',
			'icon_position' => 'top',
 			'icon_position_tablet' => '',
 			'icon_position_mobile' => '',

 			'icon_align' => 'center',
 			'icon_align_tablet' => '',
 			'icon_align_mobile' => '',
 			'hover' => '',
 			'menu_pos' => '',
 			'link_title' => '',

 			'animation' 		=> '',
			'separator' 		=> 'off',
			'alignment' 		=> 'flex-start',
			'alignment_tablet' 	=> 'flex-start',
			'alignment_mobile' 	=> 'flex-start',
			'icon_animation'	=> '',
			'menu_icon_align'	=> 'left',
			'submenu_icon'		=> 'fas fa-arrow-dow',
			'submenu_subicon'	=> 'fas fa-arrow-right',

		), $attr));

		$output = '';
		$classes = array('mfn-icon-box', 'mfn-header-menu-burger');
		$classes_desc = array();
		$close_icon_pos = 'mfn-close-icon-pos-default';
		$attr_link = '#';
		$nav_name = false;
		$sidemenu_attr = false;

		if( empty($menu_pos) ){
			$menu_pos = 'right';
		}

		if( !empty($attr['sidebar-menu-close-icon-position']) ){
			$close_icon_pos = 'mfn-close-icon-pos-'.$attr['sidebar-menu-close-icon-position'];
		}

		if(isset($attr['menu_display']) && $attr['menu_display'] > 0){
			$nav_id = get_term_by('id', $attr['menu_display'], 'nav_menu');
			if( !empty($nav_id) ) $nav_name = 'data-nav="menu-'.$nav_id->slug.'"';
		}

		if( !empty($attr['header_icon_desc_visibility']) ){
			$classes_desc[] = $attr['header_icon_desc_visibility'];
		}

 		if( $icon_position ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $icon_position );
 		}

 		if( $icon_position_tablet ){
 			$classes[] = 'mfn-icon-box-tablet-'. esc_attr( $icon_position_tablet );
 		}

 		if( $icon_position_mobile ){
 			$classes[] = 'mfn-icon-box-mobile-'. esc_attr( $icon_position_mobile );
 		}

 		if( $icon_align ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $icon_align );
 		}

 		if( $icon_align_tablet ){
 			$classes[] = 'mfn-icon-box-tablet-'. esc_attr( $icon_align_tablet );
 		}

 		if( $icon_align_mobile ){
 			$classes[] = 'mfn-icon-box-mobile-'. esc_attr( $icon_align_mobile );
 		}

 		if( $hover ){
 			$classes[] = 'mfn-icon-box-'. esc_attr( $hover );
 		}

 		if( !empty($attr['sidebar_type']) && is_numeric($attr['sidebar_type']) && $attr['sidebar_type'] != 'default' ){
 			$classes[] = 'mfn-header-sidemenu-toggle';
 			$sidemenu_attr = 'data-sidemenu="'.$attr['sidebar_type'].'"';
 		}else{
 			$classes[] = 'mfn-header-menu-toggle';
 		}

		// link

		if( empty($image) && empty($icon) ) $icon = 'icon-menu-fine';

		if( empty($desc) ) $classes[] = 'mfn-icon-box-empty-desc';

		$classes = implode(' ', $classes);
		$classes_desc = implode(' ', $classes_desc);

		$output .= '<a '.$nav_name.' href="'.$attr_link.'" class="'. $classes .'" '.$sidemenu_attr.' title="'. esc_attr($link_title ?? '') .'">';

		if( !empty($image) || !empty($icon) ){
			$output .= '<div class="icon-wrapper">';

				if( !empty($image) ){
					$output .= '<img class="scale-with-grid" src="'. esc_url( $image ) .'" alt="'. esc_attr( mfn_get_attachment_data( $image, 'alt' ) ) .'" width="'. esc_attr( mfn_get_attachment_data( $image, 'width' ) ) .'" height="'. esc_attr( mfn_get_attachment_data( $image, 'height' ) ) .'">';
 				}elseif( !empty($icon) ){
					$output .= '<i class="'. esc_attr($icon) .'" aria-hidden="true"></i>';
				}

			$output .= '</div>';
		}

		if( !empty($desc) ){
			$output .= '<div class="desc-wrapper '.$classes_desc.'">';
				$output .= $desc;
			$output .= '</div>';
		}

		$output .= '</a>';


		// tmpl menu
		$output .= '<div class="mfn-header-tmpl-menu-sidebar mfn-header-tmpl-menu-sidebar-'.$menu_pos.' '.$close_icon_pos.'"><div class="mfn-header-tmpl-menu-sidebar-wrapper">';
		$output .= '<span class="mfn-close-icon mfn-header-menu-toggle"><span class="icon">&#10005;</span></span>';

			require_once( get_theme_file_path('/visual-builder/classes/header-template-items-class.php') );

			$ul_classes = array('mfn-header-menu');

			// alignment
			$ul_classes[] = 'mfn-menu-align-'.$alignment;
			$ul_classes[] = 'mfn-menu-tablet-align-'.$alignment_tablet;
			$ul_classes[] = 'mfn-menu-mobile-align-'.$alignment_mobile;

			if( !empty($attr['items_align']) ){
	 			$ul_classes[] = 'mfn-items-align-'. esc_attr( $attr['items_align'] );
	 		}

	 		if( !empty($attr['items_align_tablet']) ){
	 			$ul_classes[] = 'mfn-items-align-'. esc_attr( $attr['items_align_tablet'] );
	 		}

	 		if( !empty($attr['items_align_mobile']) ){
	 			$ul_classes[] = 'mfn-items-align-'. esc_attr( $attr['items_align_mobile'] );
	 		}

			// icon align

			$ul_classes[] = 'mfn-menu-icon-'.$menu_icon_align;

			// animation
			if( !empty($animation) ) $ul_classes[] = 'mfn-menu-animation-'.$animation;

			// separator
			if( !empty($separator) ) $ul_classes[] = 'mfn-menu-separator-'.$separator;

			// icon animation
			if( !empty($icon_animation) ) $ul_classes[] = 'mfn-menu-icon-'.$icon_animation;

			// submenu display on click
			$ul_classes[] = 'mfn-menu-submenu-on-click';

			// submenu animation
			if( !empty($attr['submenu_animation']) ) $ul_classes[] = 'mfn-menu-submenu-show-'.$attr['submenu_animation'];

			// submenu icon display
			if( !empty($attr['submenu_icon_display']) ) {
				if( !empty($attr['submenu_icon_animation']) ) $ul_classes[] = 'mfn-menu-submenu-icon-'.$attr['submenu_icon_animation'];
			}

			$arg = array(
				'container' => false,
				'menu_class' => implode(' ', $ul_classes),
				'walker' => new Mfn_Vb_Header_Tmpl,
				'custom_icon' => '<i class="'.$submenu_icon.'"></i>',
				'custom_subicon' => '<i class="'.$submenu_subicon.'"></i>',
				'mfn_classes' => true,
				'echo' => false
			);

			if(isset($attr['menu_display']) && $attr['menu_display'] > 0)
				$arg['menu'] = $attr['menu_display'];

			$output .= wp_nav_menu( $arg );
		
		$output .= '<div class="ph-mobile-search-form">';
		$output .= '<form class="ph-mobile-header-search-form" action="/" method="get">';
		$output .= '<label for="phS">Search</label>';
		$output .= '<input name="s" id="phS">';
		$output .= '<i class="icon-search ph-mobile-search-submit"></i>';
		$output .= '</form>';
		$output .= '</div>';
		$output .= '<a class="button button_size_2 button_dark ph-mobile-start-button" href="/become-client" title=""><span class="button_label">Let’s get started</span></a>';
		$output .= '<a class="ph-mobile-portal-btn" href="/client-portal/"><span class="ph-mobile-portal-icon"><img src="/wp-content/uploads/2024/01/portal-icon-blue.svg" alt="porta-icon"></span> Client Portal <i class="fas fa-arrow-right"></i></a>';
		
		$output .= '</div></div>';



		return $output;

	}
}

/**
 * Short Code for FAQ Knowledge Base Articles
 */

function knowledge_base_list_faq_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
		'order'     => 'asc',
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'knowledgebase',
        'posts_per_page' => -1,
        'kb-category' => 'faq',
		'order' => $atts['order'],
    	'post__not_in'   => array(2917),
    );

    // Query the knowledgebase posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<ul class="kb-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $output .= '</li>';
        }
    $output .= '</ul>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No article found.';
    }

    return $output;
}
add_shortcode('knowledgebase_list_faq', 'knowledge_base_list_faq_shortcode');

/**
 * Short Code for General Tax Advice Knowledge Base Articles
 */

function knowledge_base_list_gta_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
		'order'     => 'asc',
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'knowledgebase',
        'posts_per_page' => -1,
        'kb-category' => 'general-tax-advice',
		'order' => $atts['order'],
    	'post__not_in'   => array(2918),
    );

    // Query the knowledgebase posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<ul class="kb-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $output .= '</li>';
        }
    $output .= '</ul>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No article found.';
    }

    return $output;
}
add_shortcode('knowledgebase_list_gta', 'knowledge_base_list_gta_shortcode');

/**
 * Short Code for Passive Investors Knowledge Base Articles
 */

function knowledge_base_list_pi_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
		'order'     => 'asc',
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'knowledgebase',
        'posts_per_page' => -1,
        'kb-category' => 'passive-investors',
		'order' => $atts['order'],
    	'post__not_in'   => array(2920),
    );

    // Query the knowledgebase posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<ul class="kb-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $output .= '</li>';
        }
    $output .= '</ul>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No article found.';
    }

    return $output;
}
add_shortcode('knowledgebase_list_pi', 'knowledge_base_list_pi_shortcode');

/**
 * Short Code for Flippers, Developers, and Brokers Knowledge Base Articles
 */

function knowledge_base_list_fdb_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
		'order'     => 'asc',
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'knowledgebase',
        'posts_per_page' => -1,
        'kb-category' => 'flippers-developers-and-brokers',
		'order' => $atts['order'],
    	'post__not_in'   => array(2921),
    );

    // Query the knowledgebase posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<ul class="kb-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $output .= '</li>';
        }
    $output .= '</ul>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No article found.';
    }

    return $output;
}
add_shortcode('knowledgebase_list_fdb', 'knowledge_base_list_fdb_shortcode');

/**
 * Short Code for Landlords Knowledge Base Articles
 */

function knowledge_base_list_landlords_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
		'order'     => 'asc',
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'knowledgebase',
        'posts_per_page' => -1,
        'kb-category' => 'landlords',
		'order' => $atts['order'],
    	'post__not_in'   => array(2922),
    );

    // Query the knowledgebase posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<ul class="kb-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $output .= '</li>';
        }
    $output .= '</ul>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No article found.';
    }

    return $output;
}
add_shortcode('knowledgebase_list_landlords', 'knowledge_base_list_landlords_shortcode');

/**
 * Short Code for Syndicates & Funds Knowledge Base Articles
 */

function knowledge_base_list_sf_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
		'order'     => 'asc',
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'knowledgebase',
        'posts_per_page' => -1,
        'kb-category' => 'syndicates-funds',
		'order' => $atts['order'],
    	'post__not_in'   => array(2923),
    );

    // Query the knowledgebase posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<ul class="kb-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $output .= '</li>';
        }
    $output .= '</ul>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No article found.';
    }

    return $output;
}
add_shortcode('knowledgebase_list_sf', 'knowledge_base_list_sf_shortcode');

/**
 * Short Code for State and Local Tax Knowledge Base Articles
 */

function knowledge_base_list_slt_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
		'order'     => 'asc',
        'category' => '',
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'knowledgebase',
        'posts_per_page' => -1,
        'kb-category' => $atts['category'],
		'order' => $atts['order'],
    	'post__not_in'   => array(2919),
    );

    // Query the knowledgebase posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<ul class="kb-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>';
            $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            $output .= '</li>';
        }
    $output .= '</ul>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No article found.';
    }

    return $output;
}
add_shortcode('knowledgebase_list_slt', 'knowledge_base_list_slt_shortcode');


/**
 * Short Code for Articles Grid
 */

function articles_grid_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',
        'posts_per_page' => 3, // Number of posts to display
		'order'     => 'desc',
		'orderby' => '', // use 'rand' for random order
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $atts['posts_per_page'],
        'category' => $atts['category'],
		'order' => $atts['order'],
		'orderby' => $atts['orderby'],
		// remove the comment below to hide articles from featured category
    	// 'category__not_in'   => array(100),
    );

    // Query the articles posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<div class="isotope_wrapper hall-resource-items">';
    $output .= '<div class="grid">';
        while ($query->have_posts()) {
            $query->the_post();
			// Retrieve the image field value
    		$featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'blog-portfolio');
			$featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-portfolio');
			// Check if there is a featured image
			if (empty($featured_image_url)) {
				// Use default image if no featured image is set
				$featured_image_url = 'https://www.therealestatecpa.com/wp-content/uploads/2024/02/hall-cpa-blog-post-1-960x720.jpg';
				// You can set default width and height if needed
				$featured_image_width = 960;
				$featured_image_height = 720;
			} else {
				// Get width and height from the actual featured image
				$featured_image_width = $featured_image_src[1]; // Width is at index 1
				$featured_image_height = $featured_image_src[2]; // Height is at index 2
			}
    		$author_id = get_the_author_meta('ID');
    		$author_name = get_the_author();
    		$author_link = get_author_posts_url($author_id);
			
            $output .= '<article class="post post-item isotope-item clearfix"><div class="image_frame post-photo-wrapper scale-with-grid images_only"><div class="image_wrapper"><a href="' . get_permalink() . '"><img width="' . $featured_image_width . '" height="' . $featured_image_height . '" src="' . $featured_image_url . '" class="scale-with-grid wp-post-image" alt="' . get_the_title() . '" decoding="async"></a></div></div><div class="post-desc-wrapper bg- has-custom-bg"><div class="post-desc"><div class="post-head"><div class="post-meta clearfix"><div class="author-date"><span class="vcard author post-author"><span class="fn"><img class="hall-user-icon" src="https://www.therealestatecpa.com/wp-content/uploads/2024/05/portal-icon.svg" alt="portal-icon"> <a href="' . $author_link . '">' . $author_name . '</a></span></span><span class="date"><span class="post-date updated">' . get_the_date() . '</span></span></div></div></div><div class="post-title"><h4 class="entry-title" ><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4></div><div class="post-footer"><div class="post-links"><a href="' . get_permalink() . '" class="post-more"><i class="icon-googleplay" aria-hidden="true"></i><span class="blog-only-button">read more</span></a></div></div></div></div></article>';
        }
    $output .= '</div>';
    $output .= '</div>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No articles found.';
    }

    return $output;
}
add_shortcode('articles_grid', 'articles_grid_shortcode');




/**
 * Short Code for Featured Articles Grid
 */

function featured_articles_grid_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => 'featured',
        'posts_per_page' => 3, // Number of posts to display
		'order'     => 'desc',
		'orderby' => '', // use 'rand' for random order
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $atts['posts_per_page'],
        'category' => $atts['category'],
		'order' => $atts['order'],
		'orderby' => $atts['orderby'],
		// remove the comment below to hide articles from featured category
    	// 'category__not_in'   => array(100),
    );

    // Query the articles posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<div class="isotope_wrapper hall-resource-items">';
    $output .= '<div class="grid">';
        while ($query->have_posts()) {
            $query->the_post();
			// Retrieve the image field value
    		$featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'blog-portfolio');
			$featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-portfolio');
			// Check if there is a featured image
			if (empty($featured_image_url)) {
				// Use default image if no featured image is set
				$featured_image_url = 'https://www.therealestatecpa.com/wp-content/uploads/2024/02/hall-cpa-blog-post-1-960x720.jpg';
				// You can set default width and height if needed
				$featured_image_width = 960;
				$featured_image_height = 720;
			} else {
				// Get width and height from the actual featured image
				$featured_image_width = $featured_image_src[1]; // Width is at index 1
				$featured_image_height = $featured_image_src[2]; // Height is at index 2
			}
    		$author_id = get_the_author_meta('ID');
    		$author_name = get_the_author();
    		$author_link = get_author_posts_url($author_id);
			
            $output .= '<article class="post post-item isotope-item hall--featured clearfix"><div class="image_frame post-photo-wrapper scale-with-grid images_only"><div class="image_wrapper"><a href="' . get_permalink() . '"><img width="' . $featured_image_width . '" height="' . $featured_image_height . '" src="' . $featured_image_url . '" class="scale-with-grid wp-post-image" alt="' . get_the_title() . '" decoding="async"></a></div></div><div class="post-desc-wrapper bg- has-custom-bg"><div class="post-desc"><div class="post-head"><div class="post-meta clearfix"><div class="author-date"><span class="vcard author post-author"><span class="fn"><img class="hall-user-icon" src="https://www.therealestatecpa.com/wp-content/uploads/2024/05/portal-icon.svg" alt="portal-icon"> <a href="' . $author_link . '">' . $author_name . '</a></span></span><span class="date"><span class="post-date updated">' . get_the_date() . '</span></span></div></div></div><div class="post-title"><h4 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4></div><div class="post-footer"><div class="post-links"><a href="' . get_permalink() . '" class="post-more"><i class="icon-googleplay" aria-hidden="true"></i><span class="blog-only-button">read more</span></a></div></div></div></div></article>';
        }
    $output .= '</div>';
    $output .= '</div>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No articles found.';
    }

    return $output;
}
add_shortcode('featured_articles_grid', 'featured_articles_grid_shortcode');



/**
 * Short Code for Podcasts Grid
 */

function podcasts_grid_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',
        'posts_per_page' => 3, // Number of posts to display
		'order'     => 'desc',
		'orderby' => '', // use 'rand' for random order
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'podcasts',
        'posts_per_page' => $atts['posts_per_page'],
        'logo_categories' => $atts['category'],
		'order' => $atts['order'],
		'orderby' => $atts['orderby'],
		// remove the comment below to hide articles from featured category
    	// 'category__not_in'   => array(100),
    );

    // Query the articles posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<div class="isotope_wrapper hall-resource-items test">';
    $output .= '<div class="grid">';
        while ($query->have_posts()) {
            $query->the_post();
			// Retrieve the image field value
    		$featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'blog-portfolio');
			$featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blog-portfolio');
			// Check if there is a featured image
			if (empty($featured_image_url)) {
				// Use default image if no featured image is set
				$featured_image_url = 'https://www.therealestatecpa.com/wp-content/uploads/2024/02/hall-cpa-blog-post-1-960x720.jpg';
				// You can set default width and height if needed
				$featured_image_width = 960;
				$featured_image_height = 720;
			} else {
				// Get width and height from the actual featured image
				$featured_image_width = $featured_image_src[1]; // Width is at index 1
				$featured_image_height = $featured_image_src[2]; // Height is at index 2
			}
    		$author_id = get_the_author_meta('ID');
    		$author_name = get_the_author();
    		$author_link = get_author_posts_url($author_id);
			
            $output .= '<article class="post post-item isotope-item clearfix"><div class="image_frame post-photo-wrapper scale-with-grid images_only"><div class="image_wrapper"><a href="' . get_permalink() . '"><img width="' . $featured_image_width . '" height="' . $featured_image_height . '" src="' . $featured_image_url . '" class="scale-with-grid wp-post-image" alt="' . get_the_title() . '" decoding="async"></a></div></div><div class="post-desc-wrapper bg- has-custom-bg"><div class="post-desc"><div class="post-head"><div class="post-meta clearfix"><div class="author-date"><span class="vcard author post-author"><span class="fn"><img class="hall-user-icon" src="https://www.therealestatecpa.com/wp-content/uploads/2024/05/portal-icon.svg" alt="portal-icon"> <a href="' . $author_link . '">' . $author_name . '</a></span></span><span class="date"><span class="post-date updated">' . get_the_date() . '</span></span></div></div></div><div class="post-title"><h4 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4></div><div class="post-footer"><div class="post-links"><a href="' . get_permalink() . '" class="post-more"><i class="icon-googleplay" aria-hidden="true"></i><span class="blog-only-button">listen now</span></a></div></div></div></div></article>';
        }
    $output .= '</div>';
    $output .= '</div>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No articles found.';
    }

    return $output;
}
add_shortcode('podcasts_grid', 'podcasts_grid_shortcode');





/**
 * Short Code for Videos Grid
 */

function videos_grid_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',
        'posts_per_page' => 3, // Number of posts to display
		'order'     => 'desc',
		'orderby' => '', // use 'rand' for random order
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'videos',
        'posts_per_page' => $atts['posts_per_page'],
        'logo_categories' => $atts['category'],
		'order' => $atts['order'],
		'orderby' => $atts['orderby'],
		// remove the comment below to hide articles from featured category
    	// 'category__not_in'   => array(100),
    );

    // Query the articles posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<div class="isotope_wrapper hall-resource-items">';
    $output .= '<div class="grid">';
        while ($query->have_posts()) {
            $query->the_post();
			// Retrieve the image field value
    		$featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
			$featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			// Check if there is a featured image
			if (empty($featured_image_url)) {
				// Use default image if no featured image is set
				$featured_image_url = 'https://www.therealestatecpa.com/wp-content/uploads/2024/02/hall-cpa-blog-post-1-960x720.jpg';
				// You can set default width and height if needed
				$featured_image_width = 960;
				$featured_image_height = 720;
			} else {
				// Get width and height from the actual featured image
				$featured_image_width = $featured_image_src[1]; // Width is at index 1
				$featured_image_height = $featured_image_src[2]; // Height is at index 2
			}
    		$author_id = get_the_author_meta('ID');
    		$author_name = get_the_author();
    		$author_link = get_author_posts_url($author_id);
			
            $output .= '<article class="post post-item isotope-item clearfix"><div class="image_frame post-photo-wrapper scale-with-grid images_only"><div class="image_wrapper"><a href="' . get_permalink() . '"><img width="' . $featured_image_width . '" height="' . $featured_image_height . '" src="' . $featured_image_url . '" class="scale-with-grid wp-post-image" alt="' . get_the_title() . '" decoding="async"></a></div></div><div class="post-desc-wrapper bg- has-custom-bg"><div class="post-desc"><div class="post-head"><div class="post-meta clearfix"><div class="author-date"><span class="vcard author post-author"><span class="fn"><img class="hall-user-icon" src="https://www.therealestatecpa.com/wp-content/uploads/2024/05/portal-icon.svg" alt="portal-icon"> <a href="' . $author_link . '">' . $author_name . '</a></span></span><span class="date"><span class="post-date updated">' . get_the_date() . '</span></span></div></div></div><div class="post-title"><h4 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4></div><div class="post-footer"><div class="post-links"><a href="' . get_permalink() . '" class="post-more"><i class="icon-googleplay" aria-hidden="true"></i><span class="blog-only-button">watch now</span></a></div></div></div></div></article>';
        }
    $output .= '</div>';
    $output .= '</div>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No articles found.';
    }

    return $output;
}
add_shortcode('videos_grid', 'videos_grid_shortcode');






/**
 * Short Code for Resources Grid
 */

function ebooks_grid_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',
        'posts_per_page' => -1, // Number of posts to display
		'order'     => 'asc',
		'orderby' => '', // use 'rand' for random order
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'ebook',
        'posts_per_page' => $atts['posts_per_page'],
        'logo_categories' => $atts['category'],
		'order' => $atts['order'],
		'orderby' => $atts['orderby'],
		// remove the comment below to hide articles from featured category
    	// 'category__not_in'   => array(100),
    );

    // Query the articles posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
			// Retrieve the image field value
    		$featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
			$featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			// Check if there is a featured image
			if (empty($featured_image_url)) {
				// Use default image if no featured image is set
				$featured_image_url = 'https://www.therealestatecpa.com/wp-content/uploads/2024/05/HALL-logo_white-1.png';
				// You can set default width and height if needed
				$featured_image_width = 579;
				$featured_image_height = 390;
			} else {
				// Get width and height from the actual featured image
				$featured_image_width = $featured_image_src[1]; // Width is at index 1
				$featured_image_height = $featured_image_src[2]; // Height is at index 2
			}
			
			// Retrieve custom fields value
    		$resource_type = get_field('resource_type');
    		$resource_topic = get_field('resource_topic');
    		$resource_link = get_field('resource_link');
    		$external_link = get_field('external_link');
			// Determine the target attribute for the link
			$link_target = ($external_link === 'yes') ? ' target="_blank"' : '';
			
            $output .= '<div class="resource__item topic-all type-all ' . $resource_topic . ' ' . $resource_type . '"><div class="resource__item__inner"><div class="resource__item__image"><img src="' . $featured_image_url . '" loading="lazy" width="' . $featured_image_width . '" height="' . $featured_image_height . '" alt="' . get_the_title() . ' Cover Image"></div><div class="resource__item__text"><h4 class="resource__item__title"><a href="' . $resource_link . '" ' . $link_target . '>' . get_the_title() . '</a></h4><p><a class="button ph-button button_size_2 button_dark" href="' . $resource_link . '" ' . $link_target . '>View The Resource<span class="sr-only">About ' . get_the_title() . '</span></a></p></div></div><div class="resources__pattern house-pattern house-pattern"></div></div>';
        }
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No resource found.';
    }

    return $output;
}
add_shortcode('ebooks_grid', 'ebooks_grid_shortcode');







/**
 * Short Code for eBooks Grid
 */

function ebooks_guides_grid_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',
        'posts_per_page' => 4, // Number of posts to display
		'order'     => 'asc',
		'orderby' => '', // use 'rand' for random order
    ), $atts);

    // Query arguments
    $args = array(
        'post_type' => 'ebook',
        'posts_per_page' => $atts['posts_per_page'],
        'logo_categories' => $atts['category'],
		'order' => $atts['order'],
		'orderby' => $atts['orderby'],
		// remove the comment below to hide articles from featured category
    	// 'category__not_in'   => array(100),
    );

    // Query the articles posts
    $query = new WP_Query($args);

    // Output the posts
    $output = '';

    // Check if there are posts
    if ($query->have_posts()) {
    $output .= '<div class="isotope_wrapper hall-resource-items ebooks-guides">';
    $output .= '<div class="grid">';
        while ($query->have_posts()) {
            $query->the_post();
			// Retrieve the image field value
    		$featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
			$featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			// Check if there is a featured image
			if (empty($featured_image_url)) {
				// Use default image if no featured image is set
				$featured_image_url = 'https://www.therealestatecpa.com/wp-content/uploads/2024/05/HALL-logo_white-1.png';
				// You can set default width and height if needed
				$featured_image_width = 579;
				$featured_image_height = 390;
			} else {
				// Get width and height from the actual featured image
				$featured_image_width = $featured_image_src[1]; // Width is at index 1
				$featured_image_height = $featured_image_src[2]; // Height is at index 2
			}
			
			// Retrieve custom fields value
    		$resource_link = get_field('resource_link');
			
            $output .= '<article class="post post-item isotope-item clearfix"><div class="image_frame post-photo-wrapper scale-with-grid images_only"><div class="image_wrapper"><a href="' . $resource_link . '" target="_blank"><img width="' . $featured_image_width . '" height="' . $featured_image_height . '" src="' . $featured_image_url . '" class="scale-with-grid wp-post-image" alt="' . get_the_title() . '" decoding="async"></a></div></div><div class="post-desc-wrapper bg- has-custom-bg"><div class="post-desc"><div class="post-title"><h4 class="entry-title"><a href="' . $resource_link . '" target="_blank">' . get_the_title() . '</a></h4></div><div class="post-footer"><div class="post-links"><a href="' . $resource_link . '" class="post-more" target="_blank"><i class="icon-googleplay" aria-hidden="true"></i><span class="blog-only-button">View The Resource </span></a></div></div></div></div></article>';
        }
    $output .= '</div>';
    $output .= '</div>';
        wp_reset_postdata(); // Reset post data after the loop
    } else {
        $output = 'No articles found.';
    }

    return $output;
}
add_shortcode('ebooks_guides_grid', 'ebooks_guides_grid_shortcode');








// new ajaxed numberd pagination
// AJAX handler for numbered pagination
add_action('wp_ajax_my_ajax_pagination', 'my_ajax_pagination');
add_action('wp_ajax_nopriv_my_ajax_pagination', 'my_ajax_pagination');

function my_ajax_pagination() {
    $paged     = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $instance  = isset($_POST['instance']) ? sanitize_text_field($_POST['instance']) : 'default';
    $term      = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';

    $query_args = [
				'post_type'           => 'podcasts',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'posts_per_page'      => 6,
				'no_found_rows'       => false,
				'paged'               => $paged,
				'orderby'             => 'date',  // order by post date
				'order'               => 'DESC',  // latest posts first
		   ];

    // Add taxonomy filter if term is provided
    if ($term) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $term,
            ],
        ];
    }

    $pod_query = new WP_Query($query_args);

    $attr = [
        'echo'           => true,
        'featured_image' => false,
        'more'           => true,
    ];

    if ( function_exists('mfn_opts_get') && mfn_opts_get('blog-images') ) {
        $attr['featured_image'] = 'image';
    }

    ob_start();
        echo mfn_content_post($pod_query, false, $attr);
        $html = ob_get_clean();

    ob_start();
        my_ajax_pagination_links($pod_query, $paged, $instance);
        $pagination = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success([
        'html'       => $html,
        'pagination' => $pagination,
        'instance'   => $instance,
    ]);
}


// Numbered pagination generator
function my_ajax_pagination_links($query, $paged, $instance = 'default') {
    $total_pages = $query->max_num_pages;
    if ($total_pages <= 1) return;

    // Get an array of page links
    $pages = paginate_links([
        'total'     => $total_pages,
        'current'   => $paged,
        'type'      => 'array',
        'prev_text' => '',
        'next_text' => '',
        'mid_size'  => 2,
        'end_size'  => 1,
    ]);

    if ($pages) {
        echo '<div class="column one pager_wrapper">';
            echo '<div class="pager">';
                echo '<div class="pages">';
                    foreach ($pages as $page_link) {
                        // Add custom classes and active state
                        if (strpos($page_link, 'current') !== false) {
                            $page_link = str_replace('current', 'page active', $page_link);
                        } else {
                            $page_link = str_replace('page-numbers', 'page', $page_link);
                        }

                        echo $page_link;
                    }
                echo '</div>'; // .pages

                // Next page link
                $next_page = get_next_posts_page_link($total_pages);
                if ($paged < $total_pages && $next_page) {
                    echo '<a rel="next" class="next_page" href="' . esc_url($next_page) . '">';
                    echo 'Next page<i class="icon-right-open" aria-hidden="true"></i>';
                    echo '</a>';
                }

            echo '</div>'; // .pager
        echo '</div>'; // .pager_wrapper
    }
}



add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script( 'my-ajax-pagination', get_stylesheet_directory_uri() . '/js/ajax-pagination.js?v='.time(), ['jquery'], null, true );
    wp_localize_script( 'my-ajax-pagination', 'myAjax', [
        'url' => admin_url( 'admin-ajax.php' )
    ] );
});