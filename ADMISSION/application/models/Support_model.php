<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Support_model extends CI_Model
{
    public function addStudentContactInfo($messageInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_admission_contact_us', $messageInfo);
        $insert_id = $this->db->insert_id(); 
        $this->db->trans_complete();
        return $insert_id; 
    }

    function getGrievanceInfo($registered_row_id){
        $this->db->from('tbl_admission_contact_us as con');
        $this->db->where('con.registered_row_id', $registered_row_id);
        $this->db->where('con.is_deleted', 0);
        $query = $this->db->get();
        return $query->result();   
    }
}