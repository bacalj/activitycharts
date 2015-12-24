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

//certainly not the moodle way to do this part, but will work for now ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<?php

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
  //echo $date . ': ' .count($allForDay) . ' events<br>';
  array_push($counts, count($all_for_day));

}

//map the dates to the counts
$dates_counts = array_combine($dates, $counts);

echo '</pre>'; ?>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<table id="datatable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Count< of event</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($dates_counts as $the_date => $the_count) {
          echo '<tr>';
          echo '<td>' . $the_date . '</td>';
          echo '<td>' . $the_count . '</td>';
          echo '</tr>';
        } ?>

    </tbody>
</table>
<?php

echo $OUTPUT->footer();

?>

<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        data: {
            table: 'datatable'
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Data extracted from a HTML table in the page'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Units'
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
