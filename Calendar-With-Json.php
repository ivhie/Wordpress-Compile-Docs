add_shortcode('json-events-lists','json_event_list');
function json_event_list($atts){

	ob_start();

	$args = array(
			'post_type' => 'our-event',
			'post_status' => 'publish',
			'posts_per_page'=>3,
			'order'=>'ASC',
			'meta_key' => 'ev_date_from', // The custom field you want to order by
   			'orderby' => 'ev_date_from', // Use meta_value or meta_value_num depending on the data type


	);

	$default_image_url = '/wp-content/uploads/2024/11/657c148e12781c967d4cd9390b592a69.jpg';
	$the_query = new WP_Query( $args );
    $posts = $the_query->posts;
   
	$month = array(
		'01'=>'Jan',
		'02'=>'Feb',
		'03'=>'Mar',
		'04'=>'Apr',
		'05'=>'May',
		'06'=>'Jun',
		'07'=>'Jul',
		'08'=>'Aug',
		'09'=>'Sep',
		'10'=>'Oct',
		'11'=>'Nov',
		'12'=>'Dec',
	);

	$html .= '<div class="row">';
	if($posts){
         foreach($posts as $post) {
           
			$ev_code = get_field( "ev_code", $post->ID );
			$ev_description = get_field( "ev_description", $post->ID );
			$ev_date_from = get_field( "ev_date_from", $post->ID );
			$ev_date_end = get_field( "ev_date_end", $post->ID );
			$ev_time_start = get_field( "ev_time_start", $post->ID );
			$ev_time_end = get_field( "ev_time_end", $post->ID );

			$startDate = explode('-',$ev_date_from);
			$endDate = explode('-',$ev_date_end);
            //$html .= $ev_date_from .'<br/>';
			//$html .= $ev_date_end;

			if ( $ev_date_from == $ev_date_end || $ev_date_end == ''  ) {

				$schedule = $month[$startDate[1]] .' '.$startDate[2];

			} else  if($startDate[1] == $endDate[1]) {
				$schedule = $month[$startDate[1]] .' '.$startDate[2];
				if($ev_date_end){
                    $schedule = $month[$startDate[1]] .' '.$startDate[2].' - '.$endDate[2];
				}
			} else {
				if($startDate[1] != $endDate[1]) {
					$schedule = $month[$startDate[1]] .' '.$startDate[2].' - '.$month[$endDate[1]] .' '.$endDate[2];
				} else {
					$schedule = $month[$startDate[1]] .' '.$startDate[2].' - '.$endDate[2];
				}
			
			}

			$html .= '<div class="col-lg-4 col-md-6 px-3 mb-4">';
				$html .= '<div class="eventlist-block">';
						$html .= '<div class="eventlist-block-img">';
							$html .= '<img src="'.$default_image_url.'">';
						$html .= '</div>';
						$html .= '<div class="event_title event-list-content">';
							$html .= '<h3>' .$ev_code . '</h3>';
							$html .= '<h2><a href="#" eventid="'.$ev_code.'" class="e-open-popup">' .$post->post_title . '</a></h2>';
								$html .= '<div class="custom-event-date">';
									$html .= limitStringDisplay($ev_description,25) .' | '.$schedule;
								$html .= '</div>';
								//$html .= '<div class="event-btn-block mt-4"> <a href="#"><i class="me-2"><svg class="d-block" xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
								//<path d="M1.5 6.05556H14.5M4.38889 1V2.44444M11.6111 1V2.44444M3.81111 14H12.1889C12.9978 14 13.4024 14 13.7113 13.8426C13.9831 13.7041 14.2041 13.4831 14.3426 13.2113C14.5 12.9024 14.5 12.4978 14.5 11.6889V4.75556C14.5 3.94659 14.5 3.54211 14.3426 3.23313C14.2041 2.96133 13.9831 2.74036 13.7113 2.60188C13.4024 2.44444 12.9978 2.44444 12.1889 2.44444H3.81111C3.00215 2.44444 2.59766 2.44444 2.28868 2.60188C2.01689 2.74036 1.79592 2.96133 1.65744 3.23313C1.5 3.54211 1.5 3.94659 1.5 4.75556V11.6889C1.5 12.4978 1.5 12.9024 1.65744 13.2113C1.79592 13.4831 2.01689 13.7041 2.28868 13.8426C2.59766 14 3.00214 14 3.81111 14Z" stroke="#0C5398" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								//	</svg></i>Add to Calendar</a></div>';
						$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';

		 }

	} else {
		$html .= "No events found";
	}

	$html .= '</div>';

	$html .= eventPopupHtml();
	/*

	$atts = shortcode_atts(array(
        'json_url' => '',
        'default_image_url' => ''
    ), $atts, 'json-events-lists');


	//$url = "https://members.cahf.org/cvapi/eventList";
	//$default_image_url = '/wp-content/uploads/2024/11/657c148e12781c967d4cd9390b592a69.jpg';
	$url  = esc_html($atts['json_url']);
	$default_image_url  = esc_html($atts['default_image_url']);

	$response = file_get_contents($url);
	$month = array(
		'01'=>'Jan',
		'02'=>'Feb',
		'03'=>'Mar',
		'04'=>'Apr',
		'05'=>'May',
		'06'=>'Jun',
		'07'=>'Jul',
		'08'=>'Aug',
		'09'=>'Sep',
		'10'=>'Oct',
		'11'=>'Nov',
		'12'=>'Dec',
	);


	if ($response !== false) {
		$data = json_decode($response, true); // true = associative array
		$html .= '<div class="row">';
		for($j=0;$j<3;$j++){

			
			$startDate = explode('-',$data['rows'][$j]['start']);
			$endDate = explode('-',$data['rows'][$j]['end']);

			//$startDate[0] = Year
			//$startDate[1] = month
			//$startDate[2] = day

			if($startDate[1] == $endDate[1]) {
				$schedule = $month[$startDate[1]] .' '.(int)$startDate[2];
			} else {
				if($startDate[1] != $endDate[1]) {
					$schedule = $month[$startDate[1]] .' '.(int)$startDate[2].' - '.$month[$endDate[1]] .' '.(int)$endDate[2];
				} else {
					$schedule = $month[$startDate[1]] .' '.(int)$startDate[2].'-'.(int)$endDate[2];
				}
			
			}

			$html .= '<div class="col-lg-4 col-md-6 px-3 mb-4">';
				$html .= '<div class="eventlist-block">';
						$html .= '<div class="eventlist-block-img">';
							$html .= '<img src="'.$default_image_url.'">';
						$html .= '</div>';
						$html .= '<div class="event_title event-list-content">';
							$html .= '<h3>' .$data['rows'][$j]['eventcd'] . '</h3>';
							$html .= '<h2>' .$data['rows'][$j]['title'] . '</h2>';
								$html .= '<div class="custom-event-date">';
									$html .= $data['rows'][$j]['description'] .' | '.$schedule;
								$html .= '</div>';
								//$html .= '<div class="event-btn-block mt-4"> <a href="#"><i class="me-2"><svg class="d-block" xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
								//<path d="M1.5 6.05556H14.5M4.38889 1V2.44444M11.6111 1V2.44444M3.81111 14H12.1889C12.9978 14 13.4024 14 13.7113 13.8426C13.9831 13.7041 14.2041 13.4831 14.3426 13.2113C14.5 12.9024 14.5 12.4978 14.5 11.6889V4.75556C14.5 3.94659 14.5 3.54211 14.3426 3.23313C14.2041 2.96133 13.9831 2.74036 13.7113 2.60188C13.4024 2.44444 12.9978 2.44444 12.1889 2.44444H3.81111C3.00215 2.44444 2.59766 2.44444 2.28868 2.60188C2.01689 2.74036 1.79592 2.96133 1.65744 3.23313C1.5 3.54211 1.5 3.94659 1.5 4.75556V11.6889C1.5 12.4978 1.5 12.9024 1.65744 13.2113C1.79592 13.4831 2.01689 13.7041 2.28868 13.8426C2.59766 14 3.00214 14 3.81111 14Z" stroke="#0C5398" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								//	</svg></i>Add to Calendar</a></div>';
						$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';

		}

	   $html .= '</div>';

	} else {
		$html .= "No events found";
	}
	
	*/

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


function eventListNavigation($year = null, $month = null) { // this navigation is for list view event
   
		$html ='<div class="navigation_date_wrapp">';
			$html.='<div class="arrow_nav">';
				$html.='<a title="Previous" href="#" current-year="'.$year.'" current-month="'.$month.'" class="previous-ev-list">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="arcs"><path d="M15 18l-6-6 6-6"></path></svg>
						</a>';
				$html.='<a title="Next" href="#" current-year="'.$year.'" current-month="'.$month.'" class="next-ev-list">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#8899a4" stroke-width="2" stroke-linecap="round" stroke-linejoin="arcs"><path d="M9 18l6-6-6-6"></path></svg>
						</a>';
			$html.='</div>';
			$html.='<div class="today">';
				$html.='<span><a href="#" class="today-ev-list">Today</a></span>';
			$html.='</div>';
		$html.='</div>';

		return  $html;

}

add_shortcode('json-listview-events','json_listview_events');
function json_listview_events($atts){ 
	ob_start();
    $html = '';
	$atts = shortcode_atts(array(
        'json_url' => '',
        'default_image_url' => ''
    ), $atts, 'json-listview-events');

    	//$url = "https://members.cahf.org/cvapi/eventList";
	//$default_image_url = '/wp-content/uploads/2024/11/657c148e12781c967d4cd9390b592a69.jpg';
	$url  = esc_html($atts['json_url']);
	$default_image_url  = esc_html($atts['default_image_url']);

	$response = file_get_contents($url);
	$month = array(
		'01'=>'Jan',
		'02'=>'Feb',
		'03'=>'Mar',
		'04'=>'Apr',
		'05'=>'May',
		'06'=>'Jun',
		'07'=>'Jul',
		'08'=>'Aug',
		'09'=>'Sep',
		'10'=>'Oct',
		'11'=>'Nov',
		'12'=>'Dec',
	);

    $monthName = date('F');
	$current_month = date('n');  //current month
	$year = date('Y'); 

	$html.='<form method="post" action="" id="calendar_filter_form">';
		$html.='<div class="event_form_wrap">';
				$html.='<div class="s_event_name_wrapp">';
					$html.='<input type="text" value="" class="s-input s-event-name" placeholder="Search for events" name="s-event-name">';
					$html.='<svg class="tribe-common-c-svgicon tribe-common-c-svgicon--search tribe-events-c-search__input-control-icon-svg" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.164 10.133L16 14.97 14.969 16l-4.836-4.836a6.225 6.225 0 01-3.875 1.352 6.24 6.24 0 01-4.427-1.832A6.272 6.272 0 010 6.258 6.24 6.24 0 011.831 1.83 6.272 6.272 0 016.258 0c1.67 0 3.235.658 4.426 1.831a6.272 6.272 0 011.832 4.427c0 1.422-.48 2.773-1.352 3.875zM6.258 1.458c-1.28 0-2.49.498-3.396 1.404-1.866 1.867-1.866 4.925 0 6.791a4.774 4.774 0 003.396 1.405c1.28 0 2.489-.498 3.395-1.405 1.867-1.866 1.867-4.924 0-6.79a4.774 4.774 0 00-3.395-1.405z"></path></svg>';
				$html.='</div>';
				/*$html.='<div  class="spacer">&nbsp;</div>';
				$html.='<div class="s_event_location_wrapp">';
					$html.='<input type="text" value="" class="s-input s-event-location" placeholder="In a location" name="s-event-location">';
					$html.='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 14c-3.314 0-6-2.686-6-6s2.686-6 6-6 6 2.686 6 6-2.686 6-6 6z"/></svg>';
				$html.='</div>';*/
				$html.='<div class="s_btn_wrap">';
					$html.='<input type="submit" value="Find Events" name="s-event-submit" class="s-event-submit">';
					$html.='<input type="hidden" value="list" name="s-type" class="s-type">';
				$html.='</div>';
				$html.='<div class="s_list_wrap">';
					$html.='<ul>';
						$html.='<li class="list">';
						$html.='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"/></svg>';
						$html.='<a href="#" class="show_event_list">List</a></li>';
						$html.='<li class="calendar"><a href="#" class="show_calendar_list">Calendar</a></li>';
					$html.='</ul>';
				$html.='</div>';
		$html.='</div>';
	$html.='</form>';



	$html.='<div id="event_wrapp">';

		$html.= eventListNavigation($year,$current_month);

		$html.='<div class="event_selected_date">';
			//$html.='<h3>'.substr($monthName,0,3) . ' '.$year.'</h3><div class="bar">&nbsp;</div>';
			$html.='<h3>'.$year.'</h3><div class="bar">&nbsp;</div>';
		$html.='</div>';
		if ($response !== false) {
			$data = json_decode($response, true); // true = associative array
			$html .= '<div class="row">';
			$count = 0;
			for($j=0;$j<count($data['rows']);$j++){

				if ($count >= 10) { //display only 5 events
					break; 
				}

				$startDate = explode('-',$data['rows'][$j]['start']);
				$endDate = explode('-',$data['rows'][$j]['end']);

				$startDatetms = $data['rows'][$j]['starttms'];
				$endDatetms =   $data['rows'][$j]['endtms'];

				//$startDate[0] = Year
				//$startDate[1] = month
				//$startDate[2] = day
				// Prints October 3, 1975 was on a Friday
				//echo "Oct 3,1975 was on a ".date("l", mktime(0,0,0,10,3,1975)) . "<br>";
				
				// full detail schedule
				if($startDate[1] == $endDate[1]) {
					$schedule = $month[$startDate[1]] .' '.(int)$startDate[2].', @'.$startDatetms .' - '.$endDatetms;
					$day = substr(date("l", mktime(0,0,0,$startDate[1],$startDate[2],$startDate[0])), 0, 3);
					$days = $startDate[2];

				} else {
					if($startDate[1] != $endDate[1]) {
						$schedule = $month[$startDate[1]] .' '.(int)$startDate[2].' - '.$month[$endDate[1]] .' '.(int)$endDate[2].', @'.$startDatetms .' - '.$endDatetms;
						$day = substr(date("l", mktime(0,0,0,$startDate[1],$startDate[2],$startDate[0])), 0, 3)  .' - '. substr(date("l", mktime(0,0,0,$endDate[1],$endDate[2],$endDate[0])), 0, 3);
						$days = $startDate[2].' - '.$endDate[2].'';

					} else {
						$schedule = $month[$startDate[1]] .' '.(int)$startDate[2].'-'.(int)$endDate[2].', @'.$startDatetms .' - '.$endDatetms;
						$day = substr(date("l", mktime(0,0,0,$startDate[1],$startDate[2],$startDate[0])), 0, 3) .' - '.substr(date("l", mktime(0,0,0,$endDate[1],$endDate[2],$endDate[0])), 0, 3);
						$days = $startDate[2].' - ' .$endDate[2];
					}
				
				}
					
				$html .= '<div class="event_list_row">';
					$html .= '<div class="e_date">';
							$html .= '<span class="day">'.$day.'</span>';
							$html .= '<span class="day_1">'.$days.'</span>';
					$html .= '</div>';
					$html .= '<div class="e_details">';
							$html .= '<div class="detail1">';
								$html .= '<h3 class="event_name"><a href="#" eventid="'.$data['rows'][$j]['eventcd'].'" class="e-open-popup">' .$data['rows'][$j]['title'] . '</a></h3>';
								$html .= '<span class="date_and_time">'.$schedule.'</span>';
								$html .= '<div class="e_description">'.$data['rows'][$j]['description'].'</div>';
							$html .= '</div>';
							//$html .= '<div class="detail_image">';
								//$html .= '<img src="'.$default_image_url.'" alt="" />';
							//$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';

				$count++;
			}
				
		$html .= '</div>';

		} else {
			$html .= "No events found";
		}

	$html .= '</div>';

	$html .= eventPopupHtml();


	ob_end_clean(); // avoid buffering
	return $html;
}

add_action( 'wp_ajax_nopriv_json_show_list_events', 'json_show_list_events' ); // for logout user only
add_action( 'wp_ajax_json_show_list_events', 'json_show_list_events' );

function json_show_list_events(){ 

	
    $html = '';
	
    $url = isset($_POST['api_url'])?$_POST['api_url'] : 'https://members.cahf.org/cvapi/eventList';
    $default_image_url = isset($_POST['default_image_url'])?$_POST['default_image_url'] : '/wp-content/uploads/2024/11/657c148e12781c967d4cd9390b592a69.jpg';

	$response = file_get_contents($url);
	$month = array(
		'01'=>'Jan',
		'02'=>'Feb',
		'03'=>'Mar',
		'04'=>'Apr',
		'05'=>'May',
		'06'=>'Jun',
		'07'=>'Jul',
		'08'=>'Aug',
		'09'=>'Sep',
		'10'=>'Oct',
		'11'=>'Nov',
		'12'=>'Dec',
	);

    //$monthName = date('F');
	//$month = date('n');  //current month
	$year = date('Y'); 

	$current_year = isset($_POST['current_year'])?$_POST['current_year'] : $year;
	$current_month = isset($_POST['current_month'])?$_POST['current_month'] : date('n');
	$nav = isset($_POST['nav'])?$_POST['nav'] : '';

	//search in list  /* This must be priority if list is load */
	$s_ename = isset($_POST['s_ename'])?$_POST['s_ename'] : '';
	$s_location = isset($_POST['s_location'])?$_POST['s_location'] : '';

    
	if($nav == 'previous'){
		$current_month = $current_month - 1; 
		if($current_month==0) { // if month is zero
			$current_month = 12;
			$current_year = $current_year - 1;
		} 
	}

	if($nav == 'next'){
		$current_month = $current_month + 1;
		if($current_month>12) { // if month exceed to 12
			$current_month = 1;
			$current_year = $current_year + 1;
		} 
	}

	$monthName = date("F", mktime(0, 0, 0, $current_month, 10));
	$html.= eventListNavigation($current_year, $current_month);

	

	//get events with the same year and month using this filter
	if($nav){ // check if navigation is clicking
		$search = $current_year .'-'.sprintf("%02d", $current_month); // sample 2025-05 make 5 to 05
		$monthName = substr($monthName,0,3).' ';// add space here
	} else {
		$search = '';
		$monthName ='';
	}

	$html.='<div class="event_selected_date">';
		$html.='<h3>'.$monthName.''.$current_year.'</h3><div class="bar">&nbsp;</div>';
	$html.='</div>';



	if ($response !== false) {

		$data = json_decode($response, true); // true = associative array
		
		$items = $data['rows'];
		$filtered = array_filter($items, function($item) use ($search) {
			return stripos($item['start'], $search) !== false;
		});

      
		//PRIORITY THIS IF USER IS ACTIVE SEARCHING
		
		if($s_ename) { // search for event name
			$filtered = array_filter($items, function($item) use ($s_ename) {
				return stripos($item['title'], $s_ename) !== false;
			});
		}
        
		if($s_location) { // search for event location
			$filtered = array_filter($items, function($item) use ($s_location) {
				return stripos($item['description'], $s_location) !== false;
			});
		}



		$html .= '<div class="row">';
		$count = 0;
		if(count($filtered)) { // filter by month here
			foreach ($filtered as $key => $keyname) {


                if ($count >= 10 && empty($nav)) { //display only 5 events
					break; 
				}
				
				$startDate = explode('-', $keyname['start']);
				$endDate = explode('-', $keyname['end']);
	
				$startDatetms =  $keyname['starttms'];
				$endDatetms =    $keyname['endtms'];
	
				// Prints October 3, 1975 was on a Friday
				//echo "Oct 3,1975 was on a ".date("l", mktime(0,0,0,10,3,1975)) . "<br>";
				
				// full detail schedule
				if($startDate[1] == $endDate[1]) {
					$schedule = $month[$startDate[1]] .' '.(int)$startDate[2].', @'.$startDatetms .' - '.$endDatetms;
					$day = substr(date("l", mktime(0,0,0,$startDate[1],$startDate[2],$startDate[0])), 0, 3);
					$days = $startDate[2];
	
				} else {
					if($startDate[1] != $endDate[1]) {
						$schedule = $month[$startDate[1]] .' '.(int)$startDate[2].' - '.$month[$endDate[1]] .' '.(int)$endDate[2].', @'.$startDatetms .' - '.$endDatetms;
						$day = substr(date("l", mktime(0,0,0,$startDate[1],$startDate[2],$startDate[0])), 0, 3)  .' - '. substr(date("l", mktime(0,0,0,$endDate[1],$endDate[2],$endDate[0])), 0, 3);
						$days = $startDate[2].' - '.$endDate[2].'';
	
					} else {
						$schedule = $month[$startDate[1]] .' '.(int)$startDate[2].'-'.(int)$endDate[2].', @'.$startDatetms .' - '.$endDatetms;
						$day = substr(date("l", mktime(0,0,0,$startDate[1],$startDate[2],$startDate[0])), 0, 3) .' - '.substr(date("l", mktime(0,0,0,$endDate[1],$endDate[2],$endDate[0])), 0, 3);
						$days = $startDate[2].' - ' .$endDate[2];
					}
				
				}
	
				$html .= '<div class="event_list_row">';
					$html .= '<div class="e_date">';
							$html .= '<span class="day">'.$day.'</span>';
							$html .= '<span class="day_1">'.$days.'</span>';
					$html .= '</div>';
					$html .= '<div class="e_details">';
							$html .= '<div class="detail1">';
								$html .= '<h3 class="event_name"><a href="#" eventid="'.$keyname['eventcd'].'"  class="e-open-popup">' .$keyname['title'] . '</a></h3>';
								$html .= '<span class="date_and_time">'.$schedule.'</span>';
								$html .= '<div class="e_description">'.$keyname['description'].'</div>';
							$html .= '</div>';
							//$html .= '<div class="detail_image">';
								//$html .= '<img src="'.$default_image_url.'" alt="" />';
							//$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
               
				$count++; // increment display which apply only when default
			}

		}  else {
			$html .= '<strong>No events were found</strong>';
		}

  
	$html .= '</div>';

	} else {
		$html .= "<strong>No events were found</strong>";
	}

	
	$html .= eventPopupHtml();// iframe html here

	echo  json_encode( array('html'=>$html)  ) ;
    die();

}





add_action( 'wp_ajax_nopriv_json_show_calendar_events', 'json_show_calendar_events' ); // for logout user only
add_action( 'wp_ajax_json_show_calendar_events', 'json_show_calendar_events' );

function json_show_calendar_events(){ 

    
	
	//ob_start();
    $html = '';
	/*$atts = shortcode_atts(array(
        'json_url' => '',
        'default_image_url' => ''
    ), $atts, 'json-calendar-events');
    */
	//$month = 3;
	//$year = 2025;
	//$monthName = date('F');
	$month = date('n'); 
	$year = date('Y'); 
	$current_day = date("j");
	$current_day  = $current_day - 1;
	//var_dump($month);
	//var_dump($year);
	//var_dump($monthName);
	//var_dump($current_day);
	$year = isset($_POST['current_year'])?$_POST['current_year'] : $year;
    $month = isset($_POST['current_month'])?$_POST['current_month'] : $month;
	$nav = isset($_POST['nav'])?$_POST['nav'] : '';

	

	if($nav == 'previous'){
		$month = $month - 1; 
		if($month==0) { // if month is zero
			$month = 12;
			$year = $year - 1;
		} 
	}

	if($nav == 'next'){
		$month = $month + 1;
		if($month>12) { // if month exceed to 12
			$month = 1;
			$year = $year + 1;
		} 
	}

	$monthName = date("F", mktime(0, 0, 0, $month, 10));


	//search in callendar /* This must be priority if calendar is load */
	$s_ename = isset($_POST['s_ename'])?$_POST['s_ename'] : '';
	$s_location = isset($_POST['s_location'])?$_POST['s_location'] : '';
    //$html .= $s_ename .'terererererer222';

	//get events
	$url = isset($_POST['api_url'])?$_POST['api_url'] : 'https://members.cahf.org/cvapi/eventList';
	//$html .= $url;
	$eventItems = file_get_contents($url);
	//$html .= $eventItems;
   //store event lists and access it later
   $event_items = array();
   if ($response !== false) {
		$eventItems = json_decode($eventItems, true); 
		if(count($eventItems['rows'])){
			foreach($eventItems['rows'] as $item){
			  $event_items[$item['start']] = '<div class="date_text"><span class="title"><a href="#" eventid="'.$item['eventcd'].'" class="e-open-popup">'.$item['title'].'</a></span><span class="desc">'.$item['description'].'</span></div>';
			  //$html .= ($event_items[$item['start']]);
			 // $html .= $item['start'];
			}
	    }

		//PRIORITY THIS IF USER IS ACTIVE SEARCHING
		//$html .= $s_ename .'terererererer111';
		if($s_ename) { // search for event name
			$s_items = $eventItems['rows'];
			$filtered = array_filter($s_items, function($s_item) use ($s_ename) {
				return stripos($s_item['title'], $s_ename) !== false;
			});
 
            $firstKey = array_key_first($filtered); //get the key
			$s_date = explode('-',$filtered[$firstKey]['start']);
			$month = $s_date[1];
			$year = $s_date[0];
			$monthName = date("F", mktime(0, 0, 0, $month, 10));
			
		}
        
		if($s_location) { // search for event location
			$s_items = $eventItems['rows'];
			$filtered = array_filter($s_items, function($s_item) use ($s_location) {
				return stripos($s_item['description'], $s_location) !== false;
			});

			$firstKey = array_key_first($filtered); //get the key
			$s_date = explode('-',$filtered[$firstKey]['start']);
			$month = $s_date[1];
			$year = $s_date[0];
			$monthName = date("F", mktime(0, 0, 0, $month, 10));
		}


   }
   



	$classLapse = '';

	// Start from the 1st of the month
	$startDate = new DateTime("$year-$month-01");

	// Get the first Monday before or equal to the 1st
	$startDate->modify('Monday this week');

	// End date = last day of the month + days until Sunday
	$endDate = new DateTime("$year-$month-01");
	$endDate->modify('last day of this month');
	$endDate->modify('Sunday this week');

	// Loop through the calendar
	$interval = new DateInterval('P1D');
	$period = new DatePeriod($startDate, $interval, $endDate->modify('+1 day'));
	
	$html.='<div class="navigation_date_wrapp">';
		//$html.='<div class="today">';
		//	$html.='<span>Today</span>';
		//$html.='</div>';
		$html.='<div class="year">';
			$html.='<span>'.$monthName .' '.$year.'</span>';
		$html.='</div>';
		 
		$html.='<div class="arrow_nav">';
			$html.='<a title="Previous" href="#" class="previous-calendar" current-year="'.$year.'" current-month="'.(int)$month.'">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="arcs"><path d="M15 18l-6-6 6-6"></path></svg>
					</a>';
			$html.='<a title="Next" href="#" class="next-calendar" current-year="'.$year.'" current-month="'.(int)$month.'">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#8899a4" stroke-width="2" stroke-linecap="round" stroke-linejoin="arcs"><path d="M9 18l6-6-6-6"></path></svg>
					</a>';
		$html.='</div>';
	$html.='</div>';

	$html.='<div id="calendar">';
		$html.='<ul class="weekdays">';
			$weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
			foreach ($weekDays as $day) {
				$html.='<li>'.$day.'</li>';
			}
			$html.='</ul><ul class="days">';

			$dayOfWeek = 0;
			foreach ($period as $date) {
				if ($dayOfWeek > 0 && $dayOfWeek % 7 === 0) {

					$html.='</ul><ul class="days">';
				}
				//var_dump($date->format('n'));
				if( $date->format('n') == $month || $date->format('n') > $month  ){
					if($current_day < $date->format('j')){
						$classLapse = '';
					}
				
				} else {
					$classLapse = 'lapse';
				}

				$keyD = $year.'-'.$date->format('m').'-'.$date->format('d');
				//$html.= $keyD;
				$html.='<li><div class="date '.$classLapse.'">' . $date->format('j') . '</div> '.$event_items[$keyD].'</li>';
				$dayOfWeek++;
			}

		$html.='</ul>';
	$html.='</div>';

	$html .= eventPopupHtml();// iframe html here

    echo  json_encode( array('html'=>$html)  ) ;
    die();
}


/* AJAX Script for events */

add_action( 'wp_enqueue_scripts', 'event_list_calendar_js');
/**
 * Register custom JS
 */
function event_list_calendar_js() {
    wp_enqueue_script( 'event-js-script', get_stylesheet_directory_uri() . '/js/event-script.js', array('jquery'), '1.', true );
    wp_localize_script( 'event-js-script', 
	'admin_ajax_object',
	 array( 
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'api_url' => 'https://members.cahf.org/cvapi/eventList',
		'default_image_url' => '/wp-content/uploads/2024/11/657c148e12781c967d4cd9390b592a69.jpg',
	 ) 
   );
	
}

/* EVENT IFRAME POP UP FORM HTML */
function eventPopupHtml() {
	$html ='<div id="calendar-e-popup" style="display: none;">';
		$html .='<div class="e-popup-content">';
			$html .='<span class="e-close-popup">&times;</span>';
			$html .='<div class="ev_iframe_content">&nbsp;</div>';
		$html .='</div>';
	$html .='</div>';
	//$html='<iframe src="https://www.cahf.org/cvweb/cgi-bin/Eventsdll.dll/EventInfo?sessionaltcd=WEBINAR050825" id="dnn_ctr4647_IFrame_htmIFrame" onload="if (window.parent &amp;&amp; window.parent.autoIframe) {window.parent.autoIframe(\'dnn_ctr4647_IFrame_htmIFrame\',0);}" hideadminborder="False" frameborder="0" width="100%" scrolling="no" height="1000" allowindex="True" style="height: 777px;">Your browser does not support inline frames</iframe>';
	//https://www.cahf.org/cvweb/cgi-bin/Eventsdll.dll/EventInfo?sessionaltcd=WEBINAR050825
	return $html;
}

/* STYLE */
						

/*Event Calendar Style*/
#calendar {
	width: 100%;
	max-width: 1800px;
	margin: 30px auto 50px;
	font-family: "Helvetica Neue", Helvetica, -apple-system, BlinkMacSystemFont, Roboto, Arial, sans-serif;
}

