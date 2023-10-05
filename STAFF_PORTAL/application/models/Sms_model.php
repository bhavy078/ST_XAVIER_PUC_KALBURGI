<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
    class SMS_model extends CI_Model
    {
        
   public function getStudentListForSMSByRowID($student_id){
        $this->db->select('std.row_id,std.admission_no,std.student_name,std.dob,std.application_no,std.term_name,
        std.father_mobile');
        $this->db->from('tbl_students_info as std');
        $this->db->where_in('std.row_id', $student_id);
        $this->db->where('std.is_deleted', 0);
        $this->db->where('std.is_active', 1);
        $query = $this->db->get();
        return $query->row();
        
    } 


    function addAbsentSMSInfo($absentInfo) {
        $this->db->trans_start();
        $this->db->insert('tbl_absent_sms_info', $absentInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    } 



    public function getSMSListReport($filter = '') {
        $this->db->select('log.enrollment_no,
            log.mobile_number as mobile,
            log.sms_count,
            log.sent_date, 
            log.message, 
            log.status');
        $this->db->from('tbl_student_bulk_sms_log as log');
        
        if (!empty($filter['date_from'])) {
            $this->db->where('log.sent_date >=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $this->db->where('log.sent_date <=', $filter['date_to']);
        }
        $this->db->where('log.enrollment_no', ' ');
        $this->db->order_by('log.sent_date','ASC');
        $query = $this->db->get();
        return $query->result();

    
    }

}
?>