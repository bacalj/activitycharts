<?php

/**
 * Activity charts report
 *
 * @package    report
 * @subpackage activitycharts
 * @copyright  2016 Joe Bacal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('reportactivitycharts', '', null, '', array('pagelayout'=>'report'));
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('activitycharts', 'report_activitycharts'));

?>

<?php //certainly not the moodle way to do this part, but will work for now ?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script> -->


<?php

// - - - - - - - - - - - - -
// echo 'Our goal is to get a daily count, for all days, whether they have data or not, of how many loggged-in-as events there were';
// echo '<br>';
// echo 'we will need to create an array with date => count';
// echo '<br>';
// echo 'it will need ALL dates, so some are date => 0';
//
// - - - - - - - - - - - - -


echo '<pre>';

global $DB;

//get start and end date, eventually from a form
$start_date = '2015-09-01';
$end_date = '2015-12-31';
$event_to_count = '\core\event\user_loggedinas';

//get a period object for the timespan
$timespan = new DatePeriod(
     new DateTime($start_date),
     new DateInterval('P1D'),
     new DateTime($end_date)
);

//turn it into an array
$days = iterator_to_array($timespan);

//set up an empty array for our date list, then push dates into it
$dates = array();
foreach ($days as $day) {
  $dateString = $day->format('Y-m-d');
  array_push($dates, $dateString);
}

//set up an empty array for counts, then put counts into it
$counts = array();

foreach ($dates as $date){

  $next_date = date('Y-m-d', strtotime($date .  "+1 day"));

  $params = array(
    'daystarts' => strtotime($date),
    'dayends' => strtotime($next_date),
    'whichevent' => $event_to_count
  );

  $sql =  "SELECT * FROM {logstore_standard_log}";
  $sql .= " WHERE timecreated > :daystarts";
  $sql .= " AND timecreated < :dayends";
  $sql .= " AND eventname = :whichevent";


  $allForDay = $DB->get_records_sql($sql, $params);

  echo 'DATE: ' . $date . '<br>';
  $countof = count($allForDay);
  var_dump($countof);
  echo '<br>';
}


//map the dates to the counts with array_map



echo '</pre>';


// - - - - - - - - - - - - - -
//$table = new html_table();
//echo html_writer::table($table);
echo $OUTPUT->footer();
