<?php

class MoodleHighChart {

  public $start_date = '2015-09-01';//date('Y-m-d', strtotime($date .  "-1 month"));
  public $end_date = '2016-01-01';//date('Y-m-d');
  public $event = '\core\event\user_loggedin';

  public function render_options_list(){
    global $DB;

    $this->event_options = $DB->get_records_sql(
      'SELECT DISTINCT eventname FROM {logstore_standard_log}'
    );

    echo '<option value="' . $this->event . '">' . $this->event . '</option>';

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

    /*
      reduce our array objects to an array
      then reduce that to ymd
      then flip it and zero out the values
    */

    $dates_objects = iterator_to_array($timespan);

    $dates = array();

    foreach ($dates_objects as $d) {
      $date_string = $d->format('Y-m-d');
      array_push($dates, $date_string);
    }

    foreach ($dates as $key => $value) {
      $key = $value;
    }

    $this->all_dates_array = $dates;
  }

  public function get_date_counts() {
    global $DB;

    //var_dump($this->event);

    $params = array(
      'fromday' => strtotime($this->start_date),
      'thruday' => strtotime($this->end_date),
      'whichevent' => $this->event
    );

    var_dump($params);

    $sql =  'SELECT * FROM {logstore_standard_log}';
    $sql .= ' WHERE timecreated >= :fromday';
    $sql .= ' AND timecreated <= :thruday';
    $sql .= ' AND eventname = :whichevent';

    $res = $DB->get_records_sql($sql, $params);

    var_dump($res . 'wha');
  }
}
