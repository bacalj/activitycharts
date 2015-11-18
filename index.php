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

// page parameters
//$page    = optional_param('page', 0, PARAM_INT);
//$perpage = optional_param('perpage', 30, PARAM_INT);    // how many per page
//$sort    = optional_param('sort', 'timemodified', PARAM_ALPHA);
//$dir     = optional_param('dir', 'DESC', PARAM_ALPHA);

//debug help


admin_externalpage_setup('reportactivitycharts', '', null, '', array('pagelayout'=>'report'));
echo $OUTPUT->header();

//echo $OUTPUT->heading(get_string('activitycharts', 'report_activitycharts'));
echo '<h2>Activity Charts</h2>';

// echo '<pre>';
// var_dump($OUTPUT);
// echo '</pre>';


echo $OUTPUT->footer();
