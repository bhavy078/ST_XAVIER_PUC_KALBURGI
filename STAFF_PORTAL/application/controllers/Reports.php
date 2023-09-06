<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseControllerFaculty.php';
// require FCPATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;


class Reports extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('staff_model', 'staff');
        $this->load->model('Students_model', 'student');
        $this->load->model('subjects_model', 'subject');
        $this->load->model('settings_model', 'settings');
        $this->load->model('admissionEnquiry_model', 'admission');
        $this->load->model('application_model', 'application');
        $this->load->model('Mun_model', 'mun');
        $this->load->model('fee_model', 'fee');
        $this->load->model('transport_model','transport');
        $this->load->library('excel');
        $this->load->library('pdf');
        $this->isLoggedIn();
    }

    public function reportDashboard()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $filter = array();
            $data['departments'] = $this->staff->getStaffDepartment();
            $data['designation'] = $this->staff->getStaffRoles();
            $data['streamInfo'] = $this->student->getAllStreamName();
            $data['subjectInfo'] = $this->subject->getAllSubjectInfo();
            $data['routeInfo'] = $this->transport->getTransportNameInfo();
            $this->global['pageTitle'] = '' . TAB_TITLE . ' : Reports';
            $this->loadViews("reports/reports", $this->global, $data, NULL);
        }
    }


    public function downloadAdmissionEnquiryExcelReport()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            set_time_limit(0);
            $term_name = $this->security->xss_clean($this->input->post('term_name'));

            $filter = array();
            if ($term_name == 'PU1') {
                $term = 'I PUC';
            } else {
                $term = 'II PUC';
            }
            if (!empty($term_name)) {
                $filter['term_name'] = $term_name;
                $data['term_name'] = $term_name;
            }


            $sheet = 0;
            $j = 1;
            $excel_row = 6;
            $section_name = $sections[$sheet];
            $this->excel->setActiveSheetIndex($sheet);

            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:K500');
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', $term_name . '-' . " Admission Enquiry Report 2021-2022");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->mergeCells('A1:K1');
            $this->excel->getActiveSheet()->mergeCells('A2:K2');
            // $this->excel->getActiveSheet()->mergeCells('A3:K3');



            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(28);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(25);


            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL. NO.');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Email');

            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Term');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Phone No');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Stream');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Course');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'Elective');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('I3', 'Current Institution');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('J3', 'Exam Coaching');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('K3', 'Comment');
            $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setWrapText(true);
            $this->excel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->excel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);


            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:K4')->applyFromArray($styleBorderArray);
            $this->excel->getActiveSheet()->getStyle('A5:K999')->applyFromArray($styleBorderArray);
            $filter['term_name'] = $term_name;
            $students = $this->admission->getAdmissionEnquiryInfoForReportDownload($filter);

            $excel_row = 4;
            foreach ($students as $student) {
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);

                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $student->name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $student->email);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $student->term_name);

                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $student->phone_no);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $student->stream_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $student->program_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $student->elective_sub);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $student->current_institution_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J' . $excel_row, $student->exam_coaching);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('K' . $excel_row, $student->comment);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('E' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':H' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel_row++;
            }

            $this->excel->createSheet();

            $filename = $term_name . '_Admission_Enquiry_Report.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            setcookie('isDownLoaded', 1);
            $objWriter->save("php://output");
        }
    }


    //download fee structure format
    public function downloadDayWiseFeeReport()
    {
        if ($this->isAdmin() == true) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $filter = array();
            $term_name = $this->security->xss_clean($this->input->post('term_name_select'));
            $preference = $this->security->xss_clean($this->input->post('preference'));
            $date_from = $this->security->xss_clean($this->input->post('date_from'));
            $date_to = $this->security->xss_clean($this->input->post('date_to'));
            
            $spreadsheet = new Spreadsheet();
            $headerFontSize = [
                'font' => [
                    'size' => 16,
                    'bold' => true,
                ]
            ];
            $font_style_total = [
                'font' => [
                    'size' => 12,
                    'bold' => true,
                ]
            ];
            $filter['term_name'] = $term_name;
            //$streamInfo = $this->staff->getStaffSectionByTerm($filter);

            $spreadsheet->getProperties()
                ->setCreator("SJPUC")
                ->setLastModifiedBy($this->staff_id)
                ->setTitle("SJPUC Fee Info")
                ->setSubject("Fee Structure")
                ->setDescription(
                    "SJPUC"
                )
                ->setKeywords("SJPUC")
                ->setCategory("Fee");
            $i = 0;

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('FEE');
            $spreadsheet->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $spreadsheet->getActiveSheet()->mergeCells("A1:J1");
            $spreadsheet->getActiveSheet()->getStyle("A1:A1")->applyFromArray($headerFontSize);

            $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->setCellValue('A2', $term_name . " FEES PAID FOR THE YEAR -" . date('Y'));
            $spreadsheet->getActiveSheet()->mergeCells("A2:J2");
            $spreadsheet->getActiveSheet()->getStyle("A2:A2")->applyFromArray($headerFontSize);
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->setCellValue('A3', 'SL No');
            $spreadsheet->getActiveSheet()->setCellValue('B3', 'Date');
            $spreadsheet->getActiveSheet()->setCellValue('C3', 'Application No');
            $spreadsheet->getActiveSheet()->setCellValue('D3', 'Name');
            $spreadsheet->getActiveSheet()->setCellValue('E3', 'Stream');
            $spreadsheet->getActiveSheet()->setCellValue('F3', 'Receipt No.');
            $spreadsheet->getActiveSheet()->setCellValue('G3', 'Order Id');
            $spreadsheet->getActiveSheet()->setCellValue('H3', 'Fee Paid');
            $spreadsheet->getActiveSheet()->setCellValue('I3', 'Mode');
            $spreadsheet->getActiveSheet()->setCellValue('J3', 'Pending');
            $spreadsheet->getActiveSheet()->getStyle("A3:J3")->applyFromArray($font_style_total);
            $spreadsheet->getActiveSheet()->getStyle("A3:J3")->applyFromArray($font_style_total);
            $spreadsheet->getActiveSheet()->getStyle('C3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('D3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
            // $feeTypeInfo = $this->fee->getAllFeeTypesForByStatus(1);

            $spreadsheet->getActiveSheet()->getStyle('A3:E3')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'E5E4E2')
                    ),
                    'font'  => array(
                        'bold'  =>  true
                    )
                )
            );


            $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle('B:K')->getAlignment()->setHorizontal('center');
            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

            $this->excel->getActiveSheet()->getStyle('A1:K3')->applyFromArray($styleBorderArray);
            $excel_row = 4;
            $sl_number = 1;
            $total_sslc_state_fee = 0;
            $total_cbse_icse_fee = 0;
            $total_nri_fee = 0;
            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
           
            $filter = array();
            $filter['date_from'] = date('Y-m-d', strtotime($date_from));
            $filter['date_to'] = date('Y-m-d', strtotime($date_to));
            $filter['preference'] = $preference;
            $filter['term_name'] = $term_name;
            // foreach($feeTypeInfo as $type){
          
                $studentInfo = $this->fee->getAllFeePaymentInfoForReport($filter);
                
                if (!empty($studentInfo)) {
                    foreach ($studentInfo as $std) {
                        //$frenchFeePaid = $this->fee->getFrenchFeePaidByReceipt($std->row_id);
                        if($frenchFeePaid == ''){
                            $frenchFeePaid = 0;
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row)->getFont()->setSize(14);
                        $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row,  $sl_number);
                        $spreadsheet->getActiveSheet()->setCellValue('B' . $excel_row,  date('d-m-Y', strtotime($std->payment_date)));
                        $spreadsheet->getActiveSheet()->setCellValue('C' . $excel_row,  $std->student_id);
                        $spreadsheet->getActiveSheet()->setCellValue('D' . $excel_row,  $std->student_name);
                        $spreadsheet->getActiveSheet()->setCellValue('E' . $excel_row,  $std->stream_name);
                        $spreadsheet->getActiveSheet()->setCellValue('F' . $excel_row,  $std->ref_receipt_no);
                        $spreadsheet->getActiveSheet()->setCellValue('G' . $excel_row,  $std->order_id);
                        $spreadsheet->getActiveSheet()->setCellValue('H' . $excel_row,  $std->paid_amount);
                        $spreadsheet->getActiveSheet()->setCellValue('I' . $excel_row,  $std->payment_type);
                        $spreadsheet->getActiveSheet()->setCellValue('J' . $excel_row,  $std->pending_balance);

                        $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setWrapText(true);

                        $sl_number++;
                        $excel_row++;
                    }
                }
       
           
            $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row . ":E" . $excel_row)->applyFromArray($font_style_total);
            $spreadsheet->createSheet();
            $i++;
         

            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="fee_paid_' . $term_name . '.xlsx"');
            header('Cache-Control: max-age=0');
            setcookie('isDownLoaded', 1);
            $writer->save("php://output");
        }
    }

    //download fee structure format
    public function download_fee_structure_excel()
    {
        if ($this->isAdmin() == true) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $filter = array();
            $term_name = $this->security->xss_clean($this->input->post('term_name_select'));
            $year = $this->security->xss_clean($this->input->post('year'));
            $spreadsheet = new Spreadsheet();
            $headerFontSize = [
                'font' => [
                    'size' => 16,
                    'bold' => true,
                ]
            ];
            $font_style_total = [
                'font' => [
                    'size' => 12,
                    'bold' => true,
                ]
            ];
            $filter['term_name'] = $term_name;
            //$streamInfo = $this->staff->getStaffSectionByTerm($filter);

            $spreadsheet->getProperties()
                ->setCreator("SJPUC")
                ->setLastModifiedBy($this->staff_id)
                ->setTitle("SJPUC Fee Info")
                ->setSubject("Fee Structure")
                ->setDescription(
                    "SJPUC"
                )
                ->setKeywords("SJPUC")
                ->setCategory("Fee");
            $i = 0;

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('FEE');
            $spreadsheet->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $spreadsheet->getActiveSheet()->mergeCells("A1:K1");
            $spreadsheet->getActiveSheet()->getStyle("A1:A1")->applyFromArray($headerFontSize);

            $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->setCellValue('A2', $term_name . " FEES STRUCTURE FOR THE YEAR -" .$year);
            $spreadsheet->getActiveSheet()->mergeCells("A2:K2");
            $spreadsheet->getActiveSheet()->getStyle("A2:A2")->applyFromArray($headerFontSize);
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');

            $spreadsheet->getActiveSheet()->setCellValue('A3', 'SL No');
            $spreadsheet->getActiveSheet()->setCellValue('B3', 'Student ID');
            $spreadsheet->getActiveSheet()->setCellValue('C3', 'Application No');
            $spreadsheet->getActiveSheet()->setCellValue('D3', 'Name');
            $spreadsheet->getActiveSheet()->setCellValue('E3', 'Lang');
            $spreadsheet->getActiveSheet()->setCellValue('F3', 'Stream');
            $spreadsheet->getActiveSheet()->setCellValue('G3', 'SC/ST/CATI');
            $spreadsheet->getActiveSheet()->setCellValue('H3', 'Fee Payable');
            $spreadsheet->getActiveSheet()->setCellValue('I3', 'French Fee');
            $spreadsheet->getActiveSheet()->setCellValue('J3', 'Total Fee Paid');
            $spreadsheet->getActiveSheet()->setCellValue('K3', 'Pending');
            $spreadsheet->getActiveSheet()->getStyle("A3:K3")->applyFromArray($font_style_total);
            $spreadsheet->getActiveSheet()->getStyle('C3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('D3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
            // $feeTypeInfo = $this->fee->getAllFeeTypesForByStatus(1);

            $spreadsheet->getActiveSheet()->getStyle('A3:J3')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'E5E4E2')
                    ),
                    'font'  => array(
                        'bold'  =>  true
                    )
                )
            );


            $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle('A:K')->getAlignment()->setHorizontal('center');
            $excel_row = 4;
            $sl_number = 1;
            $total_sslc_state_fee = 0;
            $total_cbse_icse_fee = 0;
            $total_nri_fee = 0;
            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);

            // foreach($feeTypeInfo as $type){
            if ($term_name == 'I PUC') {

                $studentInfo = $this->application->getAdmissionCompletedStudent($year);
                $total_state_fee_by_type = 0;
                $total_cbse_fee_by_type = 0;
                $total_nri_fee_by_type = 0;
                foreach ($studentInfo as $std) {
                    $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row)->getFont()->setSize(14);
                    $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row,  $sl_number);
                    $spreadsheet->getActiveSheet()->setCellValue('B' . $excel_row,  "");
                    $spreadsheet->getActiveSheet()->setCellValue('C' . $excel_row,  $std->application_number);
                    $spreadsheet->getActiveSheet()->setCellValue('D' . $excel_row,  $std->name);
                    $spreadsheet->getActiveSheet()->setCellValue('E' . $excel_row,  $std->second_language);
                    $spreadsheet->getActiveSheet()->setCellValue('F' . $excel_row,  $std->stream_name);
                    $spreadsheet->getActiveSheet()->setCellValue('G' . $excel_row,  $std->student_category);
                    $filter['fee_year'] = $year;
                    $filter['term_name'] = 'I PUC';
                    $filter['stream_name'] = $std->stream_name;
                    if (strtoupper($std->second_language) == 'FRENCH') {
                        $filter['lang_fee_status'] = true;
                        $french_fee = 5000;
                    } else {
                        $filter['lang_fee_status'] = false;
                        $french_fee = 0;
                    }

                    $filter['category'] = strtoupper($std->student_category);
                    $boardInfo = $this->application->getStudentRegisteredInfo($std->resgisted_tbl_row_id);
                    $data['board_id'] = $boardInfo->sslc_board_name_id;
                    if ($boardInfo->sslc_board_name_id == 1) {
                        $filter['board_name'] = "SSLC";
                    } else {
                        $filter['board_name'] = "OTHER";
                    }
                    $total_fee = $this->fee->getTotalFeeAmount($filter);
                    $total_fee_amount = $total_fee->total_fee;
                    $total_paid_amount = $this->fee->getSUM_Paid_FeeInfoByReceiptNum_2021_I_PUC($std->application_number,$year);
                    if($total_paid_amount->paid_amount == ''){
                        $paid_amt = 0;
                    }else{
                        $paid_amt = $total_paid_amount->paid_amount;
                    }
                    $spreadsheet->getActiveSheet()->setCellValue('H' . $excel_row,  $total_fee_amount);
                    $spreadsheet->getActiveSheet()->setCellValue('I' . $excel_row,  $french_fee);
                    $spreadsheet->getActiveSheet()->setCellValue('J' . $excel_row,  $paid_amt);
                    $spreadsheet->getActiveSheet()->setCellValue('K' . $excel_row,  $total_fee_amount - $total_paid_amount->paid_amount);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setWrapText(true);

                    $sl_number++;
                    $excel_row++;
                }
            } else {
                if($year == CURRENT_YEAR ){
                    $yr = $year-1;
                }else{
                    $yr = $year-2;
                }
                $studentInfo = $this->student->getAllStudentInfo_For_Fee_report($term_name,$yr);
                $total_state_fee_by_type = 0;
                $total_cbse_fee_by_type = 0;
                $total_nri_fee_by_type = 0;
                foreach ($studentInfo as $std) {

                    $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row)->getFont()->setSize(14);
                    $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row,  $sl_number);
                    $spreadsheet->getActiveSheet()->setCellValue('B' . $excel_row,  $std->student_id);
                    $spreadsheet->getActiveSheet()->setCellValue('C' . $excel_row,  $std->application_no);
                    $spreadsheet->getActiveSheet()->setCellValue('D' . $excel_row,  $std->student_name);
                    $spreadsheet->getActiveSheet()->setCellValue('E' . $excel_row,  $std->elective_sub);
                    $spreadsheet->getActiveSheet()->setCellValue('F' . $excel_row,  $std->stream_name);
                    $spreadsheet->getActiveSheet()->setCellValue('G' . $excel_row,  $std->category);
                    $filter['fee_year'] = $year;
                    $filter['term_name'] = 'II PUC';
                    $filter['stream_name'] = $std->stream_name;
                    if (strtoupper($std->elective_sub) == 'FRENCH') {
                        $filter['lang_fee_status'] = true;
                        $french_fee = 5000;
                    } else {
                        $filter['lang_fee_status'] = false;
                        $french_fee = 0;
                    }

                    $filter['category'] = strtoupper($std->category);

                    $total_fee = $this->fee->getTotalFeeAmount($filter);
                    $total_fee_amount = $total_fee->total_fee;
                    if($year== CURRENT_YEAR){
                        $total_paid_amount = $this->fee->getSUM_Paid_FeeInfoByReceiptNum_2021($std->application_no,$year);
                    }else{
                        $total_paid_amount = $this->fee->getSUM_Paid_FeeInfoIIPucLastYear($application_no);
                    }
                    if($total_paid_amount->paid_amount == ''){
                        $paid_amt = 0;
                    }else{
                        $paid_amt = $total_paid_amount->paid_amount;
                    }
                    $spreadsheet->getActiveSheet()->setCellValue('H' . $excel_row,  $total_fee_amount);
                    $spreadsheet->getActiveSheet()->setCellValue('I' . $excel_row,  $french_fee);
                    $spreadsheet->getActiveSheet()->setCellValue('J' . $excel_row,  $paid_amt);
                    $spreadsheet->getActiveSheet()->setCellValue('K' . $excel_row,  $total_fee_amount - $total_paid_amount->paid_amount);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setWrapText(true);

                    $sl_number++;
                    $excel_row++;
                }
            }
            // $excel_row++;

            // //$sl_number++;
            // $excel_row++;
            // }
            // $excel_row++;
            // $spreadsheet->getActiveSheet()->setCellValue('A'.$excel_row,  "");
            // $spreadsheet->getActiveSheet()->setCellValue('B'.$excel_row,  'ALL TOTAL');
            // $spreadsheet->getActiveSheet()->setCellValue('C'.$excel_row,  $total_sslc_state_fee);
            // $spreadsheet->getActiveSheet()->setCellValue('D'.$excel_row,  $total_cbse_icse_fee);
            // $spreadsheet->getActiveSheet()->setCellValue('E'.$excel_row,  $total_nri_fee);
            $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row . ":E" . $excel_row)->applyFromArray($font_style_total);
            $spreadsheet->createSheet();
            $i++;
            // $spreadsheet->getActiveSheet()->getStyle('A1:F'.$excel_row)->applyFromArray($styleBorder);
            //getting optional fee info




            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="fee_structure_' . $term_name . '.xlsx"');
            header('Cache-Control: max-age=0');
            setcookie('isDownLoaded', 1);
            $writer->save("php://output");
        }
    }


    public function downloadApplicationStack()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {

            $report_type = $this->security->xss_clean($this->input->post('report_type'));
            $preference = $this->security->xss_clean($this->input->post('preference'));
            $board_name = $this->security->xss_clean($this->input->post('by_board'));
            $percentage_from = $this->security->xss_clean($this->input->post('percentage_from'));
            $percentage_to = $this->security->xss_clean($this->input->post('percentage_to'));
            $student_type = $this->security->xss_clean($this->input->post('student_type'));
            $admission_year = $this->security->xss_clean($this->input->post('admission_year')); 
            $category_by = $this->security->xss_clean($this->input->post('by_category'));
            $integrated_batch = $this->security->xss_clean($this->input->post('integrated_batch'));

            if($admission_year ==2022){

                $header = ' LIST 2022-2023';
            }else{
                $header = ' LIST 2021-2022';

            }

            if($report_type == 'APPLICATION_REJECTED'){

                $typee = 'REJECTED';
            }else{
                $typee = ' APPROVED';

            }
            
            $category = array(
                'ROMAN CATHOLIC',
                'OTHER CHRISTIANS',
                'GENERAL MERIT(GM)',
                'SC',
                'ST',
                'CAT-I',
                '2A',
                '3A',
                '2B',
                '3B'
            );
            for ($sheet = 0; $sheet < count($category); $sheet++) {
                $this->excel->setActiveSheetIndex($sheet);
                //name the worksheet

                $this->excel->getActiveSheet()->setTitle($category[$sheet]);
                $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:G500');

                //set Title content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', "ST JOSEPH'S PRE-UNIVERSITY COLLEGE HASSAN");
                $this->excel->getActiveSheet()->setCellValue('A2', "I PUC " . $typee . $header);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->mergeCells('A1:L1');
                $this->excel->getActiveSheet()->mergeCells('A2:L2');
                $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);


                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);



                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL. NO.');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Application Number');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Name');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Board');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Preference');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Category');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Percentage');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'PH');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I3', 'Dyslexia');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J3', 'NCC');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('K3', 'Sports');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('L3', 'Integrated Batch');
                $this->excel->getActiveSheet()->getStyle('A3:L3')->getAlignment()->setWrapText(true);
                $this->excel->getActiveSheet()->getStyle('A3:L3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



                $this->excel->getActiveSheet()->mergeCells('A4:L4');
                $this->excel->getActiveSheet()->getStyle('A4:L4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('A4', $category[$sheet] . "- LIST");
                $this->excel->getActiveSheet()->getStyle('A4:L4')->getFont()->setBold(true);



                $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                $this->excel->getActiveSheet()->getStyle('A1:L4')->applyFromArray($styleBorderArray);

                $students = $this->application->getApprovedListDetails($preference, $category[$sheet], $board_name, $percentage_from, $percentage_to, $type, $student_type, $report_type,$admission_year,$integrated_batch);
                $j = 1;

                $excel_row = 5;
                if ($student_type == 'NCC') {
                    $student_type_print = 'NCC';
                } else if ($student_type == 'SPORTS') {
                    $student_type_print = 'SPORTS';
                } else if ($student_type == 'DYC') {
                    $student_type_print = 'Dyslexia';
                } else if ($student_type == 'PH') {
                    $student_type_print = 'PH';
                } else {
                    $student_type_print = 'ALL';
                }

                foreach ($students as $student) {
                    if ($student->board_name == 'KARNATAKA STATE BOARD') {
                        $board_name_sheet = 'SSLC';
                    } else if ($student->board_name == 'OTHER') {
                        $board_name_sheet = 'OTHERS';
                    } else {
                        $board_name_sheet = $student->board_name;
                    }

                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $student->application_number);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $student->name);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $board_name_sheet);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $student->stream_name);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $student->student_category);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $student->sslc_percentage);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $student->dyslexia_challenged);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $student->physically_challenged);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('J' . $excel_row, $student->ncc_certificate_status);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('K' . $excel_row, $student->national_level_sports_status);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('L' . $excel_row, $student->integrated_batch);
                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':L' . $excel_row)->applyFromArray($styleBorderArray);
                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':L' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $excel_row++;
                }

                $this->excel->createSheet();
            }
            $filename =  $report_type . '_Application_Report_-' . date('d-m-Y') . '.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            setcookie('isDownLoaded', 1);
            $objWriter->save("php://output");
        }
    }


    //  public function downloadApplicationStack(){
    //     if($this->isAdmin() == TRUE){
    //         setcookie('isDownLoaded',1); 
    //         $this->loadThis();
    //     } else {    

    //         $report_type = $this->security->xss_clean($this->input->post('report_type'));
    //         $preference = $this->security->xss_clean($this->input->post('preference'));
    //         $board_name = $this->security->xss_clean($this->input->post('by_board'));
    //         $percentage_from = $this->security->xss_clean($this->input->post('percentage_from'));
    //         $percentage_to = $this->security->xss_clean($this->input->post('percentage_to'));
    //         $student_type = $this->security->xss_clean($this->input->post('student_type')); 
    //         $category_by = $this->security->xss_clean($this->input->post('by_category'));
    //         $category = array(
    //             'ROMAN CATHOLIC',
    //             'OTHER CHRISTIANS',
    //             'GENERAL MERIT(GM)',
    //             'SC',
    //             'ST',
    //             'CAT-I',
    //             '2A',
    //             '3A',
    //             '2B',
    //             '3B');
    //         for($sheet = 0; $sheet < count($category);  $sheet++){
    //             $this->excel->setActiveSheetIndex($sheet);
    //             //name the worksheet

    //             $this->excel->getActiveSheet()->setTitle($category[$sheet]);
    //             $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:G500');

    //             //set Title content with some text
    //             $this->excel->getActiveSheet()->setCellValue('A1', "ST JOSEPH'S PRE-UNIVERSITY COLLEGE HASSAN");
    //             $this->excel->getActiveSheet()->setCellValue('A2', "I PUC ".$preference." APPROVED LIST 2021-2022");
    //             $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
    //             $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
    //             $this->excel->getActiveSheet()->mergeCells('A1:G1');
    //             $this->excel->getActiveSheet()->mergeCells('A2:G2');
    //             $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //             $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);


    //             $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
    //             $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
    //             $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    //             $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
    //             $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
    //             $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    //             $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);



    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL. NO.');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Application Number');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Name');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Board');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Preference');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Category');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Percentage');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'PH');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('I3', 'Dyslexia');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('J3', 'NCC');
    //             $this->excel->setActiveSheetIndex($sheet)->setCellValue('K3', 'Sports');
    //             $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setWrapText(true); 
    //             $this->excel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true); 
    //             $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



    //             $this->excel->getActiveSheet()->mergeCells('A4:K4');
    //             $this->excel->getActiveSheet()->getStyle('A4:K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //             $this->excel->getActiveSheet()->setCellValue('A4', $category[$sheet]."- LIST");
    //             $this->excel->getActiveSheet()->getStyle('A4:K4')->getFont()->setBold(true);



    //             $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
    //             $this->excel->getActiveSheet()->getStyle('A1:G4')->applyFromArray($styleBorderArray);

    //             $students = $this->application->getApprovedListDetails($preference,$category[$sheet],$board_name,$percentage_from,$percentage_to,$type,$student_type,$report_type);
    //             $j=1;

    //             $excel_row = 5;
    //             if($student_type == 'NCC'){
    //                 $student_type_print = 'NCC';
    //             }else if($student_type == 'SPORTS'){
    //                 $student_type_print = 'SPORTS';
    //             }else if($student_type == 'DYC'){
    //                 $student_type_print = 'Dyslexia';
    //             }else if($student_type == 'PH'){
    //                 $student_type_print = 'PH';
    //             }else{
    //                 $student_type_print = 'ALL';
    //             }

    //             foreach($students as $student){
    //                 if($student->board_name == 'KARNATAKA STATE BOARD'){
    //                     $board_name_sheet = 'SSLC';
    //                 } else if($student->board_name == 'OTHER'){
    //                     $board_name_sheet = 'OTHERS';
    //                 }else{
    //                     $board_name_sheet = $student->board_name;
    //                 }

    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('A'.$excel_row,$j++);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('B'.$excel_row,$student->application_number);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('C'.$excel_row,$student->name);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('D'.$excel_row,$board_name_sheet);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('E'.$excel_row,$student->stream_name);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('F'.$excel_row,$student->student_category);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('G'.$excel_row,$student->sslc_percentage);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('H'.$excel_row,$student->dyslexia_challenged);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('I'.$excel_row,$student->physically_challenged);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('J'.$excel_row,$student->ncc_certificate_status);
    //                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('K'.$excel_row,$student->national_level_sports_status);
    //                 $this->excel->getActiveSheet()->getStyle('A'.$excel_row.':K'.$excel_row)->applyFromArray($styleBorderArray);
    //                 $this->excel->getActiveSheet()->getStyle('A'.$excel_row.':B'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //                 $this->excel->getActiveSheet()->getStyle('D'.$excel_row.':K'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //                 $excel_row++;
    //             }

    //             $this->excel->createSheet(); 

    //         }
    //         $filename =  $report_type.'_Application_Report_-'.date('d-m-Y').'.xls'; //save our workbook as this file name
    //         header('Content-Type: application/vnd.ms-excel'); //mime type
    //         header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
    //         header('Cache-Control: max-age=0'); //no cache

    //         //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //         //if you want to save it as .XLSX Excel 2007 format
    //         $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
    //         ob_start();
    //         setcookie('isDownLoaded',1);  
    //         $objWriter->save("php://output");
    //     }
    // }


    public function downloadAdmittedStudentInfo()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {

            $report_type = $this->security->xss_clean($this->input->post('report_type'));
            $preference = $this->security->xss_clean($this->input->post('stream_name'));
            $board_name = $this->security->xss_clean($this->input->post('by_board'));
            $percentage_from = $this->security->xss_clean($this->input->post('percentage_from'));
            $percentage_to = $this->security->xss_clean($this->input->post('percentage_to'));
            $student_type = $this->security->xss_clean($this->input->post('student_type'));
            $admission_year = $this->security->xss_clean($this->input->post('admission_year'));
            $category_by = $this->security->xss_clean($this->input->post('by_category'));
            $integrated_batch = $this->security->xss_clean($this->input->post('integrated_batch'));

            if($admission_year ==2022){

                $header = ' ADMITTED LIST 2022-2023';
            }else{
                $header = ' ADMITTED LIST 2021-2022';

            }
            $category = array(
                'ROMAN CATHOLIC',
                'OTHER CHRISTIANS',
                'GENERAL MERIT(GM)',
                'SC',
                'ST',
                'CAT-I',
                '2A',
                '3A',
                '2B',
                '3B'
            );
            $sheet = 0;
            //for($sheet = 0; $sheet < count($category);  $sheet++){
            $this->excel->setActiveSheetIndex($sheet);
            //name the worksheet

            $this->excel->getActiveSheet()->setTitle($preference);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:G500');

            //set Title content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', "ST JOSEPH'S PRE-UNIVERSITY COLLEGE HASSAN");
            $this->excel->getActiveSheet()->setCellValue('A2', "I PUC " . $preference . $header);
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A1:K1');
            $this->excel->getActiveSheet()->mergeCells('A2:K2');
            $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);


            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);



            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL. NO.');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Application Number');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Board');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Preference');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Category');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Percentage');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'Religion');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('I3', 'Elective');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('J3', 'NCC');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('K3', 'Sports');
            $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setWrapText(true);
            $this->excel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



            $this->excel->getActiveSheet()->mergeCells('A4:K4');
            $this->excel->getActiveSheet()->getStyle('A4:K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //$this->excel->getActiveSheet()->setCellValue('A4', $category[$sheet]."- LIST");
            $this->excel->getActiveSheet()->getStyle('A4:K4')->getFont()->setBold(true);



            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:K4')->applyFromArray($styleBorderArray);

            $students = $this->application->getAdmittedListDetails($preference, $board_name, $percentage_from, $percentage_to, $type, $student_type, $report_type,$admission_year,$integrated_batch);
            $j = 1;

            $excel_row = 5;
            if ($student_type == 'NCC') {
                $student_type_print = 'NCC';
            } else if ($student_type == 'SPORTS') {
                $student_type_print = 'SPORTS';
            } else if ($student_type == 'DYC') {
                $student_type_print = 'Dyslexia';
            } else if ($student_type == 'PH') {
                $student_type_print = 'PH';
            } else {
                $student_type_print = 'ALL';
            }

            foreach ($students as $student) {
                if ($student->board_name == 'KARNATAKA STATE BOARD') {
                    $board_name_sheet = 'SSLC';
                } else if ($student->board_name == 'OTHER') {
                    $board_name_sheet = 'OTHERS';
                } else {
                    $board_name_sheet = $student->board_name;
                }

                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $student->application_number);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $student->name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $board_name_sheet);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $student->stream_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $student->student_category);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $student->sslc_percentage);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $student->religion);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $student->second_language);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J' . $excel_row, $student->ncc_certificate_status);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('K' . $excel_row, $student->national_level_sports_status);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':K' . $excel_row)->applyFromArray($styleBorderArray);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':K' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel_row++;
            }

            $this->excel->createSheet();

            // }
            $filename =  $report_type . '_Application_Report_-' . date('d-m-Y') . '.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            setcookie('isDownLoaded', 1);
            $objWriter->save("php://output");
        }
    }
    public function downloadAdmissionRegisteredStudent()
    {
        if ($this->isAdmin() == true) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $filter = array();
            $student = $this->security->xss_clean($this->input->post('by_student'));
            $term = $this->security->xss_clean($this->input->post('term'));
            $report_type = $this->security->xss_clean($this->input->post('report_type'));
            $by_sslc_board = $this->security->xss_clean($this->input->post('by_board'));
            $elective_sub = $this->security->xss_clean($this->input->post('elective_sub'));
            $cellNameByStudentReport = array('G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $sheet = 0;
            $this->excel->setActiveSheetIndex($sheet);
            $this->excel->getActiveSheet()->setTitle($sheet);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:N500');
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', $report_type . " Report");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A1:N1');
            $this->excel->getActiveSheet()->mergeCells('A2:N2');
            $this->excel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A2:N2')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel_row = 3;
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(16);

            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
            $this->excel->getActiveSheet()->getStyle('A3:N3')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, 'SL No.');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, 'Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, 'DOB');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, 'Registration No.');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, 'Board Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, 'Mobile');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, 'Email');
            $filter['report_type'] = $report_type;
            // $filter['stream_name']= $stream[$sheet];
            $filter['by_sslc_board'] = $by_sslc_board;

            $filter['term'] = $term;
            $sl = 1;
            $excel_row = 4;
            $studentInfo = $this->application->getAllRegisteredStdInfo($filter);
            foreach ($studentInfo as $std) {
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $sl++);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $std->name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, date('d-m-Y', strtotime($std->dob)));
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $std->registration_number);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $std->board_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $std->mobile);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $std->email);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('C' . $excel_row . ':F' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('I' . $excel_row . ':L' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel_row++;
            }
            $this->excel->createSheet();
            // }

        }

        $filename =  $report_type . '_Report_-' . date('d-m-Y') . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        ob_start();
        setcookie('isDownLoaded', 1);
        $objWriter->save("php://output");
    }


    public function dayWiseStructureFeePayment()
    {
        $filter = array();
        $date_to = $this->security->xss_clean($this->input->post('date_to'));
        $date_from = $this->security->xss_clean($this->input->post('date_from'));


        $cellNameByStudentReport = array('E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF');
        $sheet = 0;
        $this->excel->setActiveSheetIndex($sheet);
        //name the worksheet
        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $this->excel->getActiveSheet()->setTitle('Fee Paid Report By Structure');
        $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:G500');
        //set Title content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
        $this->excel->getActiveSheet()->setCellValue('A2', $term . " Fee Structure Report 2021-22");
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
        // $this->excel->getActiveSheet()->setCellValue('A3', "Account Number : ".$bankAccount->account_no);
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(14);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:AF1');
        $this->excel->getActiveSheet()->mergeCells('A2:AF2');
        $this->excel->getActiveSheet()->mergeCells('A3:AF3');
        $this->excel->getActiveSheet()->getStyle('A1:AF3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $excel_row = 4;
        if (!empty($date_to) && !empty($date_from)) {
            $filter['date_to'] = date('Y-m-d', strtotime($date_to));
            $filter['date_from'] = date('Y-m-d', strtotime($date_from));
        } else {
            $filter['date_to'] = "";
            $filter['date_from'] = "";
        }

        $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, 'Date');
        $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, 'Invoice No.');
        $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, 'Application No');
        $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, 'Name');
        $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, 'Stream');
        $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':Z' . $excel_row)->getFont()->setBold(true);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $cell_name = 1;
        $bank_account_amount = array();
        $fee_structure_total = array();
        $feeStructureInfo = $this->fee->getAllFeeStructureInfoForReport();
        $fee_type_name = "";
        $array_of_fee_type_id = array('');
        $fee_name_row_id = array('1', '2', '9', '4', '7', '3');
        foreach ($fee_name_row_id as $row_id) {
            $feeInfo = $this->fee->getFeeTitleInfoById($row_id);
            $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name] . $excel_row, $feeInfo->fee_name);

            $this->excel->getActiveSheet()->getStyle($cellNameByStudentReport[$cell_name] . $excel_row . ':Z' . $this->excel->getActiveSheet()->getHighestRow())
                ->getAlignment()->setWrapText(true);
            $cell_name++;
        }
        // foreach($feeStructureInfo as $fee){

        //     $fee_structure_total[$fee->row_id] = 0;
        //    // $fee_structure[$fee->row_id] = 0;
        //     // if($fee_type_name != $fee->fees_type){
        //     $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name].$excel_row, $fee->fee_name);

        //     $this->excel->getActiveSheet()->getStyle($cellNameByStudentReport[$cell_name].$excel_row.':Z'.$this->excel->getActiveSheet()->getHighestRow())
        //     ->getAlignment()->setWrapText(true);
        //     $cell_name++;
        //  //  }
        //   // $fee_type_name = $fee->fees_type;
        // }

        $this->excel->getActiveSheet()->getStyle('A4:Z4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name+1].$excel_row, 'Society Fee');
        $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name] . $excel_row, 'Grand Total');
        $excel_row++;
        $grand_total = 0;
        $paidInfo = $this->fee->getFeePaidInfoForReport($date_from, $date_to);

        foreach ($paidInfo as $paid) {
            $cell_name = 1;
            $grand_total_date = 0;
            $fee_type_name = "";
            $amount = 0;
            $total_fee_row = 0;
            $elective = substr($paid->second_language, 0, 1);
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, date('d-m-Y', strtotime($paid->payment_date)));
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $paid->row_id);
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $paid->application_no);
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $paid->name);
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $paid->stream_name);

            // foreach($feeStructureInfo as $fee){
            //     $paidAmt = $this->fee->getFeeStructureAmount($paid->receipt_number,$fee->fees_type);
            //     $amount = $paidAmt->paid_amount;
            //     $total_fee_row += $amount;
            //     $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name].$excel_row, $amount);
            //     $cell_name++;
            // }
            $total_amt = array();
            foreach ($fee_name_row_id as $row_id) {
                $paidAmt = $this->fee->getFeeStructureAmount($paid->receipt_number, $row_id);
                if ($row_id == 7) {
                    $mgmtAmt = $this->fee->getMgmtFeePaidInfo($paid->application_no);
                    if (!empty($mgmtAmt)) {
                        $mgmt_amt = $mgmtAmt->amount;
                        $total_fee_row += $mgmtAmt->amount;
                        $total_mgnt_fee += $mgmt_amt;
                    } else {
                        $mgmt_amt = 0;
                    }
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name] . $excel_row, $mgmt_amt);
                    $cell_name++;
                    // log_message('debug','ehue'.$total_fee_row);
                } else {
                    $amount = $paidAmt->paid_amount;
                    $total_fee_row += $amount;
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name] . $excel_row, $amount);
                    $cell_name++;
                }
            }


            // $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name+1].$excel_row, $mgmt_amt);
            $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameByStudentReport[$cell_name] . $excel_row, $total_fee_row);

            $this->excel->getActiveSheet()->getStyle('L' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel_row++;
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, 'Total');
        }
        $this->excel->getActiveSheet()->getStyle('A' . $excel_row)->getFont()->setBold(true);

        $date_from_ = strtotime($date_from); // Convert date to a UNIX timestamp  
        $date_to_ = strtotime($date_to); // Convert date to a UNIX timestamp  

        // Loop from the start date to end date and output all dates inbetween 

        for ($i = $date_from_; $i <= $date_to_; $i += 86400) {

            $date =  date("Y-m-d", $i);
            //  log_message('debug','fghjk='.$date); 



        }


        $this->excel->createSheet();
        $filename =  'Fees_Structure_Report_-' . date('d-m-Y') . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // $objWriter->setPreCalculateFormulas(true);  
        ob_start();
        setcookie('isDownLoaded', 1);
        $objWriter->save("php://output");
    }

    // exam mark sheet
    public function downloadExamMarkSheet()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            set_time_limit(0);
            ini_set('memory_limit', '256M');
            $term_name = $this->security->xss_clean($this->input->post('term_name'));
            $section_name = $this->security->xss_clean($this->input->post('section_name'));
            $stream_name = $this->security->xss_clean($this->input->post('stream_name'));
            $subject_code = $this->security->xss_clean($this->input->post('subject_code'));
            $filter = array();
            $filter['term'] = $term_name;
            $filter['subject_code'] = $subject_code;
            $filter['term_name'] = $term_name;
            $filter['stream_name'] = $stream_name;

            $term = $term_name;
            $cellNameCategory = array('E', 'F', 'G', 'H', 'I', 'J');
            $filter['section_name'] = $section_name;
            if ($section_name != "ALL") {
                $section = $section_name;
            } else {
                $section = '';
            }
            $sections = array($section_name);
            $subjectInfo = $this->subject->getAllSubjectByID($subject_code);
            $sheet = 0;
            $j = 1;
            $excel_row = 6;
            $filter['subject_name'] = $subjectInfo->sub_name;
            $subject_name = $subjectInfo->sub_name;
            // $class_section = $section_name[$sheet];
            $this->excel->setActiveSheetIndex($sheet);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle($stream_name);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:K500');
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', $term . ' ' . $stream_name . ' ' . $section . " MARKS SHEET 2022-23");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A1:J1');
            $this->excel->getActiveSheet()->mergeCells('A2:J2');
            $this->excel->getActiveSheet()->getStyle('A1:A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->mergeCells('A1:J1');
            $this->excel->getActiveSheet()->mergeCells('A2:J2');
            $this->excel->getActiveSheet()->setCellValue('A3', strtoupper($subjectInfo->sub_name));
            $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A3:J3');


            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);


            if ($subjectInfo->subject_code == 12) {
                $labStatus = 'true';
                $lab_title = 8;
            } else {
                $labStatus = $subjectInfo->lab_status;
                $lab_title = 8;
            }


            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A4', 'SL. NO.');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B4', 'REG. No');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C4', 'NAME OF THE STUDENT');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D4', 'PASS MARKS');
            if ($labStatus == 'true') {
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E4', 'LAB');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F4', 'UNIT TEST-1');
                // $this->excel->setActiveSheetIndex($sheet)->setCellValue('G4', 'ASSIGNMENT-2');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G4', 'LAB-' . $lab_title);
                // $this->excel->setActiveSheetIndex($sheet)->setCellValue('H4', 'INT. ASSMNT');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H4', 'TOTAL MARKS');

                // if($subjectInfo->subject_code != 12){
                //     $this->excel->setActiveSheetIndex($sheet)->setCellValue('H5', 'REC-10');
                // }
            } else {
                // $this->excel->setActiveSheetIndex($sheet)->setCellValue('E4', 'ASSIGNMENT-1');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E4', 'UNIT TEST-1');
                // $this->excel->setActiveSheetIndex($sheet)->setCellValue('F4', 'ASSIGNMENT-2');
                // $this->excel->setActiveSheetIndex($sheet)->setCellValue('F4', 'INT. ASSMNT');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H4', 'TOTAL MARKS');
            }


            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D5', 'THEORY');


            // $this->excel->getActiveSheet()->getStyle('A3:J5')->getAlignment()->setWrapText(true); 
            $this->excel->getActiveSheet()->getStyle('A3:J5')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A3:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:J5')->applyFromArray($styleBorderArray);

            $students = $this->student->getStudentInfoForInternal($filter);
            // log_message('debug','dnd='.print_r($filter,true));
            $total_mark = 0;
            foreach ($students as $student) {
                $section = $student->section_name;
                $filter['section'] = $section;
                $percentage_active = false;
                $elective_sub = strtoupper($student->elective_sub);

                if ($labStatus == 'true') {
                    if ($subjectInfo->subject_code == 12) {
                        $pass_mark_theory = 18;
                        $pass_mark_lab = 0;
                    } else {
                        $pass_mark_theory = 12;
                        $pass_mark_lab = 0;
                    }
                } else {
                    $pass_mark_theory = 35;
                    $pass_mark_lab = 0;
                }

                $subject_code == $subjectInfo->subject_code;
                $total_class_held_per_std = 0;
                $total_attd_class_std = 0;
                $absentCount = 0;
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $student->student_id);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $student->student_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $pass_mark_theory);

                $cellName = 0;
                if ($labStatus == 'true') {
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameCategory[$cellName] . $excel_row, $pass_mark_lab);
                    $cellName++;
                    // $exam_type = array('ASSIGNMENT_I', 'ASSIGNMENT_II');
                    $exam_type = array('I_UNIT_TEST');
                    if ($subjectInfo->subject_code == 12) {
                        $lab_assessment = 8;
                    } else {
                        $lab_assessment = 8;
                    }
                } else {
                    // $exam_type = array('ASSIGNMENT_I', 'ASSIGNMENT_II');
                    $exam_type = array('I_UNIT_TEST');
                    $lab_assessment = 0;
                    // ,'INTERNAL_ASSESSMENT'
                }
                //,'LAB_ASSESSMENT','INTERNAL_ASSESSMENT'

                // if ($student->student_id == '20P5965' || $student->student_id == '20P4140' || $student->student_id == '20P1754') {
                //     $internal_assessment = 1;
                // } else {
                //     $internal_assessment = 5;
                // }
                $mark_obt = 0;
                $total_mark = 0;
                foreach ($exam_type as $exam) {

                    $stdMarkInfo = $this->student->getStudentFinalMarks($student->student_id, $subject_code, $exam);
                    $sub_marks = 0;
                    $mark_obt = 0;
                    // if ($stdMarkInfo->exam_type == 'ASSIGNMENT_I' || $stdMarkInfo->exam_type == 'ASSIGNMENT_II') {
                    //     if ($stdMarkInfo->obt_theory_mark == 'AB' || $stdMarkInfo->obt_theory_mark == 'EXEM' || $stdMarkInfo->obt_theory_mark == 'MP' || $stdMarkInfo->obt_theory_mark ==  'ASGN') {
                    //         $mark_obt = 0;
                    //     } else {
                    //         $sub_marks = $this->getAssessmentMark($stdMarkInfo->obt_theory_mark, $stdMarkInfo->exam_type, $labStatus, $subject_code);
                    //         $mark_obt = $sub_marks;
                    //     }
                    // } else {
                        if ($stdMarkInfo->obt_theory_mark == 'AB' || $stdMarkInfo->obt_theory_mark == 'EXEM' || $stdMarkInfo->obt_theory_mark == 'MP' || $stdMarkInfo->obt_theory_mark ==  'ASGN') {
                            $mark_obt = 0;
                        } else {
                            $mark_obt = $stdMarkInfo->obt_theory_mark;
                        }
                    // }
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameCategory[$cellName] . $excel_row, $mark_obt);
                    $total_mark += $mark_obt;
                    $cellName++;
                }
                if ($labStatus == 'true') {
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameCategory[$cellName] . $excel_row, $lab_assessment);
                    $cellName++;
                }
                // $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameCategory[$cellName] . $excel_row, $internal_assessment);
                $totalMark = $total_mark + $pass_mark_theory + $pass_mark_lab + $lab_assessment;
                $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellNameCategory[$cellName] . $excel_row, $totalMark);
                $cellName++;

                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':J' . $excel_row)->applyFromArray($styleBorderArray);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':J' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $excel_row++;
            }

            $this->excel->createSheet();

            $filename =  $term . '_' . $stream_name . '_' . $subject_name . '_EXAM_MARKS_SHEET.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment; filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            setcookie('isDownLoaded', 1);
            $objWriter->save("php://output");
        }
    }


    // combined mark report - assignment exam
    public function downloadAssignmentExamMarkReport()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $j = 1;
            $sheet = 0;
            $term_name = $this->input->post("term_name");
            $stream_name = $this->input->post("stream_name");

            // $term_name = 'I PUC';
            $first_cell = array("L", "O", "R", "U");
            $middle_cell = array("M", "P", "S", "V");
            $last_cell = array("N", "Q", "T", "W");
            //$section = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q");
            $streamInfo = $this->student->getAllStreamName();

            if ($stream_name == 'ALL') {
                $stream_name = array(
                    'PCMB',
                    'PCMC',
                    'CEBA',
                    'SEBA',
                    'HESP',
                    'HEBA'
                );
            } else {
                $stream_name = array($stream_name);
            }

            // $term = 'I PUC';

            foreach ($stream_name as $stream) {
                $stream_name = $stream;
                $subjects = $this->getSubjectCodes($stream_name);
                // log_message('debug','subjects '.print_r($subjects,true));


                $this->excel->setActiveSheetIndex($sheet);
                // $sheet++;
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle($stream_name);
                //set Title content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
                $this->excel->getActiveSheet()->setCellValue('A2', "ANNUAL EXAMINATION OF ".$term_name." AUGUST-2022");
                $this->excel->getActiveSheet()->setCellValue('A3', "Abbreviation used in the table");
                $this->excel->getActiveSheet()->setCellValue('A4', "MO: Marks Obtained | IA: Internal Assessment | TH: Theory | PR: Practical | LT: Language Total | ST: Subjects Total | TM: Total Marks");
                $this->excel->getActiveSheet()->setCellValue('A5', $term_name . " - " . $stream_name);

                //change the font size 
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
                $this->excel->getActiveSheet()->getStyle('A5:Y5')->getFont()->setSize(13);
                $this->excel->getActiveSheet()->getStyle('A1:A5')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->mergeCells('A1:Z1');
                $this->excel->getActiveSheet()->mergeCells('A2:Z2');
                $this->excel->getActiveSheet()->mergeCells('A3:Z3');
                $this->excel->getActiveSheet()->mergeCells('A4:Z4');
                $this->excel->getActiveSheet()->mergeCells('A5:Z5');
                $this->excel->getActiveSheet()->mergeCells('A6:A7');
                $this->excel->getActiveSheet()->mergeCells('C6:C7');
                $this->excel->getActiveSheet()->mergeCells('B6:B7');
                $this->excel->getActiveSheet()->mergeCells('D6:D7');
                $this->excel->getActiveSheet()->mergeCells('E6:E7');
                $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //settting border style 
                $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                $this->excel->getActiveSheet()->getStyle('A1:Z300')->applyFromArray($styleArray);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A6', 'SL.no');

                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B6', 'SAT No.');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C6', 'Student ID');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D6', 'Name');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E6', 'Lang');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F6', 'Lng');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F7', 'Code');
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(38);

                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G6', 'Language');
                $this->excel->getActiveSheet()->mergeCells('G6:I6');


                $this->excel->getActiveSheet()->mergeCells('X6:X7');
                $this->excel->getActiveSheet()->mergeCells('Y6:Y7');
                $this->excel->getActiveSheet()->mergeCells('Z6:Z7');

                $this->excel->getActiveSheet()->getStyle('G6:I6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G7', 'TH');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H7', 'IA');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I7', 'MO');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J6', 'English(02)');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J7', 'Marks');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('K7', 'LT');

                $this->excel->setActiveSheetIndex($sheet)->setCellValue('X6', 'ST');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('Y6', 'TM');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('Z6', 'Result');

                //$this->excel->getActiveSheet()->mergeCells('K2:M2');
                $excel_row = 7;
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(6);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(5);
                $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(5);
                $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(14);
                $this->excel->getActiveSheet()->getStyle('F1:F3')->getAlignment()->setWrapText(true);
                $this->excel->getActiveSheet()->getStyle('E6:Z300')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('I7:I999')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A6:Z7')->getFont()->setBold(true);

                $this->excel->getActiveSheet()->getStyle('J8:J150')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('X8:Z999')->getFont()->setBold(true);
                $this->cellColor('A6:Z7', 'D5DBDB');

                //first subject heading
                for ($i = 0; $i < 4; $i++) {
                    $subjectInfo = $this->subject->getAllSubjectByID($subjects[$i]);
                    $this->excel->getActiveSheet()->getColumnDimension($first_cell[$i])->setWidth(6);
                    $this->excel->getActiveSheet()->getColumnDimension($middle_cell[$i])->setWidth(6);
                    $this->excel->getActiveSheet()->getColumnDimension($last_cell[$i])->setWidth(6);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '6', $subjectInfo->sub_name . '(' . $subjects[$i] . ')');
                    $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . '6:' . $last_cell[$i] . '6');
                    $this->excel->getActiveSheet()->getStyle($first_cell[$i] . '6:' . $last_cell[$i] . '6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    if ($subjectInfo->lab_status == "true") {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '7', 'TH');
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($middle_cell[$i] . '7', 'PR');
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($last_cell[$i] . '7', 'MO');
                    } else {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '7', "Marks");
                        $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . '7:' . $last_cell[$i] . '7');
                        $this->excel->getActiveSheet()->getStyle($first_cell[$i] . '7:' . $last_cell[$i] . '7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->excel->getActiveSheet()->getColumnDimension($first_cell[$i])->setWidth(5);
                        $this->excel->getActiveSheet()->getColumnDimension($middle_cell[$i])->setWidth(5);
                        $this->excel->getActiveSheet()->getColumnDimension($last_cell[$i])->setWidth(5);
                    }
                }

                $studentInfo = $this->student->getStudentsToAnnualResultReport($term_name, $stream_name);
                $excel_row = 8;
                $k = 1;
                foreach ($studentInfo  as $row) {
                    $subjects_code = array();
                    $elective_sub = strtoupper($row->elective_sub);
                    if ($elective_sub == "KANNADA") {
                        array_push($subjects_code, '01');
                    } else if ($elective_sub == 'HINDI') {
                        array_push($subjects_code, '03');
                    } else if ($elective_sub == 'FRENCH') {
                        array_push($subjects_code, '12');
                    }
                    array_push($subjects_code, '02');
                    $subjects_code = array_merge($subjects_code, $subjects);
                    // log_message('debug','scdcndj'.print_r($subjects_code,true));

                    $first_language_code = '';
                    $first_language_name = '';
                    $total_marks_subjects = 0;
                    $total_marks_all_subjects = 0;
                    $fail_flag = false;
                    $student_status = 0;
                    // $data['studentsMarks'] = $this->exams->getFullMarksOfStudentInternal($row->student_id,$exam_type);

                    // if(!empty($data['studentsMarks']) && $student_status == 0){
                    $first_language_total = 0;
                    $second_lang_mark = 0;
                    $first_lan_TH = 0;
                    $first_lan_IA = 0;
                    $subject_code_from_subjects = 0;
                    foreach ($subjects_code as $subject) {
                        // foreach($data['studentsMarks']  as $mark){
                        $subject_true = false;
                        if ($subject == '01') {
                            $subjectInfo = $this->subject->getAllSubjectByID($subject);
                            $first_language_code = $subject;
                            $first_language_name = "KAN";
                            $theory_mark = $this->getAssignmentExamTheoryTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);
                            $lab_mark = $this->getAssignmentExamLabTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);
                            $first_lan_TH =  $theory_mark;
                            $first_lan_IA =  $lab_mark;
                            $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;

                            $first_language_total =  $first_language_total;

                            // if($first_language_total < $pass_mark && $first_lan_TH != 'ASGN'){
                            //     // log_message('debug','value==' .$pass_mark);
                            //     $this->cellColor('F'.$excel_row.':H'.$excel_row, 'FFEE58');
                            //     $fail_flag = true;
                            // }
                        } else if ($subject == '03') {
                            $subjectInfo = $this->subject->getAllSubjectByID($subject);
                            $first_language_code = $subject;
                            $first_language_name = "HINDI";
                            $theory_mark = $this->getAssignmentExamTheoryTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);
                            $lab_mark = $this->getAssignmentExamLabTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);
                            $first_lan_TH =  $theory_mark;
                            $first_lan_IA =  $lab_mark;
                            $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                            $first_language_total =  $first_language_total;


                            // if($first_language_total < $pass_mark && $first_lan_TH != 'ASGN'){
                            //     $this->cellColor('F'.$excel_row.':H'.$excel_row, 'FFEE58');
                            //     $fail_flag = true;
                            // }
                        } else if ($subject == '12') {
                            $subjectInfo = $this->subject->getAllSubjectByID($subject);
                            $first_language_code = $subject;
                            $first_language_name = "FRENCH";
                            $theory_mark = $this->getAssignmentExamTheoryTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);
                            $lab_mark = $this->getAssignmentExamLabTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);
                            $first_lan_TH =  $theory_mark;
                            $first_lan_IA =  $lab_mark;
                            $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                            $first_language_total =  $first_language_total;

                            // if($first_lan_TH < $pass_mark && $first_lan_TH != 'ASGN'){
                            //     $this->cellColor('F'.$excel_row.':H'.$excel_row, 'FFEE58');
                            //     $fail_flag = true;
                            // } else if($first_language_total < $pass_mark && $first_lan_TH != 'ASGN'){
                            //     $this->cellColor('F'.$excel_row.':H'.$excel_row, 'FFEE58');
                            //     $fail_flag = true;
                            // }

                        } else if ($subject == '02') {
                            $subjectInfo = $this->subject->getAllSubjectByID($subject);
                            $theory_mark = $this->getAssignmentExamTheoryTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);
                            $second_lang_mark =  $theory_mark;

                            // if($second_lang_mark < $pass_mark && $second_lang_mark != 'ASGN'){
                            //     $this->cellColor('I'.$excel_row.':J'.$excel_row, 'FFEE58');
                            //     $fail_flag = true;
                            // }
                        } else {
                            $sub_theory_mark = 0;
                            $sub_lab_mark = 0;
                            for ($i = 0; $i < 4; $i++) {
                                if ($subject == $subjects[$i]) {
                                    $subjectInfo = $this->subject->getAllSubjectByID($subjects[$i]);
                                    $theory_mark = $this->getAssignmentExamTheoryTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);
                                    $lab_mark = $this->getAssignmentExamLabTotalMark($row->student_id, $subjectInfo->subject_code, $subjectInfo->lab_status);

                                    if ($subjectInfo->lab_status == 'true') {
                                        $sub_theory_mark = (int)$theory_mark;
                                        $sub_lab_mark = (int)$lab_mark;
                                        $sub_total_mark = $sub_theory_mark + $sub_lab_mark;
                                        $sub_total_mark =  $sub_total_mark;
                                        $sub_theory_mark = $sub_theory_mark;

                                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . $excel_row, $theory_mark);
                                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($middle_cell[$i] . $excel_row, $lab_mark);
                                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($last_cell[$i] . $excel_row,  $sub_total_mark);
                                    } else {
                                        $sub_theory_mark = (int)$theory_mark;
                                        $sub_theory_mark = $sub_theory_mark;

                                        // if($sub_theory_mark < $pass_mark && $theory_mark != 'ASGN'){
                                        //     $fail_flag = true;
                                        //     $this->cellColor($first_cell[$i].$excel_row.':'.$first_cell[$i].$excel_row, 'FFEE58');
                                        // }
                                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . $excel_row, $theory_mark);
                                        $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row);
                                        $this->excel->getActiveSheet()->getStyle($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                    $total_marks_subjects +=  $sub_theory_mark + $sub_lab_mark;
                                }
                            }
                        }
                    }

                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $k++);
                    //student info
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->sat_number);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->student_id);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->student_name);
                    //adding first Language
                    // $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;

                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row,  $first_language_name);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row,  $first_language_code);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $first_lan_TH);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row,  $first_lan_IA);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $first_language_total);
                    //second Language
                    $total_language_mark = $first_language_total + (int)$second_lang_mark;
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('J' . $excel_row, $second_lang_mark);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('K' . $excel_row, $total_language_mark);

                    $total_marks_all_subjects = $total_marks_subjects + (int)$first_language_total + (int)$second_lang_mark;
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('X' . $excel_row, $total_marks_subjects);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('Y' . $excel_row, $total_marks_all_subjects);

                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':C' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    if ($fail_flag == true) {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('Z' . $excel_row, "Failed");
                    } else {
                        $result = $this->calculateResult($total_marks_all_subjects);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('Z' . $excel_row, $result);
                    }
                    $excel_row++;
                    // }

                }
                $this->excel->createSheet();
                $sheet++;
                // }
            }

            $filename =  $term_name . '_' . $stream_name . '_EXAM_MARKS_SHEET.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment; filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            setcookie('isDownLoaded', 1);
            $objWriter->save("php://output");
        }
    }


    public function cellColor($cells, $color)
    {
        return $this->excel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $color
            )
        ));
    }



    public function getAllMeritListByApproved()
    {

        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $type = $this->security->xss_clean($this->input->post('type'));
            $preference = $this->security->xss_clean($this->input->post('preference'));
            $board_name = $this->security->xss_clean($this->input->post('board_name'));
            $percentage_from = $this->security->xss_clean($this->input->post('percentage_from'));
            $percentage_to = $this->security->xss_clean($this->input->post('percentage_to'));
            $student_type = $this->security->xss_clean($this->input->post('student_type'));

            $category = array(
                'ROMAN CATHOLIC',
                'OTHER CHRISTIANS',
                'GENERAL MERIT(GM)',
                'SC',
                'ST',
                'CAT-I',
                '2A',
                '3A',
                '2B',
                '3B'
            );



            for ($sheet = 0; $sheet < count($category); $sheet++) {
                $this->excel->setActiveSheetIndex($sheet);
                //name the worksheet

                $this->excel->getActiveSheet()->setTitle($category[$sheet]);
                $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:G500');

                //set Title content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', "ST JOSEPH'S PRE-UNIVERSITY COLLEGE HASSAN");
                $this->excel->getActiveSheet()->setCellValue('A2', "I PUC " . $preference . " MERIT LIST 2021-2022");
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->mergeCells('A1:G1');
                $this->excel->getActiveSheet()->mergeCells('A2:G2');
                $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);


                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);



                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL. NO.');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Application Number');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Name');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Board');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Preferences');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Category');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Percentage');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'PH');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I3', 'Dyslexia');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J3', 'NCC');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('K3', 'Sports');
                $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setWrapText(true);
                $this->excel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



                $this->excel->getActiveSheet()->mergeCells('A4:K4');
                $this->excel->getActiveSheet()->getStyle('A4:K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('A4', $category[$sheet] . "- LIST");
                $this->excel->getActiveSheet()->getStyle('A4:K4')->getFont()->setBold(true);



                $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                $this->excel->getActiveSheet()->getStyle('A1:G4')->applyFromArray($styleBorderArray);

                $students = $this->application->getMertListDetailsApproved($preference, $category[$sheet], $board_name, $percentage_from, $percentage_to, $type, $student_type);
                $j = 1;

                $excel_row = 5;
                if ($student_type == 'NCC') {
                    $student_type_print = 'NCC';
                } else if ($student_type == 'SPORTS') {
                    $student_type_print = 'SPORTS';
                } else if ($student_type == 'DYC') {
                    $student_type_print = 'Dyslexia';
                } else if ($student_type == 'PH') {
                    $student_type_print = 'PH';
                } else {
                    $student_type_print = 'ALL';
                }

                foreach ($students as $student) {
                    if ($student->board_name == 'KARNATAKA STATE BOARD') {
                        $board_name_sheet = 'SSLC';
                    } else if ($student->board_name == 'OTHER') {
                        $board_name_sheet = 'OTHERS';
                    } else {
                        $board_name_sheet = $student->board_name;
                    }

                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $student->application_number);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $student->name);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $board_name_sheet);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $student->stream_name);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $student->student_category);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $student->sslc_percentage);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $student->dyslexia_challenged);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $student->physically_challenged);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('J' . $excel_row, $student->ncc_certificate_status);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('K' . $excel_row, $student->national_level_sports_status);
                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':K' . $excel_row)->applyFromArray($styleBorderArray);
                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':K' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $excel_row++;
                }

                $this->excel->createSheet();
            }
            $filename = 'just_some_random_name.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache


            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            $objWriter->save("php://output");
            setcookie('isDownLoaded', 1);
            $xlsData = ob_get_contents();
            ob_end_clean();



            $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
            );
            die(json_encode($response));
        }
    }


    public function getAllMeritList()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {

            $category_by = $this->security->xss_clean($this->input->post('by_category'));

            $type = $this->security->xss_clean($this->input->post('type'));

            $preference = $this->security->xss_clean($this->input->post('preference'));

            $board_name = $this->security->xss_clean($this->input->post('board_name'));

            $percentage_from = $this->security->xss_clean($this->input->post('percentage_from'));

            $percentage_to = $this->security->xss_clean($this->input->post('percentage_to'));

            $student_type = $this->security->xss_clean($this->input->post('student_type'));

            $category = array(

                'ROMAN CATHOLIC',

                'OTHER CHRISTIANS',

                'GENERAL MERIT(GM)',

                'SC',

                'ST',

                'CAT-I',

                '2A',

                '3A',

                '2B',

                '3B'
            );



            $j = 1;

            $excel_row = 5;

            $this->excel->setActiveSheetIndex(0);

            //name the worksheet

            $this->excel->getActiveSheet()->setTitle($preference);

            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:G500');

            $this->excel->getActiveSheet()->setCellValue('A1', "ST JOSEPH'S PRE-UNIVERSITY COLLEGE HASSAN");

            $this->excel->getActiveSheet()->setCellValue('A2', "I PUC " . $preference . " MERIT LIST 2021-2022");

            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);

            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);

            $this->excel->getActiveSheet()->mergeCells('A1:G1');

            $this->excel->getActiveSheet()->mergeCells('A2:G2');

            $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);





            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);

            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);

            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);

            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);

            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);



            $this->excel->setActiveSheetIndex(0)->setCellValue('A3', 'SL. NO.');

            $this->excel->setActiveSheetIndex(0)->setCellValue('B3', 'Application Number');

            $this->excel->setActiveSheetIndex(0)->setCellValue('C3', 'Name');

            $this->excel->setActiveSheetIndex(0)->setCellValue('D3', 'Board');

            $this->excel->setActiveSheetIndex(0)->setCellValue('E3', 'Preferences');

            $this->excel->setActiveSheetIndex(0)->setCellValue('F3', 'Category');

            $this->excel->setActiveSheetIndex(0)->setCellValue('G3', 'Percentage');



            $this->excel->setActiveSheetIndex(0)->setCellValue('H3', 'Elective');

            $this->excel->setActiveSheetIndex(0)->setCellValue('I3', 'Religion');

            $this->excel->setActiveSheetIndex(0)->setCellValue('J3', 'Student Id');

            $this->excel->setActiveSheetIndex(0)->setCellValue('K3', 'Section');



            $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setWrapText(true);

            $this->excel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->excel->getActiveSheet()->mergeCells('A4:K4');

            $this->excel->getActiveSheet()->getStyle('A4:K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $this->excel->getActiveSheet()->setCellValue('A4', $board_name . "- LIST");

            $this->excel->getActiveSheet()->getStyle('A4:K4')->getFont()->setBold(true);



            if ($student_type == 'NCC') {

                $student_type_print = 'NCC';
            } else if ($student_type == 'SPORTS') {

                $student_type_print = 'SPORTS';
            } else if ($student_type == 'DYC') {

                $student_type_print = 'Dyslexia';
            } else if ($student_type == 'PH') {

                $student_type_print = 'PH';
            } else {

                $student_type_print = 'ALL';
            }

            for ($sheet = 0; $sheet < count($category); $sheet++) {



                //set Title content with some text



                $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

                $this->excel->getActiveSheet()->getStyle('A1:G4')->applyFromArray($styleBorderArray);



                $students = $this->application->getMertListDetails($preference, $category[$sheet], $board_name, $percentage_from, $percentage_to, $type, $student_type);



                foreach ($students as $student) {

                    if ($student->board_name == 'KARNATAKA STATE BOARD') {

                        $board_name_sheet = 'SSLC';
                    } else if ($student->board_name == 'OTHER') {

                        $board_name_sheet = 'OTHERS';
                    } else {

                        $board_name_sheet = $student->board_name;
                    }

                    if ($student->student_category == 'ROMAN CATHOLIC') {

                        $qouta_name = 'RC';
                    } else if ($student->student_category == 'OTHER CHRISTIANS') {

                        $qouta_name = 'CHR';
                    } else if ($student->student_category == 'GENERAL MERIT(GM)') {

                        $qouta_name = 'GM';
                    } else if ($student->student_category == 'SC') {

                        $qouta_name = 'SC';
                    } else if ($student->student_category == 'ST') {

                        $qouta_name = 'ST';
                    } else if ($student->student_category == 'CAT-I') {

                        $qouta_name = 'CAT-I';
                    } else if ($student->student_category == '2A') {

                        $qouta_name = '2A';
                    } else if ($student->student_category == '2B') {

                        $qouta_name = '2B';
                    } else if ($student->student_category == '3A') {

                        $qouta_name = '3A';
                    } else if ($student->student_category == '3B') {

                        $qouta_name = '3B';
                    }

                    $this->excel->setActiveSheetIndex(0)->setCellValue('A' . $excel_row, $j++);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('B' . $excel_row, $student->application_number);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('C' . $excel_row, $student->name);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('D' . $excel_row, $board_name_sheet);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('E' . $excel_row, $student->stream_name);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('F' . $excel_row, $qouta_name);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('G' . $excel_row, $student->sslc_percentage);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('H' . $excel_row, $student->second_language);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('I' . $excel_row, $student->religion);

                    $this->excel->setActiveSheetIndex(0)->setCellValue('J' . $excel_row, "");

                    $this->excel->setActiveSheetIndex(0)->setCellValue('K' . $excel_row, "");

                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':K' . $excel_row)->applyFromArray($styleBorderArray);

                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':K' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $excel_row++;
                }

                // $this->excel->createSheet(); 

            }

            $filename = 'just_some_random_name.xls'; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

            ob_start();

            $objWriter->save("php://output");
            setcookie('isDownLoaded', 1);

            $xlsData = ob_get_contents();

            ob_end_clean();



            $response =  array(

                'op' => 'ok',

                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)

            );

            die(json_encode($response));
        }
    }


    public function getAllShortlistedList()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {

            $category_by = $this->security->xss_clean($this->input->post('by_category'));
            $type = $this->security->xss_clean($this->input->post('type'));
            $preference = $this->security->xss_clean($this->input->post('preference'));
            $board_name = $this->security->xss_clean($this->input->post('board_name'));
            $percentage_from = $this->security->xss_clean($this->input->post('percentage_from'));
            $percentage_to = $this->security->xss_clean($this->input->post('percentage_to'));
            $student_type = $this->security->xss_clean($this->input->post('student_type'));
            $admission_year = $this->security->xss_clean($this->input->post('admission_year'));
            $shortlist_number = $this->security->xss_clean($this->input->post('shortlist_number'));    
            $integrated_batch = $this->security->xss_clean($this->input->post('integrated_batch'));    

            if($admission_year ==2022){

                $header = 'SHORTLISTED LIST 2022-2023';
            }else{
                $header = 'SHORTLISTED LIST 2021-2022';

            }


            $category = array(
                'ROMAN CATHOLIC',
                'OTHER CHRISTIANS',
                'GENERAL MERIT(GM)',
                'SC',
                'ST',
                'CAT-I',
                '2A',
                '3A',
                '2B',
                '3B'
            );
            for ($sheet = 0; $sheet < count($category); $sheet++) {
                $this->excel->setActiveSheetIndex($sheet);
                //name the worksheet

                $this->excel->getActiveSheet()->setTitle($category[$sheet]);
                $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:G500');

                //set Title content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', "ST JOSEPH'S PRE-UNIVERSITY COLLEGE HASSAN");
                $this->excel->getActiveSheet()->setCellValue('A2', "I PUC " . $preference . $header);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->mergeCells('A1:N1');
                $this->excel->getActiveSheet()->mergeCells('A2:N2');
                $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);


                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);




                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL. NO.');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Application Number');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Name');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Board');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Preferences');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Category');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Percentage');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'PH');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I3', 'Dyslexia');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J3', 'NCC');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('K3', 'Sports');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('L3', 'Father Mobile');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('M3', 'Mother Mobile');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('N3', 'Integrated Batch');
                $this->excel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setWrapText(true);
                $this->excel->getActiveSheet()->getStyle('A3:N3')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



                $this->excel->getActiveSheet()->mergeCells('A4:N4');
                $this->excel->getActiveSheet()->getStyle('A4:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('A4', $category[$sheet] . "- LIST");
                $this->excel->getActiveSheet()->getStyle('A4:N4')->getFont()->setBold(true);



                $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                $this->excel->getActiveSheet()->getStyle('A1:N4')->applyFromArray($styleBorderArray);

                $students = $this->application->getAllShortlistedList($preference, $category[$sheet], $board_name, $percentage_from, $percentage_to, $type, $student_type,$admission_year,$shortlist_number,$integrated_batch);
                $j = 1;

                $excel_row = 5;
                if ($student_type == 'NCC') {
                    $student_type_print = 'NCC';
                } else if ($student_type == 'SPORTS') {
                    $student_type_print = 'SPORTS';
                } else if ($student_type == 'DYC') {
                    $student_type_print = 'Dyslexia';
                } else if ($student_type == 'PH') {
                    $student_type_print = 'PH';
                } else {
                    $student_type_print = 'ALL';
                }

                foreach ($students as $student) {
                    if ($student->board_name == 'KARNATAKA STATE BOARD') {
                        $board_name_sheet = 'SSLC';
                    } else if ($student->board_name == 'OTHER') {
                        $board_name_sheet = 'OTHERS';
                    } else {
                        $board_name_sheet = $student->board_name;
                    }

                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $student->application_number);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $student->name);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $board_name_sheet);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $student->stream_name);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $student->student_category);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $student->sslc_percentage);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $student->dyslexia_challenged);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $student->physically_challenged);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('J' . $excel_row, $student->ncc_certificate_status);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('K' . $excel_row, $student->national_level_sports_status);

                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('L' . $excel_row, $student->father_mobile);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('M' . $excel_row, $student->mother_mobile);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue('N' . $excel_row, $student->integrated_batch);
                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':N' . $excel_row)->applyFromArray($styleBorderArray);
                    $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':N' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $excel_row++;
                }

                $this->excel->createSheet();
            }
            $filename = 'just_some_random_name.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            $objWriter->save("php://output");
            setcookie('isDownLoaded', 1);
            $xlsData = ob_get_contents();

            ob_end_clean();
            $response =  array(
                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
            );
            die(json_encode($response));
        }
    }

    function getAssessmentMark($totalMark, $exam_type, $labStatus, $subject_code)
    {
        if (is_numeric($totalMark) && !empty($totalMark)) {
            if ($labStatus == 'false') {
                if ($exam_type == 'ASSIGNMENT_I' || $exam_type == 'ASSIGNMENT_II') {
                    if ($totalMark >= 81 && $totalMark <= 100) {
                        return '30';
                    } else if ($totalMark >= 71 && $totalMark <= 80) {
                        return '25';
                    } else if ($totalMark >= 61 && $totalMark <= 70) {
                        return '20';
                    } else if ($totalMark >= 51 && $totalMark <= 60) {
                        return '15';
                    } else if ($totalMark >= 41 && $totalMark <= 50) {
                        return '10';
                    } else {
                        return '5';
                    }
                }
            } else {
                if ($exam_type == 'ASSIGNMENT_I' && $subject_code == '12' || $exam_type == 'ASSIGNMENT_II' && $subject_code == '12') {
                    if ($totalMark >= 26 && $totalMark <= 35) {
                        return '4';
                    } else if ($totalMark >= 36 && $totalMark <= 45) {
                        return '8';
                    } else if ($totalMark >= 46 && $totalMark <= 55) {
                        return '12';
                    } else if ($totalMark >= 56 && $totalMark <= 65) {
                        return '16';
                    } else if ($totalMark >= 66 && $totalMark <= 75) {
                        return '20';
                    } else {
                        return '25';
                    }
                } else if ($exam_type == 'ASSIGNMENT_I' || $exam_type == 'ASSIGNMENT_II') {
                    if ($totalMark >= 1 && $totalMark <= 28) {
                        return '4';
                    } else if ($totalMark >= 29 && $totalMark <= 35) {
                        return '8';
                    } else if ($totalMark >= 36 && $totalMark <= 42) {
                        return '12';
                    } else if ($totalMark >= 43 && $totalMark <= 49) {
                        return '16';
                    } else if ($totalMark >= 50 && $totalMark <= 56) {
                        return '19';
                    } else {
                        return '22';
                    }
                }
            }
        } else {
            return '';
        }
    }


    function getAssignmentExamTheoryTotalMark($student_id, $subject_code, $lab_status)
    {

        if ($subject_code == 12) {
            $labStatus = 'true';
        } else {
            $labStatus = $lab_status;
        }
        if ($labStatus == 'true') {
            if ($subject_code == 12) {
                $pass_mark_theory = 25;
            } else {
                $pass_mark_theory = 21;
            }
        } else {
            $pass_mark_theory = 35;
        }

        if ($student_id == '20P5965' || $student_id == '20P4140' || $student_id == '20P1754') {
            $internal_assessment = 1;
        } else {
            $internal_assessment = 5;
        }
        // $exam_type = array('ASSIGNMENT_I', 'ASSIGNMENT_II');
        // ,'INTERNAL_ASSESSMENT' I_UNIT_TEST
        $exam_type = array('I_UNIT_TEST');
        $total_mark = 0;
        foreach ($exam_type as $exam) {
            $stdMarkInfo = $this->student->getStudentFinalMarks($student_id, $subject_code, $exam);
            $sub_marks = 0;
            $mark_obt = 0;
            // if ($stdMarkInfo->exam_type == 'ASSIGNMENT_I' || $stdMarkInfo->exam_type == 'ASSIGNMENT_II') {
            //     if ($stdMarkInfo->obt_theory_mark == 'AB' || $stdMarkInfo->obt_theory_mark == 'EXEM' || $stdMarkInfo->obt_theory_mark == 'MP' || $stdMarkInfo->obt_theory_mark ==  'ASGN') {
            //         $mark_obt = 0;
            //     } else {
            //         $sub_marks = $this->getAssessmentMark($stdMarkInfo->obt_theory_mark, $stdMarkInfo->exam_type, $labStatus, $subject_code);
            //         $mark_obt = $sub_marks;
            //     }
            // } else {
                if ($stdMarkInfo->obt_theory_mark == 'AB' || $stdMarkInfo->obt_theory_mark == 'EXEM' || $stdMarkInfo->obt_theory_mark == 'MP' || $stdMarkInfo->obt_theory_mark ==  'ASGN') {
                    $mark_obt = 0;
                } else {
                    $mark_obt = $stdMarkInfo->obt_theory_mark;
                }
            // }
            // log_message('debug','bsch'.print_r($mark_obt,true));
            // log_message('debug','student_id '.$student_id);
            $total_mark += $mark_obt;
        }


        $totalMark = $total_mark + $pass_mark_theory + $internal_assessment;
        return $totalMark;
    }


    function getAssignmentExamLabTotalMark($student_id, $subject_code, $lab_status)
    {

        if ($subject_code == 12) {
            $labStatus = 'true';
        } else {
            $labStatus = $lab_status;
        }
        if ($labStatus == 'true') {
            if ($subject_code == 12) {
                $pass_mark_lab = 10;
                $lab_assessment = 10;
            } else {
                $pass_mark_lab = 14;
                $lab_assessment = 16;
            }
        } else {
            $pass_mark_lab = 0;
            $lab_assessment = 0;
        }

        $exam_type = array('LAB_ASSESSMENT');

        // foreach($exam_type as $exam){
        //     $stdMarkInfo = $this->student->getStudentFinalMarks($student_id,$subject_code,$exam);
        //     $sub_marks = 0;
        //     $mark_obt = 0;
        //     if($stdMarkInfo->exam_type == 'ASSIGNMENT_I' || $stdMarkInfo->exam_type == 'ASSIGNMENT_II'){
        //         if($stdMarkInfo->obt_theory_mark == 'AB' || $stdMarkInfo->obt_theory_mark == 'EXEM' || $stdMarkInfo->obt_theory_mark == 'MP' || $stdMarkInfo->obt_theory_mark ==  'ASGN'){
        //             $mark_obt = 0;
        //         }else{
        //             $sub_marks = $this->getAssessmentMark($stdMarkInfo->obt_theory_mark,$stdMarkInfo->exam_type,$labStatus,$subject_code);
        //             $mark_obt = $sub_marks;
        //         }
        //     }else{
        //         if($stdMarkInfo->obt_theory_mark == 'AB' || $stdMarkInfo->obt_theory_mark == 'EXEM' || $stdMarkInfo->obt_theory_mark == 'MP' || $stdMarkInfo->obt_theory_mark ==  'ASGN'){
        //             $mark_obt = 0;
        //         }else{
        //             $mark_obt = $stdMarkInfo->obt_theory_mark;
        //         }
        //     }
        //     $total_mark += $mark_obt;
        // }


        // $totalLabMark = $total_mark + $pass_mark_lab + $lab_assessment;
        $totalLabMark = $pass_mark_lab + $lab_assessment;
        return $totalLabMark;
    }


    public function shorlitedStudentPDF_PRINT()
    {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {

            $preference = $this->security->xss_clean($this->input->post('preference'));
            $this->global['pageTitle'] = '' . TAB_TITLE . ' :PDF';
            // $data['feeInfo'] = $this->fee->getStudentManagementFeeInfoById($row_id);
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mpdf', 'default_font' => 'timesnewroman', 'format' => [190, 236]]);
            $mpdf->AddPage('L', '', '', '', '', 2, 2, 2, 1, 8, 8);
            //$mpdf->AddPage('L','','','','',50,50,50,50,10,10);
            $info = $this->application->getAllShortlistedList_PDF($preference);
            $data['stdInfo'] = $info;
            $data['preference'] = $preference;
            $data_html = $this->load->view('application/printShortlistedPdf', $data, true);

            // $mpdf->WriteHTML('<columns column-count="3" vAlign="J" column-gap="2" />');
            $mpdf->WriteHTML($data_html);
            // $mpdf->WriteHTML($html_college_copy);
            // $mpdf->WriteHTML($html_bank_copy);
            $mpdf->Output($preference . '.pdf', 'I');
        }
    }


    


    //download Staff info
    public function downloadStaffExcelReport()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $filter = array();
            $staff_role = $this->security->xss_clean($this->input->post('staff_role'));
            $staff_department = $this->security->xss_clean($this->input->post('staff_department'));
            $fields = $this->security->xss_clean($this->input->post('fields'));

            if ($staff_department == 'ALL') {
                $filter['staff_department'] = "";
                $data['staff_department'] = 'ALL';
            } else {
                $filter['staff_department'] = $staff_department;
                $data['staff_department'] = $staff_department;
            }

            if ($staff_role == 'ALL') {
                $filter['staff_role'] = "";
                $data['staff_role'] = 'ALL';
                $stafRoleName = 'ALL';
            } else {
                $filter['staff_role'] = $staff_role;
                $data['staff_role'] = $staff_role;
                $role_name = $this->staff->getStaffRoleByName($filter);
                $stafRoleName = $role_name->role;
            }

            $date = date('Y');
            $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $total_fields = count($fields);
            $sheet = 0;

            // for($sheet = 0; $sheet < count($preferences);  $sheet++){
            $this->excel->setActiveSheetIndex($sheet);
            //name the worksheet
            // $this->excel->getActiveSheet()->setTitle($preferences[$sheet]);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:G500');
            //set Title content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', " STAFF INFORMATION");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A1:' . $cellName[$total_fields] . '1');
            $this->excel->getActiveSheet()->mergeCells('A2:' . $cellName[$total_fields] . '2');
            $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:' . $cellName[$total_fields] . '2')->getFont()->setBold(true);



            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);

            $excel_row = 3;
            $cell = 1;
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL No.');

            for ($h = 1; $h <= $total_fields; $h++) {
                $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellName[$h] . $excel_row, $fields[$h - 1]);
            }
            $this->excel->getActiveSheet()->getStyle('A3:' . $cellName[$total_fields] . '3')->getAlignment()->setWrapText(true);
            $this->excel->getActiveSheet()->getStyle('A3:' . $cellName[$total_fields] . '3')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A3:' . $cellName[$total_fields] . '3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:' . $cellName[$total_fields] . $total_fields)->applyFromArray($styleBorderArray);


            $staffs = $this->staff->getStaffInfoForReportDownload($filter);
            $j = 1;
            $excel_row = 4;

            foreach ($staffs as $stf) {
                if (empty($stf->dob) || $stf->dob == '0000-00-00' || $stf->dob == '1970-01-01') {
                    $dob = '';
                } else {
                    $dob = date("d-m-Y", strtotime($stf->dob));
                }

                if (empty($stf->doj) || $stf->doj == '0000-00-00' || $stf->doj == '1970-01-01') {
                    $doj = '';
                } else {
                    $doj = date("d-m-Y", strtotime($stf->doj));
                }


                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);

                for ($c = 1; $c <= $total_fields; $c++) {
                    if ($fields[$c - 1] == 'dob') {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellName[$c] . $excel_row, $dob);
                    } else if ($fields[$c - 1] == 'doj') {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellName[$c] . $excel_row, $doj);
                    } else {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($cellName[$c] . $excel_row, $stf->{$fields[$c - 1]});
                    }
                }

                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':' . $cellName[$total_fields] . $excel_row)->applyFromArray($styleBorderArray);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':' . $cellName[$total_fields] . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel_row++;
            }
            $this->excel->createSheet();
            // }
            $filename =  '_STAFF_Report_' . $stafRoleName . '-' . $date . '.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment; filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            $objWriter->save("php://output");
            setcookie('isDownLoaded', 1);
        }
    }


    //download mun external report
    public function downloadMunExternalReport()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $filter = array();
            $status = $this->security->xss_clean($this->input->post('status'));
            $register_type = $this->security->xss_clean($this->input->post('register_type'));

            $filter['status'] = $status;
            if ($register_type != 'ALL') {
                $filter['register_type'] = $register_type;
            } else {
                $filter['register_type'] = '';
            }


            $date = date('Y');
            $sheet = 0;

            // for($sheet = 0; $sheet < count($preferences);  $sheet++){
            $this->excel->setActiveSheetIndex($sheet);
            //name the worksheet
            // $this->excel->getActiveSheet()->setTitle($preferences[$sheet]);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:K500');
            //set Title content with some text
            $this->excel->getActiveSheet()->setTitle('MUN REGISRTATION');
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', "MUN EXTERNAL REGISTRATION");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A1:G1');
            $this->excel->getActiveSheet()->mergeCells('A2:G2');
            $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true);



            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);


            $excel_row = 3;
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'Register ID');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Date');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'College Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Type');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Mobile');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Email');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Total Students');

            // $this->excel->getActiveSheet()->getStyle('A3:'.$cellName[$total_fields].'3')->getAlignment()->setWrapText(true); 
            $this->excel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:G3')->applyFromArray($styleBorderArray);


            $eventInfo = $this->mun->getExternalMunRegistrationInfo($filter);
            $excel_row = 4;

            foreach ($eventInfo as $evt) {


                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $evt->event_register_id);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, date('d-m-Y', strtotime($evt->created_date_time)));
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $evt->college_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $evt->registeration_type);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $evt->mobile);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $evt->email);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $evt->total_students);

                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':' . 'G' . $excel_row)->applyFromArray($styleBorderArray);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('E' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('G' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel_row++;
            }
            $this->excel->createSheet();

            // $sheet = 1;
            // $this->excel->setActiveSheetIndex($sheet);

            // //set Title content with some text
            // $this->excel->getActiveSheet()->setTitle('MUN PARTICIPANTS');
            // $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            // $this->excel->getActiveSheet()->setCellValue('A2', "MUN EXTERNAL PARTICIPANT");
            // $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            // $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            // $this->excel->getActiveSheet()->mergeCells('A1:I1');
            // $this->excel->getActiveSheet()->mergeCells('A2:I2');
            // $this->excel->getActiveSheet()->getStyle('A1:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $this->excel->getActiveSheet()->getStyle('A1:I2')->getFont()->setBold(true);



            // $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            // $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
            // $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
            // $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
            // $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            // $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(32);
            // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
            // $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            // $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
            // // $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
            // // $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(24);
            // // $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(40);

            // $excel_row = 3;
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'Register ID');
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Name');
            // // $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'DOB');
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Class');
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Institution');
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Email');
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Whatsapp No.');
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Country');
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'Preferred Allotment');
            // $this->excel->setActiveSheetIndex($sheet)->setCellValue('I3', 'Achievements');

            // // $this->excel->getActiveSheet()->getStyle('A3:'.$cellName[$total_fields].'3')->getAlignment()->setWrapText(true); 
            // $this->excel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
            // $this->excel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            // $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            // $this->excel->getActiveSheet()->getStyle('A1:I3')->applyFromArray($styleBorderArray);


            // $eventInfo = $this->mun->getExternalMunRegistrationInfo($filter);
            // $excel_row = 4;

            // foreach ($eventInfo as $evt) {
            //     $participantInfo = $this->mun->getParticipantInfo($evt->event_register_id);
            //     foreach ($participantInfo as $part) {
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $part->registration_row_id);
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $part->student_name);
            //         // $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, date('d-m-Y', strtotime($part->dob)));
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $part->class);
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $part->institution_name);
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $part->email);
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $part->whatsapp_no);
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $part->country_name);
            //         // $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $part->city);
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $part->preferred_allotments);
            //         // $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $part->preferred_allotments_2);
            //         $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $part->past_mun_achievements);

            //         $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':' . 'I' . $excel_row)->applyFromArray($styleBorderArray);
            //         $this->excel->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //         $this->excel->getActiveSheet()->getStyle('C' . $excel_row . ':' . 'D' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //         $this->excel->getActiveSheet()->getStyle('F' . $excel_row . ':' . 'H' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //         $excel_row++;
            //     }
            // }
            // $this->excel->createSheet();
            $filename =  'MUN_EXTERNAL_' . $date . '.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment; filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            $objWriter->save("php://output");
            setcookie('isDownLoaded', 1);
        }
    }


    // DOWNLOAD mun internal report
    public function downloadMunInternalReport()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $filter = array();
            $status = $this->security->xss_clean($this->input->post('status'));

            $filter['status'] = $status;


            $date = date('Y');
            $sheet = 0;

            // for($sheet = 0; $sheet < count($preferences);  $sheet++){
            $this->excel->setActiveSheetIndex($sheet);
            //name the worksheet
            // $this->excel->getActiveSheet()->setTitle($preferences[$sheet]);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:H500');
            //set Title content with some text
            $this->excel->getActiveSheet()->setTitle('MUN');
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', "MUN INTERNAL REGISTRATION");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A1:H1');
            $this->excel->getActiveSheet()->mergeCells('A2:H2');
            $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true);



            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(12);

            $excel_row = 3;
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL NO');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Student ID');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Whatsapp No.');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Preferred Allotment');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Term');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Stream');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('H3', 'Section');

            // $this->excel->getActiveSheet()->getStyle('A3:'.$cellName[$total_fields].'3')->getAlignment()->setWrapText(true); 
            $this->excel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:H3')->applyFromArray($styleBorderArray);


            $eventInfo = $this->mun->downloadMunInternalReport($filter);
            $excel_row = 4;
            $sl_no = 1;

            foreach ($eventInfo as $evt) {


                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $sl_no++);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $evt->student_id);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $evt->student_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $evt->whatsapp_no);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $evt->committee);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $evt->term_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $evt->stream_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $evt->section_name);

                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':' . 'H' . $excel_row)->applyFromArray($styleBorderArray);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':H' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel_row++;
            }
            $this->excel->createSheet();

            $filename =  'MUN_INTERNAL_' . $date . '.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment; filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            $objWriter->save("php://output");
            setcookie('isDownLoaded', 1);
        }
    }




    public function downloadApplicationFeePaid()
    {
        if ($this->isAdmin() == TRUE) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $filter = array();
            $status = $this->security->xss_clean($this->input->post('status'));

            $filter['status'] = $status;


            $date = date('Y');
            $sheet = 0;

            // for($sheet = 0; $sheet < count($preferences);  $sheet++){
            $this->excel->setActiveSheetIndex($sheet);
            //name the worksheet
            // $this->excel->getActiveSheet()->setTitle($preferences[$sheet]);
            $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:H500');
            //set Title content with some text
            $this->excel->getActiveSheet()->setTitle('MUN');
            $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $this->excel->getActiveSheet()->setCellValue('A2', "MUN INTERNAL REGISTRATION");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $this->excel->getActiveSheet()->mergeCells('A1:H1');
            $this->excel->getActiveSheet()->mergeCells('A2:H2');
            $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true);



            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);

            $excel_row = 3;
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL NO');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Application No');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Student Name');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Stream');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Integrated Batch');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('F3', 'Board');
            $this->excel->setActiveSheetIndex($sheet)->setCellValue('G3', 'Fee');

            // $this->excel->getActiveSheet()->getStyle('A3:'.$cellName[$total_fields].'3')->getAlignment()->setWrapText(true); 
            $this->excel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
            $this->excel->getActiveSheet()->getStyle('A1:H3')->applyFromArray($styleBorderArray);


            $eventInfo = $this->mun->downloadMunInternalReport($filter);
            $excel_row = 4;
            $sl_no = 1;

            foreach ($eventInfo as $evt) {


                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $sl_no++);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $evt->student_id);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $evt->student_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $evt->whatsapp_no);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $evt->committee);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $evt->term_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row, $evt->stream_name);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $evt->section_name);

                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':' . 'H' . $excel_row)->applyFromArray($styleBorderArray);
                $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':H' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel_row++;
            }
            $this->excel->createSheet();

            $filename =  'MUN_INTERNAL_' . $date . '.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment; filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            ob_start();
            $objWriter->save("php://output");
            setcookie('isDownLoaded', 1);
        }
    }







     // DOWNLOAD Course internal report
     public function downloadCourseRegistrationReport()
     {
         if ($this->isAdmin() == TRUE) {
             setcookie('isDownLoaded', 1);
             $this->loadThis();
         } else {
             $filter = array();
             $status = $this->security->xss_clean($this->input->post('status'));
 
             $filter['status'] = $status;
 
 
             $date = date('Y');
             $sheet = 0;
 
             // for($sheet = 0; $sheet < count($preferences);  $sheet++){
             $this->excel->setActiveSheetIndex($sheet);
             //name the worksheet
             // $this->excel->getActiveSheet()->setTitle($preferences[$sheet]);
             $this->excel->getActiveSheet()->getPageSetup()->setPrintArea('A1:H500');
             //set Title content with some text
             $this->excel->getActiveSheet()->setTitle('COURSE');
             $this->excel->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
             $this->excel->getActiveSheet()->setCellValue('A2', "COURSE REGISTRATION");
             $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
             $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
             $this->excel->getActiveSheet()->mergeCells('A1:E1');
             $this->excel->getActiveSheet()->mergeCells('A2:E2');
             $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $this->excel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true);
 
 
 
             $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
             $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
             $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
             $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
             $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
           ;
 
             $excel_row = 3;
             $this->excel->setActiveSheetIndex($sheet)->setCellValue('A3', 'SL NO');
             $this->excel->setActiveSheetIndex($sheet)->setCellValue('B3', 'Student ID');
             $this->excel->setActiveSheetIndex($sheet)->setCellValue('C3', 'Name');
             $this->excel->setActiveSheetIndex($sheet)->setCellValue('D3', 'Course Name');
             $this->excel->setActiveSheetIndex($sheet)->setCellValue('E3', 'Amount');
        
 
             // $this->excel->getActiveSheet()->getStyle('A3:'.$cellName[$total_fields].'3')->getAlignment()->setWrapText(true); 
             $this->excel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
             $this->excel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 
 
             $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
             $this->excel->getActiveSheet()->getStyle('A1:E3')->applyFromArray($styleBorderArray);
 
 
             $courseInfo = $this->student->getAllCourseRegisterInfoForReport();
             $excel_row = 4;
             $sl_no = 1;
 
             foreach ($courseInfo as $course) {
 
 
                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $sl_no++);
                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('B' . $excel_row, $course->student_id);
                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('C' . $excel_row, $course->student_name);
                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row, $course->course_name);
                 $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row, $course->paid_amount);
          
 
                 $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':' . 'E' . $excel_row)->applyFromArray($styleBorderArray);
                 $this->excel->getActiveSheet()->getStyle('A' . $excel_row . ':B' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                 $this->excel->getActiveSheet()->getStyle('D' . $excel_row . ':E' . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                 $excel_row++;
             }
             $this->excel->createSheet();
 
             $filename =  'COURSE_REGISTRATION.xls'; //save our workbook as this file name
             header('Content-Type: application/vnd.ms-excel'); //mime type
             header('Content-Disposition: attachment; filename="' . $filename . '"'); //tell browser what's the file name
             header('Cache-Control: max-age=0'); //no cache
 
             //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
             //if you want to save it as .XLSX Excel 2007 format
             $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
             ob_start();
             $objWriter->save("php://output");
             setcookie('isDownLoaded', 1);
         }
     }













    //download fee structure format
    public function download_fee_structure_excel_2020()
    {
        if ($this->isAdmin() == true) {
            setcookie('isDownLoaded', 1);
            $this->loadThis();
        } else {
            $filter = array();
            $term_name = $this->security->xss_clean($this->input->post('term_name_select'));
            $spreadsheet = new Spreadsheet();
            $headerFontSize = [
                'font' => [
                    'size' => 16,
                    'bold' => true,
                ]
            ];
            $font_style_total = [
                'font' => [
                    'size' => 12,
                    'bold' => true,
                ]
            ];
            $filter['term_name'] = $term_name;
            //$streamInfo = $this->staff->getStaffSectionByTerm($filter);

            $spreadsheet->getProperties()
                ->setCreator("SJPUC")
                ->setLastModifiedBy($this->staff_id)
                ->setTitle("SJPUC Fee Info")
                ->setSubject("Fee Structure")
                ->setDescription(
                    "SJPUC"
                )
                ->setKeywords("SJPUC")
                ->setCategory("Fee");
            $i = 0;

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('FEE');
            $spreadsheet->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
            $spreadsheet->getActiveSheet()->mergeCells("A1:F1");
            $spreadsheet->getActiveSheet()->getStyle("A1:A1")->applyFromArray($headerFontSize);

            $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->setCellValue('A2', $term_name . " FEE REPORT");
            $spreadsheet->getActiveSheet()->mergeCells("A2:F2");
            $spreadsheet->getActiveSheet()->getStyle("A2:A2")->applyFromArray($headerFontSize);
            $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');

            $spreadsheet->getActiveSheet()->setCellValue('A3', 'SL No');
            $spreadsheet->getActiveSheet()->setCellValue('B3', 'Student ID');
            $spreadsheet->getActiveSheet()->setCellValue('C3', 'Application No');
            $spreadsheet->getActiveSheet()->setCellValue('D3', 'Name');
            $spreadsheet->getActiveSheet()->setCellValue('E3', 'Lang');
            $spreadsheet->getActiveSheet()->setCellValue('F3', 'Stream');
            $spreadsheet->getActiveSheet()->setCellValue('G3', 'SC/ST/CATI');
            $spreadsheet->getActiveSheet()->setCellValue('H3', 'Fee Payable');
            $spreadsheet->getActiveSheet()->setCellValue('I3', 'Fee Paid');
            $spreadsheet->getActiveSheet()->setCellValue('J3', 'Pending');
            $spreadsheet->getActiveSheet()->getStyle("A3:J3")->applyFromArray($font_style_total);
            $spreadsheet->getActiveSheet()->getStyle("A3:J3")->applyFromArray($font_style_total);
            $spreadsheet->getActiveSheet()->getStyle('C3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('D3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
            // $feeTypeInfo = $this->fee->getAllFeeTypesForByStatus(1);

            $spreadsheet->getActiveSheet()->getStyle('A3:E3')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => Fill::FILL_SOLID,
                        'color' => array('rgb' => 'E5E4E2')
                    ),
                    'font'  => array(
                        'bold'  =>  true
                    )
                )
            );


            $spreadsheet->getActiveSheet()->getStyle('A:C')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle('E:F')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle('H:J')->getAlignment()->setHorizontal('center');
            $excel_row = 4;
            $sl_number = 1;
            $total_sslc_state_fee = 0;
            $total_cbse_icse_fee = 0;
            $total_nri_fee = 0;
            $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(28);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(18);
            // foreach($feeTypeInfo as $type){
            if ($term_name == 'I PUC') {
                $studentInfo = $this->fee->getAllFeePendingAmount2020();
                $total_state_fee_by_type = 0;
                $total_cbse_fee_by_type = 0;
                $total_nri_fee_by_type = 0;
                foreach ($studentInfo as $std) {

                    $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row)->getFont()->setSize(14);
                    $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row,  $sl_number);
                    $spreadsheet->getActiveSheet()->setCellValue('B' . $excel_row,  $std->student_id);
                    $spreadsheet->getActiveSheet()->setCellValue('C' . $excel_row,  $std->application_no);
                    $spreadsheet->getActiveSheet()->setCellValue('D' . $excel_row,  $std->student_name);
                    $spreadsheet->getActiveSheet()->setCellValue('E' . $excel_row,  $std->elective_sub);
                    $spreadsheet->getActiveSheet()->setCellValue('F' . $excel_row,  $std->stream_name);
                    $spreadsheet->getActiveSheet()->setCellValue('G' . $excel_row,  $std->category);
                    $filter['fee_year'] = '2020';
                    $filter['term_name'] = 'II PUC';
                    $filter['stream_name'] = $std->stream_name;
                    if (strtoupper($std->elective_sub) == 'FRENCH') {
                        $filter['lang_fee_status'] = true;
                    } else {
                        $filter['lang_fee_status'] = false;
                    }
                    $feeYear = '2020';

                    $filter['category'] = strtoupper($std->category);
                    $total_fee = $this->fee->getTotalFeeAmount($filter);
                    // $total_fee_amount = $total_fee->total_fee;
                    $total_fee_amount = $std->total_fee;
                    $total_paid_amount = $this->fee->getSUM_Paid_FeeInfoByReceiptNum_2020($std->application_no);

                    $spreadsheet->getActiveSheet()->setCellValue('H' . $excel_row,  $total_fee_amount);
                    $spreadsheet->getActiveSheet()->setCellValue('I' . $excel_row,  $total_paid_amount->paid_amount);
                    $spreadsheet->getActiveSheet()->setCellValue('J' . $excel_row,  $total_fee_amount - $total_paid_amount->paid_amount);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setWrapText(true);

                    $sl_number++;
                    $excel_row++;
                }
            } else {
                $studentInfo = $this->fee->getAllFeePendingAmount2019();
                $total_state_fee_by_type = 0;
                $total_cbse_fee_by_type = 0;
                $total_nri_fee_by_type = 0;
                foreach ($studentInfo as $std) {

                    $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row)->getFont()->setSize(14);
                    $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row,  $sl_number);
                    $spreadsheet->getActiveSheet()->setCellValue('B' . $excel_row,  $std->student_id);
                    $spreadsheet->getActiveSheet()->setCellValue('C' . $excel_row,  $std->application_no);
                    $spreadsheet->getActiveSheet()->setCellValue('D' . $excel_row,  $std->student_name);
                    $spreadsheet->getActiveSheet()->setCellValue('E' . $excel_row,  $std->elective_sub);
                    $spreadsheet->getActiveSheet()->setCellValue('F' . $excel_row,  $std->stream_name);
                    $spreadsheet->getActiveSheet()->setCellValue('G' . $excel_row,  $std->category);
                    $filter['fee_year'] = '2020';
                    $filter['term_name'] = 'II PUC';
                    $filter['stream_name'] = $std->stream_name;
                    if (strtoupper($std->elective_sub) == 'FRENCH') {
                        $filter['lang_fee_status'] = true;
                    } else {
                        $filter['lang_fee_status'] = false;
                    }
                    $feeYear = '2019';
                    $filter['category'] = strtoupper($std->category);
                    $total_fee = $this->fee->getTotalFeeAmount($filter);
                    // $total_fee_amount = $total_fee->total_fee;
                    $total_fee_amount = $std->total_fee;
                    $total_paid_amount = $this->fee->getSUM_Paid_FeeInfoByReceiptNum_2020($std->application_no);

                    $spreadsheet->getActiveSheet()->setCellValue('H' . $excel_row,  $total_fee_amount);
                    $spreadsheet->getActiveSheet()->setCellValue('I' . $excel_row,  $total_paid_amount->paid_amount);
                    $spreadsheet->getActiveSheet()->setCellValue('J' . $excel_row,  $total_fee_amount - $total_paid_amount->paid_amount);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setWrapText(true);

                    $sl_number++;
                    $excel_row++;
                }
            }
            // $excel_row++;

            // //$sl_number++;
            // $excel_row++;
            // }
            // $excel_row++;
            // $spreadsheet->getActiveSheet()->setCellValue('A'.$excel_row,  "");
            // $spreadsheet->getActiveSheet()->setCellValue('B'.$excel_row,  'ALL TOTAL');
            // $spreadsheet->getActiveSheet()->setCellValue('C'.$excel_row,  $total_sslc_state_fee);
            // $spreadsheet->getActiveSheet()->setCellValue('D'.$excel_row,  $total_cbse_icse_fee);
            // $spreadsheet->getActiveSheet()->setCellValue('E'.$excel_row,  $total_nri_fee);
            $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row . ":E" . $excel_row)->applyFromArray($font_style_total);
            $spreadsheet->createSheet();
            $i++;
            // $spreadsheet->getActiveSheet()->getStyle('A1:F'.$excel_row)->applyFromArray($styleBorder);
            //getting optional fee info




            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="fee_paid_' . $feeYear . '_' . $term_name . '.xlsx"');
            header('Cache-Control: max-age=0');
            setcookie('isDownLoaded', 1);
            $writer->save("php://output");
        }
    }

    function getFullMarksOfStudent()
    {
        $j = 1;
        $type = $this->input->post("type");
        $stream_name = $this->input->post("streamName");
        // $section_name = $this->input->post("section_name");
        $term_name = 'I PUC';
        if ($stream_name == 'All') {
            $streamName = '';
        }else{
            $streamName =  $stream_name;
        }
        $first_cell = array("K", "N", "Q", "T");
        $middle_cell = array("L", "O", "R", "U");
        $last_cell = array("M", "P", "S", "V");
        $section = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q");
        if ($type == 'All') {
            for ($sheet = 0; $sheet < count($section); $sheet++) {
                $section_name = $section[$sheet];
                // log_message('debug', 'secton => ' .$section_name);
                $stream_name = $this->student->getStudentsStreamName($section_name, $term_name, $streamName);
                $subjects = $this->getSubjectCodes($stream_name->stream_name);
                $this->excel->setActiveSheetIndex($sheet);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle($section_name);
                //set Title content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', "ST.JOSEPH'S PRE-UNIVERSITY COLLEGE HASSAN");
                $this->excel->getActiveSheet()->setCellValue('A2', "I PUC  ANNUAL EXAMINATION RESULT 2021-22");
                $this->excel->getActiveSheet()->setCellValue('A3', "Abbreviation used in the table");
                $this->excel->getActiveSheet()->setCellValue('A4', "MO: Marks Obtained | IA: Internal Assessment | TH: Theory | PR: Practical | LT: Language Total | ST: Subjects Total | TM: Total Marks");
                $this->excel->getActiveSheet()->setCellValue('A5', "I PUC " . $section_name . " SECTION (" . $stream_name->Stream_Name . ")");
                //change the font size 
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
                $this->excel->getActiveSheet()->getStyle('A5:Y5')->getFont()->setSize(13);
                $this->excel->getActiveSheet()->getStyle('A1:A5')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->mergeCells('A1:Y1');
                $this->excel->getActiveSheet()->mergeCells('A2:Y2');
                $this->excel->getActiveSheet()->mergeCells('A3:Y3');
                $this->excel->getActiveSheet()->mergeCells('A4:Y4');
                $this->excel->getActiveSheet()->mergeCells('A5:Y5');
                $this->excel->getActiveSheet()->mergeCells('A6:A7');
                $this->excel->getActiveSheet()->mergeCells('C6:C7');
                $this->excel->getActiveSheet()->mergeCells('B6:B7');
                $this->excel->getActiveSheet()->mergeCells('D6:D7');
                $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //settting border style 
                $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                $this->excel->getActiveSheet()->getStyle('A1:Y120')->applyFromArray($styleArray);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A6', 'SL.no');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B6', 'Student ID');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C6', 'Name');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D6', 'Lang');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E6', 'Lng');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E7', 'Code');
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(11);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(38);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F6', 'Language');
                $this->excel->getActiveSheet()->mergeCells('F6:H6');
                $this->excel->getActiveSheet()->mergeCells('W6:W7');
                $this->excel->getActiveSheet()->mergeCells('X6:X7');
                $this->excel->getActiveSheet()->mergeCells('Y6:Y7');
                $this->excel->getActiveSheet()->getStyle('F6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F7', 'TH');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G7', 'IA');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H7', 'MO');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I6', 'English(02)');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I7', 'Marks');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J7', 'LT');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('W6', 'ST');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('X6', 'TM');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('Y6', 'Result');
                //$this->excel->getActiveSheet()->mergeCells('K2:M2');
                $excel_row = 7;
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(5);
                $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(5);
                $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(14);
                $this->excel->getActiveSheet()->getStyle('E1:E3')->getAlignment()->setWrapText(true);
                $this->excel->getActiveSheet()->getStyle('D6:Y120')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('H7:H999')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A6:Y7')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('J8:J150')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('W8:Y999')->getFont()->setBold(true);
                $this->cellColor('A6:Y7', 'D5DBDB');
                //first subject heading
                for ($i = 0; $i < 4; $i++) {
                    $subjectInfo = $this->subject->getSubjectsById($subjects[$i]);
                    $this->excel->getActiveSheet()->getColumnDimension($first_cell[$i])->setWidth(6);
                    $this->excel->getActiveSheet()->getColumnDimension($middle_cell[$i])->setWidth(6);
                    $this->excel->getActiveSheet()->getColumnDimension($last_cell[$i])->setWidth(6);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '6', $subjectInfo->name . '(' . $subjects[$i] . ')');
                    $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . '6:' . $last_cell[$i] . '6');
                    $this->excel->getActiveSheet()->getStyle($first_cell[$i] . '6:' . $last_cell[$i] . '6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    if ($subjectInfo->lab_status == "true") {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '7', 'TH');
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($middle_cell[$i] . '7', 'PR');
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($last_cell[$i] . '7', 'MO');
                    } else {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '7', "Marks");
                        $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . '7:' . $last_cell[$i] . '7');
                        $this->excel->getActiveSheet()->getStyle($first_cell[$i] . '7:' . $last_cell[$i] . '7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->excel->getActiveSheet()->getColumnDimension($first_cell[$i])->setWidth(5);
                        $this->excel->getActiveSheet()->getColumnDimension($middle_cell[$i])->setWidth(5);
                        $this->excel->getActiveSheet()->getColumnDimension($last_cell[$i])->setWidth(5);
                    }
                }
                $data['studentsResult'] = $this->student->getStudentsToAddMark($term_name, $section_name);
                $excel_row = 8;
                foreach ($data['studentsResult']  as $row) {
                    $total_marks_subjects = 0;
                    $total_marks_all_subjects = 0;
                    $fail_flag = false;
                    $subject_total = array();
                    $data['studentsMarks'] = $this->student->getFullMarksOfStudent($row->student_id);
                    $student_status = $row->tc_given_status;
                    if (!empty($data['studentsMarks']) && $student_status == 0) {
                        $first_language_total = 0;
                        $second_lang_mark = 0;
                        $first_lan_TH = 0;
                        $first_lan_IA = 0;
                        $subject_code_from_subjects = 0;
                        foreach ($data['studentsMarks']  as $mark) {
                            $subject_true = false;
                            if ($mark->subject_code == '01') {
                                $first_language_code = $mark->subject_code;
                                $first_language_name = "KAN";
                                $first_lan_TH =  $mark->obt_theory_mark;
                                $first_lan_IA =  $mark->obt_lab_mark;
                                $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                                if ($first_language_total < 35) {
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                }
                            } else if ($mark->subject_code == '03') {
                                $first_language_code = $mark->subject_code;
                                $first_language_name = "HINDI";
                                $first_lan_TH =  $mark->obt_theory_mark;
                                $first_lan_IA =  $mark->obt_lab_mark;
                                $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                                if ($first_language_total < 35) {
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                }
                            } else if ($mark->subject_code == '12') {
                                $first_language_code = $mark->subject_code;
                                $first_language_name = "FRENCH";
                                $first_lan_TH =  $mark->obt_theory_mark;
                                $first_lan_IA =  $mark->obt_lab_mark;
                                $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                                if ($first_lan_TH < 24) {
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                } else if ($first_language_total < 35) {
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                }
                            } else if ($mark->subject_code == '02') {
                                $second_lang_mark = $mark->obt_theory_mark;
                                if ($second_lang_mark < 35) {
                                    $this->cellColor('I' . $excel_row . ':J' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                }
                            } else {
                                $sub_theory_mark = 0;
                                $sub_lab_mark = 0;
                                for ($i = 0; $i < 4; $i++) {
                                    if ($mark->subject_code == $subjects[$i]) {
                                        if ($mark->lab_status == 'true') {
                                            $sub_theory_mark = (int)$mark->obt_theory_mark;
                                            $sub_lab_mark = (int)$mark->obt_lab_mark;
                                            $subject_total[$i] = $sub_theory_mark + $sub_lab_mark;
                                            if ($sub_theory_mark < 21) {
                                                $this->cellColor($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row, 'FFEE58');
                                                $fail_flag = true;
                                            } else if (($sub_theory_mark + $sub_lab_mark) < 35) {
                                                $this->cellColor($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row, 'FFEE58');
                                                $fail_flag = true;
                                            }
                                            $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . $excel_row, $sub_theory_mark);
                                            $this->excel->setActiveSheetIndex($sheet)->setCellValue($middle_cell[$i] . $excel_row, $sub_lab_mark);
                                            $this->excel->setActiveSheetIndex($sheet)->setCellValue($last_cell[$i] . $excel_row,  $sub_theory_mark + $sub_lab_mark);
                                        } else {
                                            $sub_theory_mark = (int)$mark->obt_theory_mark;
                                            $subject_total[$i] = $sub_theory_mark;
                                            if ($sub_theory_mark < 35) {
                                                $fail_flag = true;
                                                $this->cellColor($first_cell[$i] . $excel_row . ':' . $first_cell[$i] . $excel_row, 'FFEE58');
                                            }
                                            $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . $excel_row, $sub_theory_mark);
                                            $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row);
                                            $this->excel->getActiveSheet()->getStyle($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                        }
                                        $total_marks_subjects +=  $sub_theory_mark + $sub_lab_mark;
                                    }
                                }
                            }
                        }
                        if($first_language_total >= 35 && (int)$second_lang_mark >= 35){
                            if($total_marks_subjects >= 140){
                                if($subject_total[0] >= 30 && $subject_total[1] >= 30 && $subject_total[2] >= 30 && $subject_total[3] >= 30){
                                    $fail_flag = false;
                                }else{
                                    $fail_flag = true;
                                }
                                // if($first_language_total >= 35){
                                //     $fail_flag = false; 
                                // }else{
                                //     $fail_flag = true;
                                // }
        
                                // if($second_lang_mark >= 35){
                                //     $fail_flag = false; 
                                // }else{
                                //     $fail_flag = true;
                                // }
                                
                            }}


                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);
                        //student info
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->student_id);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->student_name);
                        //adding first Language
                        // $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row,  $first_language_name);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row,  $first_language_code);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $first_lan_TH);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row,  $first_lan_IA);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $first_language_total);
                        //second Language
                        $total_language_mark = $first_language_total + (int)$second_lang_mark;
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $second_lang_mark);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('J' . $excel_row, $total_language_mark);
                        $total_marks_all_subjects = $total_marks_subjects + (int)$first_language_total + (int)$second_lang_mark;
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('W' . $excel_row, $total_marks_subjects);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('X' . $excel_row, $total_marks_all_subjects);
                        if ($fail_flag == true) {
                            $this->excel->setActiveSheetIndex($sheet)->setCellValue('Y' . $excel_row, "Failed");
                        } else {
                            // $student_info = array(
                            //     'intake_year_id' => 2022,
                            //     'term_name' => 'II PUC'
                            // );
                            // $this->student->updateStudentInfoBStdId($student_info, $row->student_id);
                            $result = $this->calculateResult($total_marks_all_subjects);
                            $this->excel->setActiveSheetIndex($sheet)->setCellValue('Y' . $excel_row, $result);
                        }
                        $excel_row++;
                    }
                }
                $this->excel->createSheet();
            }
        } else {
            for ($sheet = 0; $sheet < count($section); $sheet++) {
                $section_name = $section[$sheet];
                // log_message('debug', 'secton => ' .$section_name);
                $stream_name = $this->student->getStudentsStreamName($section_name, $term_name, $streamName);
                $subjects = $this->getSubjectCodes($stream_name->stream_name);
                $this->excel->setActiveSheetIndex($sheet);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle($section_name);
                //set Title content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', "ST.JOSEPH'S PRE-UNIVERSITY COLLEGE HASSAN");
                $this->excel->getActiveSheet()->setCellValue('A2', "I PUC  ANNUAL EXAMINATION FAILED STUDENTS  RESULT 2021-22");
                $this->excel->getActiveSheet()->setCellValue('A3', "Abbreviation used in the table");
                $this->excel->getActiveSheet()->setCellValue('A4', "MO: Marks Obtained | IA: Internal Assessment | TH: Theory | PR: Practical | LT: Language Total | ST: Subjects Total | TM: Total Marks");
                $this->excel->getActiveSheet()->setCellValue('A5', "I PUC " . $section_name . " SECTION (" . $stream_name->Stream_Name . ")");
                //change the font size 
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
                $this->excel->getActiveSheet()->getStyle('A5:Y5')->getFont()->setSize(13);
                $this->excel->getActiveSheet()->getStyle('A1:A5')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('D5:Y5')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->mergeCells('A1:Y1');
                $this->excel->getActiveSheet()->mergeCells('A2:Y2');
                $this->excel->getActiveSheet()->mergeCells('A3:Y3');
                $this->excel->getActiveSheet()->mergeCells('A4:Y4');
                $this->excel->getActiveSheet()->mergeCells('A5:C5');
                $this->excel->getActiveSheet()->mergeCells('D5:Y5');
                $this->excel->getActiveSheet()->mergeCells('A6:A7');
                $this->excel->getActiveSheet()->mergeCells('C6:C7');
                $this->excel->getActiveSheet()->mergeCells('B6:B7');
                $this->excel->getActiveSheet()->mergeCells('D6:D7');
                $this->excel->getActiveSheet()->getStyle('A1:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->setCellValue('D5', "Color Abbreviation:- 1 Sub Failed - GREEN | 2 Sub Failed - BLUE | 3 Sub Failed - YELLOW | 4 or More Sub Failed - RED ");
                //settting border style 
                $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                $this->excel->getActiveSheet()->getStyle('A1:Y120')->applyFromArray($styleArray);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('A6', 'SL.no');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('B6', 'Student ID');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('C6', 'Name');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('D6', 'Lang');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E6', 'Lng');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('E7', 'Code');
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(11);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(38);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F6', 'Language');
                $this->excel->getActiveSheet()->mergeCells('F6:H6');
                $this->excel->getActiveSheet()->mergeCells('W6:W7');
                $this->excel->getActiveSheet()->mergeCells('X6:X7');
                $this->excel->getActiveSheet()->mergeCells('Y6:Y7');
                $this->excel->getActiveSheet()->getStyle('F6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('F7', 'TH');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('G7', 'IA');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('H7', 'MO');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I6', 'English(02)');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('I7', 'Marks');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('J7', 'LT');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('W6', 'ST');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('X6', 'TM');
                $this->excel->setActiveSheetIndex($sheet)->setCellValue('Y6', 'Result');
                //$this->excel->getActiveSheet()->mergeCells('K2:M2');
                $excel_row = 7;
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(5);
                $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(5);
                $this->excel->getActiveSheet()->getStyle('E1:E3')->getAlignment()->setWrapText(true);
                $this->excel->getActiveSheet()->getStyle('D6:Y120')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('H7:H999')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A6:Y7')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('J8:J150')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('W8:Y999')->getFont()->setBold(true);
                $this->cellColor('A6:Y7', 'D5DBDB');
                //first subject heading
                for ($i = 0; $i < 4; $i++) {
                    $subjectInfo = $this->subject->getSubjectsById($subjects[$i]);
                    $this->excel->getActiveSheet()->getColumnDimension($first_cell[$i])->setWidth(6);
                    $this->excel->getActiveSheet()->getColumnDimension($middle_cell[$i])->setWidth(6);
                    $this->excel->getActiveSheet()->getColumnDimension($last_cell[$i])->setWidth(6);
                    $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '6', $subjectInfo->name . '(' . $subjects[$i] . ')');
                    $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . '6:' . $last_cell[$i] . '6');
                    $this->excel->getActiveSheet()->getStyle($first_cell[$i] . '6:' . $last_cell[$i] . '6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    if ($subjectInfo->lab_status == "true") {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '7', 'TH');
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($middle_cell[$i] . '7', 'PR');
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($last_cell[$i] . '7', 'MO');
                    } else {
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . '7', "Marks");
                        $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . '7:' . $last_cell[$i] . '7');
                        $this->excel->getActiveSheet()->getStyle($first_cell[$i] . '7:' . $last_cell[$i] . '7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->excel->getActiveSheet()->getColumnDimension($first_cell[$i])->setWidth(5);
                        $this->excel->getActiveSheet()->getColumnDimension($middle_cell[$i])->setWidth(5);
                        $this->excel->getActiveSheet()->getColumnDimension($last_cell[$i])->setWidth(5);
                    }
                }
                $data['studentsResult'] = $this->student->getStudentsToAddMark($term_name, $section_name);
                $excel_row = 8;
                foreach ($data['studentsResult']  as $row) {
                    $total_marks_subjects = 0;
                    $total_marks_all_subjects = 0;
                    $fail_flag = false;
                    $first_language_code = "";
                    $first_language_name = "";
                    $data['studentsMarks'] = $this->student->getFullMarksOfStudent($row->student_id);
                    if (!empty($data['studentsMarks'])) {
                        $first_language_total = 0;
                        $second_lang_mark = 0;
                        $first_lan_TH = 0;
                        $first_lan_IA = 0;
                        $subject_code_from_subjects = 0;
                        $failed_subject_codes = array();
                        foreach ($data['studentsMarks']  as $mark) {
                            $subject_true = false;
                            if ($mark->subject_code == '01') {
                                $first_language_code = $mark->subject_code;
                                $first_language_name = "KAN";
                                $first_lan_TH =  $mark->obt_theory_mark;
                                $first_lan_IA =  $mark->obt_lab_mark;
                                $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                                if ($first_language_total < 35) {
                                    array_push($failed_subject_codes, $first_language_code);
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                }
                            } else if ($mark->subject_code == '03') {
                                $first_language_code = $mark->subject_code;
                                $first_language_name = "HINDI";
                                $first_lan_TH =  $mark->obt_theory_mark;
                                $first_lan_IA =  $mark->obt_lab_mark;
                                $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                                if ($first_language_total < 35) {
                                    array_push($failed_subject_codes, $first_language_code);
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                }
                            } else if ($mark->subject_code == '12') {
                                $first_language_code = $mark->subject_code;
                                $first_language_name = "FRENCH";
                                $first_lan_TH =  $mark->obt_theory_mark;
                                $first_lan_IA =  $mark->obt_lab_mark;
                                $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                                if ($first_lan_TH < 24) {
                                    array_push($failed_subject_codes, $first_language_code);
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                } else if ($first_language_total < 35) {
                                    array_push($failed_subject_codes, $first_language_code);
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                }
                            } else if ($mark->subject_code == '02') {
                                $second_lang_mark = $mark->obt_theory_mark;
                                if ($second_lang_mark < 35) {
                                    array_push($failed_subject_codes, $mark->subject_code);
                                    $this->cellColor('I' . $excel_row . ':J' . $excel_row, 'FFEE58');
                                    $fail_flag = true;
                                }
                            } else {
                                $sub_theory_mark = 0;
                                $sub_lab_mark = 0;
                                for ($i = 0; $i < 4; $i++) {
                                    if ($mark->subject_code == $subjects[$i]) {
                                        if ($mark->lab_status == 'true') {
                                            $sub_theory_mark = (int)$mark->obt_theory_mark;
                                            $sub_lab_mark = (int)$mark->obt_lab_mark;
                                            if ($sub_theory_mark < 21) {
                                                array_push($failed_subject_codes, $mark->subject_code);
                                                $this->cellColor($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row, 'FFEE58');
                                                $fail_flag = true;
                                            } else if (($sub_theory_mark + $sub_lab_mark) < 35) {
                                                array_push($failed_subject_codes, $mark->subject_code);
                                                $this->cellColor($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row, 'FFEE58');
                                                $fail_flag = true;
                                            }
                                        } else {
                                            $sub_theory_mark = (int)$mark->obt_theory_mark;
                                            if ($sub_theory_mark < 35) {
                                                array_push($failed_subject_codes, $mark->subject_code);
                                                $fail_flag = true;
                                                $this->cellColor($first_cell[$i] . $excel_row . ':' . $first_cell[$i] . $excel_row, 'FFEE58');
                                            }
                                        }
                                        $total_marks_subjects +=  $sub_theory_mark + $sub_lab_mark;
                                    }
                                }
                            }
                        }
                    }
                    if ($fail_flag == true) {
                        $data['studentsMarks'] = $this->student->getFullMarksOfStudent($row->student_id);
                        foreach ($data['studentsMarks']  as $mark) {
                            for ($i = 0; $i < 4; $i++) {
                                if ($mark->subject_code == $subjects[$i]) {
                                    $sub_theory_mark = (int)$mark->obt_theory_mark;
                                    $sub_lab_mark = (int)$mark->obt_lab_mark;
                                    if ($mark->lab_status == 'true') {
                                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . $excel_row, $sub_theory_mark);
                                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($middle_cell[$i] . $excel_row, $sub_lab_mark);
                                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($last_cell[$i] . $excel_row,  $sub_theory_mark + $sub_lab_mark);
                                    } else {
                                        $this->excel->setActiveSheetIndex($sheet)->setCellValue($first_cell[$i] . $excel_row, $sub_theory_mark);
                                        $this->excel->getActiveSheet()->mergeCells($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row);
                                        $this->excel->getActiveSheet()->getStyle($first_cell[$i] . $excel_row . ':' . $last_cell[$i] . $excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                    }
                                }
                            }
                        }
                        if (count($failed_subject_codes) >= 4) {
                            for ($i = 0; $i < count($failed_subject_codes); $i++) {
                                if (in_array('01', $failed_subject_codes) || in_array('03', $failed_subject_codes) || in_array('12', $failed_subject_codes)) {
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, 'E74C3C');
                                }
                                for ($j = 0; $j < 4; $j++) {
                                    if (in_array($subjects[$j], $failed_subject_codes)) {
                                        $this->cellColor($first_cell[$j] . $excel_row . ':' . $last_cell[$j] . $excel_row, 'E74C3C');
                                    }
                                }
                            }
                        } else if (count($failed_subject_codes) == 2) {
                            for ($i = 0; $i < count($failed_subject_codes); $i++) {
                                if (in_array('01', $failed_subject_codes) || in_array('03', $failed_subject_codes) || in_array('12', $failed_subject_codes)) {
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, '3498DB');
                                }
                                for ($j = 0; $j < 4; $j++) {
                                    if (in_array($subjects[$j], $failed_subject_codes)) {
                                        $this->cellColor($first_cell[$j] . $excel_row . ':' . $last_cell[$j] . $excel_row, '3498DB');
                                    }
                                }
                            }
                        } else if (count($failed_subject_codes) == 1) {
                            for ($i = 0; $i < count($failed_subject_codes); $i++) {
                                if (in_array('01', $failed_subject_codes) || in_array('03', $failed_subject_codes) || in_array('12', $failed_subject_codes)) {
                                    $this->cellColor('F' . $excel_row . ':H' . $excel_row, '28B463');
                                }
                                for ($j = 0; $j < 4; $j++) {
                                    if (in_array($subjects[$j], $failed_subject_codes)) {
                                        $this->cellColor($first_cell[$j] . $excel_row . ':' . $last_cell[$j] . $excel_row, '28B463');
                                    }
                                }
                            }
                        }
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('A' . $excel_row, $j++);
                        //student info
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->student_id);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->Name);
                        //adding first Language
                        // $first_language_total =  (int)$first_lan_TH + (int)$first_lan_IA;
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('D' . $excel_row,  $first_language_name);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('E' . $excel_row,  $first_language_code);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('F' . $excel_row, $first_lan_TH);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('G' . $excel_row,  $first_lan_IA);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('H' . $excel_row, $first_language_total);
                        //second Language
                        $total_language_mark = $first_language_total + (int)$second_lang_mark;
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('I' . $excel_row, $second_lang_mark);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('J' . $excel_row, $total_language_mark);
                        $total_marks_all_subjects = $total_marks_subjects + (int)$first_language_total + (int)$second_lang_mark;
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('W' . $excel_row, $total_marks_subjects);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('X' . $excel_row, $total_marks_all_subjects);
                        $this->excel->setActiveSheetIndex($sheet)->setCellValue('Y' . $excel_row, "Failed");
                        $excel_row++;
                    }
                }
                $this->excel->createSheet();
            }
        }
        $filename = 'just_some_random_name.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();
        $response =  array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );
        die(json_encode($response));
    }

    


