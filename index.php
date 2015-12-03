<?php

/**
 * Activity charts report
 *
 * @package    report
 * @subpackage activitycharts
 * @copyright  2015 Joe Bacal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('reportactivitycharts', '', null, '', array('pagelayout'=>'report'));
echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('activitycharts', 'report_activitycharts'));

?>

<?php //certainly not the moodle way to do this part, but will work for now ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


<?php
// - - - - - - - - - - - - -
echo '<pre>';

  global $DB;

  // ----  works, get the user with the id of two using sql
  $stuff      = $DB->get_records_sql('SELECT id, email FROM {user} WHERE id=?', array( 2, 3 ));
  var_dump($stuff);


  //---- works, get the logstore records (using the recordset function) where the action is created
  $creationEvents = $DB->get_recordset(
    'logstore_standard_log', //the table
    array(
      'action' => 'created' //the conditions
  ));

  foreach ($creationEvents as $e) {
     var_dump($e->eventname);
  }

  $creationEvents->close();


echo '</pre>';


// - - - - - - - - - - - - - -
//$table = new html_table();
//echo html_writer::table($table);
echo $OUTPUT->footer();
