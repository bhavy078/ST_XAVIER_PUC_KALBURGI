<?php 
 require APPPATH . 'views/includes/db.php'; 
?>

<style>
.break { page-break-before: always; } 
.break_after { page-break-before: none; } 

table{
    width: 100% !important;
}

u {    
    border-bottom: 2px dotted #00000;
    text-decoration: none;
    font-weight: bold;
    font-family:timesnewroman;
    font-size:16px;
}
/*.border{
    border: 2px solid black;
}*/
.border_full{
    border: 1px solid black;
    
    /* height: 90% !important; */
}
.border_bottom{
    
    border-bottom: 1px solid black;
}
.hr_line{
    margin: 0px;
    color: black;
}

.table_bordered{
    border-collapse: collapse;
}
.table_bordered th,.table_bordered td{
    border-top: 1px solid black;
    
    border-right: 1px solid black;
    padding: 3px;
}

.table_bordered th .border_right_none,.table_bordered td .border_right_none{
    border-right: 1px solid transparent !important;
}

<?php if($exam_type =="MID_TERM"){
    $exam_name = "MID TERM";
}else if($exam_type =="I_UNIT_TEST"){
    $exam_name = "I UNIT TEST";
}else if($exam_type =="II_UNIT_TEST"){
    $exam_name = "II UNIT TEST";
}