public function downloadTransportFeeInfoReport()
{
    if ($this->isAdmin() == true) {
        setcookie('isDownLoaded', 1);
        $this->loadThis();
    } else {
        $filter = array();
        $term_name = $this->security->xss_clean($this->input->post('term_name'));
        $month = $this->security->xss_clean($this->input->post('month'));
        $year = CURRENT_YEAR;
        $spreadsheet = new Spreadsheet();
        $headerFontSize = [
            'font' => [
                'size' => 16,
                'bold' => true,
            ]
        ];
        $font_style_total = [
            'font' => [
                'size' => 12,
                'bold' => true,
            ]
        ];
        $filter['term_name'] = $term_name;
       // $filter['month'] = $month;

        $spreadsheet->getProperties()
            ->setCreator("SJPUC")
            ->setLastModifiedBy($this->staff_id)
            ->setTitle("SJPUC Fee Info")
            ->setSubject("Fee Structure")
            ->setDescription(
                "SJPUC"
            )
            ->setKeywords("SJPUC")
            ->setCategory("Fee");
        $i = 0;

        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('FEE');
        $spreadsheet->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
        $spreadsheet->getActiveSheet()->mergeCells("A1:J1");
        $spreadsheet->getActiveSheet()->getStyle("A1:A1")->applyFromArray($headerFontSize);

        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->setCellValue('A2', $term_name . " TRANSPORT FEE PAID REPORT -" . date('Y'));
        $spreadsheet->getActiveSheet()->mergeCells("A2:J2");
        $spreadsheet->getActiveSheet()->getStyle("A2:A2")->applyFromArray($headerFontSize);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->setCellValue('A3', 'SL No');
        $spreadsheet->getActiveSheet()->setCellValue('B3', 'Application No');
        $spreadsheet->getActiveSheet()->setCellValue('C3', 'Name');
        $spreadsheet->getActiveSheet()->setCellValue('D3', 'Stream');
        // $spreadsheet->getActiveSheet()->setCellValue('E3', 'Total Amt.');
        $spreadsheet->getActiveSheet()->setCellValue('E3', 'Paid Amt.');
        // $spreadsheet->getActiveSheet()->setCellValue('G3', 'Pending Amt.');
        $spreadsheet->getActiveSheet()->setCellValue('F3', 'Month');
        $spreadsheet->getActiveSheet()->setCellValue('G3', 'Route');
        $spreadsheet->getActiveSheet()->setCellValue('H3', 'Bus No.');
        
            
        $spreadsheet->getActiveSheet()->getStyle("A3:J3")->applyFromArray($font_style_total);
        $spreadsheet->getActiveSheet()->getStyle("A3:J3")->applyFromArray($font_style_total);
        $spreadsheet->getActiveSheet()->getStyle('C3')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('D3')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
        // $feeTypeInfo = $this->fee->getAllFeeTypesForByStatus(1);

        $spreadsheet->getActiveSheet()->getStyle('A3:E3')->applyFromArray(
            array(
                'fill' => array(
                    'type' => Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E5E4E2')
                ),
                'font'  => array(
                    'bold'  =>  true
                )
            )
        );

        $spreadsheet->getActiveSheet()->getStyle('A:B')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('D:K')->getAlignment()->setHorizontal('center');
        $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

        $this->excel->getActiveSheet()->getStyle('A1:K3')->applyFromArray($styleBorderArray);
        $excel_row = 4;
        $sl_number = 1;
        $total_sslc_state_fee = 0;
        $total_cbse_icse_fee = 0;
        $total_nri_fee = 0;
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
       
        $filter = array();
        $filter['term_name'] = $term_name;
        
        if($month == 'ALL'){
            $filter['month'] = '';
        }else{
            $filter['month'] = $month;
        }
        // foreach($feeTypeInfo as $type){
    
            $studentInfo = $this->student->getCurrentStudentInfoForTransReport($filter);
         
            if (!empty($studentInfo)) {
                foreach ($studentInfo as $std) {
                    $months = [
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December'
                    ];
                    $monthNumber = $std->month; 
                    $monthName = $months[$monthNumber];
                    $routeInfo = $this->transport->getTranportRateById($std->route_id);
                
               
                    $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row)->getFont()->setSize(14);
                    $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row,  $sl_number);
                    $spreadsheet->getActiveSheet()->setCellValue('B' . $excel_row,  $std->student_id);
                    $spreadsheet->getActiveSheet()->setCellValue('C' . $excel_row,  $std->student_name);
                    $spreadsheet->getActiveSheet()->setCellValue('D' . $excel_row,  $std->stream_name);
                    // $spreadsheet->getActiveSheet()->setCellValue('E' . $excel_row,  $std->total_amount);
                    $spreadsheet->getActiveSheet()->setCellValue('E' . $excel_row,  $std->amount);
                    // $spreadsheet->getActiveSheet()->setCellValue('G' . $excel_row,  $std->pending_balance);
                    $spreadsheet->getActiveSheet()->setCellValue('F' . $excel_row,  $monthName);
                    $spreadsheet->getActiveSheet()->setCellValue('G' . $excel_row,  $routeInfo->name);
                    $spreadsheet->getActiveSheet()->setCellValue('H' . $excel_row,  $routeInfo->bus_no);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setWrapText(true);

                    $sl_number++;
                    $excel_row++;
                  //  }
                }
            }
   
       
        $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row . ":E" . $excel_row)->applyFromArray($font_style_total);
        $spreadsheet->createSheet();
        $i++;
     

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Transport_fee_paid_' . $term_name . '.xlsx"');
        header('Cache-Control: max-age=0');
        setcookie('isDownLoaded', 1);
        $writer->save("php://output");
    }
}

