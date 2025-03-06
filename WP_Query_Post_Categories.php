/* Single Post News - Category listing */
add_shortcode('single-post-cat-listing', 'postCategoryListing');
function postCategoryListing($atts){ 
   
    ob_start();

    $categories = get_categories( array(
        'hide_empty' => false,
        'exclude' => '1', // exclude default category
        'orderby' => 'name',
        'order'   => 'ASC',


    ) );
  
    $html = '';
    $html .= '<h3 class="cat_header">Browse By Category</h3>';
    $html .= '<ul class="news_cat_listing">';
        $html .= '<li><a href="'.site_url("/news-updates").'">All Posts</a></li>';
    foreach ($categories as $category) {
        //echo '<br/>'.$category->name;
        $html .= '<li><a href="'.site_url("/news-updates/?cat_ID=".$category->cat_ID."").'">'.$category->name.'</a></li>';
    }
    $html .= '</ul>';
   
    ob_end_clean(); // avoid buffering
	return $html;

}
