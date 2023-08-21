<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transport_model extends CI_Model
{

    public function getAllBusInfo($filter=''){
        $this->db->select('bus.row_id, bus.vehicle_number, bus.insurance_expiry_date,  bus.emission_expiry_date, bus.permit_date, bus.route, bus.driver_name, bus.driver_mobile, bus.total_seat_capacity, bus.tax_expiry_date, bus.fitness_certificate_expiry_date');
        $this->db->from('tbl_bus_management_details as bus'); 
      
        if(!empty($filter['vehicle_number'])){
            $likeCriteria = "(bus.vehicle_number  LIKE '%" . $filter['vehicle_number'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['insurance_expiry_date'])){
            $this->db->where('bus.insurance_expiry_date', $filter['insurance_expiry_date']);
        }
         if(!empty($filter['emission_expiry_date'])){
            $this->db->where('bus.emission_expiry_date', $filter['emission_expiry_date']);
        }
        if(!empty($filter['permit_date'])){
            $this->db->where('bus.permit_date', $filter['permit_date']);
        }
          if(!empty($filter['tax_expiry_date'])){
            $this->db->where('bus.tax_expiry_date', $filter['tax_expiry_date']);
        }
          if(!empty($filter['fitness_certificate_expiry_date'])){
            $this->db->where('bus.fitness_certificate_expiry_date', $filter['fitness_certificate_expiry_date']);
        }
        if(!empty($filter['route'])){
            $likeCriteria = "(bus.route  LIKE '%" . $filter['route'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['driver_name'])){
            $likeCriteria = "(bus.driver_name  LIKE '%" . $filter['driver_name'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['driver_mobile'])){
            $likeCriteria = "(bus.driver_mobile  LIKE '%" . $filter['driver_mobile'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['total_seat_capacity'])){
            $likeCriteria = "(bus.total_seat_capacity  LIKE '%" . $filter['total_seat_capacity'] . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('bus.is_deleted', 0);
        $this->db->limit($filter['page'], $filter['segment']);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllBusCount($filter=''){
        $this->db->select('bus.row_id, bus.vehicle_number, bus.insurance_expiry_date,  bus.emission_expiry_date, bus.permit_date, bus.route, bus.driver_name, bus.driver_mobile, bus.total_seat_capacity, bus.tax_expiry_date, bus.fitness_certificate_expiry_date');
        $this->db->from('tbl_bus_management_details as bus'); 
      
        if(!empty($filter['vehicle_number'])){
            $likeCriteria = "(bus.vehicle_number  LIKE '%" . $filter['vehicle_number'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['insurance_expiry_date'])){
            $this->db->where('bus.insurance_expiry_date', $filter['insurance_expiry_date']);
        }
         if(!empty($filter['emission_expiry_date'])){
            $this->db->where('bus.emission_expiry_date', $filter['emission_expiry_date']);
        }
        if(!empty($filter['permit_date'])){
            $this->db->where('bus.permit_date', $filter['permit_date']);
        }
        if(!empty($filter['tax_expiry_date'])){
            $this->db->where('bus.tax_expiry_date', $filter['tax_expiry_date']);
        }
        if(!empty($filter['fitness_certificate_expiry_date'])){
            $this->db->where('bus.fitness_certificate_expiry_date', $filter['fitness_certificate_expiry_date']);
        }
        if(!empty($filter['route'])){
            $likeCriteria = "(bus.route  LIKE '%" . $filter['route'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['driver_name'])){
            $likeCriteria = "(bus.driver_name  LIKE '%" . $filter['driver_name'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['driver_mobile'])){
            $likeCriteria = "(bus.driver_mobile  LIKE '%" . $filter['driver_mobile'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['total_seat_capacity'])){
            $likeCriteria = "(bus.total_seat_capacity  LIKE '%" . $filter['total_seat_capacity'] . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('bus.is_deleted', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }

    
    public function getBusInfo($filter=''){
        $this->db->select('bus.row_id, bus.vehicle_number, bus.insurance_expiry_date,  bus.emission_expiry_date, bus.permit_date, bus.route, bus.driver_name, bus.driver_mobile, bus.total_seat_capacity, , bus.tax_expiry_date, bus.fitness_certificate_expiry_date');
        $this->db->from('tbl_bus_management_details as bus'); 
      
        if(!empty($filter['vehicle_number'])){
            $likeCriteria = "(bus.vehicle_number  LIKE '%" . $filter['vehicle_number'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['insurance_expiry_date'])){
            $this->db->where('bus.insurance_expiry_date', $filter['insurance_expiry_date']);
        }
         if(!empty($filter['emission_expiry_date'])){
            $this->db->where('bus.emission_expiry_date', $filter['emission_expiry_date']);
        }
        if(!empty($filter['permit_date'])){
            $this->db->where('bus.permit_date', $filter['permit_date']);
        }
        if(!empty($filter['tax_expiry_date'])){
            $this->db->where('bus.tax_expiry_date', $filter['tax_expiry_date']);
        }
        if(!empty($filter['fitness_certificate_expiry_date'])){
            $this->db->where('bus.fitness_certificate_expiry_date', $filter['fitness_certificate_expiry_date']);
        }
        if(!empty($filter['route'])){
            $likeCriteria = "(bus.route  LIKE '%" . $filter['route'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['driver_name'])){
            $likeCriteria = "(bus.driver_name  LIKE '%" . $filter['driver_name'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['driver_mobile'])){
            $likeCriteria = "(bus.driver_mobile  LIKE '%" . $filter['driver_mobile'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['total_seat_capacity'])){
            $likeCriteria = "(bus.total_seat_capacity  LIKE '%" . $filter['total_seat_capacity'] . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('bus.is_deleted', 0);
        $query = $this->db->get();
        return $query->result();
    }

    function addNewBus($busInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_bus_management_details', $busInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }
    function addNewTyre($tyreInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_bus_tyre_info', $tyreInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }
    function addNewSpare($spareInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_bus_spare_info', $spareInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function addNewService($serviceInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_bus_service_info', $serviceInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function addNewFuelInfo($fuelInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_bus_fuel_info', $fuelInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function addNewTrip($tripInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_bus_trip_info', $tripInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    public function getBusInfoById($row_id){
        $this->db->from('tbl_bus_management_details as bus');
        // $this->db->join('tbl_bus_tyre_information as tyre', 'tyre.bus_relation_row_id = bus.row_id','left');
        $this->db->where('bus.row_id', $row_id);
        $this->db->where('bus.is_deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }


    function updateBus($busInfo,$row_id){
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_bus_management_details', $busInfo);
        return TRUE;
    }

     function getAllTyreInfo($row_id){
        $this->db->select('tyre.row_id,tyre.purchase_date,tyre.comments,tyre.amount,tyre.kilometer_driven,tyre.tyre_type');
        $this->db->from('tbl_bus_tyre_info as tyre');
        $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = tyre.bus_relation_row_id','left'); 
        $this->db->where('tyre.bus_relation_row_id', $row_id);
        $this->db->order_by('tyre.purchase_date', 'DESC');
        $this->db->where('tyre.is_deleted', 0);
          $query = $this->db->get();
        return $query->result();    
    }

    function getAllSpareInfo($row_id)
    {
         $this->db->select('spare.row_id,spare.purchase_date,spare.comments,spare.amount,spare.kilometer_driven,spare.spare_name');
         $this->db->from('tbl_bus_spare_info as spare');
         $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = spare.bus_relation_row_id','left'); 
         $this->db->where('spare.bus_relation_row_id', $row_id);
         $this->db->order_by('spare.purchase_date', 'DESC');
         $this->db->where('spare.is_deleted', 0);
          $query = $this->db->get();
        return $query->result();    
    }

    function getAllServiceInfo($row_id)
    {
         $this->db->select('service.row_id,service.service_date,service.comments,service.amount,service.next_service_kilometer');
         $this->db->from('tbl_bus_service_info as service');
         $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = service.bus_relation_row_id','left'); 
         $this->db->where('service.bus_relation_row_id', $row_id);
         $this->db->order_by('service.service_date', 'DESC');
         $this->db->where('service.is_deleted', 0);
          $query = $this->db->get();
        return $query->result();    
    }

     function getAllFuelInfo($row_id)
    {
        $this->db->select('fuel.row_id,fuel.fuel_date,fuel.liter,fuel.amount,fuel.bill_number,fuel.fuel_kilometer,fuel.liter_per_rate');
        $this->db->from('tbl_bus_fuel_info as fuel');
        $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = fuel.bus_relation_row_id','left'); 
        $this->db->where('fuel.bus_relation_row_id', $row_id);
        $this->db->order_by('fuel.fuel_date', 'DESC');
        $this->db->where('fuel.is_deleted', 0);
          $query = $this->db->get();
        return $query->result();    
    }

     function getAllTripInfo($row_id){
        $this->db->select('trip.row_id,trip.trip_date,trip.start_meter,trip.end_meter');
        $this->db->from('tbl_bus_trip_info as trip');
        $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = trip.bus_relation_row_id','left'); 
        $this->db->where('trip.bus_relation_row_id', $row_id);
        $this->db->order_by('trip.trip_date', 'DESC');
        $this->db->where('trip.is_deleted', 0);
          $query = $this->db->get();
        return $query->result();    
    }

    function updateTyre($tyreInfo,$row_id){
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_bus_tyre_info', $tyreInfo);
        return TRUE;
    }

    function updateSpare($spareInfo,$row_id){
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_bus_spare_info', $spareInfo);
        return TRUE;
    }

    function updateService($serviceInfo,$row_id){
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_bus_service_info', $serviceInfo);
        return TRUE;
    }

    function updateFuel($fuelInfo,$row_id){
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_bus_fuel_info', $fuelInfo);
        return TRUE;
    }

     function updateTrip($tripInfo,$row_id){
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_bus_trip_info', $tripInfo);
        return TRUE;
    }


    //get transport info for download   
    function getTransportInfoForReportDownload($vehicleNumber){
        $this->db->select('bus.row_id, bus.vehicle_number, bus.insurance_expiry_date,  bus.emission_expiry_date, bus.permit_date, bus.route, bus.driver_name, bus.driver_mobile, bus.total_seat_capacity, , bus.tax_expiry_date, bus.fitness_certificate_expiry_date');
        $this->db->from('tbl_bus_management_details as bus'); 
        if(!empty($vehicleNumber)){
            $this->db->where('bus.vehicle_number', $vehicleNumber);
        }
        $this->db->where('bus.is_deleted', 0);
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    } 

    function getServiceInfoForReportDownload($from_date,$to_date)
    {
        $this->db->select('service.row_id,service.service_date,service.comments,service.amount,service.next_service_kilometer,bus.vehicle_number');
        $this->db->from('tbl_bus_service_info as service');
        $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = service.bus_relation_row_id','left'); 
        if(!empty($from_date) && !empty($to_date)){
            $this->db->where('service.service_date >=', date('Y-m-d',strtotime($from_date)));
            $this->db->where('service.service_date <=', date('Y-m-d',strtotime($to_date)));
        }elseif (!empty($from_date)) {
            $this->db->where('service.service_date >=', date('Y-m-d',strtotime($from_date)));
        }elseif (!empty($to_date)) {
            $this->db->where('service.service_date <=', date('Y-m-d',strtotime($to_date)));
        }
        $this->db->where('bus.is_deleted', 0);
        $this->db->where('service.is_deleted', 0);
        $this->db->order_by('service.service_date', 'ASC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    } 
    function getTyreInfoForReportDownload($from_date,$to_date)
    {
        $this->db->select('tyre.row_id,tyre.purchase_date,tyre.comments,tyre.amount,tyre.kilometer_driven,bus.vehicle_number,tyre.tyre_type');
            $this->db->from('tbl_bus_tyre_info as tyre');
            $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = tyre.bus_relation_row_id','left');  
         if(!empty($from_date) && !empty($to_date)){
            $this->db->where('tyre.purchase_date >=', date('Y-m-d',strtotime($from_date)));
            $this->db->where('tyre.purchase_date <=', date('Y-m-d',strtotime($to_date)));
         }elseif (!empty($from_date)) {
            $this->db->where('tyre.purchase_date >=', date('Y-m-d',strtotime($from_date)));
        }elseif (!empty($to_date)) {
            $this->db->where('tyre.purchase_date <=', date('Y-m-d',strtotime($to_date)));
        }
        $this->db->where('bus.is_deleted', 0);
        $this->db->where('tyre.is_deleted', 0);
        $this->db->order_by('tyre.purchase_date', 'ASC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    } 
    function getSpareInfoForReportDownload($from_date,$to_date)
    {
        $this->db->select('spare.row_id,spare.purchase_date,spare.comments,spare.amount,spare.kilometer_driven,spare.spare_name,bus.vehicle_number');
            $this->db->from('tbl_bus_spare_info as spare');
            $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = spare.bus_relation_row_id','left'); 
       if(!empty($from_date) && !empty($to_date)){
            $this->db->where('spare.purchase_date >=', date('Y-m-d',strtotime($from_date)));
            $this->db->where('spare.purchase_date <=', date('Y-m-d',strtotime($to_date)));
        }elseif (!empty($from_date)) {
            $this->db->where('spare.purchase_date >=', date('Y-m-d',strtotime($from_date)));
        }elseif (!empty($to_date)) {
            $this->db->where('spare.purchase_date <=', date('Y-m-d',strtotime($to_date)));
        }
        $this->db->where('bus.is_deleted', 0);
        $this->db->where('spare.is_deleted', 0);
        $this->db->order_by('spare.purchase_date', 'ASC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    } 

     function getFuelInfoForReportDownload($from_date,$to_date)
    {
        $this->db->select('fuel.row_id,fuel.fuel_date,fuel.bill_number,fuel.amount,fuel.liter,bus.vehicle_number,fuel.fuel_kilometer,fuel.liter_per_rate');
            $this->db->from('tbl_bus_fuel_info as fuel');
            $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = fuel.bus_relation_row_id','left'); 
       if(!empty($from_date) && !empty($to_date)){
            $this->db->where('fuel.fuel_date >=', date('Y-m-d',strtotime($from_date)));
            $this->db->where('fuel.fuel_date <=', date('Y-m-d',strtotime($to_date)));
        }elseif (!empty($from_date)) {
            $this->db->where('fuel.fuel_date >=', date('Y-m-d',strtotime($from_date)));
        }elseif (!empty($to_date)) {
            $this->db->where('fuel.fuel_date <=', date('Y-m-d',strtotime($to_date)));
        }
        $this->db->where('bus.is_deleted', 0);
        $this->db->where('fuel.is_deleted', 0);
        $this->db->order_by('fuel.fuel_date', 'ASC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }

     function getTripInfoForReportDownload($from_date,$to_date)
    {
        $this->db->select('trip.row_id,trip.trip_date,trip.start_meter,trip.end_meter,bus.vehicle_number');
        $this->db->from('tbl_bus_trip_info as trip');
        $this->db->join('tbl_bus_management_details as bus', 'bus.row_id = trip.bus_relation_row_id','left');  
         if(!empty($from_date) && !empty($to_date)){
            $this->db->where('trip.trip_date >=', date('Y-m-d',strtotime($from_date)));
            $this->db->where('trip.trip_date <=', date('Y-m-d',strtotime($to_date)));
         }elseif (!empty($from_date)) {
            $this->db->where('trip.trip_date >=', date('Y-m-d',strtotime($from_date)));
        }elseif (!empty($to_date)) {
            $this->db->where('trip.trip_date <=', date('Y-m-d',strtotime($to_date)));
        }
        $this->db->where('bus.is_deleted', 0);
        $this->db->where('trip.is_deleted', 0);
        $this->db->order_by('trip.trip_date', 'ASC');
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    } 

    //student Bus
    public function getAllStudentTransportInfo($filter, $page, $segment){
        $this->db->select('studentBus.row_id, studentBus.receipt_no, studentBus.bus_number, studentBus.payment_date, 
        studentBus.bus_fees, studentBus.route_from, studentBus.route_to, studentBus.from_date,studentBus.payment_type,
        studentBus.to_date,student.sat_number,student.student_name,transName.name as route_name,transName.rate,student.student_id');
        $this->db->from('tbl_student_bus_management_details as studentBus'); 
        // $this->db->join('tbl_bus_management_details as bus', 'bus.vehicle_number = studentBus.bus_number','left');
        $this->db->join('tbl_students_info as student', 'student.row_id = studentBus.student_id','left');
        $this->db->join('tbl_student_transport_rate_info as transName','transName.row_id = student.route_id','left');
        
        if(!empty($filter['receipt_no'])){
            $likeCriteria = "(studentBus.receipt_no  LIKE '%" . $filter['receipt_no'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['student_id'])){
            $likeCriteria = "(student.student_id  LIKE '%" . $filter['student_id'] . "%')";
            $this->db->where($likeCriteria);
        }
        // if(!empty($filter['bus_number'])) {
        //     $this->db->where_in('bus.vehicle_number',$filter['bus_number']);
        // }
        if(!empty($filter['route_from'])){
            $likeCriteria = "(transName.name  LIKE '%" . $filter['route_from'] . "%')";
            $this->db->where($likeCriteria);
        }
        // if(!empty($filter['route_to'])){
        //     $likeCriteria = "(studentBus.route_to  LIKE '%" . $filter['route_to'] . "%')";
        //     $this->db->where($likeCriteria);
        // }
        // if(!empty($filter['from_date'])){
        //     $this->db->where('studentBus.from_date', $filter['from_date']);
        // }
         if(!empty($filter['payment_type'])){
            $this->db->where('studentBus.payment_type', $filter['payment_type']);
        }
        if(!empty($filter['bus_fees'])){
            $likeCriteria = "(studentBus.bus_fees  LIKE '%" . $filter['bus_fees'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['by_name'])){
            $likeCriteria = "(student.student_name  LIKE '%" . $filter['by_name'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['payment_date'])){
            $this->db->where('studentBus.payment_date', $filter['payment_date']);
        }

        // $this->db->where('bus.is_deleted', 0);
        $this->db->where('studentBus.is_deleted', 0);
        $this->db->order_by('studentBus.created_date_time', 'DESC');
        $this->db->limit($filter['page'], $filter['segment']);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllStudentTransportCount($filter=''){
        $this->db->from('tbl_student_bus_management_details as studentBus'); 
        // $this->db->join('tbl_bus_management_details as bus', 'bus.vehicle_number = studentBus.bus_number','left');
        $this->db->join('tbl_students_info as student', 'student.row_id = studentBus.student_id','left');
       
        $this->db->join('tbl_student_transport_rate_info as transName','transName.row_id = student.route_id','left');

        if(!empty($filter['receipt_no'])){
            $likeCriteria = "(studentBus.receipt_no  LIKE '%" . $filter['receipt_no'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['student_id'])){
            $likeCriteria = "(student.student_id  LIKE '%" . $filter['student_id'] . "%')";
            $this->db->where($likeCriteria);
        }
        // if(!empty($filter['bus_number'])) {
        //     $this->db->where_in('studentBus.bus_number',$filter['bus_number']);
        // }
         if(!empty($filter['route_from'])){
            $likeCriteria = "(transName.name  LIKE '%" . $filter['route_from'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['payment_type'])){
            $this->db->where('studentBus.payment_type', $filter['payment_type']);
        }
        // if(!empty($filter['route_to'])){
        //     $likeCriteria = "(studentBus.route_to  LIKE '%" . $filter['route_to'] . "%')";
        //     $this->db->where($likeCriteria);
        // }
        // if(!empty($filter['from_date'])){
        //     $this->db->where('studentBus.from_date', $filter['from_date']);
        // }
        //  if(!empty($filter['to_date'])){
        //     $this->db->where('studentBus.to_date', $filter['to_date']);
        // }
        if(!empty($filter['bus_fees'])){
            $likeCriteria = "(studentBus.bus_fees  LIKE '%" . $filter['bus_fees'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['by_name'])){
            $likeCriteria = "(student.student_name  LIKE '%" . $filter['by_name'] . "%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($filter['payment_date'])){
            $this->db->where('studentBus.payment_date', $filter['payment_date']);
        }
        // $this->db->where('bus.is_deleted', 0);
        $this->db->where('studentBus.is_deleted', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function addNewStudentTransport($studentTransportInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_student_bus_management_details', $studentTransportInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    public function getStudentTransportInfoById($row_id) {
        $this->db->select('stdbus.row_id, stdbus.bus_number, stdbus.receipt_no,stdbus.payment_date,  stdbus.bus_fees, stdbus.route_from,student.admission_no,stdbus.upi_ref_no,stdbus.transaction_number,stdbus.dd_number,student.register_no,student.application_no,
        stdbus.route_to, stdbus.from_date, stdbus.to_date, bus.vehicle_number,student.sat_number,student.student_name,stdbus.intake_year,stdbus.payment_type,stdbus.payment_status,student.term_name,stdbus.created_date_time');
        $this->db->from('tbl_student_bus_management_details as stdbus');
        $this->db->join('tbl_bus_management_details as bus', 'bus.vehicle_number = stdbus.bus_number','left');
        $this->db->join('tbl_students_info as student', 'student.row_id = stdbus.student_id','left');
        $this->db->where('stdbus.row_id', $row_id);
        $this->db->where('stdbus.is_deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }

    function updateStudentTransportInfo($studentTransportInfo,$row_id){
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_student_bus_management_details', $studentTransportInfo);
        return TRUE;
    }

    function getVehicleNumber(){
        $this->db->from('tbl_bus_management_details as bus');
        $this->db->where('bus.is_deleted', 0);
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }

    function getStudentId(){

        $this->db->from('tbl_students_info as std');
        $this->db->where('std.is_active', 1);
        $this->db->where('std.is_deleted', 0);
        $query = $this->db->get();
        $result = $query->result();        
        return $result;
    }

    // Transport type
    public function getTransportNameInfo(){
        $this->db->from('tbl_student_transport_rate_info as trans');
        $this->db->where('trans.is_deleted', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function getSelectedTransportNameInfo($filter){
        $this->db->from('tbl_student_transport_rate_info as trans');
        $this->db->where('trans.is_deleted', 0);
        if(!empty($filter['name'])){
            $this->db->where('trans.name',$filter['name']);
        }
        $query = $this->db->get();
        return $query->result();
    }

     function addTransportName($transportInfo){
        $this->db->trans_start();
        $this->db->insert('tbl_student_transport_rate_info', $transportInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

     function deleteTransportName($transportInfo,$row_id){
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_student_transport_rate_info', $transportInfo);
        return TRUE;
    }

    public function getTransportRateInfoById($row_id){
        $this->db->from('tbl_student_transport_rate_info');
        $this->db->where('row_id', $row_id);
        $this->db->where('is_deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }

    public function addTransportChequeInfo($checkInfo) {
        $this->db->trans_start();
        $this->db->insert('tbl_student_transport_cheque_details', $checkInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    
    public function addTransportNeftInfo($neftInfo) {
        $this->db->trans_start();
        $this->db->insert('tb_student_transport_neft_details', $neftInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    public function addChallanInfo($neftInfo) {
        $this->db->trans_start();
        $this->db->insert('tb_student_transport_challan_details', $neftInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }
    
    function checkTransportChequeInfo($std_bus_row_id){
        $this->db->from('tbl_student_transport_cheque_details as fee');
        $this->db->where('fee.std_bus_row_id', $std_bus_row_id);
        $this->db->where('fee.is_deleted', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function checkTransportneftInfo($std_bus_row_id){
        $this->db->from('tb_student_transport_neft_details as fee');
        $this->db->where('fee.std_bus_row_id', $std_bus_row_id);
        $this->db->where('fee.is_deleted', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }

      function checkTransportCardInfo($std_bus_row_id){
        $this->db->from('tbl_transport_card_payment as fee');
        $this->db->where('fee.std_bus_row_id', $std_bus_row_id);
        $this->db->where('fee.is_deleted', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }

    
    function updateTransportChequeInfo($checkInfo,$std_bus_row_id){
        $this->db->where('std_bus_row_id', $std_bus_row_id);
        $this->db->update('tbl_student_transport_cheque_details', $checkInfo);
        return TRUE;
    }
    
    function updateTransportNeftInfo($neftInfo,$std_bus_row_id){
        $this->db->where('std_bus_row_id', $std_bus_row_id);
        $this->db->update('tb_student_transport_neft_details', $neftInfo);
        return TRUE;
    }

    function getTransportChequeInfo($std_bus_row_id){
        $this->db->from('tbl_student_transport_cheque_details as fee');
        $this->db->where('fee.std_bus_row_id', $std_bus_row_id);
        $this->db->where('fee.is_deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }
    function getTransportneftInfo($std_bus_row_id){
        $this->db->from('tb_student_transport_neft_details as fee');
        $this->db->where('fee.std_bus_row_id', $std_bus_row_id);
        $this->db->where('fee.is_deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }
    function getTransportCardInfo($std_bus_row_id){
        $this->db->from('tbl_transport_card_payment as fee');
        $this->db->where('fee.std_bus_row_id', $std_bus_row_id);
        $this->db->where('fee.is_deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }

     public function addTransportCardInfo($cardInfo) {
        $this->db->trans_start();
        $this->db->insert('tbl_transport_card_payment', $cardInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function updateTransportCardInfo($cardInfo,$std_bus_row_id){
        $this->db->where('std_bus_row_id', $std_bus_row_id);
        $this->db->update('tbl_transport_card_payment', $cardInfo);
        return TRUE;
    }

    function getTotalKmToday(){
        $this->db->select('SUM(trip.end_meter-trip.start_meter)as km');
        $this->db->from('tbl_bus_trip_info as trip');
        $this->db->where('trip.trip_date',date('Y-m-d'));
        $this->db->where('trip.is_deleted',0);
        $query = $this->db->get();
        return $query->row()->km;
    }

    function getTotalFuelCostToday(){
        $this->db->select('SUM(fuel.amount)as amt,SUM(fuel.liter_per_rate) as liter');
        $this->db->from('tbl_bus_fuel_info as fuel');
        $this->db->where('fuel.fuel_date',date('Y-m-d'));
        $this->db->where('fuel.is_deleted',0);
        $query = $this->db->get();
        return $query->row();
    }

    function getTotalBusFeePaidToday(){
        $this->db->select('SUM(rt.rate)as amt');
        $this->db->from('tbl_student_bus_management_details as bus');
        $this->db->join('tbl_student_transport_rate_info as rt','rt.row_id=bus.bus_fees');
        $this->db->like('bus.created_date_time',date('Y-m-d'),'after');
        $this->db->where('bus.is_deleted',0);
        $query = $this->db->get();
        return $query->row()->amt;
    } 

    function getTotalBusFeePaidThisMonth(){
        $this->db->select('SUM(rt.rate)as amt');
        $this->db->from('tbl_student_bus_management_details as bus');
        $this->db->join('tbl_student_transport_rate_info as rt','rt.row_id=bus.bus_fees');
        $this->db->like('bus.created_date_time',date('m'));
        $this->db->where('bus.is_deleted',0);
        $query = $this->db->get();
        return $query->row()->amt;
    } 

    function getTotalBusFeePaidLastMonth(){
        $this->db->select('SUM(rt.rate)as amt');
        $this->db->from('tbl_student_bus_management_details as bus');
        $this->db->join('tbl_student_transport_rate_info as rt','rt.row_id=bus.bus_fees');
        $this->db->like('bus.created_date_time',date('m-1'));
        $this->db->where('bus.is_deleted',0);
        $query = $this->db->get();
        return $query->row()->amt;
    }

    function getChallanInfo($std_bus_row_id){
        $this->db->from('tb_student_transport_challan_details as fee');
        $this->db->where('fee.std_bus_row_id', $std_bus_row_id);
        $this->db->where('fee.is_deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }

    function updateTransportChallanInfo($challanInfo,$std_bus_row_id){
        $this->db->where('std_bus_row_id', $std_bus_row_id);
        $this->db->update('tb_student_transport_challan_details', $challanInfo);
        return TRUE;
    }

    public function getTransportFeePaidReport($filter=''){
        $this->db->select('student.sat_number,student.student_name, academic.student_id,student.student_name,student.admission_status,
        academic.term_name,academic.section_name,trans.row_id, trans.student_id, trans.bus_number, trans.payment_date, 
        trans.bus_fees, trans.route_from, trans.route_to, trans.from_date,trans.to_date,challan.std_bus_row_id,challan.challan_number,
        challan.challan_date,challan.challan_bank');
        $this->db->from('tbl_student_bus_management_details as trans');
        $this->db->join('tbl_students_info as student', 'student.sat_number = trans.student_id','left'); 
       
        $this->db->join('tb_student_transport_challan_details as challan', 'challan.std_bus_row_id = trans.row_id','left');
        if(!empty($filter['date_from'])){
            $this->db->where('trans.payment_date>=', date('Y-m-d',strtotime($filter['date_from'])));
        }
        if(!empty($filter['date_to'])){
            $this->db->where('trans.payment_date<=', date('Y-m-d',strtotime($filter['date_to'])));
        }
        if(!empty($filter['bank'])){
            $this->db->where('challan.is_deleted', 0);
            $this->db->where('challan.challan_bank', $filter['bank']);
        }

        if(!empty($filter['class'])){
            $this->db->where('academic.term_name', $filter['class']);
        }
       
        $this->db->where('trans.is_deleted', 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function getTransportTotalPaidAmount($stud_id,$year){
        $this->db->select('SUM(fee.bus_fees) as paid_amount');
        $this->db->from('tbl_student_bus_management_details as fee');
        $this->db->where('fee.is_deleted', 0);
        $this->db->where('fee.student_id', $stud_id);
        $this->db->where('fee.intake_year',$year);
        $query = $this->db->get();
        return $query->row();
    }

    public function getStudentOverallTransFeePaymentInfo($stud_id,$year){
        // $this->db->select('fee.row_id,fee.receipt_number,
        // fee.application_no,fee.fee_account_row_id,fee.payment_date,fee.payment_type,
        // fee.total_amount,fee.paid_amount,fee.excess_amount,fee.fee_concession,
        // fee.fee_pending_status,fee.pending_balance,student.admission_no,
        // fee.bank_settlement_status,fee.collected_staff_name,fee.payment_year,bank.date,
        // fee.order_id,academic.student_id,student.student_name, academic.term_name,academic.section_name');
        $this->db->from('tbl_student_bus_management_details as fee');
        $this->db->where('fee.is_deleted', 0);
        $this->db->where('fee.student_id', $stud_id);
        $this->db->where('fee.intake_year',$year);
        $query = $this->db->get();
        return $query->result();
    }

    public function getLastReceiptNoFromTransport($year){
        $this->db->from('tbl_student_bus_management_details as std');
        $this->db->where('std.intake_year', $year);
        $this->db->where('std.is_deleted', 0);
        $this->db->order_by('std.row_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row()->receipt_no;
    }

    public function deleteReceipt($row_id, $recInfo) {
        $this->db->where('row_id', $row_id);
        $this->db->update('tbl_student_bus_management_details', $recInfo);
        return TRUE;
    }

    public function getTranportRateById($row_id){
        $this->db->from('tbl_student_transport_rate_info trans');
        $this->db->where('trans.row_id', $row_id);
        $this->db->where('trans.is_deleted', 0);
        $query = $this->db->get();
        return $query->row();
    }

    public function getRouteInfo(){
        $this->db->from('tbl_student_transport_rate_info as route');
        $this->db->where('route.is_deleted', 0);
        $query = $this->db->get();
        return $query->result();
    }


    
}
?>