public function downloadBulkFeeReport(){
     
    $filter = array();
       // $term = $this->security->xss_clean($this->input->post('term_name'));
        $date_from = $this->security->xss_clean($this->input->post('date_from'));
        $date_to = $this->security->xss_clean($this->input->post('date_to'));
        $payment_type = $this->security->xss_clean($this->input->post('payment_type'));
        $payment_year = $this->security->xss_clean($this->input->post('payment_year'));
        
        $filter['term_name'] = $term;
        $data['term_name'] = $term;
        if(!empty($date_from)){
        $filter['date_from'] = date('Y-m-d',strtotime($date_from));
        
        }
        if(!empty($date_to)){
        $filter['date_to'] = date('Y-m-d',strtotime($date_to));
        }
        $filter['payment_type'] = $payment_type;
        $filter['payment_year'] = $payment_year;
        $data['payment_type'] = $payment_type;

       
        $paidInfo = $this->fee->getFeeBulkReceipt($filter);
        $data['paidInfo'] = $paidInfo; 
       
        $data['feeModel'] = $this->fee;

            $this->global['pageTitle'] = ''.TAB_TITLE.' : Fee Receipt';
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf','default_font' => 'timesnewroman', 'format' => 'A4-L']);
            $mpdf->AddPage('L','','','','',7,7,7,7,8,8);
            if (!empty($paidInfo)) {
                
                foreach ($paidInfo as $studentInfo) {
                    $feeInfo = $this->fee->getFeeInfoByReceiptNum($studentInfo->row_id);
                    $data['feeInfo'] = $feeInfo;
                    $data['studentInfo'] = $studentInfo; 
                    $data['paid_amount'] = $studentInfo->paid_amount;
                    $data['previousFeePaidInfo'] = $this->fee->getPreviousFeePaidInfo($studentInfo->fee_row_id,$studentInfo->row_id, $studentInfo->term_name);
                    $data['paid_amount_words'] = $this->getIndianCurrency(floatval($data['paid_amount']));
                    $data['name_count'] = 0;
                    $html_student_copy = $this->load->view('fees/bulkFeeReceiptPrint',$data,true);
                    $data['name_count'] = 1;
                    $html_office_copy = $this->load->view('fees/bulkFeeReceiptPrint',$data,true);

                    $mpdf->WriteHTML('<columns column-count="2" vAlign="J" column-gap="10" />');
                    $mpdf->WriteHTML($html_student_copy);
                    $mpdf->WriteHTML($html_office_copy);
                }
            }
     
        $mpdf->Output('Fee_Receipt.pdf', 'I');   
}

