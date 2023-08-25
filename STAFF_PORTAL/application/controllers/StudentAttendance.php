<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseControllerFaculty.php';

class StudentAttendance extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('studentAttendance_model','attendance');
        $this->load->model('settings_model','settings');
        $this->load->model('students_model','student');
        $this->load->model('subjects_model','subject');
        $this->load->model('staff_model','staff');
        $this->load->model('timetable_model','timetable');
        $this->load->model('push_notification_model');
        $this->load->library('pagination');
        $this->load->library('excel');
        $this->isLoggedIn();   
    }

    public function getAttendanceDetails(){
        if($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $filter = array();
            $attendance_date = $this->security->xss_clean($this->input->post('attendance_date'));
            $todayDate = date('Y-m-d');
            $weekname = date('l', strtotime($todayDate));
            if(!empty($attendance_date)){
                $data['attendance_date'] = date('d-m-Y',strtotime($attendance_date));
                $filter['attendance_date'] = date('l',strtotime($attendance_date));
                $data['attendanceDate']= date('d-m-Y', strtotime($attendance_date));
                $filter['search_date'] = date('Y-m-d',strtotime($attendance_date));
            }else{
                $data['attendance_date'] = '';
                $data['attendanceDate']= date('d-m-Y', strtotime($todayDate));
                $filter['weekName'] = $weekname;
                $filter['search_date'] = date('Y-m-d');
            }
            
            if($this->role == ROLE_TEACHING_STAFF){
                $filter['staff_id'] = $this->staff_id;
                $data['staff_id'] = $this->staff_id;
            }else{
                $data['staff_id'] = '';
            }

            $data['streamSectionInfo'] = $this->staff->getSectionById($filter);
            $data['timingsInfo'] = $this->settings->getAllClassTimingsInfo();
            $data['staffSubjectInfo'] = $this->staff->getAllSubjectInfo($filter);
            $data['classCompletedInfo'] = $this->attendance->getAttendanceClassCompletedInfo();

            // $isExists = $this->attendance->CheckTimetableDayShiftExists($filter);
            // if(!empty($isExists)){
            //     $filter['week'] = $isExists->week_name;
            //     $filter['attendance_date'] = $isExists->week_name;
            //     $count = $this->attendance->getShiftTimetableInfoCount($filter);
            //     $returns = $this->paginationCompress("getAttendanceDetails/", $count, 100);
            //     $data['attendanceInfo'] = $this->attendance->getShiftTimetableInfo($filter,$returns["page"], $returns["segment"]);
            // }else{
            //     $count = $this->attendance->getClassForAttendanceCount($filter);
            //     $returns = $this->paginationCompress("getAttendanceDetails/", $count, 100);
            //     $data['attendanceInfo'] = $this->attendance->getClassForAttendance($filter,$returns["page"], $returns["segment"]);
            // }
            
            // log_message('debug','dmkme='.print_r($data['attendanceInfo'],true));
            // $data['attendanceCount'] = $count;
            $this->global['pageTitle'] = ''.TAB_TITLE.' : Attendance Details';
            $this->loadViews("attendance/attendance", $this->global, $data, NULL);
        }
    }
    
    public function getStudentInfoForAttendance(){
        if($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $filter = array();
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $stream_name = $this->security->xss_clean($this->input->post('stream_name'));
            $subject_name = $this->security->xss_clean($this->input->post('subject_name'));
            $section_name = $this->security->xss_clean($this->input->post('section_name'));
            $subject_code = $this->security->xss_clean($this->input->post('subject_code'));
            $attendance_date = $this->security->xss_clean($this->input->post('attendance_date'));
            $time_row_id = $this->security->xss_clean($this->input->post('time_row_id'));
            $section_row_id = $this->security->xss_clean($this->input->post('section_row_id'));
            $staff_subject_row_id = $this->security->xss_clean($this->input->post('staff_subject_row_id'));
            $class_batch = $this->security->xss_clean($this->input->post('class_batch'));

            $filter['time_row_id'] = $time_row_id;
            $filter['section_row_id'] = $section_row_id;
            $filter['staff_subject_row_id'] = $staff_subject_row_id;
            $sectionInfo = $this->attendance->getSectionInfoByRowId($filter);
            $subjectInfo = $this->attendance->getSubjectByRowId($filter);
            $sectionName = $sectionInfo->section_name;

            $filter['term_name'] = $sectionInfo->term_name;
            $filter['stream_name'] = $sectionInfo->stream_name;
            $filter['subject_name'] = $subjectInfo->sub_name;
            if($sectionName == "ALL"){
                $filter['section_name'] = '';
            }else{
                $filter['section_name'] = $sectionName;
            }

            if(empty($class_batch)){
                $filter['class_batch'] = '';
                $data['class_batch'] = '';
            }else{
                $filter['class_batch'] = $class_batch;
                $data['class_batch'] = $class_batch;
            }
            
            $data['attendance_date'] = $attendance_date;
            $data['term_name'] = $sectionInfo->term_name;
            $data['stream_name'] = $sectionInfo->stream_name;
            $data['subject_name'] = $subjectInfo->sub_name;
            $data['section_name'] = $sectionName;
            $data['subject_code'] = $subjectInfo->subject_code;
            $data['subject_type'] = $subjectInfo->subject_type;
            $data['time_row_id'] = $time_row_id;
            $data['section_row_id'] = $section_row_id;
            $data['staff_subject_row_id'] = $staff_subject_row_id;
            $data['staff_id'] = $this->staff_id;
            
            $data['studentRecord'] = $this->student->getStudentInfoForInternal($filter);
            $data['classCompletedInfo'] = $this->attendance->checkClassCompletedInfo(date('Y-m-d',strtotime($attendance_date)),$subjectInfo->subject_code,$time_row_id,$sectionInfo->term_name,$sectionName,$sectionInfo->stream_name);
            $this->global['pageTitle'] = ''.TAB_TITLE.' : Attendance Details';
            $this->loadViews("attendance/takeAttendance", $this->global, $data, NULL);
        }
    }

    public function addSingleSubjectAttendanceByStaff(){
        if ($this->isAdmin() == true) {
            $this->loadThis();
        } else {
            $filter = array();
            $attInfo = json_decode(stripslashes($this->input->post('attInfo')));
            $students = array();
            foreach($attInfo as $info){
                if($info->name == 'staff_subject_row_id'){
                    $staff_subject_row_id = $info->value;
                }
                if($info->name == 'attendance_date'){
                    $attendance_date = $info->value;
                }
                if($info->name == 'time_row_id'){
                    $time_row_id = $info->value;
                }
                if($info->name == 'section_row_id'){
                    $section_row_id = $info->value;
                }
                if($info->name == 'subject_code'){
                    $subject_code = $info->value;
                }
                if($info->name == 'term_name'){
                    $term_name = $info->value;
                }
                if($info->name == 'stream_name'){
                    $stream_name = $info->value;
                }
                if($info->name == 'section_name'){
                    $section_name = $info->value;
                }
                if($info->name == 'subject_name'){
                    $subject_name = $info->value;
                }
                if($info->name == 'subject_type'){
                    $subject_type = $info->value;
                }
                if($info->name == 'student_batch'){
                    $student_batch = $info->value;
                }
                if($info->value == true){
                    $students[$info->name] = $info->name;
                }
                
            }

            $attendanceDate = date("Y-m-d", strtotime($attendance_date));
            $filter['term_name'] = $term_name;
            $filter['stream_name'] = $stream_name;
            $filter['subject_name'] = $subject_name;
            $filter['student_id'] = $students;
            $sectionName = $section_name; 
            if($section_name == "ALL"){
                $filter['section_name'] = '';
            }else{
                $filter['section_name'] = $sectionName;
            }
            
            if(empty($student_batch)){
                $filter['class_batch'] = '';
                $student_batch = '';
            }else{
                $filter['class_batch'] = $student_batch;
                $student_batch = $student_batch;
            }
            // ,$this->staff_id
            $isExistsClass = $this->attendance->getclassCompletedInfo($attendanceDate,$subject_code,$time_row_id,$term_name,$section_name,$stream_name,$subject_type,$student_batch);
            if($isExistsClass == NULL){
                $subInfo = array(
                    'date' => $attendanceDate,
                    'subject_code' => $subject_code,
                    'staff_id' => $this->staff_id,
                    'term_name' => $term_name,
                    'section_name' => $section_name,
                    'stream_name' => $stream_name,
                    'time_id' => $time_row_id,
                    'subject_type' => $subject_type,
                    'batch' => $student_batch,
                    'class_year' => CURRENT_YEAR,
                    'created_date_time' => date('Y-m-d H:i:s'),
                    'created_by' => $this->staff_id);
                $result = $this->attendance->addStaffTeachedSubjectInfo($subInfo);
                $class_row_id = $result;
            }else{
                $subInfo = array(
                    'term_name' =>$term_name,
                    'section_name' =>$section_name,
                    'stream_name' => $stream_name,
                    'subject_type' => $subject_type,
                    'batch' => $student_batch,
                    'class_year' => CURRENT_YEAR,
                    'updated_date_time' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->staff_id);
                    // $this->staff_id, 
                $result = $this->attendance->updateStaffTeachedSubjectInfo($subject_code,$attendanceDate,$time_row_id,$subInfo,$term_name,$section_name,$stream_name,$subject_type,$student_batch);
                $class_row_id = $isExistsClass->row_id;
            }
            

            $this->attendance->deleteAllStudents($subject_code,$attendanceDate,$time_row_id,$section_row_id,$student_batch);
            //,$staff_subject_row_id       
            $data['studentRecords'] = $this->student->getStudentInfoForInternal($filter);
            $subName = $this->subject->getAllSubjectByID($subject_code);
            $timeID = $this->timetable->getTimeInfoByRowID($time_row_id);
            //$this->attendance->getStudentInfoForAttendance($filter);
            foreach($data['studentRecords'] as $student){
                if($students[$student->student_id] == $student->student_id){
                    $attendanceInfo = array (
                        'class_section_row_id' => $section_row_id,
                        'staff_subject_row_id' => $staff_subject_row_id,
                        'class_row_id' => $class_row_id,
                        'student_id' => $student->student_id,
                        'subject_code' => $subject_code,
                        'absent_date' => $attendanceDate,
                        'time_row_id' => $time_row_id,
                        'student_batch' => $student_batch,
                        'year' => CURRENT_YEAR,
                        'sms_sent_status' => 0,
                        'office_verified_status' => 0,
                        'created_by' => $this->staff_id,
                        'created_date_time' => date('Y-m-d H:i:s'));
                    $result =  $this->attendance->addAbsentStudentInfo($attendanceInfo);
                    $sub_name = $this->subject->getSubjectInfoById($subject_code);
                    //FCM////////////
                    $notification_message = "Dear Parent,Your ward ".$student->student_name." was absent for the ".$subName->sub_name." class on ".date('d-m-Y',strtotime($attendanceDate))." between ".$timeID->start_time." to ".$timeID->end_time." .- ST XAVIER'S PRE–UNIVERSITY COLLEGE";
                    // $message = 'Your ward is absent for '.$sub_name->name.' on '.date('d-m-Y');
                    $all_users_token = $this->push_notification_model->getSingleStudentsToken($student->student_id);
                    $tokenBatch = array_chunk($all_users_token,500);
                    for($itr = 0; $itr < count($tokenBatch); $itr++){
                        $this->push_notification_model->sendMessage('Absent For Class',$notification_message,$tokenBatch[$itr],"student");
                    }
                    //FCM///////////
                }
            }
            if($result > 0){
                echo 'true';
            }else{
                echo 'false';
            }
        }
    }

    
    public function viewAttendanceInfo() {
        if($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {        
            $filter = array();
            // $searchTerm = $this->security->xss_clean($this->input->post('searchTerm'));
            $absentDate =$this->security->xss_clean($this->input->post('absentDate')); 
            $staff_name =$this->security->xss_clean($this->input->post('staff_name')); 
            $student_id =$this->security->xss_clean($this->input->post('student_id')); 
            $subject_id =$this->security->xss_clean($this->input->post('subject_id')); 
            $subject_type =$this->security->xss_clean($this->input->post('subject_type')); 
            $time = $this->security->xss_clean($this->input->post('time')); 
            $by_term = $this->security->xss_clean($this->input->post('by_term'));
            $stream_name = $this->security->xss_clean($this->input->post('stream_name'));
            if($this->role == ROLE_TEACHING_STAFF ){
                $filter['staff_id'] = $this->staff_id;
                $data['subjectInfo'] = $this->staff->getAllSubjectInfo($filter);
            }else{
                $data['subjectInfo'] = $this->subject->getAllSubjectInfo();
            }
            $data['timingsInfo'] = $this->settings->getAllClassTimingsInfo();
            $data['staffInfo'] = $this->staff->getDistinctSubjectInfo($filter);
            $data['streamInfo'] = $this->settings->getDistinctStreamInfo();

            if(!empty($absentDate)){
                $filter['absentDate'] = date('Y-m-d',strtotime($absentDate));
                $data['absentDate'] = date('d-m-Y',strtotime($absentDate));
            }else{
                $data['absentDate'] = '';
            }
            $data['staff_name'] = $staff_name;
            $data['student_id'] = $student_id;
            $data['by_term'] =  $by_term;
            $data['subject_id'] = $subject_id;
            $data['subject_type'] = $subject_type;
            $data['time'] = $time;
            $data['stream_name'] = $stream_name;

            $filter['staff_name'] = $staff_name;
            $filter['student_id'] = $student_id;
            $filter['by_term']= $by_term;
            $filter['subject_id'] = $subject_id;
            $filter['subject_type'] = $subject_type;
            $filter['time'] = $time;
            $filter['stream_name'] = $stream_name;
            $count = $this->attendance->viewAttendanceInfoCount($filter);
			$returns = $this->paginationCompress("viewAttendanceInfo/", $count, 100);
            $data['count_attendance'] = $count;
            $data['attendanceRecords'] = $this->attendance->getViewAttendanceInfo($filter, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = ''.TAB_TITLE.' : Attendance Management';
            $this->loadViews("attendance/viewAbsentInfo", $this->global, $data , NULL);
        }
    }

    
    public function deleteStudentAttendance(){
        if($this->isAdmin() == TRUE){
            $this->loadThis();
        } else {   
            $row_id = $this->input->post('row_id');
            $attendanceInfo = array('is_deleted' => 1,
            'updated_date_time' => date('Y-m-d H:i:s'),
            'updated_by' => $this->staff_id);
            $result = $this->attendance->updateAttendanceInfo($row_id,$attendanceInfo);
            if ($result == true) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        } 
    }

    // class completed info
    public function viewClassCompletedInfo() {
        if($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {        
            $filter = array();
            $classDate =$this->security->xss_clean($this->input->post('classDate')); 
            $staff_id =$this->security->xss_clean($this->input->post('staff_id')); 
            $subject_code =$this->security->xss_clean($this->input->post('subject_code')); 
            $subject_id =$this->security->xss_clean($this->input->post('subject_id')); 
            $subject_type =$this->security->xss_clean($this->input->post('subject_type')); 
            $time = $this->security->xss_clean($this->input->post('time')); 
            $by_term = $this->security->xss_clean($this->input->post('by_term'));
            $stream_name = $this->security->xss_clean($this->input->post('stream_name'));
            $section_name = $this->security->xss_clean($this->input->post('section_name'));
            $filter['subject_code'] = '';
            $subjectCode = array();
            if($this->role == ROLE_TEACHING_STAFF ){
                $filter['staff_id'] = $this->staff_id;
                $staff_sub = $this->staff->getAllSubjectInfo($filter);
                $data['subjectInfo'] = $staff_sub;
                foreach($staff_sub as $sub){
                    array_push($subjectCode,$sub->subject_code);
                } 
                $filter['subjectCode'] = $subjectCode;
                $filter['subject_code'] = '';
            }else{
                $data['subjectInfo'] = $this->subject->getAllSubjectInfo();
                $subject_code = $subject_code;
                $filter['subject_code'] = $subject_code;
            }
            $data['timingsInfo'] = $this->settings->getAllClassTimingsInfo();
            $data['staffInfo'] = $this->staff->getAllSubjectInfo($filter);
            $data['streamInfo'] = $this->settings->getDistinctStreamInfo();

            if(!empty($classDate)){
                $filter['classDate'] = date('Y-m-d',strtotime($classDate));
                $data['classDate'] = date('d-m-Y',strtotime($classDate));
            }else{
                $data['classDate'] = '';
            }
            $data['staff_id'] = $staff_id;
            $data['subject_code'] = $subject_code;
            $data['by_term'] =  $by_term;
            $data['subject_id'] = $subject_id;
            $data['subject_type'] = $subject_type;
            $data['time'] = $time;
            $data['stream_name'] = $stream_name;
            $data['section_name'] = $section_name;

            $filter['by_term']= $by_term;
            $filter['subject_id'] = $subject_id;
            $filter['subject_type'] = $subject_type;
            $filter['time'] = $time;
            $filter['stream_name'] = $stream_name;
            $filter['section_name'] = $section_name;
            $filter['staffId'] = $staff_id;
            $count = $this->attendance->getAllClassCompletedInfoCount($filter);
			$returns = $this->paginationCompress("viewClassCompletedInfo/", $count, 100);
            $data['classCount'] = $count;
            $data['classRecord'] = $this->attendance->getAllClassCompletedInfo($filter, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = ''.TAB_TITLE.' : My Class Completed';
            $this->loadViews("attendance/classCompleted", $this->global, $data , NULL);
        }
    }
    
    public function deleteClassCompleted(){
        if($this->isAdmin() == TRUE){
            $this->loadThis();
        } else {   
            $row_id = $this->input->post('row_id');
            $classCompletedInfo = array('is_deleted' => 1,
            'updated_date_time' => date('Y-m-d H:i:s'),
            'updated_by' => $this->staff_id);
            $result = $this->attendance->updateClassCompletedInfo($row_id, $classCompletedInfo);
            if ($result == true) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        } 
    }

    
    // get staff assigned subject 
    public function getAssignedSubjectAttendance(){
        $staff_subject_row_id = $this->input->post("staff_subject_row_id");
        $filter['staff_subject_row_id'] = $staff_subject_row_id;
        $data['result'] = $this->attendance->getSubjectByRowId($filter);
        header('Content-type: text/plain'); 
        header('Content-type: application/json'); 
        echo json_encode($data);
        exit(0);
    }

    public function downloadAbsentedStudentInfo(){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        } else
        {   
            set_time_limit(0);
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $section_name = $this->security->xss_clean($this->input->post('section_name')); 
            $date_from = $this->security->xss_clean($this->input->post('date_from'));
            $date_to = $this->security->xss_clean($this->input->post('date_to'));
            $stream_name = $this->security->xss_clean($this->input->post('stream_name'));
           
            $sections = array($section_name);
            if(!empty($date_from) && !empty($date_to)){
                $date_description = $date_from .' To '. $date_to;
            }else{
                $date_description = "Upto Today Date";
            }

         
            // $stream = $this->student->getStreamNameBySectionAndTerm($term_name,$section_name);
            $filter = array();
            $filter['by_term'] = $term_name;
            $filter['stream_name'] = $stream_name;
            if(!empty($date_from)){
                $filter['absentDateFrom'] = date('Y-m-d',strtotime($date_from));
            }
            if(!empty($date_to)){
                $filter['absentDateTo'] = date('Y-m-d',strtotime($date_to));
            }
            if($section_name == 'ALL'){
                $filter['section_name'] = '';
            }else{
                $filter['section_name'] = $section_name;
            }
            // $subject_info_header = $this->getSubjectCodes($stream_name);
            $sheet = 0;
            $j=1;
            $excel_row = 6;
            $section_name = $sections[$sheet];
            $this->excel->setActiveSheetIndex($sheet);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle($stream_name);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:H500');
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', $term_name.'-'.$stream_name." Attendance Report 2022-2023");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->mergeCells('A1:H1');
            $this->excel->getActiveSheet()->mergeCells('A2:H2');


            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);


            

            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A4', 'SL. NO.');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B4', 'Date');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C4', 'Student ID');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D4', 'Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E4', 'Section');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('F4', 'Staff');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('G4', 'Subject');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('H4', 'Time');

            $this->excel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setWrapText(true); 
            $this->excel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true); 
            $this->excel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->excel->getActiveSheet()->getStyle('A3:W4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
            $this->excel->getActiveSheet()->getStyle('A3:W4')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->setCellValue('A3', "Report Date: ".$date_description);
            $this->excel->getActiveSheet()->mergeCells('A3:H3');

            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:H4')->applyFromArray($styleBorderArray);
            $this->excel->getActiveSheet()->getStyle('A5:H999')->applyFromArray($styleBorderArray);
           
            $students = $this->attendance->getAbsentedStudentInfo($filter);
            $excel_row = 5;
            foreach($students as $student){
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A'.$excel_row,$j++);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B'.$excel_row,date('d-m-Y',strtotime($student->absent_date)));
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C'.$excel_row,$student->student_id);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D'.$excel_row,$student->student_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E'.$excel_row,$student->section_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F'.$excel_row,$student->staff_name);
                
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G'.$excel_row,$student->sub_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H'.$excel_row,$student->start_time .'-'.$student->end_time);
                $this->excel->getActiveSheet()->getStyle('A'.$excel_row.':C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('E'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('G'.$excel_row.':H'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel_row++;
            }
        
            $filename='just_some_random_name.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            ob_start();
            $objWriter->save("php://output");
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
            die(json_encode($response));
            
        }
    }

    
    public function downloadClassCompletedReport(){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        } else {   
            set_time_limit(0);
            ini_set('memory_limit', '256M');
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $section_name = $this->security->xss_clean($this->input->post('section_name')); 
            $date_from = $this->security->xss_clean($this->input->post('date_from'));
            $date_to = $this->security->xss_clean($this->input->post('date_to'));
            $stream_name = $this->security->xss_clean($this->input->post('stream_name'));
            $filter = array();
            $filter['term'] = $term_name;
            $filter['term_name'] = $term_name;
            $filter['preference'] = $stream_name;
            if(!empty($date_from)){
                $date_from = date('Y-m-d',strtotime($date_from));
            }
            if(!empty($date_to)){
                $filter['date_to'] = date('Y-m-d',strtotime($date_to));
            }
            // $stream = $this->timetable->getAssignedStreamInfo($filter)
            // if($section_name == 'ALL'){
            //     $sectionName = array('A','B','C','D','E','F','G','H','I','J');
            // }else{
                // $sectionName = $section_name;
            // }
            $filter['section_name'] = $section_name;
            
            $sections = array($section_name);
            if(!empty($date_from) && !empty($date_to)){
                $date_description = $date_from .' To '. $date_to;
            }else{
                $date_description = "Upto Today Date";
            }

            $class_held_cell_name = array("E","H","K","N","Q","T");
            $class_attended_cell_name = array("F","I","L","O","R","U");
            $class_percentage_cell_name = array("G","J","M","P","S","V");

            // $stream = $this->students_model->getStreamNameBySectionAndTerm($term_name,$section_name);
            $subject_info_header = $this->getSubjectCodes($stream_name);
            $sheet = 0;
            $j=1;
            $excel_row = 6;
            $class_section = $section_name[$sheet];
            $this->excel->setActiveSheetIndex($sheet);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle($stream_name);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:W500');
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', $term_name.'-'. $stream_name.'-'.$section_name." Section Attendance Report 2022-23");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A1:W1');
            $this->excel->getActiveSheet()->mergeCells('A2:W2');
            $this->excel->getActiveSheet()->getStyle('A1:A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);


            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(7);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(7);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(7);

            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(7);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(7);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(7);

            

            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL. NO.');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Roll No');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'LAG');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('W3', 'OA.%');

            $this->excel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setWrapText(true); 
            $this->excel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true); 
            $this->excel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $this->excel->getActiveSheet()->mergeCells('A3:A4');
            $this->excel->getActiveSheet()->mergeCells('B3:B4');
            $this->excel->getActiveSheet()->mergeCells('C3:C4');
            $this->excel->getActiveSheet()->mergeCells('D3:D4');

            $this->excel->getActiveSheet()->mergeCells('W3:W4');

            

            //first elective subject
            $this->excel->getActiveSheet()->mergeCells('E3:G3');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Language');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E4', 'CH');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('F4', 'CA');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('G4', 'A%');

            //english subject
            $this->excel->getActiveSheet()->mergeCells('H3:J3');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'ENGLISH');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('H4', 'CH');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('I4', 'CA');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('J4', 'A%');
            $s=2;
            for($sub=0; $sub<count($subject_info_header); $sub++){
                $subject_name = $this->subject->getAllSubjectByID($subject_info_header[$sub]);
                $this->excel->getActiveSheet()->getColumnDimension($class_held_cell_name[$s])->setWidth(7);
                $this->excel->getActiveSheet()->getColumnDimension($class_attended_cell_name[$s])->setWidth(7);
                $this->excel->getActiveSheet()->getColumnDimension($class_percentage_cell_name[$s])->setWidth(7);
                $this->excel->getActiveSheet()->mergeCells($class_held_cell_name[$s].'3:'.$class_percentage_cell_name[$s].'3');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue($class_held_cell_name[$s].'3', $subject_name->sub_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue($class_held_cell_name[$s].'4', 'CH');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue($class_attended_cell_name[$s].'4', 'CA');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue($class_percentage_cell_name[$s].'4', 'A%');
            $s++;
            }
            $this->excel->getActiveSheet()->getStyle('A3:W4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
            $this->excel->getActiveSheet()->getStyle('A3:W4')->getFont()->setBold(true);
            
            $this->excel->getActiveSheet()->mergeCells('A5:W5');
            $this->excel->getActiveSheet()->getStyle('A5:W5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A5:W5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->setCellValue('A5', "Report Date: ".$date_description);


            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:W4')->applyFromArray($styleBorderArray);

            // $students = $this->students_model->getStudentInfoBySectionTerm($term_name,$section_name);
            $students = $this->student->getStudentInfoForReportDownload($filter);
            // log_message('debug','dnd='.print_r($students,true));
           
            foreach($students as $student){
                $filter['doj'] = '';
                if(date('Y-m-d',strtotime($student->doj)) > date('Y-m-d',strtotime($date_from))){
                    $filter['doj'] = date('Y-m-d',strtotime($student->doj));
                } else{
                    $filter['doj'] = '';
                }
                // $filter['doj'] = $student->doj;
                $section = $student->section_name;
                $filter['section'] = $section;
                $subjects_code = array();
                $percentage_active = false;
                $elective_sub = strtoupper($student->elective_sub);
                $std_batch = $student->batch;
            
                if($elective_sub == 'KANNADA'){
                    array_push($subjects_code, '01');
                }else if($elective_sub == 'HINDI'){
                    array_push($subjects_code, '03');
                } else if($elective_sub == 'FRENCH'){
                    array_push($subjects_code, '12');
                }

                if(!empty($student->date_of_admission) || $student->date_of_admission != '0000-00-00' || $student->date_of_admission != '1970-01-01'){
                    $date_of_admission = $student->date_of_admission;
                }else{
                    $date_of_admission = $date_from;
                }
                // if($student->term_name == 'I PUC'){
                //     $filter['date_from'] = $date_of_admission;
                // }else{
                    $filter['date_from'] = $date_from;
                // }
                array_push($subjects_code, '02');
                $subjects = $this->getSubjectCodes($student->stream_name);
                $subjects_code = array_merge($subjects_code,$subjects);
                $total_class_held_per_std = 0;
                $total_attd_class_std = 0;
                $absentCount = 0;
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A'.$excel_row,$j++);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B'.$excel_row,$student->student_id);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C'.$excel_row,$student->student_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D'.$excel_row,$elective_sub);
                for($i=0; $i < count($subjects_code); $i++)
                {
                    // if($student->term_name == 'I PUC' && $date_from == ""){
                    //     $date_from = $student->date_of_admission; 
                    // }
                    
                    $class_held = 0;
                    $class_held_lab  = 0;
                    $class_attended = 0;
                    $absent_count = 0;
                    $std_absent_count = 0;
                    $absent_count_theory = 0;
                    $absent_count_lab = 0;
                    $absent_countLab = 0;
                    $subInfo = $this->subject->getAllSubjectByID($subjects_code[$i]);
                   
                    $type="THEORY";
                    $filter['std_batch'] = '';
                    $class_held += $this->attendance->getClassInfoAttendanceReportStudent($subjects_code[$i],$filter,$type);
                    
                    $type="LAB";
                    $filter['std_batch'] = $std_batch;
                    $class_held_lab += $this->attendance->getClassInfoAttendanceReportStudent($subjects_code[$i],$filter,$type);

                    // if($class_held_lab != 0){
                        $class_held += ($class_held_lab * 2);
                    // }
                    // $data['classHeldDate'] = $this->attendance->getTotalClassHeldByStaff($subjects_code[$i],$filter,$type);
                    
                    // foreach($data['classHeldDate'] as $classdata){
                    $type="THEORY";
                    $absent_count_theory = $this->attendance->isStudentIsAbsentForClass($student->student_id,$subjects_code[$i],$filter,$type);
                    
                    // log_message('debug','absent_count_theory='.print_r($absent_count_theory,true));
                    $type="LAB";
                    $absent_count_lab = $this->attendance->isStudentIsAbsentForClass($student->student_id,$subjects_code[$i],$filter,$type);
                    $absent_countLab = $absent_count_lab * 2;

                    $std_absent_count = $absent_count_theory + $absent_countLab;
                    //     if($absent_count_theory != NULL){
                    //         $absent_count += 1;
                    //     }
                    
                    // }
                
                    
                    //no change
                    $total_class_held_per_std += $class_held;
                    $absent_count = $class_held - $std_absent_count;
                    $absentCount += $std_absent_count;
                    $total_attd_class_std = $absentCount;
                    
                    if($class_held != 0){
                        $avg = ($absent_count)/$class_held;
                        $percentage = round($avg*100, 2);
                    }else{
                        $percentage = 0;
                    }
                    if(!empty($percentage_sort)){
                        if($percentage <= $percentage_sort){
                            $percentage_active = true;
                        }
                    }
                    //writing result to excel cell
                    
                    if($percentage < 85){
                        $this->cellColor($class_held_cell_name[$i].$excel_row, 'FFEE58');
                        $this->cellColor($class_attended_cell_name[$i].$excel_row, 'FFEE58');
                        $this->cellColor($class_percentage_cell_name[$i].$excel_row, 'FFEE58');
                    }
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($class_held_cell_name[$i].$excel_row,$class_held);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($class_attended_cell_name[$i].$excel_row,$absent_count);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($class_percentage_cell_name[$i].$excel_row,$percentage);
                        
                }
                //total subject percentage
            
                if($total_class_held_per_std != 0){
                    $avg = ($total_class_held_per_std-$total_attd_class_std)/$total_class_held_per_std;
                    $percentage = round($avg*100, 2);
                }else{
                    $percentage = 0;
                }
                
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('W'.$excel_row,$percentage);
                $this->excel->getActiveSheet()->getStyle('A'.$excel_row.':W'.$excel_row)->applyFromArray($styleBorderArray);
                $this->excel->getActiveSheet()->getStyle('A'.$excel_row.':B'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('D'.$excel_row.':W'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if(!empty($percentage_sort)){
                    if($percentage_active == true){
                        $excel_row++;
                    }else{
                        $this->excel->getActiveSheet()->removeRow($excel_row);
                    }
                }else{
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('W'.$excel_row,$percentage);
                    $excel_row++;
                }
                
                
            }
            
            $filename='just_some_random_name.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            ob_start();
            $objWriter->save("php://output");
            $xlsData = ob_get_contents();
            ob_end_clean();

            $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
            die(json_encode($response));
            
        }
    }

    
    public function cellColor($cells,$color){
        return $this->excel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $color
            )
        ));
    }


    
    // verify attendance
    public function verifyStudentAttendance(){
        if($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $data['timingsInfo'] = $this->settings->getAllClassTimingsInfo();
            $this->global['pageTitle'] = ''.TAB_TITLE.' : Verify Attendance';
            $this->loadViews("attendance/verifyAbsentStudent", $this->global, $data, null);
        }
    }

    public function getStudentInfoToVerifyAttendance(){
        if ($this->isAdmin() == true) {
            $this->loadThis();
        } else {
            $filter = array();
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $section_name = $this->security->xss_clean($this->input->post('section_name'));
            $attendance_date = $this->security->xss_clean($this->input->post('attendance_date'));
            // $weekname = date('l', strtotime($attendanceDate));

            $attendanceDate = date('Y-m-d',strtotime($attendance_date));
            if(!empty($attendance_date)){
                $filter['attendance_date'] = $attendanceDate;
                $data['attendance_date'] = date('d-m-Y',strtotime($attendance_date));
            }else{
                $data['attendance_date'] = '';
            }


            $filter['section_row_id'] = $section_name;
            $sectionInfo = $this->attendance->getSectionInfoByRowId($filter);
            $sectionName = $sectionInfo->section_name;
            $stream_name = $sectionInfo->stream_name;

            $filter['term_name'] = $term_name;
            if($this->role == ROLE_TEACHING_STAFF){
                $filter['staff_id'] = $this->staff_id;
            }
            $filter['stream_name'] = $stream_name;
            if($sectionName == "ALL"){
                $filter['section_name'] = '';
            }else{
                $filter['section_name'] = $sectionName;
            }

            $data['termInfo'] = $this->staff->getSectionById($filter);
            
            $data['term_name'] = $term_name;
            $data['stream_name'] = $stream_name;
            $data['section_name'] = $sectionName;
            $data['stream_name'] = $stream_name;
            $data['staff_id'] = $this->staff_id;
            $data['section_row_id'] = $sectionInfo->row_id;
            
            $data['classInfo'] = $this->attendance->getClassSectionInfoForAttendance($term_name,$sectionName,$attendanceDate);
            // log_message('debug','cdjcd'.print_r($data['classInfo'],true));
            $data['studentRecord'] = $this->student->getStudentInfoForInternal($filter);
            $this->global['pageTitle'] = ''.TAB_TITLE.' : Attendance Details';
            $this->loadViews("attendance/verifyAbsentStudent", $this->global, $data, null);
        }   
    }

    
    public function confirmStudentVerifyAttendance(){
        if ($this->isAdmin() == true) {
            $this->loadThis();
        } else {
            $result = 1;
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $stream_name = $this->security->xss_clean($this->input->post('stream_name'));
            $section_name = $this->security->xss_clean($this->input->post('section_name'));
            $section_row_id = $this->security->xss_clean($this->input->post('section_row_id'));
            $attendance_date = $this->security->xss_clean($this->input->post('attendance_date'));
            $data['term_name']=$term_name;
            $data['section_name']=$section_name;
            $data['stream_name']=$stream_name;
            $data['section_row_id']=$section_row_id;

            // $data['attendance_date']= $attendanceDate; 
            $attendanceDate = date('Y-m-d',strtotime($attendance_date));
            if(!empty($attendance_date)){
                $filter['attendance_date'] = $attendanceDate;
                $data['attendance_date'] = date('d-m-Y',strtotime($attendance_date));
            }else{
                $data['attendance_date'] = '';
            }
            
            $filter['term_name'] = $term_name;
            if($this->role == ROLE_TEACHING_STAFF){
                $filter['staff_id'] = $this->staff_id;
            }
            $filter['stream_name'] = $stream_name;
            $filter['section_name'] = $section_name;
            $filter['section_row_id'] = $section_row_id;


            $data['studentRecord'] = $this->student->getStudentInfoForInternal($filter);
            $data['todaySectionClass'] = $this->attendance->getClassSectionInfoForAttendance($term_name,$section_name,$attendanceDate);
            // log_message('debug','cdjcd'.print_r($filter,true));
            foreach($data['studentRecord'] as $student){
                foreach($data['todaySectionClass'] as $class){
                    $staffSubjectInfo = $this->attendance->getStaffSubjectRowId($class->staff_id,$class->subject_code,$class->subject_type);
                    $absentStatus = $this->input->post($student->student_id.$section_row_id.$class->time_id.$staffSubjectInfo->row_id);
                    if($absentStatus == 'true'){
                        $attendanceInfo = array (
                            'class_section_row_id' => $section_row_id,
                            'staff_subject_row_id' => $staffSubjectInfo->row_id,
                            'class_row_id' => $class->row_id,
                            'student_id' => $student->student_id,
                            'subject_code' => $class->subject_code,
                            'absent_date' => date("Y-m-d", strtotime($attendanceDate)),
                            'time_row_id' => $class->time_id,
                            'student_batch' => $class->batch,
                            'year' => CURRENT_YEAR,
                            'sms_sent_status' => 0,
                            'office_verified_status' => 1,
                            'created_by' => $this->staff_id,
                            'created_date_time' => date('Y-m-d H:i:s'));
                        $isAttendanceExist = $this->attendance->checkStudentAttendanceAlreadyExist($student->student_id,$section_row_id,date("Y-m-d", strtotime($attendanceDate)),$class->time_id,$staffSubjectInfo->row_id,$class->subject_code);
                        if($isAttendanceExist == NULL){
                            $result =  $this->attendance->addAbsentStudentInfo($attendanceInfo);
                        }else if($isAttendanceExist->office_verified_status == 0){
                            $attendanceUpdateInfo = array (
                                'updated_by' =>$this->staff_id,
                                'sms_sent_status' => 0,
                                'office_verified_status' => 1,
                                'updated_date_time' => date('Y-m-d H:i:s')
                            );   
                            $result = $this->attendance->updateAttendanceInfo($isAttendanceExist->row_id,$attendanceUpdateInfo);
                        }
                        //FCM////////////
                        // $sub_name = $this->subject->getSubjectInfoById($class->subject_code);
                        // $message = 'Your ward is absent for '.$sub_name->name.' on '.date("Y-m-d", strtotime($attendanceDate));
                        // $all_users_token = $this->push_notification_model->getSingleStudentsToken($student->student_id);
                        // $tokenBatch = array_chunk($all_users_token,500);
                        // for($itr = 0; $itr < count($tokenBatch); $itr++){
                        //     $this->push_notification_model->sendMessage('Absent For Class',$message,$tokenBatch[$itr],"student");
                        // }
                        //FCM///////////

                    }else{
                        $isAttendanceExist = $this->attendance->checkStudentAttendanceAlreadyExist($student->student_id,$section_row_id,date("Y-m-d", strtotime($attendanceDate)),$class->time_id,$staffSubjectInfo->row_id,$class->subject_code);
                        if($isAttendanceExist != NULL){
                            $attendanceUpdateInfo = array (
                                'updated_by' =>$this->staff_id,
                                'is_deleted' => 1,
                                'updated_date_time' => date('Y-m-d H:i:s')
                            );   
                            $result = $this->attendance->updateAttendanceInfo($isAttendanceExist->row_id,$attendanceUpdateInfo);
                        
                        }
                    }

                }
            }
            if ($result > 0) {
                $this->session->set_flashdata('success', 'Attendance Added Successfully');
            } else {
                $this->session->set_flashdata('error', 'Attendance update failed');
            }
            $data['studentRecord'] = $this->student->getStudentInfoForInternal($filter);
            $data['classInfo'] = $this->attendance->getClassSectionInfoForAttendance($term_name,$section_name,$attendanceDate);
            $this->global['pageTitle'] = ''.TAB_TITLE.' : Attendance Details';
            $this->loadViews("attendance/verifyAbsentStudent", $this->global, $data, null);
        }
    }


    public function getAttendanceMonthWise(){
        if($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $filter = array();
            
            if($this->role == ROLE_TEACHING_STAFF){
                $filter['staff_id'] = $this->staff_id;
                $data['staff_id'] = $this->staff_id;
            }else{
                $data['staff_id'] = '';
            }

            $data['streamSectionInfo'] = $this->staff->getSectionById($filter);
            $data['staffSubjectInfo'] = $this->staff->getAllSubjectInfo($filter);

            $this->global['pageTitle'] = ''.TAB_TITLE.' : Attendance By Month Wise';
            $this->loadViews("attendance/addMonthlyClass", $this->global, $data, NULL);
        }
    }


    public function getStudentInfoForAttendnaceAdditionalDetails(){
        if($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $filter = array();
            
            $filter= array();
            if($this->role == ROLE_TEACHING_STAFF){
                $filter['staff_id'] = $this->staff_id;
            }
            $term_name = $this->input->post("term_name");
            $section_row_id = $this->input->post("section_row_id");
            $staff_subject_row_id = $this->input->post("staff_subject_row_id");
            $month_name = $this->input->post("month_name");
            $filter['section_row_id'] = $section_row_id;
            $filter['staff_subject_row_id'] = $staff_subject_row_id;
            $sectionInfo = $this->attendance->getSectionInfoByRowId($filter);
            $subjectInfo = $this->attendance->getSubjectByRowId($filter);
            $data['term_name'] = $term_name;
            $data['stream_name'] = $sectionInfo->stream_name;
            $data['sub_name'] = $subjectInfo->sub_name;
            $data['subject_row_id'] = $subjectInfo->row_id;
            $data['subject_code'] = $subjectInfo->subject_code;
            $data['staff_name'] = $subjectInfo->staff_name; 
            $data['month_name'] = $month_name;
            $data['section_name'] = $sectionInfo->section_name; 
            $data['section_row_id'] = $section_row_id;
            $data['staff_subject_row_id'] = $staff_subject_row_id;
            $data['subject'] = $subjectInfo; 

            $sectionName = $sectionInfo->section_name;
            if($sectionName == "ALL"){
                $filter['section_name'] = '';
            }else{
                $filter['section_name'] = $sectionName;
            }
            $filter['stream_name'] = $sectionInfo->stream_name;
            $filter['term_name'] = $term_name;
            $filter['subject_name'] = $subjectInfo->sub_name;

            $data['streamInfo'] = $this->student->getAllStreamName(); 
            $data['studentsInfo'] = $this->student->getStudentInfoForInternal($filter);
            $data['staffSubjectInfo'] = $this->staff->getAllSubjectInfo($filter);
            $this->global['pageTitle'] = ''.TAB_TITLE.' : Attendance Additional Details';
            $this->loadViews("attendance/addMonthlyClass", $this->global, $data, NULL);
        }
    }
    

    public function addStudentAttendanceAdditionalDetails(){
        if($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('term_name','Term','trim|required');
            $this->form_validation->set_rules('subject_id','Subject Name','trim|required');
           // $this->form_validation->set_rules('subject_id','Subject Name','trim|required');
            if($this->form_validation->run() == FALSE) {
                $this->getStudentInfoForAttendnaceAdditionalDetails();
            }else{
                $term_name = $this->input->post("term_name");
                $section_row_id = $this->input->post("section_row_id");
                $stream_name = $this->input->post("stream_name");
                $subject_id = $this->input->post("subject_id");
                $section_name = $this->input->post("section_name");
                $month_name = $this->input->post("month_name");
                $class_held = $this->input->post("class_held");
                $subject = $this->subject->getAllSubjectByID($subject_id);
               
                $filter= array();
                if($this->role == ROLE_TEACHING_STAFF){
                    $filter['staff_id'] = $this->staff_id;
                }
               
                $staff_subject_row_id = $this->input->post("staff_subject_row_id");
                $filter['staff_subject_row_id'] = $staff_subject_row_id;
                $data['staff_subject_row_id'] = $staff_subject_row_id;
                $subjectInfo = $this->attendance->getSubjectByRowId($filter);
                $data['term_name'] = $term_name;
                $data['stream_name'] = $stream_name;
                $data['sub_name'] = $subjectInfo->sub_name;
                $data['subject_row_id'] = $subjectInfo->row_id;
                $data['subject_code'] = $subjectInfo->subject_code;
                $data['staff_name'] = $subjectInfo->staff_name; 
                $data['section_row_id'] = $section_row_id; 
                $data['month_name'] = $month_name;
                $data['section_name'] = $section_name; 
                $data['subject'] = $subjectInfo; 

                $filter['stream_name'] = $stream_name;
                $filter['term_name'] = $term_name;
                $filter['subject_name'] = $subjectInfo->sub_name;
                $sectionName = $section_name;
                if($sectionName == "ALL"){
                    $filter['section_name'] = '';
                }else{
                    $filter['section_name'] = $sectionName;
                }

                $year = CURRENT_YEAR;
                $data['studentsInfo'] = $this->student->getStudentInfoForInternal($filter);
               
                $return_id = 0;
                foreach($data['studentsInfo'] as $student){
                    $student_id = trim($student->student_id);
                    $class_attended = $this->input->post("class_attended_".$student_id);
                    $classHeld = $this->input->post("class_held_".$student_id);
                    if(!empty($class_held)){
                        $classHeld = $class_held;
                        $class_held = $class_held;
                    }else{
                        $classHeld = $this->input->post("class_held_".$student_id);
                    }
                    
                    $attendanceInfo = array(
                        'student_id' => $student_id,
                        'staff_id' => $this->staff_id,
                        'subject_code' => $subject_id,
                        'class_attended' => $class_attended,
                        'class_held' => $class_held,
                        'year' => $year,
                        'month' => $month_name,
                        'created_by' => $this->staff_id, 
                        'created_date_time' => date('Y-m-d H:i:s'));
                    $attendanceInfoUpdate = array(
                        'subject_code' => $subject_id,
                        'staff_id' => $this->staff_id,
                        'student_id' => $student_id,
                        'class_attended' => $class_attended,
                        'class_held' => $classHeld,
                        'updated_by' => $this->staff_id,
                        'updated_date_time' => date('Y-m-d H:i:s'));
                    if($classHeld != ''){
                        $isExist = $this->attendance->getAttendanceAdditionalById($subject_id,$student_id,$month_name,$year);
                        // log_message('debug','cedcb'.print_r($isExist,true));
                        if(empty($isExist)){
                            $return_id += $this->attendance->addAttendanceStudentAdditionalDetails($attendanceInfo);
                            $this->session->set_flashdata('success', 'Attendance additional details added Successfully');
                        }else{
                            //$return_id = 0;
                            $return_id += $this->attendance->updateStudentAttendanceAdditional($subject_id,$student_id,$month_name,$year,$attendanceInfoUpdate);
                            $this->session->set_flashdata('success', 'Attendance additional details Updated');
                        }
                    }
                }
                if($return_id == 0){
                    $this->session->set_flashdata('error', 'Failed to add attendance additional details '); 
                }
                
                $data['streamInfo'] = $this->student->getAllStreamName(); 
                $data['staffSubjectInfo'] = $this->staff->getAllSubjectInfo($filter);
                $this->global['pageTitle'] = ''.TAB_TITLE.' : Attendance Additional Details';
                $this->loadViews("attendance/addMonthlyClass", $this->global, $data, NULL);
            }
        }
    } 
    
    
    public function getSubjectCodes($stream_name){
        //science
        $PCMB = array("33", "34", "35", '36');
        $PCMC = array("33", "34", "35", '41');
        $PCME = array("33", "34", "35", '40');
        $PCMS = array("33", "34", "35", '31');
        $PCBH = array("33", "34", "36", '67');
        //commarce
        $BEBA = array("75", "22", "27", '30');
        $BSBA = array("75", "31", "27", '30');
        $CSBA = array("41", "31", "27", '30');
        $SEBA = array("31", "22", "27", '30');
        $CEBA = array("41", "22", "27", '30');
        //art
        $HEPP = array("21", "22", "32", '29');
        $HEPS = array("21", "22", "29", '28');

        $PEBA = array("29", "22", "27", '30');
        $MEBA = array("75", "22", "27", '30');
        $MSBA = array("75", "31", "27", '30');

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
            case "PCBH":
                return $PCBH;
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
            case "HEBA":
                return $HEBA;
                break;
            case "MEBA":
                return $MEBA;
                break;
            case "MSBA":
                return $MSBA;
                break;
            case "PEBA":
                return $PEBA;
                break;
        }
    }
}
?>