

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<div class="main-content-container container-fluid px-4">

    <div class="col-md-12">

        <?php

            $this->load->helper('form');

            $error = $this->session->flashdata('error');

            if($error)

            {

        ?>

        <div class="alert alert-danger alert-dismissable">

            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

            <?php echo $this->session->flashdata('error'); ?>                    

        </div>

        <?php } ?>

        <?php  

            $success = $this->session->flashdata('success');

            if($success)

            {

        ?>

        <div class="alert alert-success alert-dismissable">

            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

            <?php echo $this->session->flashdata('success'); ?>

        </div>

        <?php } ?>



        <?php  

            $noMatch = $this->session->flashdata('nomatch');

            if($noMatch)

            {

        ?>

        <div class="alert alert-warning alert-dismissable">

            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

            <?php echo $this->session->flashdata('nomatch'); ?>

        </div>

        <?php } ?>

        

        <div class="row">

            <div class="col-md-12">

                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>

            </div>

        </div>

    </div>

    <!-- Content Header (Page header) -->

    <section class="content-header">

        <div class="row mt-1 mb-2">

            <div class="col padding_left_right_null">

            <div class="card card-small p-0 card_head_dashboard">

                <div class="card-body p-2 ml-2">

                <span class="page-title">

                    <i class="material-icons">format_list_bulleted</i> My Attendance

                </span>

                <a onclick="window.history.back(); return false;" class="btn btn-primary float-right text-white pt-2" value="Back" >Back </a>

                </div>

            </div>

            </div>

        </div>

    </section>

    <div class="row form-employee">

        <div class="col-12 padding_left_right_null">

            <div class="card card-small c-border p-1 mb-4">

               <div class="row">

                    <div class="col-lg-8 col-12 mb-2">

                        <div class="table-responsive">

                            <table class="table table-bordered table_info">

                                <thead>

                                    <tr class="table-primary">

                                        <th colspan="4" class="text-center" style="font-size: 20px;">Attendance</th>

                                        <!-- <td colspan="1" class="text-right"><a href="<?php echo base_url(); ?>overallStudentAttendance">Click here for detail report</a></td> -->

                                    </tr>

                                    <tr class="table_row_backgrond">

                                        <th class="text-center">SUBJECTS</th>

                                        <!-- <th class="text-center">Classes Held</th>

                                        <th class="text-center">Classes Present</th> -->

                                        <th class="text-center">Percentage</th>

                                    </tr>

                                </thead>

                                <?php  

                                    $dataPoints = array();

                                    for($i=0;$i<count($subject_code);$i++){  

                                        

                                        ?>

                                    <tr>

                                        <th><?php echo $subject_attendance[$subject_code[$i]]['name']->name; ?></th>

                                        <!-- <th class="text-center"><?php echo $subject_attendance[$subject_code[$i]]['class_held']; ?></th> -->

                                        <!-- <th class="text-center"><?php echo $subject_attendance[$subject_code[$i]]['class_attended']; ?></th> -->

                                        <?php if(round($subject_attendance[$subject_code[$i]]['percentage'],2) < 85.00){ ?>

                                            <th width="300" style="background:#f76a7ebf" class="text-center"><?php echo round($subject_attendance[$subject_code[$i]]['percentage'],2);?></th>

                                        <?php }else{ ?>

                                            <th width="300" class="text-center"><?php echo round($subject_attendance[$subject_code[$i]]['percentage'],2);?></th>

                                        <?php  } ?>

                                    </tr>

                                <?php $attendance_percentage = round($subject_attendance[$subject_code[$i]]['percentage'],2);

                                    array_push($dataPoints, array("label"=> $subject_attendance[$subject_code[$i]]['name']->name, "y"=> (int)$attendance_percentage));

                                    }  ?>



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

                    <div class="col-lg-4 col-12">

                        <div id="chartContainer" style="height: 370px; width: 100%;"></div>

                        <script>

                        function loadGraph() {

                            var chart = new CanvasJS.Chart("chartContainer", {

                                animationEnabled: true,

                                exportEnabled: true,

                                theme: "light1", 

                                    title:{

                                        text: ""

                                    },

                                    data: [{

                                        type: "column", 

                                        color: "#47d647",

                                        dataPoints: <?php echo json_encode($dataPoints); ?>

                                    }]

                                });

                                setColor(chart);

                                chart.render();

                            }

                            <?php

                                echo "loadGraph();";

                            ?>

                            function setColor(chart){

                                for(var i = 0; i < chart.options.data.length; i++) {

                                dataSeries = chart.options.data[i];

                                for(var j = 0; j < dataSeries.dataPoints.length; j++){

                                    if(dataSeries.dataPoints[j].y < 85)

                                    dataSeries.dataPoints[j].color = '#e82626';

                                    }

                                }

                            }

                        </script>

                    </div>

                </div>

            </div>

        </div>

    </div>

    

</div>