?>
</style>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- Content Header (Page header) -->
<div class="main-content-container px-3 pt-1">
    <div class="row p-0">
        <div class="col column_padding_card">
            <div class="card card-small card_heading_title p-0 m-b-1">
                <div class="card-body p-2">
                    <div class="row c-m-b">
                    <div class="col-6">
                            <span class="page-title count_heading" style="font-size: 16px;">
                                Exam Name: <b><?php echo $exam_name ?></b>
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="page-title count_heading" style="font-size: 16px;">
                                Subject Name: <b><?php echo $subInfo->sub_name ?></b>
                            </span>
                        </div>
                       
                       <div class="col-6">
                            <span class="page-title count_heading" style="font-size: 16px;">
                                 Class Stream & Section: <b><?php echo $term_name.' - '.$stream_name.' - '.$section_name; ?></b>
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="page-title count_heading" style="font-size: 16px;">
                                 Total Students: <b><?php echo count($studentInfo); ?></b>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row form-employee">
        <div class="col-12 column_padding_card">
            <div class="card card-small c-border p-2">
        
                        <hr class="mt-1 mb-1">
                        <!-- <div class="table-responsive-sm">
                            <div class="row pb-2">
                                <div class="col-lg-4 col-md-4 col-6 mb-1">
                                    <span class="badge badge-pill badge-info" style="font-size: 16px;">Stream:
                                        <b><?php echo $stream_name; ?></b></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-6 mb-1">
                                    <span class="badge badge-pill badge-info" style="font-size: 16px;">Section:
                                        <b><?php echo $section_name; ?></b></span>
                                </div>
                                <div class="col-lg-4 col-md-4 col-6 mb-1">
                                    <span class="badge badge-pill badge-info" style="font-size: 16px;">Total Students:
                                        <b><?php echo count($studentInfo); ?><b></b></span>
                                </div>
                            </div>
                        </div> -->
                        
                
                        <?php 

                            $dataPoints_Pie = array();
                            $total_pass_students = 0;
                            $total_fail_students = 0;
                            $total_distiction_students = 0;
                            $total_first_class_students = 0;
                            $total_second_class_students = 0;
                            $total_student_mark_obtained = 0;
                            $total_absented_students = 0;
                            $total_malpractice_students = 0;
                            $total_exempted_students = 0;
                            $total_student_count = 0; 
                            $subject_count = array();

                             foreach($studentInfo as $std){
                                $single_subject_fail_status = false;

                                if($subInfo->lab_status == 'true'){

                                    if($exam_type == 'I_UNIT_TEST' || $exam_type == 'II_UNIT_TEST'){
                                        $min_mark = 12;
                                        $max_mark = 35;
                                    }else if($exam_type == "PREPARATORY_I"){
                                        $min_mark = 24;
                                        $max_mark = 100;
                                    }else if($exam_type == "MID_TERM"){
                                        $min_mark = 24;
                                        $max_mark = 70;
                                    }
                                }else{
                                    if($exam_type == 'I_UNIT_TEST' || $exam_type == 'II_UNIT_TEST'){
                                        $min_mark = 14;
                                        $max_mark = 40;

                                    } else if($exam_type == "PREPARATORY_I"){
                                        $min_mark = 35;
                                        $max_mark = 100;
                                    }else if($exam_type == "MID_TERM"){
                                        $min_mark = 35;
                                        $max_mark = 100;
                                    }

                                } 
                            $exam_mark = getSubjectMarkInfo($con,$subInfo->subject_code,$std->student_id,$exam_type);

                            // $mark_theory_obt = $exam_mark['obt_theory_mark'];
                            // $mark_lab_obt = $exam_mark['obt_lab_mark'];
                            
                            if($subInfo->lab_status == 'true'){
                                $mark_theory_obt = $exam_mark['obt_theory_mark'];
                                $mark_lab_obt = $exam_mark['obt_lab_mark'];
                                
                                if($mark_theory_obt == 'AB' || $mark_theory_obt == 'EX' || $mark_theory_obt == 'MP'){
                                    $mark_obt = $mark_theory_obt;
                                }else{
                                    $mark_obt = $mark_theory_obt + $mark_lab_obt;
                                }
                            }else{
                                $mark_theory_obt = $exam_mark['obt_theory_mark'];
                                $mark_obt = $mark_theory_obt;
                            }

                            $total_max_marks += $max_mark;

                            if($mark_obt == 'AB' && $mark_obt != ''){

                                $subject_fail_status = true;
                                $single_subject_fail_status = true;

                                $total_absented_students++;

                            }else if($mark_obt == 'EX' && $mark_obt != ''){

                                $single_subject_fail_status = true;

                                $total_exempted_students++;

                            }else if($mark_obt == 'MP' && $mark_obt != ''){

                                $single_subject_fail_status = true;

                                $total_malpractice_students++;

                            }else if($mark_theory_obt < $min_mark){

                                $subject_fail_status = true;

                                $single_subject_fail_status = true;

                                $total_marks_obtained += $mark_obt; 

                                $total_fail_students += 1;

                            }else{

                                $total_pass_students += 1;

                                $total_marks_obtained += $mark_obt; 

                            }

                            if((int) $mark_obt == $mark_obt){

                            $percentage_sub = round(($mark_obt / $max_mark) * 100,2);

                    

                            if($percentage_sub >= 85){

                                $total_distiction_students += 1;

                            

                            } else if($percentage_sub >= 60 && $percentage_sub <= 84){

                                $total_first_class_students += 1;

                            

                            } else if($percentage_sub >= 50 && $percentage_sub <= 59){

                                $total_second_class_students += 1;

                            

                            }

                        }
                    
                    }

                        if($total_marks_obtained != 0){

                            $percentage = ($total_marks_obtained / $total_max_marks) * 100;
                            $percentage = round($percentage,2);
                        }else{

                            $percentage = 0;

                        }

                        

                        // if($subject_fail_status == true){

                        //     $total_fail_students++;

                        // }else{

                        //     $total_pass_students++;

                        //     if($percentage >= 85){

                        //         $total_distiction_students++;

                        //     } else if($percentage >= 60 && $percentage <= 84){

                        //         $total_first_class_students++;

                        //     } else if($percentage >= 50 && $percentage <= 59){

                        //         $total_second_class_students++;

                        //     } 

                        // }

                    

                    

                        ?>



           
                <div class="table-responsive">
                    <table class="table table-hover table_bordered">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid black;text-align: center;width: 100px;">Passed</td>
                                <td style="border: 1px solid black;text-align: center;width: 100px;">Failed</td>
                                <td style="border: 1px solid black;text-align: center;width: 100px;">Distinction</td>
                                <td style="border: 1px solid black;text-align: center;width: 100px;">First Class</td>
                                <td style="border: 1px solid black;text-align: center;width: 100px;">Second Class</td>
                            </tr>

                            <tr>
                                <td style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $total_pass_students; ?></td>
                                <td style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $total_fail_students; ?></td>
                                <td style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $total_distiction_students; ?></td>
                                <td style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $total_first_class_students; ?></td>
                                <td style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $total_second_class_students; ?></td>
                            </tr>

                            
                        </tbody>
                    </table>
                </div>


        
                                
                
             
            </div>
        </div>
    </div>




</div>


<?php

function getSubjectMarkInfo($con,$subject_id,$student_id,$exam_type){
    $query = "SELECT * FROM tbl_college_internal_exam_marks as exam
    WHERE exam.subject_code = '$subject_id' AND exam.student_id = '$student_id' AND exam.exam_type = '$exam_type' AND exam.is_deleted = 0 AND exam.exam_year = '2023-24'";
    $pdo_statement = $con->prepare($query);
    $pdo_statement->execute();
    return $pdo_statement->fetch();

}

?>

