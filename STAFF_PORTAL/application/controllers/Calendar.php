<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseControllerFaculty.php';

class Calendar extends BaseController {
    public function __construct(){
        parent::__construct();
        $this->load->helper('date');
        $this->isLoggedIn();
        $this->load->model("calendar_model");
    }

    function index(){
        $this->loadViews("calendar/calendar", $this->global, NULL, NULL);
    }  

    function getCalendarEvents(){        
        if($this->input->server('REQUEST_METHOD') === 'POST'){
            echo json_encode($this->calendar_model->getCalendarEvents());
        }else{
            echo 0;
        }
    }

    function addEvent(){
        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $eventData = array(
                'title' => $this->input->post('data')['title'],
                'start' => date('Y-m-d h:m:s',strtotime($this->input->post('data')['start'])),
                'end' => date('Y-m-d h:m:s',strtotime($this->input->post('data')['end'])),
                'all_day' => $this->input->post('data')['allDay'],
                'created_by' => $this->name,
                'created_date_time' => mdate("%Y-%m-%d %h:%i:%s")
            );
            echo $this->calendar_model->addEvent($eventData);
        }else{
            echo 0;
        }
    }

    function deleteEvent(){
        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $eventID = $this->input->post('eventID');
            if(!empty($eventID)){
                $details = array(
                    "is_deleted" => 1,
                    "updated_by" => $this->name,
                    "updated_date_time" =>  mdate("%Y-%m-%d %h:%i:%s"),
                );
                echo $this->calendar_model->updateEventByID($eventID,$details);
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }
    }

    function updateEvent(){
        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $eventData = $this->input->post('data');
            if(!empty($eventData['id'])){
                $details = array(
                    "start" => $eventData['start'],
                    "end" => $eventData['end'],
                    "updated_by" => $this->name,
                    "updated_date_time" =>  mdate("%Y-%m-%d %h:%i:%s"),
                );
                echo $this->calendar_model->updateEventByID($eventData['id'],$details);
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }
    }

}