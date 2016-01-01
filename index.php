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

include('moodlehighchart.php');

// $event_to_count = $_GET['event-dropdown'] == null ? '\core\event\user_loggedin' : $_GET['event-dropdown'];
// $start_date = $_GET['event-dropdown'] == null ? '2015-09-01' : $_GET['startdate'];
// $end_date = $_GET['event-dropdown'] == null ? '2015-09-03' : $_GET['enddate'];
//
// global $DB;
//
// //get a list of all possible events
// $events_list = $DB->get_records_sql('SELECT DISTINCT eventname FROM {logstore_standard_log}');
//
// //get a period object for the timespan
// $timespan = new DatePeriod(
//      new DateTime($start_date),
//      new DateInterval('P1D'),
//      new DateTime($end_date)
// );
//
// //turn it into an array
// $days = iterator_to_array($timespan);
//
// //set up an empty array for our date list, then push dates into it
// $dates = array();
// foreach ($days as $day) {
//   $date_string = $day->format('Y-m-d');
//   array_push($dates, $date_string);
// }
//
// //set up an empty array for counts, then put counts into it
// $counts = array();
//
// foreach ($dates as $date){
//
//   $next_date = date('Y-m-d', strtotime($date .  "+1 day"));
//
//   $params = array(
//     'daystarts' => strtotime($date),
//     'dayends' => strtotime($next_date),
//     'whichevent' => $event_to_count
//   );
//
//   $sql =  "SELECT * FROM {logstore_standard_log}";
//   $sql .= " WHERE timecreated > :daystarts";
//   $sql .= " AND timecreated < :dayends";
//   $sql .= " AND eventname = :whichevent";
//
//   $all_for_day = $DB->get_records_sql($sql, $params);
//   array_push($counts, count($all_for_day));
//
// }
//
// //map the dates to the counts
// $dates_counts = array_combine($dates, $counts);

?>

<form method="get" id="chart-params-form">
	<select name="event-dropdown" id="event-dropdown">

    <option value="<?php echo $event_to_count; ?>">
      <?php echo $event_to_count; ?>
    </option>

    <?php foreach ($events_list as $strkey => $obj) {
      echo '<option value="' . $strkey .'">' . $strkey . '</option>';
    } ?>

	</select><br>

  <i>input dates like this: 2015-09-28</i><br>
  <input type="text" name="startdate" value="<?php echo $_GET['startdate']; ?>">
  <input type="text" name="enddate" value="<?php echo $_GET['enddate']; ?>">
	<input type="submit" id="event-submit">
</form>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<table id="datatable" style="position:absolute;left:-999px; width:300px;">
    <thead>
        <tr>
            <th>Date</th>
            <th><?php echo $event_to_count; ?> per day</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dates_counts as $the_date => $the_count) {
          echo '<tr><td>'. $the_date . '</td><td>' . $the_count . '</td></tr>';
        } ?>
    </tbody>
</table>

<script src="highcharts/jquery.min.js"></script>
<script src="highcharts/highcharts.js"></script>
<script src="highcharts/data.js"></script>
<script src="highcharts/exporting.js"></script>

<script type="text/javascript">

  $(function () {

      var counted = <?php echo json_encode($event_to_count);?>;
      var startDate = <?php echo json_encode($start_date);?>;
      var endDate = <?php echo json_encode($end_date); ?>;

      $('#container').highcharts({
          data: {
              table: 'datatable'
          },
          chart: {
              type: 'column'
          },
          title: {
              text: counted + ":<br>" + startDate + " to " + endDate
          },
          yAxis: {
              allowDecimals: false,
              title: {
                  text: 'count per day'
              }
          },

          tooltip: {
            enabled: true
          },

          xAxis: {
     			 type: 'datetime',
     			 labels: {
     			 	formatter: function(){
     			 		var dt = new Date(this.value);
     			 		var shrt = dt.toString().substring(0,15);
     			 		return shrt;
     			 	},

     			 	rotation: -40
     			 }
    		}

      });
  });

</script>

<?php echo $OUTPUT->footer(); ?>