public function downloadTransportBulkFeeReport(){
     
    $filter = array();
       // $term = $this->security->xss_clean($this->input->post('term_name'));
        $date_from = $this->security->xss_clean($this->input->post('date_from'));
        $date_to = $this->security->xss_clean($this->input->post('date_to'));
        $payment_type = $this->security->xss_clean($this->input->post('payment_type'));
        $payment_year = $this->security->xss_clean($this->input->post('payment_year'));
        
        $filter['term_name'] = $term;
        $data['term_name'] = $term;
        if(!empty($date_from)){
        $filter['date_from'] = date('Y-m-d',strtotime($date_from));
        
        }
        if(!empty($date_to)){
        $filter['date_to'] = date('Y-m-d',strtotime($date_to));
        }
        $filter['payment_type'] = $payment_type;
        $filter['payment_year'] = $payment_year;
        $data['payment_type'] = $payment_type;

       
        $TransportInfo = $this->transport->getTransportFeeBulkReceipt($filter);
       
        $data['TransportInfo'] = $TransportInfo; 
       
        $data['feeModel'] = $this->fee;

            $this->global['pageTitle'] = ''.TAB_TITLE.' : Fee Receipt';
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf','default_font' => 'timesnewroman', 'format' => 'A4-L']);
            $mpdf->AddPage('L','','','','',7,7,25,15,8,8);
            if (!empty($TransportInfo)) {
                
                foreach ($TransportInfo as $studentInfo) {
                  //  $feeInfo = $this->fee->getFeeInfoByReceiptNum($studentInfo->row_id);
                   // $data['feeInfo'] = $feeInfo;
                   $data['studentTransportInfo'] = $studentInfo; 
                   log_message('debug','transport'.print_r($studentInfo,true));
                    $data['transport_rate'] = $studentInfo->bus_fees;
                    $data['transport_rate_words'] = $this->getIndianCurrency(floatval($data['transport_rate']));
                    $data['name_count'] = 0;
                    $html_student_copy = $this->load->view('transport/bulkTransportReceiptPrint',$data,true);
                    $data['name_count'] = 1;
                    $html_office_copy = $this->load->view('transport/bulkTransportReceiptPrint',$data,true);
                    $data['name_count'] = 2;
                    $html_bus_copy = $this->load->view('transport/bulkTransportReceiptPrint',$data,true);

                    $mpdf->WriteHTML('<columns column-count="3" vAlign="J" column-gap="10" />');
                    $mpdf->WriteHTML($html_student_copy);
                    $mpdf->WriteHTML($html_office_copy);
                    $mpdf->WriteHTML($html_bus_copy);
                }
            }
     
        $mpdf->Output('Transport_Receipt.pdf', 'I');   
}

