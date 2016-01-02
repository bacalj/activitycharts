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

global $DB;
$mch = new MoodleHighChart();

?>

<form method="get" id="chart-params-form">
	<select name="event-dropdown" id="event-dropdown">
		<?php $mch->render_options_list(); ?>
	</select>
	<?php	$mch->render_date_form(); ?>
</form>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php $mch->create_all_dates_array(); ?>

<table id="datatable">
    <thead>
        <tr>
            <th>Date</th>
            <th><?php echo $mch->event; ?> per day</th>
        </tr>
    </thead>
    <tbody>
        <?php $mch->render_records_table_rows(); ?>
    </tbody>
</table>

<script src="highcharts/jquery.min.js"></script>
<script src="highcharts/highcharts.js"></script>
<script src="highcharts/data.js"></script>
<script src="highcharts/exporting.js"></script>

<script type="text/javascript">

  $(function () {

      var counted = <?php echo json_encode($mch->event);?>;
      var startDate = <?php echo json_encode($mch->start_date);?>;
      var endDate = <?php echo json_encode($mch->end_date); ?>;

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
    		},

        credits: {
          enabled: false
        }

      });
  });

</script>

<?php echo $OUTPUT->footer(); ?>
