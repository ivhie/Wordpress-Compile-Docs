/******************** COURSE SECTION *********************/
// display course lesson details
function LearndashCourse($atts) {
    global $post;
	 $lessons = get_published_lessons_by_course($post->ID);
    ob_start();
    ob_end_clean(); 
    $html = '';
    $circle_css = 'background:radial-gradient(closest-side, white 60%, transparent 80% 100%),
    conic-gradient(#5fa1d8 35%,#D1D1D1 0);';
	/*
    $args = array(
        'post_type'      => 'gb_xapi_content', 
        'posts_per_page' => -1, 
        'orderby'        => 'date',
        'order'          => 'ASC'
    );
    */
    //$lessons = new WP_Query($args);

    $html .= '<div class="ld_course_wrapper">';
    $html .= '<div class="ld_top_nav">';
    $html .= '<span class="course_text">Course Content</span>';
    //$html .= '<a class="expand_btn" href="#">Expand All</a>';
    $html .= '</div>';
     
	if($lessons){
		foreach ($lessons as $lesson) {
			$percentage = getPercentageLesson($lesson->ID);
			$html .= '<div class="ld_content_item">';
				$html .= '<div class="course_percent">';
				$html .= '<span class="per" style="background:radial-gradient(closest-side, white 60%, transparent 80% 100%),
    conic-gradient(#5fa1d8 '.esc_attr($percentage).'%,#D1D1D1 0)">&nbsp;</span>';
				$html .= '</div>';
				$html .= '<div class="course_lesson">';
				$html .= '<h3><a href="'.get_permalink($lesson->ID).'">'.$lesson->post_title.'</a></h3>';
				//$html .= '<span class="topics"><span class="number">5</span> Topics</span>';
				$html .= '</div>';
	//             $html .= '<div class="course_expand">';
	//             $html .= '<a class="exp_btn close" href="#">&nbsp;</a>';
	//             $html .= '</div>';
			// $html .= '<div class="lesson_content">';
				//$html .= do_shortcode('[grassblade id="'.$lesson_id.'"]'); // Dynamically load lesson
				//$html .= '</div>';
			$html .= '</div>';
		}
	}
	/*
    if ($lessons->have_posts()) {
        while ($lessons->have_posts()) {
            $lessons->the_post();
            $lesson_id = get_the_ID();
            $lesson_title = get_the_title();

            $html .= '<div class="ld_content_item">';
				$html .= '<div class="course_percent">';
				$html .= '<span class="per" style="'.$circle_css.'">&nbsp;</span>';
				$html .= '</div>';
				$html .= '<div class="course_lesson">';
				$html .= '<h3><a href="#">'.$lesson_title.'</a></h3>';
				$html .= '<span class="topics"><span class="number">5</span> Topics</span>';
				$html .= '</div>';
	//             $html .= '<div class="course_expand">';
	//             $html .= '<a class="exp_btn close" href="#">&nbsp;</a>';
	//             $html .= '</div>';
			// $html .= '<div class="lesson_content">';
				//$html .= do_shortcode('[grassblade id="'.$lesson_id.'"]'); // Dynamically load lesson
				//$html .= '</div>';
            $html .= '</div>';
        }
      //  wp_reset_postdata();
    } else {
        $html .= '<p>No lessons found.</p>';
    }
	*/

    $html .= '</div>';
    return $html;
}
add_shortcode('LearndashCourse', 'LearndashCourse');


//display course take progressive bar
add_shortcode('LearndashCourseProgressBar', 'LearndashCourseProgressBar');
function LearndashCourseProgressBar($atts){

	global $post;

	$course_id = $post->ID;
	$user = wp_get_current_user();
	$user_id =  $user->ID;
	$context = 'course';
    //$progress = apply_filters( 'learndash-learndash-progress-stats', learndash_lesson_progress( $post, $course_id ) );
	$progress_args = apply_filters(
		'learndash_progress_args',
		array(
			'array'     => true,
			'course_id' => $course_id,
			'user_id'   => $user_id,
		),
		$course_id,
		$user_id,
		$context
	);

	$progress = apply_filters( 'learndash-' . $context . '-progress-stats', learndash_course_progress( $progress_args ) );
    
	// get course status
	//$has_access  = sfwd_lms_has_access( $course_id, $user_id );
	//$status = '';
	$course_progress = learndash_user_get_course_progress($user_id, $course_id );
	if(empty($course_progress['status'])) {
		$course_progress['status'] = 'INCOMPLETE';

	}

	$course_args     = array(
		'course_id'     => $course_id,
		'user_id'       => $user_id,
		'post_id'       => $course_id,
		'activity_type' => 'course',
	);
	$course_activity = learndash_get_user_activity( $course_args );
	$course_activity = learndash_adjust_date_time_display( $course_activity->activity_updated, 'F j, Y' );
	if(empty($course_activity)){
		$course_activity = date('F j, Y'); // set current date
	}

	ob_start();
    ob_end_clean(); // avoid buffering
    $html = '';
	$html .= '<div class="progress_wrapper">';
		$html .= '<div class="detail_row_1">';
			$html .= '<span class="progress_date_stat">';
				//$html .= '<span class="date_text">Last Activity on March 20,2024</span>';
				$html .= '<span class="date_text">Last Activity on '.$course_activity.'</span>';
				$html .= '<span class="prog_status">'.esc_attr(strtoupper($course_progress['status'])).'</span>';
			$html .= '</span>';
		$html .= '</div>';

		$html .= '<div class="detail_row_2">';
			$html .= '<span class="prog_percent">'.esc_attr($progress['percentage']).'% complete</span>';
		$html .= '</div>';

		$html .= '<div class="detail_row_3">';
			$html .= '<span class="progress_bar">';
			   $html .= '<span class="bar" style="width:'.esc_attr($progress['percentage']).'%;">&nbsp;</span>';
			$html .= '</span>';
		$html .= '</div>';
	$html .= '</div>';
    return $html;
}

/******************** LESSON SECTION *********************/
// display lesson content
add_shortcode('LearndashCourselesson', 'LearndashCourselesson');
function LearndashCourselesson($atts){
	global $post;
	$post_id = $post->ID;
	ob_start();
    ob_end_clean(); // avoid buffering

   // Set default values and extract shortcode attributes
    $atts = shortcode_atts(array(
		'course_id' => '0',
		'lesson_id' => '0',
		'grassblade_id' => '0',
		
	), $atts, 'LearndashCourselesson');

	// Learndash get Lesson Breadcrumb
	$course_id = learndash_get_course_id($post->ID);
	$user = wp_get_current_user();
	$user_id =  $user->ID;
	$has_access  = sfwd_lms_has_access( $course_id, $user_id );

	/*$breadcrumb = learndash_get_template_part(
					'modules/breadcrumbs.php',
					array(
						'context'   => 'lesson',
						'user_id'   => $user_id,
						'course_id' => $course_id,
						'post'      => $post,
					),
					true
				);
    */

	$breadcrumbs = learndash_get_breadcrumbs( $post );
	$keys = apply_filters(
		'learndash_breadcrumbs_keys',
		array(
			'course',
			'lesson',
			'topic',
			'current',
		)
	);

	$status = '';
	if ( ( is_user_logged_in() ) && ( true === $has_access ) ) {
		$status = ( learndash_is_item_complete( $post->ID, $user_id, $course_id ) ? 'complete' : 'incomplete' );
	} else {
		$course_status = '';
		$status        = '';
	}

	//$status = learndash_status_bubble( ( ! empty( $course_status ) ? $course_status : $status ) );
	switch ( $status ) {
		case 'In Progress':
		case 'progress':
		case 'incomplete':
			$status =  esc_html_x( 'In Progress', 'In Progress item status', 'learndash' );
			break;

		case 'complete':
		case 'completed':
		case 'Completed':
			$status = esc_html_x( 'Complete', 'In Progress item status', 'learndash' );
			break;

		case 'graded':
			$status =  esc_html_x( 'Graded', 'In Progress item status', 'learndash' );
			break;

		case 'not_graded':
			$status = esc_html_x( 'Not Graded', 'In Progress item status', 'learndash' );
			break;

		case '':
		default:
		break;
	}

    $html = '';

	$html .= '<div class="ld_course_lesson_wrapper">';

			$html .= '<div class="lesson_row_1">';
				$html .= '<h1 class="lesson_title">'.$post->post_title.'</h1>';
			$html .= '</div>';

			$html .= '<div class="lesson_title_head">';
				$html .= '<div class="lesson_row_2">';
					$html .= '<span class="lesson_bread">';
						//$html .= '<span class="lesson_title"><span class="lesson_course">WTC NorCal Rise Course</span> > <span class="lesson_name">Lesson 1: What Is a Trade Plan</span></span>';
						$html .= '<span class="lesson_title">';
						
					    foreach ( $keys as $key ) :
							if ( isset( $breadcrumbs[ $key ] ) ) :
								
								$html .= '<span><a href="'.esc_url( $breadcrumbs[ $key ]['permalink'] ).'">'.esc_html( wp_strip_all_tags( $breadcrumbs[ $key ]['title'] ) ).'</a> </span>';
								
							endif;
						endforeach;
						
						
						$html .= '</span>';
						//$html .= '<span class="prog_status">IN PROGRESS</span>';
						$html .= '<span class="prog_status">'.$status.'</span>';
						
					$html .= '</span>';
				$html .= '</div>';
			$html .= '</div>';

			/* Box Content */

			$html .= '<div class="ld_lesson_content_item">';
				$html .= do_shortcode('[grassblade id="'.esc_html($atts['grassblade_id']).'"]'); // Dynamically load lesson
			$html .= '</div>';
		
			$html .= '<div class="lesson_footer">';
				$html .= '<div class="lesson_row_3">';


				
			
						if ($course_id && $post_id) {
							// Get all course steps (Lessons, Topics, Quizzes)
							$course_steps = learndash_get_course_steps($course_id);
							//var_dump($course_steps);
							//$all_lessons = $course_steps['sfwd-lessons'] ?? [];
					
							// Find the position of the current lesson in the list
							//$lesson_keys = array_keys($all_lessons);
							$current_index = array_search($post_id, $course_steps);
					
							// Get previous and next lesson IDs
							$prev_lesson_id = $course_steps[$current_index - 1] ?? null;
							$next_lesson_id = $course_steps[$current_index + 1] ?? null;
					
							// Get URLs for previous and next lessons
							$prev_link = $prev_lesson_id ? get_permalink($prev_lesson_id) : '';
							$next_link = $next_lesson_id ? get_permalink($next_lesson_id) : '';
					
							// Display navigation links
								$html .= '<div class="text-a-left">';
									if ($prev_link) {
										
											$html .= '<a  href="'. esc_url($prev_link) .'" class="btn l_previous">Previous Lesson</a>';
										
									}
								$html .= '</div>';
                                $html .= '<div class="text-a-center">';
									$html .= '<a  href="'.get_the_permalink($course_id).'" class="l_back_t_course">Back to Course</a>';
								$html .= '</div>';
								$html .= '<div class="text-a-right">';
									if ($next_link) {
									
											$html .= '<a  href="' . esc_url($next_link) . '" class="btn l_next">Next Lesson</a>';
										
									}
								$html .= '</div>';

						} else {
							$html .= '<a  href="'.get_the_permalink($course_id).'" class="l_back_t_course">Back to Course</a>';
						}

						//$html .= '<a  href="#" class="btn l_previous">Previous Lesson</a>';
						//$html .= '<a  href="'.get_the_permalink($course_id).'" class="l_back_t_course">Back to Course</a>';
						//$html .= '<a  href="#" class="btn l_next">Next Lesson</a>';

				$html .= '</div>';
				// Mark the step as complete
				//$html .= learndash_process_mark_complete($user_id, $post_id, false, $course_id);
			$html .= '</div>';
	$html .= '</div>';

    return $html;
}

// display sidebar lesson details
add_shortcode('LearndashLessonSidebar', 'LearndashLessonSidebar');
function LearndashLessonSidebar($atts){
	ob_start();
    ob_end_clean(); // avoid buffering

	global $post;

	$course_id = learndash_get_course_id($post->ID);
	$course_title = get_the_title($course_id);
	$user = wp_get_current_user();
	$user_id =  $user->ID;
	$context = 'course';
    //$progress = apply_filters( 'learndash-learndash-progress-stats', learndash_lesson_progress( $post, $course_id ) );
	$progress_args = apply_filters(
		'learndash_progress_args',
		array(
			'array'     => true,
			'course_id' => $course_id,
			'user_id'   => $user_id,
		),
		$course_id,
		$user_id,
		$context
	);
	$progress = apply_filters( 'learndash-' . $context . '-progress-stats', learndash_course_progress( $progress_args ) );
    
	$lessons = get_published_lessons_by_course($course_id);
	$course_steps = learndash_get_course_steps($course_id);
	$current_step = 0;
    if(count($course_steps)){
	  
	   $step_number = 1;
       for ($j=0;$j<count($course_steps);$j++) {

		    if($course_steps[$j] == $post->ID){
				$current_step = $step_number;
			}
			$step_number++;
	   }

	}



    $html = '';

	//conic-gradient(#5fa1d8 35%,#D1D1D1 0) - SIMPLY CHANGE "35%" THE PERCENTAGE ACCORDING TO ITS VALUE
	$circle_css = 'background:radial-gradient(closest-side, white 60%, transparent 80% 100%),
    conic-gradient(#5fa1d8 35%,#D1D1D1 0);';

	$circle_css2 = 'background:radial-gradient(closest-side, white 60%, transparent 80% 100%),
    conic-gradient(#5fa1d8 90%,#D1D1D1 0);';
   
	$html .= '<div class="lesson_sidebar">';

			$html .= '<div class="l_detail_row_1">';
				$html .= '<h3 class="lesson_name">'.$course_title .'</h3>';
				$html .= '<span class="progress_date_stat">';
					$html .= '<span class="prcent">'.esc_html( $progress['percentage'] ).'% complete</span> |';
					$html .= '<span class="prog_status">'.$current_step.'/'.count($course_steps).' Steps</span>';
				$html .= '</span>';
				$html .= '<span class="progress_bar_w">';
					$html .= '<span class="progress_bar"><span class="bar" style="width:'.esc_html( $progress['percentage'] ).'%;">&nbsp;</span></span>';
				$html .= '</span>';
			$html .= '</div>';
           
             if($lessons){
				foreach ($lessons as $lesson) {
					$percentage = getPercentageLesson($lesson->ID);
					$html .= '<div class="l_detail_lesson_row"  id="ld_lesson-'.$lesson->ID.'">';
						$html .= '<span class="prog_percent" style="background:radial-gradient(closest-side, white 60%, transparent 80% 100%),
    conic-gradient(#5fa1d8 '.esc_attr($percentage).'%,#D1D1D1 0)">&nbsp;</span>';
						$html .= '<div class="lesson_name_d">';
							$html .= '<div class="lesson_name"><a href="'.get_permalink($lesson->ID).'">'.$lesson->post_title.'</a></div>';
							//$html .= '<a href="#" class="view_topics" lesson="'.$lesson->ID.'">4 Topics</a>';
						$html .= '</div>';
					$html .= '</div>';
					//$html .= '<div class="l_detail_lesson_row_details" id="ld_lesson_show-'.$lesson->ID.'">';
					/*$html .= '<ul>';
						$html .= '<li>Learn What an HS Code is</li>';
						$html .= '<li>Learn what HS codes are for importing to the U.S.</li>';
						$html .= '<li>Learn what HS codes are for exporting from the U.S.</li>';
						$html .= '<li>Lorem Ipsum Dolorem Titlem</li>';
					$html .= '</ul>';*/
					//$html .= '</div>';
				}
			 }

         /*
			$html .= '<div class="l_detail_lesson_row"  id="ld_lesson-2">';
		    $html .= '<span class="prog_percent" style="'.$circle_css.'">&nbsp;</span>';
				$html .= '<div class="lesson_name_d">';
					$html .= '<div class="lesson_name">Lesson 2: Product Selection and Classification (HS Codes)</div>';
					$html .= '<a href="#" class="view_topics" lesson="2">4 Topics</a>';
				$html .= '</div>';
			$html .= '</div>';
			$html .= '<div class="l_detail_lesson_row_details" id="ld_lesson_show-2">';
				$html .= '<ul>';
					$html .= '<li>Learn What an HS Code is</li>';
					$html .= '<li>Learn what HS codes are for importing to the U.S.</li>';
					$html .= '<li>Learn what HS codes are for exporting from the U.S.</li>';
					$html .= '<li>Lorem Ipsum Dolorem Titlem</li>';
				$html .= '</ul>';
			$html .= '</div>';



			$html .= '<div class="l_detail_lesson_row"  id="ld_lesson-3">';
		    $html .= '<span class="prog_percent" style="'.$circle_css.'">&nbsp;</span>';
				$html .= '<div class="lesson_name_d">';
					$html .= '<div class="lesson_name">Lesson 2: Product Selection and Classification (HS Codes)</div>';
					$html .= '<a href="#" class="view_topics" lesson="3">3 Topics</a>';
				$html .= '</div>';
			$html .= '</div>';
			$html .= '<div class="l_detail_lesson_row_details" id="ld_lesson_show-3">';
				$html .= '<ul>';
					$html .= '<li>Learn What an HS Code is</li>';
					$html .= '<li>Learn what HS codes are for importing to the U.S.</li>';
					$html .= '<li>Lorem Ipsum Dolorem Titlem</li>';
				$html .= '</ul>';
			$html .= '</div>';
            */



	$html .= '</div>';
    return $html;

	//var_dump($course_steps);
}

/* Js FIle Location */
add_action( 'wp_enqueue_scripts', 'lesson_js_file');
/**
 * Register custom JS
 */
function lesson_js_file() {
    wp_enqueue_script( 'learndash-lesson-js-script', get_stylesheet_directory_uri() . '/js/learndash-lesson.js', array('jquery'), '1.', true );
}


/* Get lessons */
function get_published_lessons_by_course($course_id) {
    $args = array(
        'post_type'      => 'sfwd-lessons',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'course_id',
                'value'   => $course_id,
                'compare' => '='
            )
        ),
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
    );

    $lessons = get_posts($args);

    return $lessons;
}



function getPercentageLesson($lesson_id){

        $percentage = '35';
		// Get the user ID (current logged-in user)
		$user_id = get_current_user_id();

		// Specify the lesson ID
		//$lesson_id = 123; // Replace with your actual lesson ID

		// Get the course ID that the lesson belongs to
		$course_id = learndash_get_course_id($lesson_id);

		// Check if the lesson is completed
		$is_completed = learndash_is_lesson_complete($user_id, $lesson_id, $course_id);

		if ($is_completed) {
			$percentage = '100';
		} 
		return $percentage;
}
