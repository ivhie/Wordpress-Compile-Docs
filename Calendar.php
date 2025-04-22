<style>
body {
	font-family: Verdana, Tahoma, sans-serif;
}

h1 {
	text-align: center;
	color: #2B2B2B;
}

form {
	text-align: center;
	margin-bottom: 20px;
}

.styled-select {
   height: 5em;
   overflow: hidden;
   border: 1px solid #ccc;
   }

.select-month select {
	background-color: #eee;
	padding: 5px;
	margin-right: 10px;
	font-size: 16px;
	border: 1px solid #999;
}

.select-month select[name="months"] {
	width: 8em;
}

.select-month select[name="years"] {
	width: 5em;
}

.select-month .button {
	border: 1px solid #666;
	background-color: #999;
	color: #fff;
	font-weight: bold;
	padding: 5px;
	cursor: pointer;
}

#calendar {
	width: 90%;
	max-width: 1800px;
	margin: 0 auto;
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
	text-align: center;
	text-transform: uppercase;
	line-height: 20px;
	border: none !important;
	padding: 10px 10px;
	color: #5d5d5d;
	font-size: 0.9em;
}

#calendar .days li {
	height: 8em;
}

#calendar .days li:hover {
	background-color: #eee;
}

#calendar .date {
	text-align: center;
	margin-bottom: 5px;
	padding: 5px;
	color: #141827;
	width: 25px;
	float: left;
	font-size:24px;
	font-weight:400;
}
#calendar .date.lapse{
color: #5d5d5d
}

#calendar .other-month .date  {
	display: none;
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

#calendar .other-month {
	
}

@media(max-width:1100px) {
	#calendar {
		width: 100%;
	}
}

@media(max-width:768px) {
	#calendar {
		width: 100%;
	}
	
	#calendar .weekdays, #calendar .other-month {
		display: none;
	}
	
    #calendar li {
		height: auto !important;
		border: 1px solid #ededed;
		width: 100%;
		padding: 10px;
		margin-bottom: -1px;
	}
	
	#calendar .date {
		float: none;
	}
	
	#calendar .event {
		margin-top: 8px;
	}
}
</style>


<?php
//$month = 3;
//$year = 2025;
$monthName = date('F');
$month = date('n'); 
$year = date('Y'); 
$current_day = date("j");
$current_day  = $current_day - 1;
//var_dump($month);
//var_dump($year);
//var_dump($monthName);
//var_dump($current_day);
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
?>
<style>
.event_form_wrap { 
    display:flex;
    border:1px solid #e4e4e4;
    justify-content:space-between;
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
    padding: 10px 25px;
    
 }
 .s_list_wrap ul:hover { border: 1px solid #e4e4e4; }
.s_list_wrap li { width : 85px; text-align:left; margin-bottom:10px; position:relative;}
.s_list_wrap li.calendar { display:none;}
.s_list_wrap:hover li.calendar  { display:block;}
.s_list_wrap li a { text-decoration:none; color:#141827; }
.s_list_wrap li.list svg { 
    position: absolute;
    height: 12px;
    width: 12px;
    right: 0px;
    top: 18%;
}

.current_date_wrapp {}

</style>
<form method="post" action="">
    <div class="event_form_wrap">
            <div class="s_event_name_wrapp">
                <input type="text" value="" class="s-input" placeholder="Search for events" name="s-event-name">
                <svg class="tribe-common-c-svgicon tribe-common-c-svgicon--search tribe-events-c-search__input-control-icon-svg" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.164 10.133L16 14.97 14.969 16l-4.836-4.836a6.225 6.225 0 01-3.875 1.352 6.24 6.24 0 01-4.427-1.832A6.272 6.272 0 010 6.258 6.24 6.24 0 011.831 1.83 6.272 6.272 0 016.258 0c1.67 0 3.235.658 4.426 1.831a6.272 6.272 0 011.832 4.427c0 1.422-.48 2.773-1.352 3.875zM6.258 1.458c-1.28 0-2.49.498-3.396 1.404-1.866 1.867-1.866 4.925 0 6.791a4.774 4.774 0 003.396 1.405c1.28 0 2.489-.498 3.395-1.405 1.867-1.866 1.867-4.924 0-6.79a4.774 4.774 0 00-3.395-1.405z"></path></svg>
            </div>
            <div  class="spacer">&nbsp;</div>
            <div class="s_event_location_wrapp">
                <input type="text" value="" class="s-input" placeholder="In a location" name="s-event-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 14c-3.314 0-6-2.686-6-6s2.686-6 6-6 6 2.686 6 6-2.686 6-6 6z"/></svg>
            </div>
            <div class="s_btn_wrap">
                <input type="submit" value="Find Events" name="s-event-submit" class="s-event-submit">
            </div>
            <div class="s_list_wrap">
                <ul>
                    <li class="list">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"/></svg>
                    <a href="#">List</a></li>
                    <li class="calendar"><a href="#">Calendar</a></li>
                </ul>
            </div>
    </div>
</form>

<div class="current_date_wrapp">
    <h3><?php echo $monthName . ' '.$year;?></h3>
</div>
<?php
echo '<div id="calendar">';
    echo "<ul class=\"weekdays\">";
    $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    foreach ($weekDays as $day) {
        echo "<li>$day</li>";
    }
    echo "</ul><ul class=\"days\">";

    $dayOfWeek = 0;
    foreach ($period as $date) {
        if ($dayOfWeek > 0 && $dayOfWeek % 7 === 0) {

            echo "</ul><ul class=\"days\">";
        }
        //var_dump($date->format('n'));
        if( $date->format('n') == $month || $date->format('n') > $month  ){
            if($current_day < $date->format('j')){
                $classLapse = '';
            }
        
        } else {
            $classLapse = 'lapse';
        }
        echo "<li><div class=\"date $classLapse\">" . $date->format('j') . "</div></li>";
        $dayOfWeek++;
    }

    echo "</ul>";
echo '</div>';
?>
