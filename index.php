<?php

/**
 * Activity charts report
 *
 * @package    report
 * @subpackage activitycharts
 * @copyright  2016 Joe Bacal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 ?>

 <script src="highcharts/jquery.min.js"></script>
 <script src="highcharts/highcharts.js"></script>
 <script src="highcharts/data.js"></script>
 <script src="highcharts/exporting.js"></script>

 <?php

require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

//set up stuff
admin_externalpage_setup('reportactivitycharts', '', null, '', array('pagelayout'=>'report'));
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('activitycharts', 'report_activitycharts'));


//get ready to get at the database
global $DB;

//get a list of all possible events


//populate selection form with those events

	// $faculty_or_staff = Roots\Sage\Utils\fac_or_staff();
	// $tags = get_terms('person_tag');
	// $placeText = 'placeholder="Search..."';
	// $searchInputVal = 'value="' . $_GET['search-filter'] . '"';
	// $attrs = (strlen($_GET['search-filter']) < 1 ) ?  $placeText : $searchInputVal;
?>

<form method="get" id="chart-params-form">
	<select name="which-event-dropdown" id="which-event-dropdown">

    <option value="\core\event\user_loggedin">
      choose an event
    </option>

		<option value="\core\event\user_loggedin">
			\core\event\user_loggedin
		</option>

		<option value="\core\event\user_loggedinas">
			\core\event\user_loggedinas
		</option>

	</select><br>

  input dates like this: 2015-09-28<br>
  <input type="text" name="startdate" value="enter a start date">

  <input type="text" name="enddate" value="enter an end date">

	<input type="submit" id="which-event-submit">
</form>

<?php
//get start and end date, eventually from a form
$start_date = $_GET['startdate'];
$end_date = $_GET['enddate'];
$event_to_count = $_GET['which-event-dropdown'];

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
  $date_string = $day->format('Y-m-d');
  array_push($dates, $date_string);
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

  $all_for_day = $DB->get_records_sql($sql, $params);
  array_push($counts, count($all_for_day));

}

//map the dates to the counts
$dates_counts = array_combine($dates, $counts);

?>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<table id="datatable" style="position:absolute;left:-999px; width:300px;">
    <thead>
        <tr>
            <th>Date</th>
            <th>Count< of event</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dates_counts as $the_date => $the_count) {
          echo '<tr><td>'. $the_date . '</td><td>' . $the_count . '</td></tr>';
        } ?>
    </tbody>
</table>

<script type="text/javascript">

  $(function () {

      var counted = <?php echo json_encode($event_to_count); ?>;

      $('#container').highcharts({
          data: {
              table: 'datatable'
          },
          chart: {
              type: 'column'
          },
          title: {
              text: counted
          },
          yAxis: {
              allowDecimals: false,
              title: {
                  text: 'count per day'
              }
          },
          tooltip: {
              formatter: function () {
                  return '<b>' + this.series.name + '</b><br/>' +
                      this.point.y + ' ' + this.point.name.toLowerCase();
              }
          }
      });
  });

</script>

<?php echo $OUTPUT->footer(); ?>
