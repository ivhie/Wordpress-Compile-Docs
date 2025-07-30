/* ----------------------------JS SCRIPT --------------------------------*/

jQuery(document).ready(function (jQuery) {

    /* AJAX Script -  Query by Author ID*/
    jQuery('#filter-search-form .author_name').on('change', function() {

        var author_id = this.value // get option value
        var paged =  1; 
        var posts_per_page = jQuery('#blog_grid_listing').attr('posts_per_page'); 
        var exclude_slug = jQuery('#blog_grid_listing').attr('exclude_slug'); 
        var tot_number_post = 0;
        //alert(author_id);
        //get post count
        jQuery.ajax({
            url: admin_ajax_object.ajax_url, // I add this tru localize
            type: 'POST',
            //dataType: "json",
            data: {
                action : 'getBlogCountByAuthorID',
                author_id: author_id,
                //cat_id: 0,
                exclude_slug: exclude_slug,
            },
            success: function(data) {

                const obj = JSON.parse(data);
                //console.log(obj.found_posts);
                tot_number_post = obj.found_posts; // set total number of posts
                 jQuery('#loadmore').attr( 'author_id', author_id);
                 jQuery('#loadmore').attr( 'cat_id', '');
               
                  
            }
        });   

        
        jQuery.ajax({
                url: admin_ajax_object.ajax_url, // I add this tru localize
                type: 'POST',
                dataType: 'html',
                data: {
                    action : 'blog_listing_load_more_content',
                    paged: paged,
                    posts_per_page: posts_per_page,
                    //cat_id: 0,
                    author_id: author_id,
                    exclude_slug: exclude_slug,
                },
                beforeSend : function ( xhr ) {
                
                },
                success: function(data) {
                        // append html new wrap 
                        const obj = JSON.parse(data);
                        //console.log(obj.num_post);
                        jQuery("div.blog_grid_listing").html(obj.html);
                        count_post_displayed =  parseInt(posts_per_page) *  paged;
                        //alert(tot_number_post);
                       // alert(posts_per_page);
                        if(  parseInt(tot_number_post) < count_post_displayed ){ 
                            jQuery('#loadmore').css('display','none') //hide load more button;
                        } else {
                            jQuery('#loadmore').css('display','inline') //show load more button;
                            jQuery('#loadmore').attr( 'page', paged ); // set page always to default 1
                            jQuery('#loadmore').attr( 'tot_number_post', tot_number_post); // set total number of post
                        }
                      
                }
        });   


    });


    /* AJAX Script -  Query by Category ID*/
    jQuery('#filter-search-form .cat_name').on('change', function() {

        var cat_id = this.value // get option value
        var paged =  1; 
        var posts_per_page = jQuery('#blog_grid_listing').attr('posts_per_page'); 
        var exclude_slug = jQuery('#blog_grid_listing').attr('exclude_slug'); 
        var tot_number_post = 0;
       
        //get post count
        jQuery.ajax({
            url: admin_ajax_object.ajax_url, // I add this tru localize
            type: 'POST',
            //dataType: "json",
            data: {
                action : 'getBlogCountByCatID',
                cat_id: cat_id,
                exclude_slug: exclude_slug,
            },
            success: function(data) {

                const obj = JSON.parse(data);
                //console.log(obj.found_posts);
                tot_number_post = obj.found_posts; // set total number of posts
                 jQuery('#loadmore').attr( 'cat_id', cat_id);
                 jQuery('#loadmore').attr( 'author_id', '');
               
                  
            }
        });   

        
        jQuery.ajax({
                url: admin_ajax_object.ajax_url, // I add this tru localize
                type: 'POST',
                dataType: 'html',
                data: {
                    action : 'blog_listing_load_more_content',
                    paged: paged,
                    posts_per_page: posts_per_page,
                    cat_id: cat_id,
                    exclude_slug: exclude_slug,
                },
                beforeSend : function ( xhr ) {
                
                },
                success: function(data) {
                        // append html new wrap 
                        const obj = JSON.parse(data);
                        //console.log(obj.num_post);
                        jQuery("div.blog_grid_listing").html(obj.html);
                        count_post_displayed =  parseInt(posts_per_page) *  paged;
                        //alert(tot_number_post);
                       // alert(posts_per_page);
                        if(  parseInt(tot_number_post) < count_post_displayed ){ 
                            jQuery('#loadmore').css('display','none') //hide load more button;
                        } else {
                            jQuery('#loadmore').css('display','inline') //show load more button;
                            jQuery('#loadmore').attr( 'page', paged ); // set page always to default 1
                            jQuery('#loadmore').attr( 'tot_number_post', tot_number_post); // set total number of post
                        }
                      
                }
        });   


    });


    // onclick readmore button script
    jQuery("#loadmore").on("click", function(e) {
  
            e.preventDefault();
            var btn = jQuery(this);
            btn.text( 'Loading...' ); // change the button text, you can also add a preloader image
            var paged = parseInt(btn.attr('page')) + 1; 
            var posts_per_page = jQuery('#blog_grid_listing').attr('posts_per_page'); 
            var tot_number_post = btn.attr('tot_number_post');
            var exclude_slug = btn.attr('exclude_slug');
            var cat_id = btn.attr('cat_id');
            var author_id = btn.attr('author_id');
            
           // alert(paged);
            //alert(posts_per_page);
            //alert(tot_number_post);
            //alert(admin_ajax_object.ajax_url);
            jQuery.ajax({
                url: admin_ajax_object.ajax_url, // I add this tru localize
                type: 'POST',
                dataType: 'html',
                data: {
                    action : 'blog_listing_load_more_content',
                    paged: paged,
                    posts_per_page: posts_per_page,
                    exclude_slug: exclude_slug,
                    cat_id: cat_id,
                    author_id: author_id,
                },
                beforeSend : function ( xhr ) {
                   //alert('beforesend');
                },
                success: function(data) {
                        // append html new wrap
                        //alert('apenddd');
                        const obj = JSON.parse(data);
                        jQuery("div.blog_grid_listing").append(obj.html);
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



/* ------------------------------------------------------ PHP Script ----------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'blog_register_js');
/**
 * Register custom JS
 */
function blog_register_js() {
    wp_enqueue_script( 'blog-js-script', get_stylesheet_directory_uri() . '/js/blog-listing.js', array('jquery'), '1.01', true );
    wp_localize_script( 'blog-js-script', 'admin_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

/* Display Search Form of Blog Section */
add_shortcode('blog-search-form', 'blog_search_form');
function blog_search_form($atts){ 

    ob_start();
    $atts = shortcode_atts(array(
        'exclude_slug' => '0',
    ), $atts, 'blog-search-form');
   
    $exclude_slugs = isset($atts['exclude_slug'])?explode(',', $atts['exclude_slug']):'0';
    $exclude_ids = array();
    if($exclude_slugs) {
         foreach ( $exclude_slugs as $slug ) {
            $cat = get_category_by_slug( $slug );
            if ( $cat ) {
                $exclude_ids[] = $cat->term_id;
            }
        }
    }
  


    $categories = get_categories( array(
        'hide_empty' => false,
        'exclude' => $exclude_ids,
        'orderby' => 'name',
        'order'   => 'ASC',
    ) );


    $authors = get_users( array(
        'role__in' => array( 'author', 'editor', 'administrator' ), // roles to include
        'orderby'  => 'display_name',
        'order'    => 'ASC'
    ) );


     
    $html = '<div class="blog_search_wrap">';
     $html .= '<form method="post" action="" class="filter_search_form" id="filter-search-form">';
        $html .= '<label>Filter by</label>';
        $html .= '<select class="cat_name" id="cat-name">';
            $html .= '<option value="0">Category</option>';
            foreach ($categories as $category) {
                $html .= '<option value="'.$category->cat_ID.'">'.$category->name.'</option>';
            }
        $html .= '</select>';
        $html .= '<select class="author_name" id="author-name">';
            $html .= '<option value="0">Author</option>';
            foreach ($authors as $author) {
                $html .= '<option value="'.$author->ID.'">'.$author->display_name.'</option>';
            }
        $html .= '</select>';
     $html .= '</form>';
    $html .= '</div>';

    ob_end_clean(); // avoid buffering
 

	return $html;

}


//Display blogs listing grid
add_shortcode('blog-grid-listing', 'blog_grid_listing');
function blog_grid_listing($atts){ 
    global $post;

    $atts = shortcode_atts(array(
        'post_per_page' => '9',
        'exclude_slug' => '',
    ), $atts, 'blog-grid-listing');

    ob_start();

    
    $exclude_ids = post_get_category_by_slug($atts['exclude_slug']);
    
    $posts_per_page = isset($atts['post_per_page'])?$atts['post_per_page']:'-1';
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
                'category__not_in' => $exclude_ids
        );


    $the_query = new WP_Query( $args );
    
    $found_posts = ( $the_query->found_posts ) ? $the_query->found_posts : 0;
    
    $posts = $the_query->posts;
    $html .= '<div id="blog_grid_listing" class="blog_grid_listing" exclude_slug="'.$atts['exclude_slug'].'" posts_per_page="'.$posts_per_page.'">';
     foreach ( $posts as $blog ) {
        $post_thumbnail_id = get_post_meta( $blog->ID, '_thumbnail_id', true );
        $thumbnail_url = get_the_post_thumbnail_url( $blog->ID, 'full' ); 
        $html .= '<div class="blog_item item-'.$blog->ID.'">';
           $html .= '<a  class="blog_link" href="'.get_permalink($blog->ID).'">
            <div class="elementor-post__thumbnail elementor-fit-height zoom-in" style="background-image:url('.$thumbnail_url.')">'.get_the_post_thumbnail( $blog->ID, $post_thumbnail_id ).'</div>
           </a>';
            $html .= '<div class="title"><h3><a href="'.get_permalink($blog->ID).'">'.limitStringDisplay($blog->post_title,35).'</a></h3></div>';
            $html .= '<div class="except">'.limitStringDisplay($blog->post_excerpt,170).'</div>';
            $html .= '<div class="continue"><a href="'.get_permalink($blog->ID).'" />Read More <i class="fas fa-arrow-right"></i></a></div>';       
        $html .= '</div>';
     }
     $html .= '</div>';
  
     if(  $found_posts > $count_post_displayed ){
        $html .= '<div class="loadmore-section"><a href="#" cat_id="" author_id=""  exclude_slug="'.$atts['exclude_slug'].'" tot_number_post="'.$found_posts.'" id="loadmore" page="'.$paged.'" class="lmore">Load More</a></div>';
     }

    ob_end_clean(); // avoid buffering
	return $html;
}


// get blog post count by category
add_action( 'wp_ajax_nopriv_getBlogCountByCatID', 'getBlogCountByCatID' ); // for logout use only
add_action( 'wp_ajax_getBlogCountByCatID', 'getBlogCountByCatID' ); // for login user
function getBlogCountByCatID(){
   
    $cat_id = isset($_POST['cat_id'])?$_POST['cat_id'] : '';

    $exclude_slug = isset($_POST['exclude_slug'])?$_POST['exclude_slug'] : '';
    $exclude_ids = post_get_category_by_slug($exclude_slug);

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'order'=>'DESC',
    );
    if(!empty($cat_id)){ // check if cat is exist or has cat
        $args['cat'] = $cat_id;
    }
    if(!empty($exclude_ids)){ // check if post is not in category
       $args['category__not_in'] = $exclude_ids;
    }

    $the_query = new WP_Query( $args );
    $found_posts = isset( $the_query->found_posts ) ? $the_query->found_posts :0;
    echo  json_encode( array('found_posts'=>$found_posts)  ) ;
    die();
}


// get blog post count by author id
add_action( 'wp_ajax_nopriv_getBlogCountByAuthorID', 'getBlogCountByAuthorID' ); // for logout use only
add_action( 'wp_ajax_getBlogCountByAuthorID', 'getBlogCountByAuthorID' ); // for login user
function getBlogCountByAuthorID(){
   
    $author_id = isset($_POST['author_id'])?$_POST['author_id'] : 0;

    $exclude_slug = isset($_POST['exclude_slug'])?$_POST['exclude_slug'] : '';
    $exclude_ids = post_get_category_by_slug($exclude_slug);

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'order'=>'DESC',
    );

    if(!empty($author_id)){ // check if cat is exist or has cat
        $args['author'] = $author_id;
    }
    if(!empty($exclude_ids)){ // check if post is not in category
       $args['category__not_in'] = $exclude_ids;
    }

    $the_query = new WP_Query( $args );
    $found_posts = isset( $the_query->found_posts ) ? $the_query->found_posts :0;
    echo  json_encode( array('found_posts'=>$found_posts)  ) ;
    die();
}


// blog listing ajax loadmore content
add_action( 'wp_ajax_nopriv_blog_listing_load_more_content', 'blog_listing_load_more_content' ); // for logout user only
add_action( 'wp_ajax_blog_listing_load_more_content', 'blog_listing_load_more_content' );
function blog_listing_load_more_content(){ 
    
    $paged = isset($_POST['paged'])?$_POST['paged'] : 1;
    $posts_per_page = isset($_POST['posts_per_page'])?$_POST['posts_per_page'] : 9;
    $cat_id = isset($_POST['cat_id'])?$_POST['cat_id'] : 0;
    $author_id = isset($_POST['author_id'])?$_POST['author_id'] : '';
    $exclude_slug = isset($_POST['exclude_slug'])?$_POST['exclude_slug'] : '';
    $exclude_ids = post_get_category_by_slug($exclude_slug);
    $test = '';
    $html  = '';
     // The Query
    $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page'=>$posts_per_page,
            'order'=>'DESC',
            'paged' => $paged,
           // 'category__not_in' => $exclude_ids
    );
    
     if(!empty($author_id)){ // check if this post is in the author id
        $args['author'] = $author_id;
        //$test = 'test-author';
    }

    if(!empty($cat_id)){ // check if cat is exist or has cat
        $args['cat'] = $cat_id;
        //$test = 'test-cat';
    }

    if(!empty($exclude_ids)){ // check if post is not in category
       $args['category__not_in'] = $exclude_ids;
    }

    $the_query = new WP_Query( $args );

    $blogs = $the_query->posts;
    
     if($blogs) {
        foreach ( $blogs as $blog ) {

                $post_thumbnail_id = get_post_meta( $blog->ID, '_thumbnail_id', true );
                $thumbnail_url = get_the_post_thumbnail_url( $blog->ID, 'full' ); 
                $html .= '<div class="blog_item item-'.$blog->ID.'">';
                $html .= '<a  class="blog_link" href="'.get_permalink($blog->ID).'">
                    <div class="elementor-post__thumbnail elementor-fit-height zoom-in" style="background-image:url('.$thumbnail_url.')">'.get_the_post_thumbnail( $blog->ID, $post_thumbnail_id ).'</div>
                </a>';
                    $html .= '<div class="title"><h3> '.$test.' <a href="'.get_permalink($blog->ID).'">'.limitStringDisplay($blog->post_title,35).'</a></h3></div>';
                    $html .= '<div class="except">'.limitStringDisplay($blog->post_excerpt,170).'</div>';
                    $html .= '<div class="continue"><a href="'.get_permalink($blog->ID).'" />Read More <i class="fas fa-arrow-right"></i></a></div>';       
                $html .= '</div>';
            
                //$html .= 'testerererer';
        }

    } else {
          $html .= '<h3>No latest blog found!</h3>';
    }
     
     echo  json_encode( array('html'=>$html,'num_post'=> $the_query->post_count)  ) ;
     die();
}



//Related blog listing 
add_shortcode('related-resouces-listing', 'related_resouces_listing');
function related_resouces_listing($atts){ 
    global $post;

    $atts = shortcode_atts(array(
        'post_per_page' => '3',
        'category_slug' => 'blog',
    ), $atts, 'related-resouces-listing');

    ob_start();
    $posts_per_page = isset($atts['post_per_page'])?$atts['post_per_page']:'-1';
    $category_slug = isset($atts['category_slug'])?$atts['category_slug']:'blog';
    $html  = '';
     // The Query
        $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page'=>$posts_per_page,
                'order'=>'DESC',
                'category_name' => $category_slug,
        );


    $the_query = new WP_Query( $args );
    
    $found_posts = ( $the_query->found_posts ) ? $the_query->found_posts : 0;
    
    $posts = $the_query->posts;
    $html .= '<div id="related_resources_listing" class="related_resources_listing" posts_per_page="'.$posts_per_page.'">';
     foreach ( $posts as $blog ) {
        $post_thumbnail_id = get_post_meta( $blog->ID, '_thumbnail_id', true );
        $thumbnail_url = get_the_post_thumbnail_url( $blog->ID, 'full' ); 
        $html .= '<div class="blog_item item-'.$blog->ID.'">';
           $html .= '<a  class="blog_link" href="'.get_permalink($blog->ID).'">
            <div class="elementor-post__thumbnail elementor-fit-height zoom-in" style="background-image:url('.$thumbnail_url.')">'.get_the_post_thumbnail( $blog->ID, $post_thumbnail_id ).'</div>
           </a>';
            $html .= '<div class="title"><h3><a href="'.get_permalink($blog->ID).'">'.limitStringDisplay($blog->post_title,35).'</a></h3></div>';
            $html .= '<div class="except">'.limitStringDisplay($blog->post_excerpt,170).'</div>';
            $html .= '<div class="continue"><a href="'.get_permalink($blog->ID).'" />Read More <i class="fas fa-arrow-right"></i></a></div>';       
        $html .= '</div>';
     }
     $html .= '</div>';
  
    ob_end_clean(); // avoid buffering
	return $html;
}

add_shortcode('blog-date', 'getPostDate');
function getPostDate(){
    global $post;
      ob_start();
       $post_date = get_the_date( 'm/d/y', $post->ID );
       $html = '';
       $html .= $post_date;
      ob_end_clean(); // avoid buffering
	  return $html;

}

function limitStringDisplay($string, $limit){
    if(strlen($string) >= $limit) {
         $limitedString =  substr($string, 0, $limit).'...';

    } else {
        $limitedString = $string;
    }

   // return $limitedString .'-'.strlen($string);
   return $limitedString;
}

function post_get_category_by_slug($exclude_slugs){

        $exclude_slugs = isset($exclude_slugs)?explode(',', $exclude_slugs):'0';
        $exclude_ids = array();
        if($exclude_slugs) {
            foreach ( $exclude_slugs as $slug ) {
                $cat = get_category_by_slug( $slug );
                if ( $cat ) {
                    $exclude_ids[] = $cat->term_id;
                }
            }
        } else {
            $exclude_ids = 0;
        }

        return $exclude_ids;
}


/* ------------------------------- STYLE --------------------------------*/
/* Filter Blog Form */
.filter_search_form { display:flex; column-gap: 20px; } 
.filter_search_form label {
	color: #75C043;
	text-align: center;
	font-size: 14px;
	font-style: normal;
	font-weight: 600;
	line-height: normal;
	align-content: center;
}
.filter_search_form .cat_name,
.filter_search_form .author_name {
    border: 1px solid #F6F6F6;
	background: #FFF;
	box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.15);
	display: flex;
	width: 237px;
	height: 40px;
	padding: 10px 20px;
	justify-content: space-between;
	align-items: center;
	color: #BABABA;
	text-align: left;
	font-size: 14px;
	font-style: normal;
	font-weight: 600;
	line-height: normal;
}



/* Blog Grid Listing */
.blog_grid_listing {
      display: grid;
	  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); /* Responsive */
	  gap: 33px; /* Spacing between items */
	  padding: 10px;
	  margin-bottom:37px;
}
.blog_grid_listing  .blog_item { 
	border-radius: 18px;
	border: 1px solid #FFF;
	background: #FFF;
	box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);
	overflow:hidden;
	padding-bottom: 20px;
}

.blog_grid_listing  .blog_item .elementor-post__thumbnail { 
	width: 100%; 
	background-size:cover;
	background-repeat:no-repeat;
	background-position:center;
	display: block;
	height:195px;

}
.blog_grid_listing  .blog_item .elementor-post__thumbnail img {opacity:0; width: 100%;}
.blog_grid_listing  .blog_item  .title,
.blog_grid_listing  .blog_item  .except,
.blog_grid_listing  .blog_item .continue { padding-left: 25px; padding-right:25px;}

.blog_grid_listing  .blog_item  .title h3  {
    color: #333;
	font-size: 20px;
	font-style: normal;
	font-weight: 600;
	line-height: 125%; /* 25px */
	margin-bottom: 10px;
    margin-top: 22px;
}
.blog_grid_listing  .blog_item  .title h3  a {  color: #333; } 

.blog_grid_listing  .blog_item  .except {
	color: #000;
	font-size: 16px;
	font-style: normal;
	font-weight: 400;
	line-height: 125%; /* 20px */
	margin-bottom:10px;
} 

.blog_grid_listing  .blog_item .continue a {
	color: #75C043;
	font-family: "Montserrat", Sans-serif !important;
	font-size: 16px;
	font-style: normal;
	font-weight: 700;
	line-height: 125%; /* 20px */
	background-image:url('/wp-content/uploads/2025/07/Vector-7.png');
	background-repeat:no-repeat;
	background-position: center right;
	padding-right:35px; 

}


/*Related Resource Listing */

.related_resources_listing {
      display: grid;
	  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); /* Responsive */
	  gap: 33px; /* Spacing between items */
	  margin-bottom:37px;
}
.related_resources_listing  .blog_item { 

	border: 1px solid #FFF;
	background: #FFF;
	overflow:hidden;
	padding-bottom: 20px;
}

.related_resources_listing  .blog_item .elementor-post__thumbnail { 
	width: 100%; 
	background-size:cover;
	background-repeat:no-repeat;
	background-position:center;
	display: block;
	border-radius: 20px;
	overflow:hidden;
	height:245px;

}
.related_resources_listing  .blog_item .elementor-post__thumbnail img {opacity:0; width: 100%;}

.related_resources_listing  .blog_item  .title h3  {
    color: #333;
	font-size: 24px;
	font-style: normal;
	font-weight: 600;
	line-height: 125%; /* 25px */
	margin-bottom: 10px;
    margin-top: 22px;
}
.related_resources_listing  .blog_item  .title h3  a {  color: #333; } 

.related_resources_listing  .blog_item  .except {
	color: #000;
	font-size: 16px;
	font-style: normal;
	font-weight: 400;
	line-height: 1.5em;
	margin-bottom:10px;
} 

.related_resources_listing  .blog_item .continue a {
	color: #75C043;
	font-family: "Montserrat", Sans-serif !important;
	font-size: 16px;
	font-style: normal;
	font-weight: 700;
	line-height: 125%; /* 20px */
	background-image:url('/wp-content/uploads/2025/07/Vector-7.png');
	background-repeat:no-repeat;
	background-position: center right;
	padding-right:35px; 

}


@media only screen and (max-width: 743px) {
   .related_resources_listing,
   .blog_grid_listing {
        display: flex;
		flex-wrap: wrap;
		justify-content: center;
   }

}

@media only screen and (max-width: 460px) {
	.filter_search_form {
		display: grid;
    	row-gap: 15px;
	}
}