function getIndianCurrency(float $number) {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}


public function downloadTransportDueInfoReport()
{
    if ($this->isAdmin() == true) {
        setcookie('isDownLoaded', 1);
        $this->loadThis();
    } else {
        $filter = array();
        $term_name = $this->security->xss_clean($this->input->post('term_name_select'));
        $year = CURRENT_YEAR;
        $spreadsheet = new Spreadsheet();
        $headerFontSize = [
            'font' => [
                'size' => 16,
                'bold' => true,
            ]
        ];
        $font_style_total = [
            'font' => [
                'size' => 12,
                'bold' => true,
            ]
        ];
        $filter['term_name'] = $term_name;

        $spreadsheet->getProperties()
            ->setCreator("SJPUC")
            ->setLastModifiedBy($this->staff_id)
            ->setTitle("SJPUC Fee Info")
            ->setSubject("Fee Structure")
            ->setDescription(
                "SJPUC"
            )
            ->setKeywords("SJPUC")
            ->setCategory("Fee");
        $i = 0;

        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('FEE');
        $spreadsheet->getActiveSheet()->setCellValue('A1', EXCEL_TITLE);
        $spreadsheet->getActiveSheet()->mergeCells("A1:J1");
        $spreadsheet->getActiveSheet()->getStyle("A1:A1")->applyFromArray($headerFontSize);

        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->setCellValue('A2', $term_name . " TRANSPORT FEE DUE REPORT -" . date('Y'));
        $spreadsheet->getActiveSheet()->mergeCells("A2:J2");
        $spreadsheet->getActiveSheet()->getStyle("A2:A2")->applyFromArray($headerFontSize);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->setCellValue('A3', 'SL No');
        $spreadsheet->getActiveSheet()->setCellValue('B3', 'Application No');
        $spreadsheet->getActiveSheet()->setCellValue('C3', 'Name');
        $spreadsheet->getActiveSheet()->setCellValue('D3', 'Stream');
        $spreadsheet->getActiveSheet()->setCellValue('E3', 'Total Amt.');
        $spreadsheet->getActiveSheet()->setCellValue('F3', 'Paid Amt.');
        $spreadsheet->getActiveSheet()->setCellValue('G3', 'Pending Amt.');
        $spreadsheet->getActiveSheet()->setCellValue('H3', 'Route');
        $spreadsheet->getActiveSheet()->setCellValue('I3', 'Bus No.');
        
            
        $spreadsheet->getActiveSheet()->getStyle("A3:J3")->applyFromArray($font_style_total);
        $spreadsheet->getActiveSheet()->getStyle("A3:J3")->applyFromArray($font_style_total);
        $spreadsheet->getActiveSheet()->getStyle('C3')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('D3')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
        // $feeTypeInfo = $this->fee->getAllFeeTypesForByStatus(1);

        $spreadsheet->getActiveSheet()->getStyle('A3:E3')->applyFromArray(
            array(
                'fill' => array(
                    'type' => Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E5E4E2')
                ),
                'font'  => array(
                    'bold'  =>  true
                )
            )
        );


        $spreadsheet->getActiveSheet()->getStyle('A:B')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('D:K')->getAlignment()->setHorizontal('center');
        $styleBorderArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

        $this->excel->getActiveSheet()->getStyle('A1:K3')->applyFromArray($styleBorderArray);
        $excel_row = 4;
        $sl_number = 1;
        $total_sslc_state_fee = 0;
        $total_cbse_icse_fee = 0;
        $total_nri_fee = 0;
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
       
        $filter = array();
        $filter['term_name'] = $term_name;
        // foreach($feeTypeInfo as $type){
      
            $studentInfo = $this->student->getStudentInfoForTransReport($filter);

            if (!empty($studentInfo)) {
                foreach ($studentInfo as $std) {
                    $routeInfo = $this->transport->getTranportRateById($std->route_id);
                   
                    $total_fee = $routeInfo->rate;
                    $feePaidInfo = $this->transport->getTransportTotalPaidAmount($std->row_id,$year);

                    if(!empty($feePaidInfo->paid_amount)){
                        $paid_amt = $feePaidInfo->paid_amount;
                    }else{
                        $paid_amt = 0;
                    }
                    $pending_bal = $total_fee - $paid_amt;
                   // if($paid_amt == 0) {
                    $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row)->getFont()->setSize(14);
                    $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row,  $sl_number);
                    $spreadsheet->getActiveSheet()->setCellValue('B' . $excel_row,  $std->student_id);
                    $spreadsheet->getActiveSheet()->setCellValue('C' . $excel_row,  $std->student_name);
                    $spreadsheet->getActiveSheet()->setCellValue('D' . $excel_row,  $std->stream_name);
                    $spreadsheet->getActiveSheet()->setCellValue('E' . $excel_row,  $total_fee);
                    $spreadsheet->getActiveSheet()->setCellValue('F' . $excel_row,  $paid_amt);
                    $spreadsheet->getActiveSheet()->setCellValue('G' . $excel_row,  $pending_bal);
                    $spreadsheet->getActiveSheet()->setCellValue('H' . $excel_row,  $routeInfo->name);
                    $spreadsheet->getActiveSheet()->setCellValue('I' . $excel_row,  $routeInfo->bus_no);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setWrapText(true);

                    $sl_number++;
                    $excel_row++;
               // }
            }
            }
   
       
        $spreadsheet->getActiveSheet()->getStyle("A" . $excel_row . ":E" . $excel_row)->applyFromArray($font_style_total);
        $spreadsheet->createSheet();
        $i++;
     

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="transport_fee_due_' . $term_name . '.xlsx"');
        header('Cache-Control: max-age=0');
        setcookie('isDownLoaded', 1);
        $writer->save("php://output");
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


    function calculateResult($total_marks)
    {
        $percentage = floor(($total_marks / 600) * 100);
        if ($percentage >= 85) {
            return "Distinction";
        } else if ($percentage >= 60 && $percentage <= 84) {
            return "I Class";
        } else if ($percentage >= 50 && $percentage <= 59) {
            return "II Class";
        } else if ($percentage >= 35 && $percentage <= 49) {
            return "III Class";
        } else {
            return "Fail";
        }
    }

}
