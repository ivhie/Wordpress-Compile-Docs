

/* Shortcode parameter tag should be lower case to make it work*/
function my_custom_shortcode($atts) {
    // Set default values and extract shortcode attributes
    $atts = shortcode_atts(array(
        'name' => 'Guest',
        'age' => 'unknown'
    ), $atts, 'custom_shortcode');

    return "Hello, my name is " . esc_html($atts['name']) . " and I am " . esc_html($atts['age']) . " years old.";
}
add_shortcode('custom_shortcode', 'my_custom_shortcode');

[custom_shortcode name="John Doe" age="30"]
