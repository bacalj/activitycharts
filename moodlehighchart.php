<?php

class MoodleHighChart {

  public $start_date = '2015-09-01';//date('Y-m-d', strtotime($date .  "-1 month"));
  public $end_date = '2016-01-01';//date('Y-m-d');
  public $event = '\core\event\user_loggedin';

  public function render_options_list(){
    global $DB;

    //this works but is slow so commenting out for debugging
    // $this->event_options = $DB->get_records_sql(
    //   'SELECT DISTINCT eventname FROM {logstore_standard_log}'
    // );

    //temportary hard-coded
    $this->event_options = $event;

    echo '<option value=' . $this->event . '">' . $this->event . '</option>';

    foreach ($this->event_options as $key => $obj){
      echo '<option value="' . $key .'">' . $key . '</option>';
    }
  }

  public function render_date_form(){
    if ($_GET['startdate'] !== NULL) {
      $this->start_date = ($_GET['startdate']);
    }

    if ($_GET['enddate'] !== NULL) {
      $this->end_date = ($_GET['enddate']);
    }

    if ($_GET['event-dropdown'] !== NULL) {
      $this->event = ($_GET['event-dropdown']);
    }

    echo '<input type="text" name="startdate" value="' . $this->start_date . '">';
    echo '<input type="text" name="enddate" value="' . $this->end_date . '">';
    echo '<input type="submit" id="event-submit">';
  }

  public function create_all_dates_array(){
    $timespan = new DatePeriod(
      new DateTime($this->start_date),
      new DateInterval('P1D'),
      new DateTime($this->end_date)
    );

    $dates_objects = iterator_to_array($timespan);

    $dates = array();

    foreach ($dates_objects as $key => $value) {
      echo 'h1';
    }
  }
}
