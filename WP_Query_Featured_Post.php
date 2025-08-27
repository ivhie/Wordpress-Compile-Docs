
------------ QUERY SINGLE FEATURED CASE STUDY / OR ANY POST TYPE
// display top banner latest featured case study
add_shortcode('featured-casestudy', 'featured_casestudy');
function featured_casestudy($atts){ 
    ob_start();
    // avoid buffering
    // The Query
        $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page'=>1,
                'order'=>'DESC',
                'category_name' => 'case-studies',
                'meta_query' => array(
                        array(
                        'key' => 'is_featured_case_studies',
                        'value' => '1',
                        ),
                    ), // I used true false option instead of checkbox field

        );

        $atts = shortcode_atts(array(
            'display' => '',
        ), $atts, 'featured-casestudy');



        $the_query = new WP_Query( $args );
        $posts = $the_query->posts;

        $display = esc_html($atts['display']);
        $html = '';

        if($display == 'post_title'){ //[featured-casestudy display="post_title"]

            $html = $posts[0]->post_title;

        } else if ($display == 'post_excerpt' ) { //[featured-casestudy display="post_excerpt"]

            $html = limitStringDisplay($posts[0]->post_excerpt,170);

        }  else if ($display == 'permalink' ) { //[featured-casestudy display="permalink"]

            $html = get_permalink($posts[0]->ID);

        }  else if ($display == 'post-thumbnail' ) { //[featured-casestudy display="post-thumbnail"]
            
            $html = get_the_post_thumbnail_url( $posts[0]->ID , 'post-thumbnail' );

        } else if ($display == 'testimonial_message' ) { //[featured-casestudy display="testimonial_message"]

            $html = get_post_meta($posts[0]->ID,'testimonial_message',true);

        }  else if ($display == 'customer_name_company_name' ) { //[featured-casestudy display="customer_name_company_name"]

            $html = get_post_meta($posts[0]->ID,'customer_name_company_name',true);
        }
   
   ob_end_clean(); // avoid buffering
	return $html;
}





//How to query post by feature post

// display top banner latest featured post
add_shortcode('latest-news-banner', 'latest_news_banner');
function latest_news_banner($atts){ 
    ob_start();
    // avoid buffering
    // The Query
        $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page'=>1,
                'order'=>'DESC',
                'meta_query' => array(
                        array(
                        'key' => 'set_as_featured',
                        'value' => '1',
                        ),
                 ), // I used true false option instead of checkbox field

        );

    $the_query = new WP_Query( $args );
    $posts = $the_query->posts;
   
    $set_as_featured = get_post_meta($posts[0]->ID,'set_as_featured',true);
    $html = '<div class="banner_top_latest_news">';
        $html .= '<div class="title"><h1><a href="'.get_permalink($posts[0]->ID).'" />'.limitStringDisplay($posts[0]->post_title,60).'</a></h1></div>';
        $html .= '<div class="date">'.getNewsDateFormat($posts[0]->post_date).'</div>'; // Febraury 25, 2025 format
        $html .= '<div class="except">'.limitStringDisplay($posts[0]->post_excerpt,170).'</div>';
        $html .= '<div class="continue"><a href="'.get_permalink($posts[0]->ID).'" />Continue Reading <i class="fas fa-arrow-right"></i></a></div>';       
    $html .= '</div>';
   ob_end_clean(); // avoid buffering
	return $html;
}

// display top banner latest featured image
add_shortcode('latest-news-banner-image', 'latest_news_banner_image');
function latest_news_banner_image($atts){ 
    ob_start();

     // The Query
         $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page'=>1,
                 'order'=>'DESC',
                 'meta_query' => array(
                        array(
                        'key' => 'set_as_featured',
                        'value' => '1',
                        ),
                 ), // I used true false option instead of checkbox field


                //'orderby'=>'ID',
        );

    $the_query = new WP_Query( $args );


    $posts = $the_query->posts;
    $url = get_the_post_thumbnail_url( $posts[0]->ID , 'post-thumbnail' );
    //$set_as_featured = get_post_meta($posts[0]->ID,'set_as_featured',true);
    $html = '<div class="news-img zoom-in">';
        $html .= '<a href="'.get_permalink($posts[0]->ID).'" /><img src="'.$url.'" alt="'.$posts[0]->post_title.'" title="'.$posts[0]->post_title.'" /></a>';
    $html .= '</div>';
   ob_end_clean(); // avoid buffering
	return $html;

}
