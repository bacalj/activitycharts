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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


<?php


global $DB;
$stuff      = $DB->get_record_sql('SELECT * FROM {user} WHERE id=?', array(2));

//see if you can use below syntax with the query in the comments
//$result = $DB->get_records_sql('SELECT * FROM {table} WHERE foo = ? AND bob = ?', array( 'bar' , 'tom' ));
/*
SELECT DATE(from_unixtime(created)) AS date, COUNT(*)
FROM prefix_forum_posts
WHERE created BETWEEN :date_start AND :date_end
GROUP BY DATE(from_unixtime(created))
ORDER BY created
*/


echo '<pre>';
var_dump($stuff);
echo '</pre>';

//$table = new html_table();
//echo html_writer::table($table);
echo $OUTPUT->footer();
