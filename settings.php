<?php

/**
 * Report settings
 *
 * @package    report
 * @subpackage activitycharts
 * @copyright  2015 Joe Bacal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$ADMIN->add('reports', new admin_externalpage('reportactivitycharts', get_string('activitycharts', 'report_activitycharts'), "$CFG->wwwroot/report/activitycharts/index.php"));

// no report settings
$settings = null;