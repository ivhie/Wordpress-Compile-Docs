
/*Pre requesite plugin is Breadcrumb-NavXT - Please install this plugin so that this function will work*/
add_shortcode('bread-crumb-naxtnav', 'breadcrumbDisplay');
function breadcrumbDisplay(){
    ob_start();
    //bcn_display();
    bcn_display($return = false, $linked = true, $reverse = false, $force = false);
    //ob_end_clean(); // avoid buffering
    //return $html;
    $output_string = ob_get_contents();
    ob_end_clean();

    return $output_string;
}
