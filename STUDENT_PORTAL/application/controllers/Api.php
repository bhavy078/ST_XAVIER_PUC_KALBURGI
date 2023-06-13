<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/third_party/Paytmchecksum.php';
class Api extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('registration_model');
        $this->load->model('student_model');
        // $this->load->model('push_notification_model');
        $this->load->model('studymaterial_model');   
        // $this->load->model('remarks_model');   
        $this->load->model('performance_model');
        $this->load->model('attendance_model');
        // $this->load->model('wallet_model','wallet');
        $this->load->model('admission_model','admission');
        // $this->load->model('Transport_model','transport');
        // $this->load->model('Hostel_model','hostel');
    }
   

    //----------------------LOGIN-----------------------
    /**
     * This function used to login
     */
    public function loginMe(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $username = $obj['user_id'];
        $password = $obj['password'];
        $result = $this->login_model->loginMe($username, $password, '');
        if(!empty($result))
        {
            $onLoginSuccess = 'Login Matched';
            $SuccessMSG = json_encode($result);
            echo $SuccessMSG ;     
        }
        else
        {
            $InvalidMSG = 'Invalid Username or Password Please Try Again' ;
            $InvalidMSGJSon = json_encode($InvalidMSG);
            echo $InvalidMSGJSon ;
        }
    }
    
    /**
     * This function used to generate reset password request link
     */
    function resetPasswordUser(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);

        $student_id = $obj['studentid'];
        $dob = $obj['dob'];

            // $isExist = $this->login_model->isStudentAlreadyRegisterd($student_id);
            // if($isExist > 0){
                $dob_from_db = str_replace('/', '-', $dob);
                if((date('Y-m-d',strtotime($dob_from_db))) == (date('Y-m-d',strtotime($dob)))){
                    $result = $this->login_model->resetPasswordUser($student_id,date('Y-m-d',strtotime($dob)));
                     if(!empty($result)){
                        $msg = "success";
                    }else{
                        $msg = 'Date of Birth or Student ID is Invalid';
                    }
                }else{
                    $msg = 'Date of Birth is Invalid';
                }
            // }else{
            //     $msg = $student_id .' is Not Registered.';
            // }
            $jsonmsg = json_encode($msg);
            echo $jsonmsg ;
    }

    /**
     * This function used to reset the password 
     */
    function resetPasswordConfirmUser(){
        $json = file_get_contents('php://input'); 
            $obj = json_decode($json,true);
 
            $student_id = $obj['studentid'];
            $password = $obj['password'];
            $studentInfo = array(
                'password'=>getHashedPassword($password),
                'password_text' => base64_encode($password),
                );
           
            $result = $this->login_model->resetPasswordConfirmUser($studentInfo,$student_id);
            if($result > 0)
            {
                $msg="success";
            }
            else
            {
                $msg="failed";
            }
        $jsonmsg = json_encode($msg);
        echo $jsonmsg ;
    }

    /**
     * This function used to add fb token to database 
     */
    function tokenToDB(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);

        $student_id = $obj['userid'];
        $token = $obj['token'];
        $student_name = $obj['student_name'];
        $term = $obj['term'];
        $section = $obj['section'];
        $device_id= $obj['id'];
        // log_message("debug","err=".print_r($device_id,true));
        if($student_id !='' && $device_id != '' ){
            $check_device = $this->login_model->checkDeviceExists($student_id,$device_id);
            if($check_device>0){
                $info = array(
                    'token'=>$token,
                    'updated_by'=>$student_id,
                    'updated_date_time'=>date('Y-m-d H:i:s')
                );
                $result = $this->login_model->updateToken($device_id,$info);
            }else{
                $info = array(
                    'student_id'=>$student_id,
                    'student_name'=>$student_name,
                    'stream' => $term,
                    'section' => $section, 
                    'token'=> $token,
                    'device_model'=>$obj['model'],
                    'device_sdk'=>$obj['sdk'],
                    'device_id'=>$device_id,
                    'created_by'=>$student_id,
                    'created_date_time'=>date('Y-m-d H:i:s')
                );
                $result = $this->login_model->addToken($info);    
            }
        }
        if($result > 0){
            $msg = "token success";
        }else{
            $msg ="token failed";
        }
        $jsonmsg = json_encode($msg);
        echo $jsonmsg;
    }

    
    /**
     * This function used to get dashboard menu 
     */
    function dashboardMenu(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);

        $student_id = $obj['user_id'];
        $dashboardInfo = $this->login_model->dashboardInfo();
        
        $db_data = array();
        foreach($dashboardInfo as $info){
            $db_data[] = $info;
        }
        $data = json_encode($db_data);
        echo $data;
    }

    /**
     * This function used to get dashboard sub menu 
     */
    function dashboardSubMenu(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);

        $student_id = $obj['user_id'];
        $menu_id = $obj['user_id'];
        $dashboardInfo = $this->login_model->subMenuInfo($menu_id);
        
        $db_data = array();
        foreach($dashboardInfo as $info){
            $db_data[] = $info;
        }
        $data = json_encode($db_data);
        echo $data;
    }

    //---------------------------REGISTER-------------------------------
    /**
     * This function used to insert registeration details  
     */
    function userRegisterDB()
    {
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);

        $student_id = $obj['studentid'];
        $dob = $obj['dob'];
        $password = $obj['password'];
        $isExist = $this->registration_model->isStudentAlreadyRegisterd($student_id);
            
        if($isExist > 0){
            $msg = $student_id .' is Already Registered.';
        }else{
            $isValid = $this->registration_model->checkStuentIdAndDobIsValid($student_id,'');
            if($isValid == NULL){
                $msg = 'Student ID is Invalid.';
            }else if($isValid != NULL){
                    $dob_from_db = str_replace('/', '-', $isValid->dob);
                    if(date('Y-m-d',strtotime($dob_from_db)) == date('Y-m-d',strtotime($dob))){
                        $studentInfo = array(
                            'student_id'=>$student_id, 
                            'dob'=> date('Y-m-d',strtotime($dob)), 
                            'password'=>getHashedPassword($password), 
                            'password_text' => base64_encode($password),
                            'createdBy' => $student_id,
                            'created_date'=>date('Y-m-d H:i:s'));
                        $result = $this->registration_model->userRegisterDB($studentInfo);

                        if($result > 0){
                            $msg = "success";
                        }else{
                            $msg ='Failed To Register';
                        }
                    }else{
                        $msg = 'Entered Date of Birth is Invalid.';
                    }
                }else{
                    $msg = 'Entered Date of Birth is Invalid.';
                }
            } 
            $jsonmsg = json_encode($msg);
            echo $jsonmsg;
    }

    //--------------STUDENT------------------------------
    /**
     * This function is used to show users profile
     */
    function profile(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $student_id = $obj['student_id'];
        $studentInfo = $this->student_model->getStudentInfoByStudentId($student_id);
        $data["active"] = $active;
        $data = json_encode($studentInfo);
        echo $data; 
    }

    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    function changePassword(){
        $json = file_get_contents('php://input'); 
            $obj = json_decode($json,true);

            $oldPassword = $obj['old_password'];
            $password = $obj['password'];
            $student_id=$obj['student_id'];
            //log_message('debug','ggg'.print_r($obj,true));
            $resultPas = $this->student_model->matchOldPassword($student_id, $oldPassword);
            if(empty($resultPas)) {
                $msg= 'Your old password is not correct';
            }
            else{
                $usersData = array('password'=>getHashedPassword($password)
                               );
                $result = $this->student_model->changePassword($student_id, $usersData);
                if($result > 0) { 
                    $msg='success'; 
                }else { 
                    $msg= 'Password updation failed'; 
                }
            }
            echo json_encode($msg);
    }

    
    //-----------------SUGGESTION--------------    
    // view suggestion
    public function mySuggestion(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $student_id = $obj['user_id'];
        // log_message('debug','ee='.print_r($student_id,true));
        $data['suggestionInfo'] = $this->student_model->getSuggestionInfoById($student_id);
        $db_data = array();
        foreach($data['suggestionInfo'] as $suggestion){
            // if($suggestion->is_viewed == 0 && !(is_null($suggestion->management_reply))){
            //     $tempData = array(
            //         'is_viewed' => 1,
            //         'viewed_date_time' => date('Y-m-d H:i:s')
            //     );
            //     $this->student_model->updateSuggestionInfoById($suggestion->row_id,$tempData);
            // }
            $db_data[] = $suggestion;
        }
        // log_message('debug','ee='.print_r($data['suggestionInfo'],true));
        $data = json_encode($db_data);
        echo $data;
    }

    //Save suggestion to db
    function suggestionToDB()
    {
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);

        $from = $obj['msg_frm'];
        $message = $obj['msg'];
            $this->sendPushNotificationToStaffs('A new message from student: '.$obj['studentid'],$message);  
            $suggestionInfo = array(
                'student_id'=> $obj['studentid'], 
                'msg_from'=>$from, 
                'message' => $message, 
                'date'=> date('Y-m-d H:i:s'), 
                'created_by' => $obj['studentid'], 
                'created_date_time' => date('Y-m-d H:i:s'));
            
            $result = $this->student_model->suggestionToDB($suggestionInfo);
            
            if($result > 0)
            {
                $msg="success";
            }
            else
            {
                $msg="failed";
            }
            $data = json_encode($msg);
            echo $data;
    }

    private function sendPushNotificationToStaffs($title,$body){ 
        $title = substr($title,0,35);
        $body = substr($body,0,40);         
        $fields = array(
            'app_id' => ONE_SIGNAL_APP_ID,
            'contents' => array(
                "en" => $body
            ),
            'headings' => array(
                "en" => $title
            ),
            'web_url' => URL_TO_BE_OPENED_ON_CLICK,                
            'app_url' => URL_TO_BE_OPENED_ON_CLICK,
            'chrome_web_badge' => NOTIFICATION_BADGE,
            'ios_badgeType' => "Increase",
            "ios_badgeCount" => 1,                
        );
        
        $fields['filters'] = array(
                                    array(
                                        "field" => "tag", "key" => "role", "relation" => "=", "value" => "1"
                                    ),
                                    array("operator" => "OR"),
                                    array(
                                        "field" => "tag", "key" => "role", "relation" => "=", "value" => "3"
                                    ),
                                    array("operator" => "OR"),
                                    array(
                                        "field" => "tag", "key" => "role", "relation" => "=", "value" => "5"
                                    )
                                );
        return $this->oneSignalSendNotification($fields);           
    }

    private function oneSignalSendNotification($fields){
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ONE_SIGNAL_NOTIFICATION_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            ONE_SIGNAL_AUTHORIZATION
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        
        $response = curl_exec($ch);
        curl_close($ch);
        $responseArr = json_decode($response);
        if(!empty($responseArr->errors)) return 0;
        else return 1;
    }

    //-----------------------ATTENDANCE---------------
    public function myAttendance(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $student_id = $obj['student_id'];
        $studentInfo = $this->student_model->getStudentInfoByStudentId($student_id);
        //log_message('debug','fff'.print_r($studentInfo,true));

        // if($studentInfo->term_name == "I PUC"){ 
            $absent_date_from = date("Y-m-d", strtotime($studentInfo->doj));
        // } else { 
        //     $absent_date_from = '2022-07-01';
        // } 
        $attendance_date_to = date("Y-m-d");
        $elective_sub = strtoupper($studentInfo->elective_sub);
        $subjects_code = array();
        $subject_name = array();
        $classes = array();
        $percentages = array();
        if($elective_sub == "KANNADA"){
            array_push($subjects_code, '01');
        }else if($elective_sub == 'HINDI'){
            array_push($subjects_code, '03');
        } else if($elective_sub == 'FRENCH'){
            array_push($subjects_code, '12');
        }else{
            // array_push($subject_mark_chart,0);
            // array_push($subject_names, 'EXM');
        }
        array_push($subjects_code, '02');
        $subjects = $this->getSubjectCodes($studentInfo->stream_name);
       // log_message('debug','subjects'.print_r($subjects,true));

        $subjects_code = array_merge($subjects_code,$subjects);
        $total_class_held_all = 0;
        $total_class_attended_all = 0;
        for($i=0; $i < count($subjects_code); $i++){
            $absent_count = 0;
            $absent_count_lab = 0;
            $batch_name = '';
            $subject_info = $this->attendance_model->getSubjectInfo($subjects_code[$i]);
           // log_message('debug','subject_info'.print_r($subject_info,true));

            if($subject_info->lab_status == 'true'){
                $batch_name = $studentInfo->batch;
            }                    
            $subject_class_held_theory = $this->attendance_model->getTotalClassHeld($subjects_code[$i],$studentInfo->term_name,$studentInfo->section_name,'THEORY','',$absent_date_from,$attendance_date_to);
           // log_message('debug','subject_class_held_theory'.print_r($subject_class_held_theory,true));
            $total_dates_held_theory = $this->attendance_model->getTotalClassCompletedDates($subjects_code[$i],$studentInfo->term_name,$studentInfo->section_name,'THEORY','',$absent_date_from,$attendance_date_to);
           // log_message('debug','total_dates_held_theory '.print_r($total_dates_held_theory ,true));
            $absent_count_theory = $this->attendance_model->getStudentAbsentCount($subjects_code[$i],$student_id,$absent_date_from,$attendance_date_to,'THEORY');
            $absent_count += $absent_count_theory;
            $subject_class_held_lab = $this->attendance_model->getTotalClassHeld($subjects_code[$i],$studentInfo->term_name,$studentInfo->section_name,'LAB',$batch_name,$absent_date_from,$attendance_date_to);
            $total_dates_held_lab = $this->attendance_model->getTotalClassCompletedDates($subjects_code[$i],$studentInfo->term_name,$studentInfo->section_name,'LAB',$batch_name,$absent_date_from,$attendance_date_to);
            $total_class_held = $subject_class_held_theory + ($subject_class_held_lab*2);               
            $absent_count_lab = $this->attendance_model->getStudentAbsentCount($subjects_code[$i],$student_id,$absent_date_from,$attendance_date_to,'LAB');
            if($absent_count_lab != 0){
                $absent_count += ($absent_count_lab * 2);
            }
            $total_class_presnts = $total_class_held-$absent_count;
            if($total_class_held>0){
                $attendance_percentage = ($total_class_presnts/$total_class_held)*100;
            }else{
                $attendance_percentage=0;
            }
            $total_class_held_all += $total_class_held;
            $total_class_attended_all += $total_class_presnts;
            array_push($subject_name,$subject_info->name); 
            array_push($classes,$total_class_held.'/'.$total_class_presnts); 
            array_push($percentages,round($attendance_percentage,2));                   
        }
        $i=0;
        foreach($subject_name as $sub){
            $data[$i] = array('subject_name'=>$subject_name[$i],'classes'=>$classes[$i],'percentages'=>$percentages[$i]);
            $i++;
        }
        $data = json_encode($data);                 
        echo $data;
    }

    //----------------NOTIFICATION----------------
    public function myNotificationsApi(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $term_name=$obj['term_name'];
        $section_name=$obj['section_name'];
        $stream_name = $obj['stream_name'];
        $student_id = $obj['student_id'];
        $row_id = $obj['row_id'];
        $student = $this->student_model->getStudentInfoBystudId($student_id);
        //log_message('debug','student'.print_r($student,true));
        // $term_name='KG II';
        // $section_name='C';
        // $student_id = '565464';
        // $row_id = '2';
        $notifications = $this->student_model->getStudentNotifications($student->term_name,$student->section_name,$student->stream_name);
        //log_message('debug','notii'.print_r($notifications,true));
         
        ////
        // $date = date('Y-m-d');
        // $notificationMsg = $this->student_model->getStudentNotification($student_id,$date);

        // $bulkNotifications = $this->push_notification_model->getStudentBulkNotificationsApi($date,$row_id);        
        /////
        // log_message('debug','old='.print_r($notifications,true));

        // log_message('debug','mss='.print_r($notificationMsg,true));
        // log_message('debug','aaw='.print_r($bulkNotifications,true));
        $db_data = array();
            // foreach($bulkNotifications as $info){
            //     if($info->active_date!=null){
            //         $info->date_time =date('d-m-Y',strtotime($info->active_date));
            //         $info->subject = "Notification";
            //     }
            //     $db_data[] = $info;
            // }
            foreach($notifications as $info){
                if($info->date_time!=null){
                    $info->date_time =date('d-m-Y H:i',strtotime($info->date_time));
                }
                $db_data[] = $info;
            }
            // foreach($notificationMsg as $info){
            //     if($info->date_time!=null){
            //         $info->date_time =date('d-m-Y H:i',strtotime($info->date_time));
            //         $info->subject = "Notification";
            //         $info->filepath = "";
            //     }
            //     $db_data[] = $info;
            // }
            
            $data = json_encode($db_data);
        echo $data;
    }

    
    //---------------STUDY MATERIAL--------------------
    public function viewstudyMaterials(){
        $filter = array();
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $student_id = $obj['student_id'];
        $term_name = $obj['term_name'];
        $stream_name = $obj['stream_name'];
        // $student = $this->student_model->getStudentInfoById($student_id,$term_name);
        $filter['term_name'] = $term_name;
        $filter['stream_name'] = $stream_name;
        $filter['stream_name1'] = 'ALL';
        $studyMaterialInfo = $this->studymaterial_model->getStudyMaterial($filter);
        $db_data = array();
        foreach($studyMaterialInfo as $info){
            if($info->created_date_time!=null){
                $info->created_date_time =date('d-m-Y',strtotime($info->created_date_time));
            }
            $db_data[] = $info;
        }
        $data = json_encode($db_data);
        echo $data;
    }

    public function viewYoutubeVideos(){

        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $student_id = $obj['student_id'];
        $term_name = $obj['term_name'];
        $stream_name = $obj['stream_name'];
        $filter['stream_name'] = $stream_name;
        $filter['stream_name1'] = 'ALL';

        $filter['term_name'] = $term_name;

        $videoInfo = $this->studymaterial_model->getYoutubeLink($filter);
        $data = json_encode($videoInfo);
        echo $data;
    }

    //----------NEWS FEED---------
    public function viewNewsFeed(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $term_name = $obj['term_name'];
        $userId = $obj['user_id'];
        $filter = array();
        $filter['role'] = 'Student';
        $filter['role_one'] = 'ALL';
        $filter['term_name'] = $term_name;
        $newsInfo = '';//$this->student_model->getNewsFeed($filter);
        // foreach($newsInfo as $news){
        //     if($news->date!=null){
        //         $news->date=date('d-m-Y h:i A',strtotime($news->date));
        //     }
        //     $news->isLiked=$this->student_model->isLiked($news->row_id,$userId);
        //     $news->totalLikes=$this->student_model->totalLikes($news->row_id);
        // }
        $data = json_encode($newsInfo);
        echo $data;
    }

    //----------EXAM----------
    public function examPerformance(){
        // $json = file_get_contents('php://input'); 
        // $obj = json_decode($json,true);
        // $termName = $obj['term_name'];
        // $student_id = $obj['user_id'];
        //$app = $_GET['app_name'];
        $student_id =$_GET['id'];
        $subjects_code = array();
        $data['studentInfo'] = $this->student_model->getStudentInfoByStudentId($student_id);
        $term_name = $data['studentInfo']->term_name;

                $assignment_exam_marks = array();
                

                // if($data['studentInfo']->term_name == 'I PUC'){

                    $exam_year = '2022-23';

                // }else{

                //     $exam_year = '2020';

                // }                

                $elective_sub = strtoupper( $data['studentInfo']->elective_sub);

                if($elective_sub == "KANNADA"){

                array_push($subjects_code, '01');

                }else if($elective_sub == 'HINDI'){

                array_push($subjects_code, '03');

                } else if($elective_sub == 'FRENCH'){

                array_push($subjects_code, '12');

                }

                array_push($subjects_code, '02');

                $exam_mark_first_test = array();

                $exam_mark_mid_term = array();

                $exam_mark_second_test = array();

                $exam_mark_first_preparatory = array();

                $exam_mark_assignment_one = array();

                $exam_mark_assignment_two = array();

                $exam_mark_assignment = array();

                $subjects = $this->getSubjectCodes($data['studentInfo']->stream_name);

                $subjects_code = array_merge($subjects_code,$subjects);

                

                for($i=0;$i < count($subjects_code);$i++){

                        $getMarkOfFirstUnitTest = $this->performance_model->getFirstInternaltMark($student_id,$subjects_code[$i],$exam_year);

                        $exam_mark_first_test[$i] = $getMarkOfFirstUnitTest;



                        $getMarkOfMidTerm = $this->performance_model->getMidTermExamMark($student_id,$subjects_code[$i],$exam_year);

                        $exam_mark_mid_term[$i] = $getMarkOfMidTerm;



                        $getMarkOfSecondUnitTest = $this->performance_model->getSecondInternalMark($student_id,$subjects_code[$i],$exam_year);

                        $exam_mark_second_test[$i] = $getMarkOfSecondUnitTest;



                        $getMarkOfFirstPreparatory = $this->performance_model->getFirstPreparatoryMark($student_id,$subjects_code[$i],$exam_year);

                        $exam_mark_first_preparatory[$i] = $getMarkOfFirstPreparatory;


                        $exam_type = array('ASSIGNMENT_I','ASSIGNMENT_II');

                        $total_mark = 0;

                        for($j=0;$j<count($exam_type);$j++){

                         $getAssignmentMarks[$j] = $this->performance_model->getStudentAssignmentExamMarks($student_id,$subjects_code[$i],$exam_type[$j]);

                        }

                        $exam_mark_assignment[$i] = $getAssignmentMarks;

                        
                        $exam_types = 'ASSIGNMENT_I';

                        $getAssignmentOneMarks = $this->performance_model->getStudentAssignmentExamMarks($student_id,$subjects_code[$i],$exam_types);

                        $exam_mark_assignment_one[$i] = $getAssignmentOneMarks;

                        $examType = 'ASSIGNMENT_II';

                        $getAssignmenttwowMarks = $this->performance_model->getStudentAssignmentExamMarks($student_id,$subjects_code[$i],$examType);

                        $exam_mark_assignment_two[$i] = $getAssignmenttwowMarks;

                        // }

                        // $exam_mark_assignment[$i] = $getAssignmentMarks;

                        // 'ASSIGNMENT_II'

                }


                $assignment_exam_marks = array_merge($exam_mark_assignment_one,$exam_mark_assignment_two);


                $total_assignment_mark = array();

                foreach($assignment_exam_marks as $assignmentMarks){



                        if(!empty($assignmentMarks->subject_code)){



                                // $total_assignment_mark[$assignmentMarks->subject_code] = 0;

                                $sub_marks = 0;

                                $mark_obt = 0;

                                

                                if($assignmentMarks->subject_code == 12){

                                        $labStatus = 'true';

                                }else{

                                        $labStatus = $assignmentMarks->lab_status;

                                }

                                if($assignmentMarks->exam_type == 'ASSIGNMENT_I' || $assignmentMarks->exam_type == 'ASSIGNMENT_II'){

                                        if($assignmentMarks->obt_theory_mark == 'AB' || $assignmentMarks->obt_theory_mark == 'EXEM' || $assignmentMarks->obt_theory_mark == 'MP' || $assignmentMarks->obt_theory_mark ==  'ASGN'){

                                                $mark_obt = 0;

                                        }else{

                                                $sub_marks = $this->getAssessmentMark($assignmentMarks->obt_theory_mark,$assignmentMarks->exam_type,$labStatus,$assignmentMarks->subject_code);

                                                $mark_obt = $sub_marks;

                                        }

                                }



                                $total_assignment_mark[$assignmentMarks->subject_code] += $mark_obt;

                        }

                        

                }

                // log_message('debug','total_assignment_mark'.print_r($total_assignment_mark,true));

                

                $data['subjects_code'] = $subjects_code;

                $data['firstUnitTestMarkInfo'] = $exam_mark_first_test;

                $data['midTermExamMarkInfo'] = $exam_mark_mid_term;

                $data['SecondUnitTestMarkInfo'] = $exam_mark_second_test;

                $data['firstPreparatoryMarkInfo'] = $exam_mark_first_preparatory;

                $data['assignmentOneExamMarks'] = $exam_mark_assignment_one;

                $data['assignmentTwoExamMarks'] = $exam_mark_assignment_two;

                // $data['subjectInfo'] = $subjectInfo;

                $data['assignmentExamMarks'] = $total_assignment_mark;
        $data['record'] = $this->student_model->getStudentMarksSheetByStudentId($student_id);
        $this->global['pageTitle'] = ''.TAB_TITLE.' : My Performance' ;
        $this->load->view("student/performanceApp",$data);

    }

    public function testPerformance(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $rel_stud_id = $obj['row_id'];
        $data = $this->performance_model->getTestMarkInfo($rel_stud_id);
        echo json_encode($data);
    }

    //--------FEE PAYMENT---------
    //Paytm Token
    function paytmToken(){
        // log_message('debug','mmmmmidddd'.print_r(PAYTM_MERCHANT_MID,true));
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $studentRowId = $obj['row_id'];
        $fee_pay_type = $obj['pay_type'];
        $studentInfo = $this->student_model->getStudentInfoByRowId($studentRowId);

        $application_number = $studentInfo->application_no;
        $filter = array();   
        $filter['stream_name'] = $studentInfo->stream_name;
        $term_name = $filter['term_name'] = $studentInfo->term_name;
        if(strtoupper($studentInfo->elective_sub) == 'FRENCH'){
            $filter['lang_fee_status'] = true;
        }else{
            $filter['lang_fee_status'] = false;
        }
        $filter['category'] = strtoupper($studentInfo->category);
        $filter['fee_year'] = CURRENT_YEAR;
        $instalmentInfo = $this->admission->checkInstallmentAlreadyExistNew($application_number);
        if(!empty($instalmentInfo)){
            $data->instalment_amt = $instalmentInfo->amount;
        }
        if($studentInfo->is_admitted == 1){
            $filter['term_name'] = 'I PUC';
        }
        if($term_name == 'I PUC'){
            if($studentInfo->last_board_name == 'KARNATAKA STATE BOARD'){
                $filter['board_name'] = "SSLC";
            }else{
                $filter['board_name'] = "OTHER";
            }
            $total_fee_obj = $this->admission->getTotalFeeAmountIPuc($filter);
        }else{
            $filter['board_name'] = "SSLC";
            $total_fee_obj = $this->admission->getTotalFeeAmount($filter);
        }
        $total_fee_amount = $total_fee_obj->total_fee;
        $fee_amount = $total_fee_amount;       
        $paidFee = $this->admission->getReAdmissionTotalPaidAmount($application_number);
        $total_fee_amount -= $paidFee->paid_amount;
        $total_paid_amt = $paidFee->paid_amount;
        $total_fee_to_pay = $total_fee_amount;


        // $fee_pay_type = $this->security->xss_clean($this->input->post('CHECKOUT'));
        if($fee_pay_type == 'INSTALMENT'){
            $fee_amount = $instalmentInfo->amount;
        }else{
            $fee_amount = $total_fee_to_pay;
        }
         
        $paymentLog = array(
            'mid'=>PAYTM_MERCHANT_MID,
            'student_id' =>$studentInfo->application_no,
            'remarks' =>'Fee Payment 2022',
            'fee_amount' => $fee_amount,
            'payment_status' =>'PENDING',
            'payment_date' => date('Y-m-d'),
            'payment_time' => date('h:i:s'),
            'created_by' => $studentInfo->application_no,
            'created_date_time' => date('Y-m-d H:i:s')
        );
        $CUST_ID = "SJPUC".$studentInfo->application_no;
        $INDUSTRY_TYPE_ID = "Retail";
        $CHANNEL_ID = "WEB";
        $TXN_AMOUNT = $fee_amount;
        if($term_name == 'I PUC'){
            $response = $this->admission->addFeePaymentLogPaytm($paymentLog);
            if($response > 0){
                $ORDER_ID = '22IPU'.$response;
                $payInfo = array('order_id' =>$ORDER_ID);
                $this->admission->updatePaymentLogPaytm($payInfo, $response);
                $_SESSION['order_id'] = $ORDER_ID;
            }
        }else{
            $response = $this->admission->addFeePaymentLogPaytmReadmission($paymentLog);
            if($response > 0){
                $ORDER_ID = '22S'.$response;
                $payInfo = array('order_id' =>$ORDER_ID);
                $this->admission->updateFeePaymentLogPaytmReadmission($payInfo, $response);
                $_SESSION['order_id'] = $ORDER_ID;
            }
        }
        

        $paytmParams = array();
        $TXN_AMOUNT = $fee_amount;
        $paytmParams["body"] = array(
            "requestType" => "Payment",
            "mid" => PAYTM_MERCHANT_MID,
            "websiteName" => "",
            "orderId" => $ORDER_ID,
            "callbackUrl" => "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=$ORDER_ID",
            "txnAmount"  => array(
                "value" => $TXN_AMOUNT,
                "currency" => "INR",
            ),
            "userInfo" => array(
                "custId" => $studentRowId,
            ),
        );

        /*
        * Generate checksum by parameters we have in body
        * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
        */
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), PAYTM_MERCHANT_KEY);

        $paytmParams["head"] = array(
            "signature" => $checksum
        );
        $PAYTM_MERCHANT_MID = PAYTM_MERCHANT_MID;
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
        /* for Staging */
        // $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=$PAYTM_MERCHANT_MID&orderId=$ORDER_ID";

        /* for Production */
        $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=$PAYTM_MERCHANT_MID&orderId=$ORDER_ID";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        $myresponse = json_decode($response);
        // log_message('debug',print_r($myresponse,true));
        $myresponse->order_id = $ORDER_ID;
        $myresponse->fee_amount = $TXN_AMOUNT;
        print_r(json_encode($myresponse));
      
    }

    //FEE payment Response
    public function feePaymentResponse(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $studentRowId = $obj['row_id'];
        $response = $obj['response'];
        
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        // log_message('debug',print_r($response,true));
        // log_message('debug',print_r($response['TXNAMOUNT'],true));
        // echo json_encode($response);

        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";

        $filter = array();
        $studentInfo = $this->student_model->getStudentInfoByRowId($studentRowId);
     
        $data['instalment_status'] = true;
        $paid_fee_amount = $response['TXNAMOUNT'];

            
        // $paytmChecksum = $response['CHECKSUMHASH']!=null ? $response['CHECKSUMHASH'] : ""; //Sent by Paytm pg
        $data['application_applied_status'] = false;
        // // Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        // $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.
        // $data['isValidChecksum'] = $isValidChecksum;
        $data['paramList'] = $paramList;
        $data['payment_status'] = false;
        $data['payment_done_now'] = false;
        $total_fee_pending_to_pay = $total_fee;
        // if($isValidChecksum == true){ 

        if($studentInfo->term_name == 'II PUC'){
            if($response['STATUS'] == 'TXN_SUCCESS'){ 

                if($studentInfo->term_name == 'II PUC'){}
                $filter = array();
                $filter['stream_name'] = $studentInfo->stream_name;
                $filter['term_name'] = 'II PUC';
                $filter['fee_year'] =  CURRENT_YEAR;
                if(strtoupper($studentInfo->elective_sub) == 'FRENCH'){
                    $filter['lang_fee_status'] = true;
                }else{
                    $filter['lang_fee_status'] = false;
                }
            
                $filter['category'] = strtoupper($studentInfo->category);
                if($studentInfo->is_admitted == 1){
                    $filter['term_name'] = 'I PUC';
                }
                $filter['board_name'] = "SSLC";
                $totalFeeObj = $this->admission->getTotalFeeAmount($filter);
                $feeStructureInfo = $this->admission->getFeeStructureInfo2021($filter);
                $total_fee_pending_to_pay = $totalFeeObj->total_fee;
           
                $fee_excess_amount = 0;
                $fee_pending_status = 1;
                $pending_fee_balance = 0;
                $receipt_number = 0;
                $totalPaid = $this->admission->getReAdmissionTotalPaidAmount($studentInfo->application_no);
                $responseStatus =  $this->admission->getReAdmission_PaymentByOrderId($response["ORDERID"]);
                $paid_fee_amount = $response['TXNAMOUNT'];
                    if($totalPaid->paid_amount != 0){
                        $total_fee_pending_to_pay -= $totalPaid->paid_amount;
                    }
                    
                    $pending_fee_balance = $total_fee_pending_to_pay - $paid_fee_amount;
                    if($pending_fee_balance <= 0){
                        $fee_excess_amount = abs($pending_fee_balance);
                        $fee_pending_status = 0;
                    }else if($pending_fee_balance > 0){
                        $fee_excess_amount = 0;
                        $fee_pending_status = 1;
                    }
                    $feePaymentInfo = $this->admission->getReadmission_FeePaidDetailsByApplicationNo($studentInfo->application_no);
                    if(empty($feePaymentInfo)){
                        $paid_count = 1;
                    }else{
                        $paid_count = $feePaymentInfo->payment_count+1;
                    }
                    $receipt_number = $this->admission->getLastReceiptNoFromOverall();
                    if(empty($receipt_number)){
                        $receipt_number = 0;
                    }
                    $receipt_number += 1;
                    $receipt_number = sprintf('%04d', $receipt_number);
                    $overallFee = array(
                        'receipt_number'=> $receipt_number,
                        'application_no' => $studentInfo->application_no,
                        'payment_type' => 'ONLINE',
                        'payment_date' => date('Y-m-d',strtotime($response['TXNDATE'])),
                        'total_amount' => $total_fee_pending_to_pay,
                        'paid_amount' => $paid_fee_amount,
                        'excess_amount' => $fee_excess_amount,
                        'fee_concession' => 0,
                        'payment_year' => CURRENT_YEAR,
                        'term_name' => 'II PUC',
                        'pending_balance' => $pending_fee_balance,
                        'fee_pending_status' => $fee_pending_status,
                        'payment_count' => $paid_count,
                        'order_id' => $response["ORDERID"],
                        'collected_staff_name' => 'schoolphins',
                        'created_by' => $studentInfo->application_no,
                        'created_date_time' => date('Y-m-d H:i:s'));
                    $row_id = $this->admission->addReadmission_FeeDetailsInfo($overallFee);
                    $installmentAmtExist = $this->admission->checkInstallmentAlreadyExistNew($studentInfo->application_no);
                     if(!empty($installmentAmtExist)){
                        $instalUpdate = array(
                            'payment_status' =>1,
                            'amount' => $response['TXNAMOUNT'],
                            'receipt_number' => $row_id,
                            'updated_by' => $studentInfo->application_no,
                            'updated_date_time' => date('Y-m-d H:i:s')
                        );
                        $this->admission->updateInstalmentNew($instalUpdate, $studentInfo->application_no);
                     }
                  
                     $paymentLogUpdate = array(
                        'payment_mode' => $response['PAYMENTMODE'],
                        'reference_number'=>$response['TXNID'],
                        'payment_status' =>$response['STATUS'],
                        'receipt_number' =>$row_id,
                        'amount_pending' =>$pending_fee_balance,
                        'fee_amount' => $response['TXNAMOUNT'],
                        'updated_by' => $studentInfo->application_no,
                        'updated_date_time' => date('Y-m-d H:i:s')
                    );
                  
                    $fee_amount_balance_pending = $paid_fee_amount;
                    $remaining_fee_amt = $paid_fee_amount;
                    foreach($feeStructureInfo as $fee){
                        $db_save_status = false;
                        $fee_structure_amt = $fee->fee_amount_state_board;
                        $isAlreadyPaid = $this->admission->checkFeeTypeIsAlreadyPaid($studentInfo->application_no,$fee->row_id);
                        if($remaining_fee_amt >= 0){
                            if(!empty($isAlreadyPaid)){
                                if($isAlreadyPaid->pending_status == 1){
                                    $remaining_fee_amt -= $isAlreadyPaid->pending_amt;
                                    if($remaining_fee_amt >= 0){
                                        //$pending_amount = 0;
                                        $paid_amt = $isAlreadyPaid->pending_amt;
                                        $pending_amt = 0;
                                        $fee_pending_status = 0;
                                    } else {
                                        //$dd_amount = 0; 
                                        $paid_amt = $isAlreadyPaid->pending_amt - abs($remaining_fee_amt);
                                        $pending_amt = $isAlreadyPaid->pending_amt - $paid_amt;
                                        $fee_pending_status = 1;
                                    } 
                                    $db_save_status = true;
                                }
                            }else{
                                $remaining_fee_amt -= $fee_structure_amt;
                                if($remaining_fee_amt >= 0){
                                    //$pending_amount = 0;
                                    $paid_amt = $fee_structure_amt;
                                    $pending_amt = 0;
                                    $fee_pending_status = 0;
                                } else {
                                    //$dd_amount = 0; 
                                    $paid_amt = $fee_structure_amt - abs($remaining_fee_amt);
                                    $pending_amt = $fee_structure_amt - $paid_amt;
                                    $fee_pending_status = 1;
                                } 
                                $db_save_status = true;
                            }
                        }else{
                            if(empty($isAlreadyPaid)){
                            $pending_amt = $fee_structure_amt;
                            $paid_amt = 0;
                            $fee_pending_status = 1;
                            $db_save_status = true;
                            }
                        }
                        if($db_save_status){
                            $feeReceiptPayment = array(
                                'application_no' => $studentInfo->application_no,
                                'receipt_number' => $row_id,
                                'payment_date' => date('Y-m-d',strtotime($response['TXNDATE'])), 
                                'fee_type_id' => $fee->row_id,
                                'paid_amount' => $paid_amt,
                                'pending_amt' => $pending_amt,
                                'pending_status' => $fee_pending_status,
                                'school_account_id' => $fee->account_row_id,
                                'created_by' => 'schoolphins',
                                'fee_amount' => $fee_structure_amt,
                                'created_date_time' => date('Y-m-d H:i:s'));
                                
                            $receipt_return_feeType = $this->admission->addReceiptFeeType2021($feeReceiptPayment);
                        }
                    
                    }
                }else{
                    $paymentLogUpdate = array(
                        'payment_status' =>$response['STATUS'],
                        'fee_amount' => $response['TXNAMOUNT'],
                        'updated_by' => $studentInfo->application_no,
                        'updated_date_time' => date('Y-m-d H:i:s')
                    );
                }
                $this->admission->updateReadmission_PaymentLogByOrderIdPaytm($paymentLogUpdate, $response["ORDERID"]);
            }else{
                $filter = array();
                $filter['stream_name'] = $studentInfo->stream_name;
                $filter['term_name'] = 'I PUC';
                if(strtoupper($studentInfo->elective_sub) == 'FRENCH'){
                    $filter['lang_fee_status'] = true;
                }else{
                    $filter['lang_fee_status'] = false;
                }
            // $catInfo = $this->admission_model->getStudentCategoryByApplicationNum($studentInfo->application_no);
                $filter['category'] = strtoupper($studentInfo->category);
            
            
                //for display installment button
            // $data['installmentAmtExist'] = $this->admission_model->checkInstallmentAlreadyExist($studentInfo->student_id);;
            //$boardInfo = $this->student_model->getStudentRegisteredInfo($this->student_row_id);
            if($studentInfo->last_board_name == 'KARNATAKA STATE BOARD'){
                $filter['board_name'] = "SSLC";
            }else{
                $filter['board_name'] = "OTHER";
            }
                $totalFeeObj = $this->admission->getTotalFeeAmountIPuc($filter);
                $total_fee_pending_to_pay = $totalFeeObj->total_fee;
                //get overall fee paid info 
                $feePaidInfo = $this->admission->getFeePaidInfo($studentInfo->application_no);
                $fee_excess_amount = 0;
                $fee_pending_status = 1;
                $pending_fee_balance = 0;
                $receipt_number = 0;
                $isOrderIDExist =  $this->admission->isOrderIDExist($response["ORDERID"]);
                $paid_fee_amount = $response["TXNAMOUNT"];
            if(empty($isOrderIDExist) && $isValidChecksum == true){
                if($response['STATUS'] == 'TXN_SUCCESS'){
                    if(!empty($feePaidInfo)){
                        foreach($feePaidInfo as $paid){
                            $total_fee_pending_to_pay -= $paid->paid_amount;
                        }
                    }
                    $pending_fee_balance = $total_fee_pending_to_pay - $paid_fee_amount;
                    if($pending_fee_balance <= 0){
                        $fee_excess_amount = abs($pending_fee_balance);
                        $fee_pending_status = 0;
                    }else if($pending_fee_balance > 0){
                        $fee_excess_amount = 0;
                        $fee_pending_status = 1;
                    }
        
                    $feePaymentInfo = $this->admission->getReadmission_FeePaidDetailsByApplicationNo($studentInfo->application_number);
                    if(empty($feePaymentInfo)){ 
                        $paid_count = 1;
                    }else{
                        $paid_count = $feePaymentInfo->payment_count+1;
                    }
        
                    $receipt_number = $this->admission->getLastReceiptNoFromOverallI();
                    if(empty($receipt_number)){
                        $receipt_number = 0;
                    }
                    $receipt_number += 1;
                    $receipt_number = sprintf('%04d', $receipt_number);
        
                    $overallFee = array(
                        'receipt_number'=> $receipt_number,
                        'application_no' => $studentInfo->application_no,
                        'payment_type' => 'ONLINE',
                        'payment_date' => date('Y-m-d',strtotime($response['TXNDATE'])),
                        'total_amount' => $total_fee_pending_to_pay,
                        'paid_amount' => $paid_fee_amount,
                        'excess_amount' => $fee_excess_amount,
                        'fee_concession' => 0,
                        'payment_count' => $paid_count,
                        'pending_balance' => $pending_fee_balance,
                        'fee_pending_status' => $fee_pending_status,
                        'order_id' => $response["ORDERID"],
                        'payment_year' => CURRENT_YEAR,
                        'term_name' => 'I PUC',
                        'collected_staff_name' => 'parrophins',
                        'created_by' => 'schoolphins',
                        'created_date_time' => date('Y-m-d H:i:s'));
                    $overall_row_id = $this->admission->addReadmission_FeeDetailsInfo($overallFee);
            
                    //update installment exist only
                    $installmentAmt = $this->admission->getInstalmentByApplicationNo($studentInfo->application_no);
                    if(!empty($installmentAmt)){
                        $instllInfo = array(
                            'payment_status'=>1,
                            'invoice_no' => $overall_row_id,
                            'updated_by'=>'schoolphins',
                            'updated_date_time'=>date('Y-m-d H:i:s'));
                        $this->admission->updateNewAdmInstalment($instllInfo, $studentInfo->application_no);
                    }
                    $feeStructureInfo = $this->admission->getAllFeeStructureInfoIPuc($filter);
                    $fee_amount_balance_pending = $paid_fee_amount;
                    $remaining_fee_amt = $paid_fee_amount;
                    foreach($feeStructureInfo as $fee){
                        $db_save_status = false;
                        $fee_structure_amt = $fee->fee_amount_state_board;
                        $isAlreadyPaid = $this->admission->checkFeeTypeIsAlreadyPaid($studentInfo->application_no,$fee->row_id);
                        if($remaining_fee_amt >= 0){
                            if(!empty($isAlreadyPaid)){
                                if($isAlreadyPaid->pending_status == 1){
                                    $remaining_fee_amt -= $isAlreadyPaid->pending_amt;
                                    if($remaining_fee_amt >= 0){
                                        //$pending_amount = 0;
                                        $paid_amt = $isAlreadyPaid->pending_amt;
                                        $pending_amt = 0;
                                        $fee_pending_status = 0;
                                    } else {
                                        //$dd_amount = 0; 
                                        $paid_amt = $isAlreadyPaid->pending_amt - abs($remaining_fee_amt);
                                        $pending_amt = $isAlreadyPaid->pending_amt - $paid_amt;
                                        $fee_pending_status = 1;
                                    } 
                                    $db_save_status = true;
                                }
                            }else{
                                $remaining_fee_amt -= $fee_structure_amt;
                                if($remaining_fee_amt >= 0){
                                    //$pending_amount = 0;
                                    $paid_amt = $fee_structure_amt;
                                    $pending_amt = 0;
                                    $fee_pending_status = 0;
                                } else {
                                    //$dd_amount = 0; 
                                    $paid_amt = $fee_structure_amt - abs($remaining_fee_amt);
                                    $pending_amt = $fee_structure_amt - $paid_amt;
                                    $fee_pending_status = 1;
                                } 
                                $db_save_status = true;
                            }
                        }else{
                            if(empty($isAlreadyPaid)){
                            $pending_amt = $fee_structure_amt;
                            $paid_amt = 0;
                            $fee_pending_status = 1;
                            $db_save_status = true;
                            }
                        }
                        if($db_save_status){
                            $feeReceiptPayment = array(
                                'application_no' => $studentInfo->application_no,
                                'receipt_number' => $overall_row_id,
                                'payment_date' => date('Y-m-d',strtotime($response['TXNDATE'])), 
                                'fee_type_id' => $fee->row_id,
                                'paid_amount' => $paid_amt,
                                'pending_amt' => $pending_amt,
                                'pending_status' => $fee_pending_status,
                                'school_account_id' => $fee->account_row_id,
                                'created_by' => 'schoolphins',
                                'fee_amount' => $fee_structure_amt,
                                'created_date_time' => date('Y-m-d H:i:s'));
                                
                            $receipt_return_feeType = $this->admission->addReceiptFeeType2021($feeReceiptPayment);
                        }
                    
                    }
                     
                     $paymentLogUpdate = array(
                        'payment_mode' => $response['PAYMENTMODE'],
                        'reference_number'=>$response['TXNID'],
                        'payment_status' =>'SUCCESS',
                        'receipt_number' =>$receipt_number,
                        'amount_pending' =>$pending_fee_balance,
                        'fee_amount' => $response['TXNAMOUNT'],
                        'updated_by' => $studentInfo->application_no,
                        'updated_date_time' => date('Y-m-d H:i:s')
                    );
                    $applicationStatus = array(
                        'joined_status' => 1,
                        'admission_status'=> 1,
                        'updated_date_time' => date('Y-m-d H:i:s'));
                    $this->student_model->updateStudentApplicationStatus($this->student_row_id,$applicationStatus);
                    $mobile = $studentInfo->father_mobile.','.$studentInfo->mother_mobile;
                    $msg = 'Dear Student, %n Thank you, %n Received Rs.'.$paid_fee_amount.' towards annual fees, Your receipt number: '.$receipt_number.' %n Principal - SJPUCB';
                    $res = $this->sendSingleNumberSMS($mobile,$msg);
                }else{
                    $paymentLogUpdate = array(
                        'payment_status' =>'FAILED',
                        'fee_amount' => $response['TXNAMOUNT'],
                        'updated_by' => $studentInfo->application_no,
                        'updated_date_time' => date('Y-m-d H:i:s')
                    );
                }
                $data['receipt_number'] = $overall_row_id;
                $data['pending_fee_balance'] = $pending_fee_balance;
                $this->admission->updatePaymentLogByOrderIdPaytm($paymentLogUpdate, $response["ORDERID"]);
               
                // $stdInfo = array(
                //     'term_name' => 'II PUC',
                //     'updated_date_time' =>date('Y-m-d H:i:s'),
                //     'fee_pending_status' => $fee_pending_status,
                // );
               // $this->admission_model->promoteStudent($stdInfo, $studentInfo->application_no);
               }else{
                $data['receipt_number'] = $responseStatus->receipt_number;
                $data['pending_fee_balance'] = $responseStatus->amount_pending;
               }

            }
                echo json_encode("success");
            
            }   
    


    //FEE payment Info
    function overAllFeePaidInfo(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $studentRowId = $obj['row_id'];

        $stud = $this->student_model->getStudentInfoByRowId($studentRowId);
        // $studentRowId = "2";
        $data = $this->admission->getFeePaidInfo($stud->application_no);
        echo json_encode($data);
    }
    
    public function viewFeePaymentInfo(){	
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $studentRowId = $obj['row_id'];
	    $requestParamList = array();
	    $responseParamList = array();
        
        $studentInfo = $this->student_model->getStudentInfoByRowId($studentRowId);
        $dt = array();
        $data = (object)$dt;
        $data->instalment_status = false;
        $data->application_no = $application_number = $studentInfo->application_no;
        $filter = array();   
        $filter['stream_name'] = $studentInfo->stream_name;
        $term_name = $filter['term_name'] = $studentInfo->term_name;
        if(strtoupper($studentInfo->elective_sub) == 'FRENCH'){
            $filter['lang_fee_status'] = true;
        }else{
            $filter['lang_fee_status'] = false;
        }
        $filter['category'] = strtoupper($studentInfo->category);
        $filter['fee_year'] = CURRENT_YEAR;
        $instalmentInfo = $this->admission->checkInstallmentAlreadyExistNew($application_number);
        if(!empty($instalmentInfo)){
            $data->instalment_status = true;
            $data->instalment_amt = $instalmentInfo->amount;
        }
        if($studentInfo->is_admitted == 1){
            $filter['term_name'] = 'I PUC';
        }
        if($term_name == 'I PUC'){
            if($studentInfo->last_board_name == 'KARNATAKA STATE BOARD'){
                $filter['board_name'] = "SSLC";
            }else{
                $filter['board_name'] = "OTHER";
            }
            $total_fee_obj = $this->admission->getTotalFeeAmountIPuc($filter);
        }else{
            $filter['board_name'] = "SSLC";
            $total_fee_obj = $this->admission->getTotalFeeAmount($filter);
        }
        $total_fee_amount = $total_fee_obj->total_fee;
        $data->fee_amount = $total_fee_amount;       
        $paidFee = $this->admission->getReAdmissionTotalPaidAmount($application_number);
        $total_fee_amount -= $paidFee->paid_amount;
        $data->total_paid_amt = $paidFee->paid_amount;
        $data->total_fee_to_pay = $total_fee_amount;
        $data->elective = $studentInfo->elective_sub;;
        $data->category = $studentInfo->category;
        echo json_encode($data);
    }

    //REMARK
    public function myRemarks(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $studentRowId = $obj['row_id'];
        $studentData = $this->student_model->getStudentApplicationNoByID($studentRowId);
        $data = $this->remarks_model->getRemarkInfoApi($studentData->application_no);
        echo json_encode($data);
    }


    //Late Arrival
    public function lateToClassListing(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $student_id = $obj['student_id'];
        $latecomerInfo = $this->student_model->getStudentLateInfo($student_id);
        $db_data = array();
        foreach($latecomerInfo as $info){
            $db_data[] = $info;
        }
        $data = json_encode($db_data);               
        echo $data;

    }

    public function deleteAccount(){
        $json = file_get_contents('php://input'); 
        $obj = json_decode($json,true);
        $student_id = $obj['student_id'];
        $info = array('is_deleted' => 1);
        $return = $this->student_model->updateRegistration($student_id,$info);
        $data = json_encode($return);
        echo $data;
    }

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
        //log_message('error', 'JSON='.print_r($json));
        curl_close($ch);
        return $status;
    }
        
    function getTermID($term_name) {
        $KGI = 1;
        $KGII = 2;
        $I = 3;
        $II = 4;
        $III = 5;
        $IV = 6;
        $V = 7;
        $VI = 8;
        $VII = 9;
        $VIII = 10;
        $IX = 11;
        $X = 12;
        switch ($term_name) {
            case "KG I":
                    return $KGI;
                    break;
            case "KG II":
                    return $KGII;
                    break;
            case "I":
                    return  $I;
                    break;
            case "II":
                    return $II;
                    break;
            case "III":
                    return $III;
                    break;
            case "IV":
                    return $IV;
                    break;
            case "V":
                    return $V;
                    break;
            case "VI":
                    return $VI;
                    break;
            case "VII":
                    return $VII;
                    break;
            case "VIII":
                    return $VIII;
                    break;
            case "IX":
                    return $IX;
                    break;
            case "X":
                    return $X;
                    break;
        }
    }

    
	
    public function getSubjectCodes($stream_name){
        //science
        $PCMB = array("33", "34", "35", '36');
        $PCMC = array("33", "34", "35", '41');
        $PCME = array("33", "34", "35", '40');
        $PCMS = array("33", "34", "35", '31');
        //commarce
        $BEBA = array("75", "22", "27", '30');
        $BSBA = array("75", "31", "27", '30');
        $CSBA = array("41", "31", "27", '30');
        $SEBA = array("31", "22", "27", '30');
        $CEBA = array("41", "22", "27", '30');
        $PEBA = array("29", "22", "27", '30');
        //art
        $HEPP = array("21", "22", "32", '29');
        $MEBA = array("75", "22", "27", '30');
        $MSBA = array("75", "31", "27", '30');
        $HEPS = array("21", "22", "29", '28');

        switch ($stream_name) {
            case "PCMB":
                return  $PCMB;
                break;
            case "PCMC":
                return $PCMC;
                break;
            case "PCME":
                return $PCME;
                break;
            case "PCMS":
                return $PCMS;
                break;
            case "PEBA":
                return $PEBA;
                break;
            case "BEBA":
                return $BEBA;
                break;
            case "BSBA":
                return $BSBA;
                break;
            case "CSBA":
                return $CSBA;
                break;
            case "SEBA":
                return $SEBA;
                break;
            case "CEBA":
                return $CEBA;
                break;
            case "HEPP":
                return $HEPP;
                break;
            case "HEPS":
                return $HEPS;
                break;
            case "MEBA":
                return $MEBA;
                break;
            case "MSBA":
                return $MSBA;
                break;
        }
    }