#calendar a {
	color: #666;
	text-decoration: none;
}
#calendar ul {
	list-style: none;
	padding: 0;
	margin: 0;
	clear: both;
	width: 100%;
}
#calendar li {
	display: block;
	float: left;
	width: 14.2857142857%;
	padding: 5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border: 1px solid #ccc;
	margin-right: -1px;
	margin-bottom: -1px;
}
#calendar .weekdays {
	height: 40px;
	width: 99.6%;
}
#calendar .weekdays li {
	text-align: left;
	line-height: 20px;
	border: none !important;
	padding: 10px 0px;
	color: #5d5d5d;
	font-size: 0.9em;
}

#calendar .days li {
	height: 12em;
}

#calendar .days li:hover {
	background-color: #eee;
}

#calendar .date {
	text-align: left;
	width:100%;
	margin-bottom: 0px;
	padding: 0px 5px 5px;
	color: #141827;
	display:block;
	font-size:24px;
	font-weight:700;
}
#calendar .date.lapse{
    color: #72747d
}

#calendar .other-month .date  {
	display: none;
}
#calendar .date_text { display:block;width:100%;}
#calendar .date_text span.title {
	color: #337CC3;
    font-size: 16px;
    font-weight: 600;
    display: block;
    line-height: 20px;
	margin-bottom: 4px;
    font-family: "Open Sans", Sans-serif;
}

