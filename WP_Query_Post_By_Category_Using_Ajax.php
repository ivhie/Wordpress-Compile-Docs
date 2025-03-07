/* AJAX Script */
   //get admin ajax url from this variable
    //var ajax_url = ctPublicFunctions['_ajax_url'];

    // onchange select option category
    jQuery('#category-search-form select').on('change', function() {


        var cat_id = this.value // get option value
        var paged =  1; 
        var posts_per_page = jQuery('#listing_news_wrapp').attr('posts_per_page'); 
        var tot_number_post = 0;
       
        //get post count
        jQuery.ajax({
            url: admin_ajax_object.ajax_url, // I add this tru localize
            type: 'POST',
            //dataType: "json",
            data: {
                action : 'getNewsCountByCatID',
                cat_id: cat_id,
            },
            success: function(data) {

                const obj = JSON.parse(data);
                console.log(obj.found_posts);
                tot_number_post = obj.found_posts; // set total number of posts
               
                  
            }
        });   

        
        jQuery.ajax({
                url: admin_ajax_object.ajax_url, // I add this tru localize
                type: 'POST',
                dataType: 'html',
                data: {
                    action : 'news_load_more_content',
                    paged: paged,
                    posts_per_page: posts_per_page,
                    cat_id: cat_id,
                },
                beforeSend : function ( xhr ) {
                
                },
                success: function(data) {
                        // append html new wrap
                        
                        const obj = JSON.parse(data);
                        console.log(obj.num_post);
                        //alert(posts_per_page);
                        jQuery("div.listing_news_wrapp").html(obj.html);
                        //check if total number of post is lesser than current post display
                        count_post_displayed =  parseInt(posts_per_page) *  paged;
                        //alert(tot_number_post);
                        //alert(count_post_displayed);
                        if(  parseInt(tot_number_post) < count_post_displayed ){ 
                            jQuery('#loadmore').css('display','none') //hide load more button;
                        } else {
                            jQuery('#loadmore').css('display','inline') //show load more button;
                            jQuery('#loadmore').attr(  'page', paged ); // set page always to default 1
                            jQuery('#loadmore').attr(  'tot_number_post', tot_number_post ); // set total number of post
                        }
                      
                }

                //jQuery('#loadmore').attr(  'page', paged );
        });   


    });


    // onclick readmore button script
    jQuery("#loadmore").on("click", function(e) {
  
            e.preventDefault();
            var btn = jQuery(this);
            btn.text( 'Loading...' ); // change the button text, you can also add a preloader image
            var paged = parseInt(btn.attr('page')) + 1; 
            var posts_per_page = jQuery('#listing_news_wrapp').attr('posts_per_page'); 
            var tot_number_post = btn.attr('tot_number_post')
            
            jQuery.ajax({
                url: admin_ajax_object.ajax_url, // I add this tru localize
                type: 'POST',
                dataType: 'html',
                data: {
                    action : 'news_load_more_content',
                    paged: paged,
                    posts_per_page: posts_per_page
                },
                beforeSend : function ( xhr ) {
                
                },
                success: function(data) {
                        // append html new wrap
                        const obj = JSON.parse(data);
                        jQuery("div.listing_news_wrapp").append(obj.html);
                        btn.text('Load More')
                        //check if total number of post is lesser than current post display
                        count_post_displayed =  parseInt(posts_per_page) *  paged;
                        if(  parseInt(tot_number_post) < count_post_displayed ){ 
                            btn.css('display','none') //remove load more button;
                        }
                        btn.attr(  'page', paged );
                }
            });   

    });

    

});

/* AJAX Script */


add_action( 'wp_enqueue_scripts', 'child_register_js');
/**
 * Register custom JS
 */
