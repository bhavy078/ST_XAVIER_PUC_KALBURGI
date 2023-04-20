
<div class="main-content-container container-fluid px-4 pt-2">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card card-small p-0 card_heading_title">
                    <div class="card-body p-1 card-content-title">
                        <div class="row ">
                            <div class="col-lg-8 col-md-8 col-8 text-black" style="font-size:22px;">
                                <i class="fa fa-users"></i>&nbsp;Detailed View of <?php echo $studentInfo->student_name; ?>
                            </div>
                            <div class="col-lg-4 col-md-4 col-4"> 
                                <a href="#" onclick="GoBackWithRefresh();return false;" class="btn text-white primary_color btn-bck float-right mobile-bck ">
                                <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Back </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- form start -->
        <!-- Default Light Table -->
        <?php if(empty($studentInfo)){ ?>
        <div class="row form-employee">
            <div class="col-lg-12 col-md-12 col-12 pr-0 text-center">
            <img height="270" src="<?php echo base_url(); ?>assets/images/404.png"/>
            </div>
        </div>
        <?php } else {  ?>
        <div class="row form-employee">
            <div class="col-12">
                <div class="card card-small c-border p-0 mb-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item p-1">
                            <div class="row">
                                <div class="col profile-head">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="personal-tab" data-toggle="tab"
                                                href="#personal" role="tab" aria-controls="personal"
                                                aria-selected="false">Personal Info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic"
                                                role="tab" aria-controls="academic" aria-selected="true">Academic
                                                Info</a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" id="first_unit_test-tab" data-toggle="tab" href="#first_unit_test"
                                                role="tab" aria-controls="first_unit_test" aria-selected="true">I Unit Test
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="first_term-tab" data-toggle="tab" href="#first_term"
                                                role="tab" aria-controls="first_term" aria-selected="true">I Term
                                            </a>
                                        </li>-->
                                        <li class="nav-item">
                                            <a class="nav-link" id="mid_term-tab" data-toggle="tab" href="#mid_term"
                                                role="tab" aria-controls="mid_term" aria-selected="true">MID TERM
                                            </a>
                                        </li> 
                                         <li class="nav-item">
                                            <a class="nav-link" id="second_unit_test-tab" data-toggle="tab" href="#second_unit_test"
                                                role="tab" aria-controls="second_unit_test" aria-selected="true">II Unit Test
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="preparatory-tab" data-toggle="tab" href="#preparatory"
                                                role="tab" aria-controls="preparatory" aria-selected="true">Preparatory
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content personal-tab" id="myTabContent">
                                        <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                            aria-labelledby="personal-tab">
                                            <div class="table-responsive-sm table-responsive-md table-responsive-xs">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td style="background:white" width="160" rowspan="7" class="p-0">
                                                            <div class="profile-img">
                                                        
                                                            <?php  ;
                                                               $profileImg = $studentInfo->photo_url;
                                                                if(!empty($profileImg)){ ?>
                                                                  <img src="<?php echo $profileImg; ?>" class="avatar img-thumbnail"
                                                                 alt="Profile Image" id="uploadedImage"/>
                                                               <?php } else { ?>
                                                                <img src="<?php echo base_url(); ?>assets/dist/img/user.png" class="avatar img-thumbnail"
                                                               id="uploadedImage" alt="Profile default">
                                                              <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <th class="tbl-head" width="80">Application No.</th>
                                                        <th class="tbl-head-content" width="120">
                                                            <?php echo $studentInfo->application_no; ?>
                                                        </th>
                                                        <th class="tbl-head text-uppercase" width="140">Full Name</th>
                                                        <th class="tbl-head-content text-uppercase" width="240">
                                                            <?php echo $studentInfo->student_name; ?>
                                                        </th>
                                                        <th class="tbl-head" width="140">Date of Birth</th>
                                                        <th class="tbl-head-content" width="120">
                                                            <?php if(empty($studentInfo->dob) || $studentInfo->dob == '0000-00-00'){
                                                                echo "";
                                                            } else{
                                                                echo date('d-m-Y',strtotime($studentInfo->dob));
                                                            } ?>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="tbl-head" width="140">Gender</th>
                                                        <th class="tbl-head-content" width="180">
                                                            <?php echo $studentInfo->gender; ?>
                                                        </th>
                                                        <th class="tbl-head">Blood Group</th>
                                                        <th class="tbl-head-content">
                                                            <?php echo $studentInfo->blood_group; ?>
                                                        </th>
                                                        <th class="tbl-head">Nationality</th>
                                                        <th class="tbl-head-content">
                                                            <?php echo $studentInfo->nationality; ?>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="tbl-head">Religion</th>
                                                        <th class="tbl-head-content">
                                                            <?php echo $studentInfo->religion; ?>
                                                        </th>
                                                        <th class="tbl-head">Caste</th>
                                                        <th class="tbl-head-content"><?php echo $studentInfo->caste; ?></th>
                                                        <th class="tbl-head">Category</th>
                                                        <th class="tbl-head-content">
                                                            <?php echo $studentInfo->category; ?>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                    <th class="tbl-head">Sub Caste</th>
                                                        <th class="tbl-head-content">
                                                            <?php echo $studentInfo->sub_caste; ?>
                                                        </th>
                                                        <th class="tbl-head">Mother Tongue</th>
                                                        <th class="tbl-head-content text-uppercase">
                                                            <?php echo $studentInfo->mother_tongue; ?>
                                                        </th>
                                                      
                                                        <th class="tbl-head">Mobile</th>
                                                        <th class="tbl-head-content">
                                                            <?php echo $studentInfo->mobile; ?>
                                                        </th>
                                                    </tr>
                                                  
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="head-title">
                                                            <th colspan="7" class="text-center tbl-head">Family Info</th>
                                                        </tr>
                                                        <tr class="head-title tbl-head">
                                                            <th>Member Name</th>
                                                            <th>Relationship</th>
                                                            <th>Qualification</th>
                                                            <th>Profession</th>
                                                            <th>Annual Income</th>
                                                            <th>Mobile Number</th>
                                                            <th>Email Id</th>
                                                        </tr>
                                                    </thead>
                                                    <tr class="tbl-head-content">
                                                        <th class="text-uppercase"><?php echo $studentInfo->father_name; ?></th>
                                                        <th>Father</th>
                                                        <th><?php echo $studentInfo->father_educational_qualification; ?></th>
                                                        <th><?php echo $studentInfo->father_profession; ?></th>
                                                        <th><?php echo $studentInfo->father_annual_income; ?></th>
                                                        <th><?php echo $studentInfo->father_mobile; ?></th>
                                                        <th><?php echo $studentInfo->father_email; ?></th>
                                                    </tr>
                                                    <tr class="tbl-head-content">
                                                        <th class="text-uppercase"><?php echo $studentInfo->mother_name; ?></th>
                                                        <th>Mother</th>
                                                        <th><?php echo $studentInfo->mother_educational_qualification; ?></th>
                                                        <th><?php echo $studentInfo->mother_profession; ?></th>
                                                        <th><?php echo $studentInfo->mother_annual_income; ?></th>
                                                        <th><?php echo $studentInfo->mother_mobile; ?></th>
                                                        <th><?php echo $studentInfo->mother_email; ?></th>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-12 mb-1">
                                                    <div class="card" style="font-size:16px; font-weight:900">
                                                        <div class="card-header head-title text-center p-1 tbl-head">
                                                            <span style="font-size:16px; font-weight:900">Present Address
                                                            </span></div>
                                                        <div class="card-body p-1 tbl-head-content font-weight-bold">
                                                            <?php echo $studentInfo->present_address; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-12 mb-1">
                                                    <div class="card " style="font-size:16px; font-weight:900">
                                                        <div class="card-header head-title text-center p-1 tbl-head">
                                                            <span style="font-size:16px; font-weight:900">Permanent Address</span></div>
                                                        <div class="card-body p-1 tbl-head-content font-weight-bold">
                                                            <?php echo $studentInfo->residential_address; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive mt-1">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th class="tbl-head" width="250">Physically Challenged</th>
                                                        <th class="tbl-head-content">
                                                            <?php if($studentInfo->Is_physically_challenged == 0){ echo "No"; } else { echo "Yes"; } ?> 
                                                        </th>
                                                        <th class="tbl-head" width="250">Dyslexia</th>
                                                        <th class="tbl-head-content">
                                                        <?php echo $studentInfo->is_dyslexic; ?> 

                                                        </th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                                            <table class="table table-bordered">
                                                <tr>
                                                <th class="tbl-head" width="160">Application Number</th>
                                                    <th class="tbl-head-content" width="140"><?php echo $studentInfo->application_no; ?></th>
                                                    <th class="tbl-head" width="160">PU Board No.</th>
                                                    <th class="tbl-head-content" width="140"><?php echo $studentInfo->pu_board_number; ?></th>
                                                    <th class="tbl-head" width="160">Student ID</th>
                                                    <th class="tbl-head-content" width="140"><?php echo $studentInfo->student_id; ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="tbl-head">Elective</th>
                                                    <th class="tbl-head-content"><?php echo $studentInfo->elective_sub; ?></th>
                                                    <th class="tbl-head">Term</th>
                                                    <th class="tbl-head-content"><?php echo strtoupper($studentInfo->term_name); ?></th>
                                                    <th class="tbl-head">Stream</th>
                                                    <th class="tbl-head-content"><?php echo strtoupper($studentInfo->stream_name); ?></th>
                                                   
                                                <tr>
                                                <th class="tbl-head">Section</th>
                                                    <th class="tbl-head-content"><?php echo strtoupper($studentInfo->section_name); ?></th>
                                                    <th class="tbl-head">Hall Ticket No.</th>
                                                    <th class="tbl-head-content"><?php echo strtoupper($studentInfo->hall_ticket_no); ?></th>
                                                    <th class="tbl-head">Date of Admmission</th>
                                                    <th class="tbl-head-content"><?php echo date('d-m-Y',strtotime($studentInfo->date_of_admission)); ?></th>
                                                </tr>
                                                <tr>
                                                <th class="tbl-head">SAT Number</th>
                                                    <th class="tbl-head-content"><?php echo $studentInfo->sat_number; ?></th>
                                                    <th class="tbl-head">Date of Join</th>
                                                    <th class="tbl-head-content"><?php echo date('d-m-Y',strtotime($studentInfo->doj)); ?></th>

                                                    </tr>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="first_unit_test" role="tabpanel" aria-labelledby="first_unit_test-tab">
                                            <div class=" table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center">SUBJECTS</th>
                                                            <th class="text-center">Max. Marks</th>
                                                            <th class="text-center">Min. Marks</th>
                                                            <th class="text-center">Marks Scored</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                               
                                                    $result_subject_fail_status = false;
                                                    $result_fail_status = false;
                                                    $max_mark = 0;
                                                    $min_mark_pass = 0;
                                                    $total_mark_obtained = 0;
                                                    $total_max_mark = 0;
                                                    $total_min_mark = 0;
                                                 
                                                    for($i=0;$i<count($subject_code);$i++){
                                                        if(strtoupper($firstUnitTestMarkInfo[$i]->sub_name) != ''){ 
                                                        $result_display = "";
                                                        $result_subject_fail_status = false;
                                                        if($firstUnitTestMarkInfo[$i]->lab_status == 'true'){
                                                            $max_mark = 100;
                                                            $min_mark_pass = 35;
                                                        }else{
                                                            $max_mark = 100;
                                                            $min_mark_pass = 35;
                                                        }
                                                        $total_max_mark += $max_mark;
                                                        $total_min_mark += $min_mark_pass;
                                                        $obtainedMark = $firstUnitTestMarkInfo[$i]->obt_theory_mark;
                                                        $obatained_mark = (float)$obtainedMark * 4;
                                                        if($obtainedMark == 'AB' || $obtainedMark == 'EXEM' || $obtainedMark == 'MP' || $obtainedMark == 'SAT'){
                                                            $result_subject_fail_status = true;
                                                            $result_display = $obtainedMark;
                                                            $result_fail_status = true;
                                                        }else if($min_mark_pass > $obatained_mark){
                                                            $result_subject_fail_status = true;
                                                            $result_fail_status = true;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark .'F';
                                                        }else{
                                                            $result_subject_fail_status = false;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark;
                                                        }
                                                    ?>
                                                <tr>
                                                    <th>
                                                        <?php echo strtoupper($firstUnitTestMarkInfo[$i]->sub_name); ?></th>
                                                    <th class="text-center table_marks_data"><?php echo $max_mark; ?>
                                                    </th>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $min_mark_pass; ?></th>
                                                    <?php if($result_subject_fail_status == true){ ?>
                                                    <th style="background: #f76a7ebf"
                                                        class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php }else{ ?>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php } ?>
                                                </tr>
                                                <?php  } }
                                                       if($total_mark_obtained != 0){
                                                        $total_percentage = ($total_mark_obtained/$total_max_mark)*100; 
                                                        $exam_result = calculateResult($total_mark_obtained);
                                                        ?>
                                                    <tr class="text-center table_row_backgrond">
                                                        <th class="total_row">Total</th>
                                                        <th><?php echo $total_max_mark; ?></th>
                                                        <th><?php echo $total_min_mark; ?></th>
                                                        <th><?php echo $total_mark_obtained; ?></th>
                                                    </tr>
    
                                                    <tr>
                                                        <th colspan="2" class="total_row">Percentage:
                                                            <?php echo round($total_percentage,2).'%'; ?></th>
                                                        <th colspan="2">Result:
                                                            <?php if($result_fail_status == true){ ?>
                                                            <span class="text_fail"><?php echo 'FAIL'; ?></span>
                                                            <?php } else { ?>
                                                            <span class="text_pass"><?php echo $exam_result; ?></span>
                                                            <?php } ?></th>
                                                    </tr>
                                                  <?php } ?>
                                                </table>  
                                            </div>
                                        </div>


                                      
<!-- II UNIT TEST -->
                                      <div class="tab-pane fade" id="second_unit_test" role="tabpanel" aria-labelledby="second_unit_test-tab">
                                         <div class=" table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center">SUBJECTS</th>
                                                            <th class="text-center">Max. Marks</th>
                                                            <th class="text-center">Min. Marks</th>
                                                            <th class="text-center">Marks Scored</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                               
                                                    $result_subject_fail_status = false;
                                                    $result_fail_status = false;
                                                    $max_mark = 0;
                                                    $min_mark_pass = 0;
                                                    $total_mark_obtained = 0;
                                                    $total_max_mark = 0;
                                                    $total_min_mark = 0;
                                                 
                                                    for($i=0;$i<count($subject_code);$i++){
                                                        if(strtoupper($secondUnitTestMarkInfo[$i]->name) != ''){ 
                                                        $result_display = "";
                                                        $result_subject_fail_status = false;
                                                        if($secondUnitTestMarkInfo[$i]->lab_status == 'true'){
                                                            $max_mark = 35;
                                                            $min_mark_pass = 12;
                                                        }else{
                                                            $max_mark = 50;
                                                            $min_mark_pass = 18;
                                                        }
                                                        $total_max_mark += $max_mark;
                                                        $total_min_mark += $min_mark_pass;
                                                        $obtainedMark = $secondUnitTestMarkInfo[$i]->obt_theory_mark;
                                                        // if($studentInfo->term_name == 'II PUC'){
                                                        //     $obatained_mark = (float)$obtainedMark * 2;
                                                        // }else{
                                                            $obatained_mark = $obtainedMark;
                                                        // }
                                                        if($obtainedMark == 'AB' || $obtainedMark == 'EXEM' || $obtainedMark == 'MP' || $obtainedMark == 'SAT'){
                                                            $result_subject_fail_status = true;
                                                            $result_display = $obtainedMark;
                                                            $result_fail_status = true;
                                                        }else if($min_mark_pass > $obatained_mark){
                                                            $result_subject_fail_status = true;
                                                            $result_fail_status = true;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark .'F';
                                                        }else{
                                                            $result_subject_fail_status = false;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark;
                                                        }
                                                    ?>
                                                <tr>
                                                    <th>
                                                        <?php echo strtoupper($secondUnitTestMarkInfo[$i]->name); ?></th>
                                                    <th class="text-center table_marks_data"><?php echo $max_mark; ?>
                                                    </th>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $min_mark_pass; ?></th>
                                                    <?php if($result_subject_fail_status == true){ ?>
                                                    <th style="background: #f76a7ebf"
                                                        class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php }else{ ?>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php } ?>
                                                </tr>
                                                <?php  } }
                                                       if($total_mark_obtained != 0){
                                                        $total_percentage = ($total_mark_obtained/$total_max_mark)*100; 
                                                        $exam_result = calculateResult($total_mark_obtained);
                                                        ?>
                                                    <tr class="text-center table_row_backgrond">
                                                        <th class="total_row">Total</th>
                                                        <th><?php echo $total_max_mark; ?></th>
                                                        <th><?php echo $total_min_mark; ?></th>
                                                        <th><?php echo $total_mark_obtained; ?></th>
                                                    </tr>
    
                                                    <tr>
                                                        <th colspan="2" class="total_row">Percentage:
                                                            <?php echo round($total_percentage,2).'%'; ?></th>
                                                        <th colspan="2">Result:
                                                            <?php if($result_fail_status == true){ ?>
                                                            <span class="text_fail"><?php echo 'FAIL'; ?></span>
                                                            <?php } else { ?>
                                                            <span class="text_pass"><?php echo 'PASS'; ?></span>
                                                            <?php } ?></th>
                                                    </tr>
                                                  <?php } ?>
                                                </table>  
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="first_term" role="tabpanel" aria-labelledby="first_term-tab">
                                            <div class=" table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center">SUBJECTS</th>
                                                            <th class="text-center">Max. Marks</th>
                                                            <th class="text-center">Min. Marks</th>
                                                            <th class="text-center">Marks Scored</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                               
                                                    $result_subject_fail_status = false;
                                                    $result_fail_status = false;
                                                    $max_mark = 0;
                                                    $min_mark_pass = 0;
                                                    $total_mark_obtained = 0;
                                                    $total_max_mark = 0;
                                                    $total_min_mark = 0;
                                                 
                                                    for($i=0;$i<count($subject_code);$i++){
                                                        if(strtoupper($firstTermMarkInfo[$i]->sub_name) != ''){ 
                                                        $result_display = "";
                                                        $result_subject_fail_status = false;
                                                        if($firstTermMarkInfo[$i]->lab_status == 'true'){
                                                            $max_mark = 100;
                                                            $min_mark_pass = 35;
                                                        }else{
                                                            $max_mark = 100;
                                                            $min_mark_pass = 35;
                                                        }
                                                        $total_max_mark += $max_mark;
                                                        $total_min_mark += $min_mark_pass;
                                                        $obtainedMark = $firstTermMarkInfo[$i]->obt_theory_mark;
                                                        if($studentInfo->term_name == 'II PUC'){
                                                            $obatained_mark = (float)$obtainedMark * 2;
                                                        }else{
                                                            $obatained_mark = $obtainedMark;
                                                        }
                                                        if($obtainedMark == 'AB' || $obtainedMark == 'EXEM' || $obtainedMark == 'MP' || $obtainedMark == 'SAT'){
                                                            $result_subject_fail_status = true;
                                                            $result_display = $obtainedMark;
                                                            $result_fail_status = true;
                                                        }else if($min_mark_pass > $obatained_mark){
                                                            $result_subject_fail_status = true;
                                                            $result_fail_status = true;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark .'F';
                                                        }else{
                                                            $result_subject_fail_status = false;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark;
                                                        }
                                                    ?>
                                                <tr>
                                                    <th>
                                                        <?php echo strtoupper($firstTermMarkInfo[$i]->sub_name); ?></th>
                                                    <th class="text-center table_marks_data"><?php echo $max_mark; ?>
                                                    </th>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $min_mark_pass; ?></th>
                                                    <?php if($result_subject_fail_status == true){ ?>
                                                    <th style="background: #f76a7ebf"
                                                        class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php }else{ ?>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php } ?>
                                                </tr>
                                                <?php  } }
                                                       if($total_mark_obtained != 0){
                                                        $total_percentage = ($total_mark_obtained/$total_max_mark)*100; 
                                                        $exam_result = calculateResult($total_mark_obtained);
                                                        ?>
                                                    <tr class="text-center table_row_backgrond">
                                                        <th class="total_row">Total</th>
                                                        <th><?php echo $total_max_mark; ?></th>
                                                        <th><?php echo $total_min_mark; ?></th>
                                                        <th><?php echo $total_mark_obtained; ?></th>
                                                    </tr>
    
                                                    <tr>
                                                        <th colspan="2" class="total_row">Percentage:
                                                            <?php echo round($total_percentage,2).'%'; ?></th>
                                                        <th colspan="2">Result:
                                                            <?php if($result_fail_status == true){ ?>
                                                            <span class="text_fail"><?php echo 'FAIL'; ?></span>
                                                            <?php } else { ?>
                                                            <span class="text_pass"><?php echo $exam_result; ?></span>
                                                            <?php } ?></th>
                                                    </tr>
                                                  <?php } ?>
                                                </table>  
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="mid_term" role="tabpanel" aria-labelledby="mid_term-tab">
                                            <div class=" table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center">SUBJECTS</th>
                                                            <th class="text-center">Max. Marks</th>
                                                            <th class="text-center">Min. Marks</th>
                                                            <th class="text-center">Marks Scored</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                               
                                                    $result_subject_fail_status = false;
                                                    $result_fail_status = false;
                                                    $max_mark = 0;
                                                    $min_mark_pass = 0;
                                                    $total_mark_obtained = 0;
                                                    $total_max_mark = 0;
                                                    $total_min_mark = 0;
                                                 
                                                    for($i=0;$i<count($subject_code);$i++){
                                                        if(strtoupper($midTermMarkInfo[$i]->name) != ''){ 
                                                        $result_display = "";
                                                        $result_subject_fail_status = false;
                                                        if($midTermMarkInfo[$i]->lab_status == 'true'){
                                                            $max_mark = 70;
                                                            $min_mark_pass = 24;
                                                        }else{
                                                            $max_mark = 100;
                                                            $min_mark_pass = 35;
                                                        }
                                                        $total_max_mark += $max_mark;
                                                        $total_min_mark += $min_mark_pass;
                                                        $obtainedMark = $midTermMarkInfo[$i]->obt_theory_mark;
                                                        // if($studentInfo->term_name == 'II PUC'){
                                                        //     $obatained_mark = (float)$obtainedMark * 2;
                                                        // }else{
                                                            $obatained_mark = $obtainedMark;
                                                        // }
                                                        if($obtainedMark == 'AB' || $obtainedMark == 'EXEM' || $obtainedMark == 'MP' || $obtainedMark == 'SAT'){
                                                            $result_subject_fail_status = true;
                                                            $result_display = $obtainedMark;
                                                            $result_fail_status = true;
                                                        }else if($min_mark_pass > $obatained_mark){
                                                            $result_subject_fail_status = true;
                                                            $result_fail_status = true;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark .'F';
                                                        }else{
                                                            $result_subject_fail_status = false;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark;
                                                        }
                                                    ?>
                                                <tr>
                                                    <th>
                                                        <?php echo strtoupper($midTermMarkInfo[$i]->name); ?></th>
                                                    <th class="text-center table_marks_data"><?php echo $max_mark; ?>
                                                    </th>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $min_mark_pass; ?></th>
                                                    <?php if($result_subject_fail_status == true){ ?>
                                                    <th style="background: #f76a7ebf"
                                                        class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php }else{ ?>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php } ?>
                                                </tr>
                                                <?php  } }
                                                       if($total_mark_obtained != 0){
                                                        $total_percentage = ($total_mark_obtained/$total_max_mark)*100; 
                                                        $exam_result = calculateResult($total_mark_obtained);
                                                        ?>
                                                    <tr class="text-center table_row_backgrond">
                                                        <th class="total_row">Total</th>
                                                        <th><?php echo $total_max_mark; ?></th>
                                                        <th><?php echo $total_min_mark; ?></th>
                                                        <th><?php echo $total_mark_obtained; ?></th>
                                                    </tr>
    
                                                    <tr>
                                                        <th colspan="2" class="total_row">Percentage:
                                                            <?php echo round($total_percentage,2).'%'; ?></th>
                                                        <th colspan="2">Result:
                                                            <?php if($result_fail_status == true){ ?>
                                                            <span class="text_fail"><?php echo 'FAIL'; ?></span>
                                                            <?php } else { ?>
                                                            <span class="text_pass"><?php echo 'PASS'; ?></span>
                                                            <?php } ?></th>
                                                    </tr>
                                                  <?php } ?>
                                                </table>  
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="preparatory" role="tabpanel" aria-labelledby="preparatory-tab">
                                            <div class=" table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center">SUBJECTS</th>
                                                            <th class="text-center">Max. Marks</th>
                                                            <th class="text-center">Min. Marks</th>
                                                            <th class="text-center">Marks Scored</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                               
                                                    $result_subject_fail_status = false;
                                                    $result_fail_status = false;
                                                    $max_mark = 0;
                                                    $min_mark_pass = 0;
                                                    $total_mark_obtained = 0;
                                                    $total_max_mark = 0;
                                                    $total_min_mark = 0;
                                                 
                                                    for($i=0;$i<count($subject_code);$i++){
                                                        if(strtoupper($firstPreparatoryMarkInfo[$i]->name) != ''){ 
                                                        $result_display = "";
                                                        $result_subject_fail_status = false;
                                                        if($firstPreparatoryMarkInfo[$i]->lab_status == 'true'){
                                                            $max_mark = 70;
                                                            $min_mark_pass = 24;
                                                        }else{
                                                            $max_mark = 100;
                                                            $min_mark_pass = 35;
                                                        }
                                                        $total_max_mark += $max_mark;
                                                        $total_min_mark += $min_mark_pass;
                                                        $obtainedMark = $firstPreparatoryMarkInfo[$i]->obt_theory_mark;
                                                        // if($studentInfo->term_name == 'II PUC'){
                                                        //     $obatained_mark = (float)$obtainedMark * 2;
                                                        // }else{
                                                            $obatained_mark = $obtainedMark;
                                                        // }
                                                        if($obtainedMark == 'AB' || $obtainedMark == 'EXEM' || $obtainedMark == 'MP' || $obtainedMark == 'SAT'){
                                                            $result_subject_fail_status = true;
                                                            $result_display = $obtainedMark;
                                                            $result_fail_status = true;
                                                        }else if($min_mark_pass > $obatained_mark){
                                                            $result_subject_fail_status = true;
                                                            $result_fail_status = true;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark .'F';
                                                        }else{
                                                            $result_subject_fail_status = false;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark;
                                                        }
                                                    ?>
                                                <tr>
                                                    <th>
                                                        <?php echo strtoupper($firstPreparatoryMarkInfo[$i]->name); ?></th>
                                                    <th class="text-center table_marks_data"><?php echo $max_mark; ?>
                                                    </th>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $min_mark_pass; ?></th>
                                                    <?php if($result_subject_fail_status == true){ ?>
                                                    <th style="background: #f76a7ebf"
                                                        class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php }else{ ?>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php } ?>
                                                </tr>
                                                <?php  } }
                                                       if($total_mark_obtained != 0){
                                                        $total_percentage = ($total_mark_obtained/$total_max_mark)*100; 
                                                        $exam_result = calculateResult($total_mark_obtained);
                                                        ?>
                                                    <tr class="text-center table_row_backgrond">
                                                        <th class="total_row">Total</th>
                                                        <th><?php echo $total_max_mark; ?></th>
                                                        <th><?php echo $total_min_mark; ?></th>
                                                        <th><?php echo $total_mark_obtained; ?></th>
                                                    </tr>
    
                                                    <tr>
                                                        <th colspan="2" class="total_row">Percentage:
                                                            <?php echo round($total_percentage,2).'%'; ?></th>
                                                        <th colspan="2">Result:
                                                            <?php if($result_fail_status == true){ ?>
                                                            <span class="text_fail"><?php echo 'FAIL'; ?></span>
                                                            <?php } else { ?>
                                                            <span class="text_pass"><?php echo 'PASS'; ?></span>
                                                            <?php } ?></th>
                                                    </tr>
                                                  <?php } ?>
                                                </table>  
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="firstPreparartory" role="tabpanel" aria-labelledby="firstPreparartory-tab">
                                            <div class=" table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th class="text-center">SUBJECTS</th>
                                                            <th class="text-center">Max. Marks</th>
                                                            <th class="text-center">Min. Marks</th>
                                                            <th class="text-center">Marks Scored</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                               
                                                    $result_subject_fail_status = false;
                                                    $result_fail_status = false;
                                                    $max_mark = 0;
                                                    $min_mark_pass = 0;
                                                    $total_mark_obtained = 0;
                                                    $total_max_mark = 0;
                                                    $total_min_mark = 0;
                                                 
                                                    for($i=0;$i<count($subject_code);$i++){
                                                        if(strtoupper($firstPreparatoryMarkInfo[$i]->sub_name) != ''){ 
                                                        $result_display = "";
                                                        $result_subject_fail_status = false;
                                                        if($firstPreparatoryMarkInfo[$i]->lab_status == 'true'){
                                                            $max_mark = 100;
                                                            $min_mark_pass = 35;
                                                        }else{
                                                            $max_mark = 100;
                                                            $min_mark_pass = 35;
                                                        }
                                                        $total_max_mark += $max_mark;
                                                        $total_min_mark += $min_mark_pass;
                                                        $obtainedMark = $firstPreparatoryMarkInfo[$i]->obt_theory_mark;
                                                        // if($studentInfo->term_name == 'II PUC'){
                                                        //     $obatained_mark = (float)$obtainedMark * 2;
                                                        // }else{
                                                            $obatained_mark = $obtainedMark;
                                                        // }
                                                        if($obtainedMark == 'AB' || $obtainedMark == 'EXEM' || $obtainedMark == 'MP' || $obtainedMark == 'SAT'){
                                                            $result_subject_fail_status = true;
                                                            $result_display = $obtainedMark;
                                                            $result_fail_status = true;
                                                        }else if($min_mark_pass > $obatained_mark){
                                                            $result_subject_fail_status = true;
                                                            $result_fail_status = true;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark .'F';
                                                        }else{
                                                            $result_subject_fail_status = false;
                                                            $total_mark_obtained += $obatained_mark;
                                                            $result_display = $obatained_mark;
                                                        }
                                                    ?>
                                                <tr>
                                                    <th>
                                                        <?php echo strtoupper($firstPreparatoryMarkInfo[$i]->sub_name); ?></th>
                                                    <th class="text-center table_marks_data"><?php echo $max_mark; ?>
                                                    </th>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $min_mark_pass; ?></th>
                                                    <?php if($result_subject_fail_status == true){ ?>
                                                    <th style="background: #f76a7ebf"
                                                        class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php }else{ ?>
                                                    <th class="text-center table_marks_data">
                                                        <?php echo $result_display; ?></th>
                                                    <?php } ?>
                                                </tr>
                                                <?php  } }
                                                       if($total_mark_obtained != 0){
                                                        $total_percentage = ($total_mark_obtained/$total_max_mark)*100; 
                                                        $exam_result = calculateResult($total_mark_obtained);
                                                        ?>
                                                    <tr class="text-center table_row_backgrond">
                                                        <th class="total_row">Total</th>
                                                        <th><?php echo $total_max_mark; ?></th>
                                                        <th><?php echo $total_min_mark; ?></th>
                                                        <th><?php echo $total_mark_obtained; ?></th>
                                                    </tr>
    
                                                    <tr>
                                                        <th colspan="2" class="total_row">Percentage:
                                                            <?php echo round($total_percentage,2).'%'; ?></th>
                                                        <th colspan="2">Result:
                                                            <?php if($result_fail_status == true){ ?>
                                                            <span class="text_fail"><?php echo 'FAIL'; ?></span>
                                                            <?php } else { ?>
                                                            <span class="text_pass"><?php echo $exam_result; ?></span>
                                                            <?php } ?></th>
                                                    </tr>
                                                  <?php } ?>
                                                </table>  
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="attendance_info" role="tabpanel" aria-labelledby="attendance_info-tab">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table_info">
                                                    <thead>
                                                        <tr class="table_row_backgrond">
                                                            <th class="text-center">SUBJECTS</th>
                                                            <th class="text-center">Classes Held</th>
                                                            <th class="text-center">Classes Present</th>
                                                            <th class="text-center">Percentage</th>
                                                        </tr>
                                                    </thead>
                                                    <?php for($i=0;$i<count($subject_code);$i++){  ?>
                                                        <tr>
                                                            <th><?php echo $subject_attendance[$subject_code[$i]]['sub_name']->sub_name; ?></th>
                                                            <th class="text-center"><?php echo $subject_attendance[$subject_code[$i]]['class_held']; ?></th>
                                                            <th class="text-center"><?php echo $subject_attendance[$subject_code[$i]]['class_attended']; ?></th>
                                                            <?php if(round($subject_attendance[$subject_code[$i]]['percentage'],2) < 85.00){ ?>
                                                                <th width="300" style="background:#f76a7ebf" class="text-center"><?php echo round($subject_attendance[$subject_code[$i]]['percentage'],2);?></th>
                                                            <?php }else{ ?>
                                                                <th width="300" class="text-center"><?php echo round($subject_attendance[$subject_code[$i]]['percentage'],2);?></th>
                                                            <?php  } ?>
                                                        </tr>
                                                    <?php }  ?>

                                                    <tr>
                                                        <th colspan="4" class="total_row">Total Percentage: 
                                                        <?php if(round($total_attendance_percentage,2) < 85.00){ ?>
                                                            <span colspan="3" class="total_row text_fail"><?php echo round($total_attendance_percentage,2).'%'; ?></span>
                                                        <?php }else{ ?>
                                                            <span colspan="3" class="total_row"><?php echo round($total_attendance_percentage,2).'%'; ?></span>
                                                        <?php  } ?>
                                                        </th>
                                                    </tr>
                                                </table>  
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Default Light Table -->
        
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
function GoBackWithRefresh(event) {
    showLoader();
    if ('referrer' in document) {
        window.location = document.referrer;
        /* OR */
        //location.replace(document.referrer);
    } else {
        window.history.back();
    }
}
jQuery(document).ready(function() {

    jQuery('.resetFilters').click(function() {
        $(this).closest('form').find("input[type=text]").val("");
    })
});
</script>

<?php


function calculateResult($total_marks){
    $percentage = floor(($total_marks / 600) * 100);
    if($percentage >= 85){
        return "Distinction";
    } else if($percentage >= 60 && $percentage <= 84){
        return "I Class";
    } else if($percentage >= 50 && $percentage <= 59){
        return "II Class";
    } else if($percentage >= 35 && $percentage <= 49){
        return "III Class";
    }
}
?>