#calendar .date_text span.desc {
	color: #000;
    font-size: 14px;
    font-weight: 400;
    display: block;
    line-height: 18px;
    font-family: "Open Sans", Sans-serif;

}


#calendar .event {
	clear: both;
	display: block;
	font-size: 0.9em;
	border-radius: 4px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	padding: 5px;
	margin-top: 40px;
	margin-bottom: 5px;
	color: #009aaf;
	line-height: 1.2em;
	background-color: #e4f2f2;
	border: 1px solid #b5dbdc;
	text-decoration: none;
}

#calendar .event:hover {
	background-color: #BAEFEF;
	cursor: pointer;
}

#calendar .event-desc {
	color: #666;
	margin: 3px 0 7px 0;
	text-decoration: none;
}





.event_form_wrap { 
    display:flex;
    border:1px solid #e4e4e4;
    justify-content:flex-start;
    padding: 10px 40px;
 }
.s_event_name_wrapp { width: 30%; position:relative;}
.s_event_name_wrapp .s-input { 
    width: 100%; 
    border:0px; 
    outline: 0px; 
    font-size:14px;
    padding: 12px 10px;
}
.s_event_name_wrapp svg { 
    position:absolute;
    left:0px;
    height: 16px;
    width: 16px;
    left: -20px;
    top: 28%;
}

