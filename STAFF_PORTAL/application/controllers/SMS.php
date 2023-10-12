<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseControllerFaculty.php';

class SMS extends BaseController {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sms_model','sms');
        $this->load->model('staff_model','staff');
        $this->load->model('students_model','student');
        $this->isLoggedIn();
    }

    public function viewSMSPortal(){
        if ($this->isAdmin() == true ) {
            $this->loadThis();
        } else {
            $data['templates'] = $this->sms->getSMSTemplates();
            $data['departments'] = $this->staff->getStaffDepartment();
            $data['streamInfo'] = $this->student->getAllStreamName();
            $data['sms_balance'] =  $this->checkSMSBalance();
            $data['studentInfo'] = $this->student->getStudentInfo();
           // $data['studentGroupInfo'] = $this->student->getAllStdMessageGroupInfo();
            $this->global['pageTitle'] = ''.TAB_TITLE.' : SMS Portal';
            $this->loadViews("sms/send_bulk_sms.php", $this->global, $data, null);
        }
    }

    public function getSMSTemplateByID(){
        if($this->input->server('REQUEST_METHOD') == "POST"){
            $templateID = $this->input->post('template_id');
            
            $result = $this->sms->getSMSTemplateByID($templateID);
            if(empty($result))
                echo 0;
            else{
                $result->template = $this->getProcessedTemplate($result->content);
                echo json_encode($result);
            }
        }else{
            echo 0;
        }
    }

    function getProcessedTemplate($templateStr){
        $searchVal = "{#var#}";
        $replaceVal = "<span class='editableSpan' contenteditable='true' placeholder='".$searchVal."'></span>";
        return str_replace($searchVal, $replaceVal, $templateStr);
    }

    public function openSMSSentReport(){
        if ($this->isAdmin() == true ) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = ''.TAB_TITLE.' : SMS Report';
            
            $student_id = $this->security->xss_clean($this->input->post('student_id'));
            $by_name = $this->security->xss_clean($this->input->post('by_name'));
            $by_stream = $this->security->xss_clean($this->input->post('by_stream'));
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $date_search = $this->security->xss_clean($this->input->post('date_search'));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $message = $this->security->xss_clean($this->input->post('message'));
            $sms_count = $this->security->xss_clean($this->input->post('sms_count'));
            $filter = array();
           


            $filter['mobile'] = $mobile;
            // if(empty($date_search)){
            //     $filter['date_search'] =  date('Y-m-d')
            //     $data['date_search'] = date('Y-m-d');
            // }else{
            //     $filter['date_search'] = date('Y-m-d',strtotime($date_search));
            //     $data['date_search'] = $date_search;
            // }

            if(empty($date_search)){
                $filter['date_search'] = date('Y-m-d');
                $data['date_search'] = date('d-m-Y');
            }else{
                $filter['date_search'] = date('Y-m-d',strtotime($date_search));
                $data['date_search'] = date('d-m-Y',strtotime($date_search));
            }

            if(empty($term_name)){
                $data['term_name'] ='';
            }else{
                $filter['term_name'] = $term_name;
                $data['term_name'] = $term_name;
            }
            $data['message'] = $message;
            $filter['message'] = $message;

            $data['student_id'] = $student_id;
            $filter['student_id'] = $student_id;

            $data['by_name'] = $by_name;
            $filter['by_name'] = $by_name;

            $data['by_stream'] = $by_stream;
            $filter['by_stream'] = $by_stream;

            $data['mobile'] = $mobile;
            $filter['mobile'] = $mobile;

            $data['sms_count'] = $sms_count;
            $filter['sms_count'] = $sms_count;

            $data['streamInfo'] = $this->student->getAllStreamName();
            $count = $this->sms->getSMSSentReportCount($filter);
            $returns = $this->paginationCompress("openSMSSentReport/", $count, 100);
            $data['sms_counts'] = $count;
            $filter['page'] = $returns["page"];
            $filter['segment'] = $returns["segment"];
            $data['accountDetails'] = $this->sms->getSMSSentReport($filter);
            $data['sms_balance'] =  $this->checkSMSBalance();
            //$data['sms_count'] =  $this->sms->totalSMSSentCount();
            $this->loadViews("sms/sms_sent_report", $this->global, $data, null);
            
        }
    }

    
    public function get_sms_report(){
        if($this->isAdmin() == TRUE || $this->isSuperAdmin() != TRUE )
        {
            $this->loadThis();
        } else {
            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $date_search = $this->security->xss_clean($this->input->post('date_search'));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $filter = array();
           
            $filter['term_name'] = $term_name;
            $filter['date_search'] = $date_search;
            $filter['mobile'] = $mobile;
            $data_array_new = [];
            $accountDetails = $this->sms->getSMSSentReport($filter);
            foreach($accountDetails as $account) {
    

                $data_array_new[] = array(
                    date('d-m-Y',strtotime($account->sent_date)),
                    $account->student_id,
                    $account->term_name,
                    $account->stream_name,
                    $account->message,
                    $account->mobile,
                    $account->sms_count,
                    $account->status,
                );
            }
            $count = count($accountDetails);
            $result = array(
                "draw" => $draw,
                    "recordsTotal" => $count,
                    "recordsFiltered" => $count,
                    "data" => $data_array_new
            );
            echo json_encode($result);
            exit();
        }
    }

    //call from ajax /vuejs Method
    public function checkSMSBalanceVueCall(){
        if ($this->isAdmin() == true ) {
            $this->loadThis();
        } else {
           // $this->global['pageTitle'] = ''.TAB_TITLE.' : SMS Portal';
            $sms_balance =  $this->checkTextSMSBalance();
            //$this->loadViews("sms/send_bulk_sms.php", $this->global, $data, null);
            header('Content-type: text/plain'); 
                // set json non IE
            header('Content-type: application/json'); 
            echo json_encode($sms_balance);
            exit(0); 
        }
    }

    public function sendSMSToSingleNumber(){
        if($this->input->server('REQUEST_METHOD') == "POST"){
            $smsDetails = json_decode($this->input->post('data'));
           
            $smsLog = array(
                'sent_date' => date('Y-m-d'),	
                'sent_time' => date('H:i:s'),
                'application_no' => '',
                'message' => $smsDetails->message,
                'status' => 'success',
                'sent_by' => $this->staff_id,
                'sms_count' => $smsDetails->sms_cost,
                'mobile_number' => $smsDetails->mobile
            );
            // $this->sendSMS($smsDetails->mobile, $smsDetails->message);
            $this->sendSMSBulkNumber($smsDetails->mobile, $smsDetails->message);
            echo $this->sms->saveSMSLog($smsLog); 
        }else echo 0;
    }

    public function sendSMSToStudentGroup(){
        if($this->input->server('REQUEST_METHOD') == "POST"){
            $smsDetails = json_decode($this->input->post('data'));
            $studentInfo = $this->sms->getStudentInfoForSMS($smsDetails->term_name,$smsDetails->stream_name,$smsDetails->section_name);
           
            $number = array();
            if(!empty($studentInfo)){
                foreach($studentInfo as $std){
                    // $primary_contact = $std->father_mobile;

                    // if(!empty($primary_contact)){
                    //     $contactInfo = $std->father_mobile;
                    // } else {
                    //     $contactInfo = $std->mother_mobile;
                    // }
                    $contactInfo = $std->primary_mobile;
                 
                $results = $this->sendSMSBulkNumber($contactInfo,$smsDetails->message);
                $parts = explode(' ', $results);
                if (count($parts) > 0) {
                    $result = $parts[0];
                } 
                        $smsLog = array(
                            'sent_date' => date('Y-m-d'),	
                            'sent_time' => date('H:i:s'),
                            'application_no' => $std->application_no,
                            'message' => $smsDetails->message,
                            'status' => $result,
                            'sent_by' => $this->staff_id,
                            'sms_count' => $smsDetails->sms_cost,
                            'mobile_number' => $contactInfo);
                        $this->sms->saveSMSLog($smsLog);
            }
                echo 1;
        }else{ echo 0; } 
        }else{ echo 0; }
    }

    public function sendSMSToStaffGroup(){
        if($this->input->server('REQUEST_METHOD') == "POST"){
            $smsDetails = json_decode($this->input->post('data'));
            $staffInfo = $this->sms->getAllStaffInfoForSMSByDepartment($smsDetails->department_id);
            // log_message('debug','staff'.print_r($staffInfo,true));
            if(!empty($staffInfo)){
                foreach($staffInfo as $staff){
                    if(strlen($staff->mobile) == 10){
                        $smsLog = array(
                            'sent_date' => date('Y-m-d'),	
                            'sent_time' => date('H:i:s'),
                            'application_no' => '',
                            'message' => $smsDetails->message,
                            'status' => 'success',
                            'sent_by' => $this->staff_id,
                            'sms_count' => $smsDetails->sms_cost,
                            'mobile_number' => $staff->mobile
                        );
                        $this->sms->saveSMSLog($smsLog); 
                        $this->sendSMS($staff->mobile,$smsDetails->message);
                    }                    
                }
                echo 1;
            }else echo 0;
        }else echo 0;
    }

    public function sendBulkSMS(){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('message','Message','trim|required');
            $this->form_validation->set_rules('term_name','Term Name','trim|required');
            $this->form_validation->set_rules('stream_name','Stream Name','trim|required');
            $this->form_validation->set_rules('section_name','Section Name','trim|required');
            if($this->form_validation->run() == FALSE)
            {
                $this->viewSMSPortal();
            }
            else
            {
                $number = "";
                $term_name = $this->security->xss_clean($this->input->post('term_name'));
                $stream_name = $this->security->xss_clean($this->input->post('stream_name'));
                
                $parentsMobile = $this->security->xss_clean($this->input->post('parentsMobile'));
                $onlyStudents = $this->security->xss_clean($this->input->post('onlyStudent'));
                $onlyGuardian = $this->security->xss_clean($this->input->post('onlyGuardian'));
                $sms_cost = $this->security->xss_clean($this->input->post('sms_cost'));
                $message = $this->security->xss_clean($this->input->post('message'));
               // log_message('debug','sms_cost==='.$sms_cost);
               if(empty($parentsMobile) && empty($onlyStudents) && empty($onlyGuardian)){
                $this->session->set_flashdata('error', 'Please Choose any option to send SMS');
                $this->viewSMSPortal();
               }else{
                $studentInfo = $this->sms->getStudentInfoForSMS($term_name,$stream_name,$section_name);
                foreach($studentInfo as $std){
                    $number = "";
                    $parentsNumber = $this->sms->getStudentParentsNumberByAppNo($std->application_no);
                    log_message('debug','student_id==='.$std->student_id);
                    if(!empty($onlyStudents)){
                     if(strlen($std->mobile_one) == 10){
                         $number .= $std->mobile_one.',';
                         $smsLog = array(
                             'sent_date' => date('Y-m-d'),	
                             'sent_time' => date('H:m:s'),
                             'application_no' => $std->application_no,
                             'message' => $message,
                             'status' => 'success',
                             'sent_by' => $this->staff_id,
                             'sms_count' => $sms_cost,
                             'mobile_number' => $std->mobile_one
                         );
                    // $this->sms->saveSMSLog($smsLog); 
                    // log_message('debug','student_mobile==='.$std->mobile_one);
                   
                     }
                    }
                    if(!empty($parentsMobile)){
                        foreach($parentsNumber as $parent){
                            if(strlen($parent->mobile_one) == 10 && $parent->relation_type != "GUARDIAN"){
                            $number .= $parent->mobile_one.',';
                            $smsLog = array(
                                'sent_date' => date('Y-m-d'),	
                                'sent_time' => date('H:m:s'),
                                'application_no' => $std->application_no,
                                'message' => $message,
                                'status' => 'success',
                                'sent_by' => $this->staff_id,
                                'sms_count' => $sms_cost,
                                'mobile_number' => $parent->mobile_one
                            );
                            // log_message('debug','student_mobile==='.$parent->mobile_one);
                             // $this->sms->saveSMSLog($smsLog); 
                        }
                        if(!empty($onlyGuardian)){
                            if(strlen($parent->mobile_one) == 10 && $parent->relation_type == "GUARDIAN"){
                                $number .= $parent->mobile_one.',';
                                $smsLog = array(
                                    'sent_date' => date('Y-m-d'),	
                                    'sent_time' => date('H:m:s'),
                                    'application_no' => $std->application_no,
                                    'message' => $message,
                                    'status' => 'success',
                                    'sent_by' => $this->staff_id,
                                    'sms_count' => $sms_cost,
                                    'mobile_number' => $parent->mobile_one
                                );
                                // log_message('debug','gurdiean==='.$parent->mobile_one);
                                 // $this->sms->saveSMSLog($smsLog); 
                            }
                        }
                      
                        }
                    }
                    // log_message('debug','total_number==='.$number);
                    $smsStatus = 'success ';//$this->sendSMS($number,$message);

                }

                $response_array = explode(" ",$smsStatus);
                if($response_array[0] == 'success'){
                    $this->session->set_flashdata('success', 'SMS Sent Successfully');
                }else{
                    $this->session->set_flashdata('warning', 'Something Went wrong please contact us');
                }
                redirect('viewSMSPortal');
               }
               
            }
           
        }
        
    }

    function getSMSResponse()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        } else {
            $start_date = $this->input->post('date_from');
            $end_date = $this->input->post('date_to');
            if(empty($start_date)){
                if(empty($_SESSION['sms_start_date'])){
                    $data['start_date'] = date('d-m-Y 00:00:00'); 
                    $data['end_date'] = date('d-m-Y H:i:s');
                }else{
                    $data['start_date'] = $_SESSION['sms_start_date']; 
                    $data['end_date'] = $_SESSION['sms_end_date'];
                }
            }else{
                $data['start_date'] = $_SESSION['sms_start_date'] = $start_date;
                $data['end_date'] = $_SESSION['sms_end_date'] = $end_date;

            }
            $this->global['pageTitle'] = ''.TAB_TITLE.' : Subject Details';
            $this->loadViews("sms/sms_status", $this->global,$data, NULL);
        }
    }

    public function get_sms_response(){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        } else {
            $draw = intval($this->input->post("draw"));
            $start = intval($this->input->post("start"));
            $length = intval($this->input->post("length"));
            $data_array_new = [];

            // if(empty($_SESSION['sms_start_date'])){
            //     $start_date = date('d-m-Y 00:00:00'); 
            //     $end_date = date('d-m-Y H:i:s');
            // }else{
            //     $start_date = date('d-m-Y 00:00:00',strtotime($_SESSION['sms_start_date'])); 
            //     $end_date = date('d-m-Y 24:00:00',strtotime($_SESSION['sms_end_date']));
            // }
            
            // $apiKey = API_KEY;
            // // Prepare data for POST request
            // $data = array('apikey' => $apiKey,'min_time' => strtotime($start_date),'max_time' => strtotime($end_date));
            // // Send the POST request with cURL
            // $ch = curl_init('https://api.textlocal.in/get_history_api/');
            // curl_setopt($ch, CURLOPT_POST, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // $response = curl_exec($ch);
            // curl_close($ch);
            // // Process your response here
            // // echo $response;

            // // log_message('debug','response'.print_r($response['messages'],true));
            // $smsInfo = json_decode($response);
            // $smsInfo = $smsInfo->messages;
            
            // // log_message('debug','cc'.count($response));
            
            // // foreach($smsInfo['messages'] as $subject) {
            //     for($i=0;$i<count($smsInfo);$i++){
            //         $status = 'UnKnown';
            //         switch($smsInfo[$i]->status){
            //             case 'D': $status = 'Delivered'; 
            //             break; 
            //             case 'U': $status = 'Undelivered'; 
            //             break;
            //             case 'P': $status = 'Pending'; 
            //             break;
            //             case 'I': $status = 'Invalid'; 
            //             break;
            //             case 'E': $status = 'Expired'; 
            //             break;
            //             case '?': $status = 'Pushed'; 
            //             break;
            //             case 'B': $status = 'Blocked'; 
            //             break;
            //         }   
            //         $smsNumber = $smsInfo[$i]->number;
            //         if (substr($smsNumber, 0, 2) == '91') {
            //             $studentNumber = substr($smsNumber, 2); // Remove the "91" prefix
            //         } else {
            //             $studentNumber = $smsNumber;
            //         }
                   
            //         $studentInfo = $this->student->getStudentInfoByPhoneNumber($studentNumber);
                  
            //     $data_array_new[] = array(
            //         date('d-m-Y H:i:s',strtotime($smsInfo[$i]->datetime)),
            //         $smsInfo[$i]->number,
            //         $studentInfo->student_name,
            //         $studentInfo->term_name,
            //         $studentInfo->stream_name,
            //         $studentInfo->section_name,
            //         $smsInfo[$i]->content,
            //         $status
            //     );
            // }
            $data_array_new = [];
            $accountDetails = $this->sms->getSMSSentReport($filter);
            foreach($accountDetails as $account) {
    

                $data_array_new[] = array(

                    date('d-m-Y',strtotime($account->sent_date)),
                    $account->mobile,
                    $account->student_name,
                    $account->term_name,
                    $account->stream_name,
                    $account->section_name,
                    $account->message,
                    $account->status,
                );
            }
            $count = count($accountDetails);
            $result = array(
                "draw" => $draw,
                    "recordsTotal" => $count,
                    "recordsFiltered" => $count,
                    "data" => $data_array_new
            );
            echo json_encode($result);
            exit();
        }
    }

