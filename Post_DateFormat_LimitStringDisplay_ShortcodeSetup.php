//$post->post_date - Date parameter
function getNewsDateFormat($data) {
    // Febraury 25, 2025 format
    $getdate = explode(' ', $data);
    $getdate = date_create($getdate[0]);
    $getdate =  date_format($getdate,"F d, Y");
    return  $getdate;

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

function readymadeshortcode($atts){ 
    ob_start();
    ob_end_clean(); // avoid buffering
    $html = '';

	return $html;

}