.event_form_wrap .spacer { width: 2px; border-right:1px solid  #e4e4e4;   height: 38px; }


.s_event_location_wrapp { width: 30%; position:relative;}
.s_event_location_wrapp .s-input { 
    width: 100%; 
    border:0px; 
    outline: 0px; 
    font-size:14px;
    padding: 12px 10px;
}

.s_event_location_wrapp svg { 
    position:absolute;
    left:0px;
    height: 16px;
    width: 16px;
    left: -20px;
    top: 28%;
}
.s_btn_wrap { margin-right:10px;}
.s_btn_wrap  .s-event-submit {
    border: #000000 solid 2px;
    border-radius: 5px;
    background-color: #fff;
    color: #000000;
    font-weight: bold;
    padding: 11px 20px;
    text-align:center;
    cursor:pointer;
}

.s_list_wrap { width:120px; height: 42px; overflow-y:hidden;}
.s_list_wrap ul {
    margin: 0px 0;
    padding: 0;
    list-style: none;
    position: absolute;
    background-color:#fff;
    padding: 6px 25px;
	border: 1px solid #fff; 
    
 }
 .s_list_wrap ul:hover { border: 1px solid #e4e4e4; }
 .s_list_wrap ul:hover li { display:block !important; }
.s_list_wrap li { width : 85px; text-align:left; margin-bottom:10px; position:relative;}
.s_list_wrap li.calendar { display:none;}
.s_list_wrap:hover li.calendar  { display:block;}
.s_list_wrap li a { text-decoration:none; color:#141827; }
.s_list_wrap li a:hover {  color:#0C5398; }
.s_list_wrap li.list svg { 
    position: absolute;
    height: 12px;
    width: 12px;
    right: 0px;
    top: 18%;
}

.navigation_date_wrapp { display:flex; justify-content: flex-start; margin-top: 40px;}
.navigation_date_wrapp .arrow_nav { display:flex; column-gap: 0px;}
.navigation_date_wrapp .arrow_nav a { outline:none; }
.navigation_date_wrapp .arrow_nav a svg {
    stroke: #444;
}
.navigation_date_wrapp .today {
    display: block;
    align-items: center;
    align-content: center;
}
.navigation_date_wrapp .today span { 
	border: 1px solid #e4e4e4;
    /* color: #e4e4e4; */
    padding: 3px 16px 5px;
    display: block;
    border-radius: 3px;
    font-size: 16px;
    line-height: 16px;
 }

 .navigation_date_wrapp .today span a { color:#000; text-decoration:none;}


 .navigation_date_wrapp .year {
    display: block;
    align-items: center;
    align-content: center;
}
.navigation_date_wrapp .year span { 
	
    font-size: 24px;
	font-weight:600;
	color:#000;
    font-family: "Open Sans", Sans-serif;;
 }



.event_selected_date { display:flex;  align-items: center; margin-bottom:50px; margin-top:60px;}
.event_selected_date h3 {
	font-family: "Open Sans", Sans-serif;
	font-weight: 700;
	font-size: 24px;
	line-height: 100%;
	color:#000;
	margin: 0;
	width: 170px;
}

.event_selected_date .bar { width: 100%; height:2px; border-bottom:1px solid #000;}

/* POP UP EVENT CONTENT*/
#calendar-e-popup {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0,0,0,0.5);
	display: none;
	z-index: 9999;
  }
  
  .e-popup-content {
	background-color: #fff;
	padding: 20px;
	margin: 3% auto;
	width: 90%;
	border-radius: 8px;
	position: relative;
  }
  .ev_iframe_content { overflow-x: auto; height:570px;}
  
  .e-close-popup {
	position: absolute;
	top: 5px;
	right: 10px;
	cursor: pointer;
	font-size: 20px;
  }