//send sms to single number
    public function sendSMS_to_SingleNumber(){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }else{
            $number = "";
            $mobile_no = $this->security->xss_clean($this->input->post('mobile'));
            $message = $this->security->xss_clean($this->input->post('msg'));
            $mobile = explode(',', $mobile_no);
            $sms_count = $this->countSmsCost(strlen($message));
            for($i=0;$i<count($mobile); $i++){
                //$number .= $mobile[$i].',';
                $response = $this->sendSMS($mobile[$i],$message);
                $response_array = explode(" ",$response);
              //  log_message('error', 'JSON_reponse'.);
              if($response_array[0] == 'success'){
                $smsLog = array(
                    'sent_date' => date('Y-m-d'),	
                    'sent_time' => date('h:m:s'),
                    'application_no' => "CUSTOM",
                    'message' => $message,
                    'status' => 'success',
                    'sent_by' => $this->staff_id,
                    'sms_count' => $sms_count,
                    'mobile_number' => $mobile[$i]
                );
              }else{
                $smsLog = array(
                    'sent_date' => date('Y-m-d'),	
                    'sent_time' => date('h:m:s'),
                    'application_no' => "CUSTOM",
                    'message' => $message,
                    'status' => 'failed',
                    'sent_by' => $this->staff_id,
                    'sms_count' => 0,
                    'mobile_number' => $mobile[$i]
                );
               
              }
              $this->sms->saveSMSLog($smsLog);
                }
              
                if($response_array[0] == 'success'){
                    $status =  "success";
                }else{
                    $status = "error";
                }
                
                header('Content-type: text/plain'); 
                // set json non IE
                header('Content-type: application/json'); 
                echo json_encode($status);
                exit(0); 
            }
            
    }
    
    public function sendSMSToNumberList(){
        if($this->input->server('REQUEST_METHOD') == "POST"){
            $smsDetails = json_decode($this->input->post('data'));
            $phoneNumbers = json_decode($smsDetails->mobile);
            foreach($phoneNumbers as $number){
                if(strlen($number) == 10){
                    $smsLog = array(
                        'sent_date' => date('Y-m-d'),	
                        'sent_time' => date('H:i:s'),
                        'application_no' => '',
                        'message' => $smsDetails->message,
                        'status' => 'success',
                        'sent_by' => $this->staff_id,
                        'sms_count' => $smsDetails->sms_cost,
                        'mobile_number' => $number
                    );
                    $this->sms->saveSMSLog($smsLog); 
                    $this->sendSMS($number, $smsDetails->message);
                }
            }
            echo 1;
        }else echo 0;
    }

    public function sendSMSByStudentList(){
        if($this->input->server('REQUEST_METHOD') == "POST"){
            $smsDetails = json_decode($this->input->post('data'));
            $application_no = json_decode($smsDetails->application_no);
            $number = array();
            foreach($application_no as $application){
                if(!empty($application)){
                    // log_message('debug','application'.$application);
                    $stdInfo = $this->sms->getStudentListForSMS($application);
                    
                    
                        $contactInfo = $stdInfo->primary_mobile;
                        
                    
                    // $contactInfo = $this->sms->getParentContactInfo($stdInfo->application_no,$primary_contact);
                  
                  
                    if(strlen($contactInfo) == 10){
                        $number .= $contactInfo.',';
                        
                    }
                }
            }

            $result = $this->sendSMS($number, $smsDetails->message);
            // if($result == 'success'){
                $parts = explode(' ', $result);
                if (count($parts) > 0) {
                    $results = $parts[0];
                }
                foreach($application_no as $application){
                    if(!empty($application)){
                        
                        $stdInfo = $this->sms->getStudentListForSMS($application);
                        $contactInfo = $stdInfo->primary_mobile;
                       
                        //$contactInfo = $this->sms->getParentContactInfo($stdInfo->application_no,$primary_contact);
                       
                       
                        $smsLog = array(
                            'sent_date' => date('Y-m-d'),	
                            'sent_time' => date('H:i:s'),
                            'application_no' => $stdInfo->student_id,
                            'message' => $smsDetails->message,
                            'status' => $results,
                            'sent_by' => $this->staff_id,
                            'sms_count' => $smsDetails->sms_cost,
                            'mobile_number' => $contactInfo
                        );
                        $this->sms->saveSMSLog($smsLog); 
                    }
                }
            // }
            echo 1;
        }else echo 0;
    }


    public function sendSMSAbsentedStudents(){
        if($this->isAdmin() == TRUE){
            $this->loadThis();
        }else{
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $students = $this->sms->getNumbersByTerms($term_name);

            foreach ($students as $student) {
                $absentedStudentInfo = $this->timetable->getStudentAbsentDetails(date("Y-m-d"),$student->student_id,$term_name);
                $count = 0;
                
                foreach($absentedStudentInfo as $infoAb){
                    if($count == 0){
                    // if($infoAb->father_mobile != ""){
                    // $mobile = $infoAb->father_mobile;
                    // }else{
                    // $mobile = $infoAb->father_mobile.','.$infoAb->mother_mobile;
                    // }
                    
                    $mobile = $infoAb->father_mobile.','.$infoAb->mother_mobile;
                    $student_name = strtoupper($infoAb->student_name);
                    // $student_name = explode(" ", $student_name);
                    // $student_name = $student_name[0].' '.$student_name[1];
                    $absent_date = date("d-m-Y");
                    $subject_name = strtoupper(substr($infoAb->sub_name, 0, 5));
                    } else {

                        if(!(preg_match("/{$infoAb->sub_name}/i", $subject_name))) {
                            $subject_name .= ', '.strtoupper(substr($infoAb->sub_name, 0, 5));
                        }
                    }
                    $count++;
                }
                if($absentedStudentInfo != NULL){
                    // $finalMessage = 'Your ward '.$student_name.' is absent for the subject '.$subject_name.' on '.$absent_date.'.Kindly contact the office to confirm.-Principal, SJPUC';
                    // $finalMessage = 'Your ward '.$student_name.' is absent for the subject '.$subject_name.' on '.$absent_date.'.Kindly contact the office to confirm.-Principal, SJPUC';
                    

                     $finalMessage = 'Dear Parent, your ward '.$student_name.' is absent for the subject '.$subject_name.' on '.$absent_date.' Kindly contact the office to confirm - Principal, SJPUC.';

                    $smsStatus = $this->sendAbsentSMS($mobile, $finalMessage);
                if($smsStatus == 'success'){
                        $attendanceUpdateInfo = array(
                            'sms_sent_status' => 1,
                            'updated_date_time' => date('Y-m-d H:i:s')
                        );

                        $smsLog = array(
                            'date_time' => date('Y-m-d H:i:s'),
                            'student_id' => $student->student_id,
                            'message' => $finalMessage,
                            'status' => $smsStatus,
                            'sent_by' => $this->vendorId,
                            'sms_count' => 1,
                            'mobile_number' => $mobile
                        );

                        $this->sms->addNewSMS_Log($smsLog);
                        $this->timetable->updateAttendanceSMSStatus($student->student_id,date("Y-m-d"),$attendanceUpdateInfo);

                    }
                    //FCM////////////
                    $all_users_token = $this->push_notification_model->getSingleStudentsToken($student->student_id);
                    $tokenBatch = array_chunk($all_users_token,500);
                    for($itr = 0; $itr < count($tokenBatch); $itr++){
                        $this->push_notification_model->sendMessage('Absent For Class',$finalMessage,$tokenBatch[$itr],"student");
                    }
                    //FCM///////////
                }
            }
            echo "success";
            exit;
        }
    }

    public function checkTextSMSBalance(){
            $apiKey = urlencode(API_KEY);
            // Prepare data for POST request
            $data = array('apikey' => $apiKey);
            // Send the POST request with cURL
            $ch = curl_init('https://api.textlocal.in/balance/');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($response, true);
            // Process your response here
           // $data['balance']= $json['balance']['sms'];
            return $json;
    }

    // single sms
    function sendSingleNumberSMS($mobile,$msg){
        $message = $msg;
        $message = rawurlencode($message);  
        $data = "username=".USERNAME_TEXTLOCAL."&hash=".HASH_TEXTLOCAL."&message=".$message."&sender=".SENDERID_TEXTLOCAL."&numbers=".$mobile;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result_sms = curl_exec($ch); // This is the result from the API
        $json = json_decode($result_sms, true);
        //log_message('error', 'JSON=' );
        $status= $json['status'];
        // log_message('error', 'JSON='.print_r($json));
        curl_close($ch);
        return $status;

    }



    function sendSMS($mobile, $message){
        // curl_close($ch);
        // return $response;
        $request =""; //initialise the request variable
        $param['method']= "sendMessage";
        $param['send_to'] = $mobile;
        $param['msg'] = $message;
        $param['userid'] = "2000232122";
        $param['password'] = "iHDNnLKji";
        $param['v'] = "1.1";
        $param['msg_type'] = "TEXT"; //Can be "FLASH”/"UNICODE_TEXT"/”BINARY”
        $param['auth_scheme'] = "PLAIN";
        //Have to URL encode the values
        foreach($param as $key=>$val) {
        $request.= $key."=".urlencode($val);
        //we have to urlencode the values
        $request.= "&";
        //append the ampersand (&) sign after each parameter/value pair
        }
        $request = substr($request, 0, strlen($request)-1);
        //remove final (&) sign from the request
        $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?".$request;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        // curl_close($ch);
        // echo $curl_scraped_page;
       // $response = json_decode($result_sms, true);
        log_message('debug', 'JSON='.print_r($result_sms,true));
     
        curl_close($ch);
        return $response;
    }

          public function sendAbsentSMS($mobile,$msg){
       
        $message = rawurlencode($msg);  
        $data = "username=".USERNAME_TEXTLOCAL."&hash=".HASH_TEXTLOCAL."&message=".$message."&sender=".SENDERID_TEXTLOCAL."&numbers=".$mobile;
        $ch = curl_init('https://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result_sms = curl_exec($ch); // This is the result from the API
        
        $json = json_decode($result_sms, true);
        log_message('error', 'JSON='.print_r($json,true) );
        $status= $json['status'];
       
        log_message('error', 'RESULT API'.$message);
           log_message('error', 'status'.$status);
        curl_close($ch);
        return $status;
    }



    function sendSMSBulkNumber($mobile, $message){

        $request =""; //initialise the request variable
        $param['method']= "sendMessage";
        $param['send_to'] = $mobile;
        $param['msg'] = $message;
        $param['userid'] = "2000232122";
        $param['password'] = "iHDNnLKji";
        $param['v'] = "1.1";
        $param['msg_type'] = "TEXT"; //Can be "FLASH”/"UNICODE_TEXT"/”BINARY”
        $param['auth_scheme'] = "PLAIN";
        //Have to URL encode the values
        foreach($param as $key=>$val) {
        $request.= $key."=".urlencode($val);
        //we have to urlencode the values
        $request.= "&";
        //append the ampersand (&) sign after each parameter/value pair
        }
        $request = substr($request, 0, strlen($request)-1);
        //remove final (&) sign from the request
        $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?".$request;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        // curl_close($ch);
        // echo $curl_scraped_page;
       // $response = json_decode($result_sms, true);
        // log_message('debug', 'JSON='.print_r($response,true));
     
        curl_close($ch);
        return $response;
    }

    function countSmsCost($len) {

       if($len <= 160){
           return 1;
       }else if($len >= 161 && $len <= 306){
           return 2;
       }else if($len >= 306 && $len <= 459){
        return 3;
    }else if($len >= 459 && $len <= 612){
        return 4;
    }else if($len >= 612 && $len <= 765){
        return 5;
    }else if($len >= 765 && $len <= 918){
        return 6;
    }else if($len >= 918 && $len <= 1071){
        return 7;
    }else if($len >= 1071 && $len <= 1224){
        return 8;
    }else if($len >= 1224 && $len <= 1377){
        return 9;
    }else if($len >= 1377 && $len <= 1530){
        return 10;
    }else{
        return 11;
    }
     
    }

   
}