function getAssessmentMark($totalMark,$exam_type,$labStatus,$subject_code){

    if(is_numeric($totalMark) && !empty($totalMark)){

            if($labStatus == 'false'){ 

                    if($exam_type == 'ASSIGNMENT_I' || $exam_type == 'ASSIGNMENT_II'){

                    if($totalMark >= 81 && $totalMark <= 100){

                            return '30';

                    }else if($totalMark >= 71 && $totalMark <= 80){

                            return '25';

                    }else if($totalMark >= 61 && $totalMark <= 70){

                            return '20';

                    }else if($totalMark >= 51 && $totalMark <= 60){

                            return '15';

                    }else if($totalMark >= 41 && $totalMark <= 50){

                            return '10';

                    }else{

                            return '5';

                    }

                    }

            }else{

                    if($exam_type == 'ASSIGNMENT_I' && $subject_code == '12' || $exam_type == 'ASSIGNMENT_II' && $subject_code == '12'){

                            if($totalMark >= 26 && $totalMark <= 35){

                                    return '4';

                            }else if($totalMark >= 36 && $totalMark <= 45){

                                    return '8';

                            }else if($totalMark >= 46 && $totalMark <= 55){

                                    return '12';

                            }else if($totalMark >= 56 && $totalMark <= 65){

                                    return '16';

                            }else if($totalMark >= 66 && $totalMark <= 75){

                                    return '20';

                            }else{

                                    return '25';

                            }

                            }else if($exam_type == 'ASSIGNMENT_I' || $exam_type == 'ASSIGNMENT_II'){

                            if($totalMark >= 1 && $totalMark <= 28){

                                    return '4';

                            }else if($totalMark >= 29 && $totalMark <= 35){

                                    return '8';

                            }else if($totalMark >= 36 && $totalMark <= 42){

                                    return '12';

                            }else if($totalMark >= 43 && $totalMark <= 49){

                                    return '16';

                            }else if($totalMark >= 50 && $totalMark <= 56){

                                    return '19';

                            }else{

                                    return '22';

                            }

                    }

            }

    }else{

            return '';

    }

}


//---------------Event--------------------
public function upcomingEvent(){
    $filter = array();
    $json = file_get_contents('php://input'); 
    $obj = json_decode($json,true);
   
    $eventInfo = $this->student_model->getEvents();
    foreach($eventInfo as $info){
            $info->date =date('d-m-Y',strtotime($info->date));
            $info->time =date('h:m a',strtotime($info->time));
        $db_data[] = $info;
    }
    $data = json_encode($db_data);
    echo $data;
    }

//---------------Calender----------
public function calender(){
    $filter = array();
    $json = file_get_contents('php://input'); 
    $obj = json_decode($json,true);
    $calenderInfo = $this->student_model->getCalender();
    $data = json_encode($calenderInfo);
    echo $data;
}    

public function absentDetails(){
    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
    $student_id = $obj['student_id'];
    $absentInfo= $this->student_model->getabsentDetails($student_id);
    log_message('debug','dwfw'.print_r($absentInfo,true));
    $data = json_encode($absentInfo);
    echo $data;
    
}

}
 
?>