function child_register_js() {
	wp_enqueue_script( 'twentytwenty-fontawesome-script', 'https://kit.fontawesome.com/a076d05399.js', array('jquery'), '', true );
    //wp_enqueue_script( 'twentytwenty-custom-js-script', get_stylesheet_directory_uri() . '/customjs/script.js', array('jquery'), '1.', true );
    wp_enqueue_script( 'news-post-js-script', get_stylesheet_directory_uri() . '/customjs/newsposts.js', array('jquery'), '1.', true );
    wp_localize_script( 'news-post-js-script', 'admin_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	
}

/* Display Search News Section */
add_shortcode('latest-news-search-form', 'latest_news_search_form');
function latest_news_search_form($atts){ 

    ob_start();

    $categories = get_categories( array(
        'hide_empty' => false,
        'exclude' => '1', // exclude default category
        'orderby' => 'name',
        'order'   => 'ASC',
    ) );

     
    $html = '<div class="news_search_wrap">';
     $html .= '<form method="post" action="" id="category-search-form">';
        $html .= '<label>Browser by category</label>';
        $html .= '<select class="cat_name">';
            $html .= '<option value="0">All Posts</option>';
            foreach ($categories as $category) {
                $html .= '<option value="'.$category->cat_ID.'">'.$category->name.'</option>';
            }
        $html .= '</select>';
     $html .= '</form>';
    $html .= '</div>';

    ob_end_clean(); // avoid buffering
 

	return $html;

}

// display all listing news post
add_shortcode('all-listing-news-blogs', 'alllatest_listing_blogs');
function alllatest_listing_blogs($atts){ 
    global $post;
    ob_start();
    $posts_per_page =  get_post_meta($post->ID, 'Display number of news', TRUE); // get custom post data
    $posts_per_page = isset($posts_per_page)?$posts_per_page:'-1';
    $paged = isset($_POST['paged'])?$_POST['paged'] : 1;
    $count_post_displayed = ( $posts_per_page * $paged );

    $html  = '';
     // The Query
        $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page'=>$posts_per_page,
                'order'=>'DESC',
                'paged' => $paged,
                //'orderby'=>'ID',
        );


    $the_query = new WP_Query( $args );
    
    $found_posts = ( $the_query->found_posts ) ? $the_query->found_posts : 0;
    
    //var_dump($the_query->found_posts);

    $posts = $the_query->posts;
    //$html .=$args['cat'];
    $html .= '<div id="listing_news_wrapp" class="listing_news_wrapp" posts_per_page="'.$posts_per_page.'">';
     foreach ( $posts as $news ) {
        $post_thumbnail_id = get_post_meta( $news->ID, '_thumbnail_id', true );
        $html .= '<div class="news_item item-'.$news->ID.'">';
           $html .= '<a  class="news_thumblink" href="'.get_permalink($news->ID).'"><div class="elementor-post__thumbnail elementor-fit-height zoom-in">'.get_the_post_thumbnail( $news->ID, $post_thumbnail_id ).'</div></a>';
            $html .= '<div class="title"><h3><a href="'.get_permalink($news->ID).'">'.limitStringDisplay($news->post_title,35).'</a></h3></div>';
            $html .= '<div class="date">'.getNewsDateFormat($news->post_date).'</div>'; // Febraury 25, 2025 format
            $html .= '<div class="except">'.limitStringDisplay($news->post_excerpt,170).'</div>';
            $html .= '<div class="continue"><a href="'.get_permalink($news->ID).'" />Continue Reading <i class="fas fa-arrow-right"></i></a></div>';       
        $html .= '</div>';
     }
     $html .= '</div>';
  
     if(  $found_posts > $count_post_displayed ){
        //$paged = $paged + 1;//increment next page
        $html .= '<div class="loadmore-section"><a href="#" cat_id= ""  tot_number_post="'.$found_posts.'" id="loadmore" page="'.$paged.'" class="lmore">Load More</a></div>';
     }

    ob_end_clean(); // avoid buffering
	return $html;
}

// ajax loadmore content
add_action( 'wp_ajax_nopriv_news_load_more_content', 'news_load_more_content' ); // for logout user only
add_action( 'wp_ajax_news_load_more_content', 'news_load_more_content' );
//add_action( 'wp_ajax_filter_news_load_more_content', 'news_load_more_content' );
function news_load_more_content(){ 
    
    $paged = isset($_POST['paged'])?$_POST['paged'] : 1;
    $posts_per_page = isset($_POST['posts_per_page'])?$_POST['posts_per_page'] : 1;
    $cat_id = isset($_POST['cat_id'])?$_POST['cat_id'] : 0;
   
    $html  = '';
     // The Query
    $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page'=>$posts_per_page,
            'order'=>'DESC',
            'paged' => $paged,
    );
    
    if(!empty($cat_id)){ // check if cat is exist or has cat
        $args['cat'] = $cat_id;
    }

    $the_query = new WP_Query( $args );

    $posts = $the_query->posts;
    
     if($posts) {
        foreach ( $posts as $news ) {
            $post_thumbnail_id = get_post_meta( $news->ID, '_thumbnail_id', true );
            $html .= '<div class="news_item item-'.$news->ID.'">';
            $html .= '<a  class="news_thumblink" href="'.get_permalink($news->ID).'"><div class="elementor-post__thumbnail elementor-fit-height zoom-in">'.get_the_post_thumbnail( $news->ID, $post_thumbnail_id ).'</div></a>';
                $html .= '<div class="title"><h3><a href="'.get_permalink($news->ID).'">'.limitStringDisplay($news->post_title,35).'</a></h3></div>';
                $html .= '<div class="date">'.getNewsDateFormat($news->post_date).'</div>'; // Febraury 25, 2025 format
                $html .= '<div class="except">'.limitStringDisplay($news->post_excerpt,170).'</div>';
                $html .= '<div class="continue"><a href="'.get_permalink($news->ID).'" />Continue Reading <i class="fas fa-arrow-right"></i></a></div>';       
            $html .= '</div>';
        }

    } else {
          $html = '<h3>No latest news found!</h3>';
    }
     
     echo  json_encode( array('html'=>$html,'num_post'=> $the_query->post_count)  ) ;
     die();
     //echo $html;
     //die();

}

// get post count by category
add_action( 'wp_ajax_nopriv_getNewsCountByCatID', 'getNewsCountByCatID' ); // for logout use only
add_action( 'wp_ajax_getNewsCountByCatID', 'getNewsCountByCatID' ); // for login user
//add_action( 'wp_ajax_filter_getNewsCountByCatID', 'getNewsCountByCatID' );
function getNewsCountByCatID(){
   
    $cat_id = isset($_POST['cat_id'])?$_POST['cat_id'] : 0;
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'order'=>'DESC',
    );
    if(!empty($cat_id)){ // check if cat is exist or has cat
        $args['cat'] = $cat_id;
    }

    $the_query = new WP_Query( $args );
    $found_posts = isset( $the_query->found_posts ) ? $the_query->found_posts :0;
    echo  json_encode( array('found_posts'=>$found_posts)  ) ;
    die();
}

