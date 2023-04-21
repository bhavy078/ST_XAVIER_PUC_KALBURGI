<style>
.select2-container .select2-selection--single {
    height: 38px !important;
    width: 360px !important;
}


.form-control {
    border: 1px solid #000000 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    margin-top: 3px !important;
    color: black !important;

}

@media screen and (max-width: 480px) {
    .select2-container--default .select2-selection--single .select2-selection__arrow {

        margin-right: 20px !important;
    }

    .select2-container .select2-selection--single {
        width: 270px !important;
    }
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous"></script>
<?php
$this->load->helper('form');
$error = $this->session->flashdata('error');
if ($error) { 
    ?>
<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <i class="fa fa-check mx-2"></i>
    <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
</div>
<?php } ?>
<?php
        $success = $this->session->flashdata('success');
        if ($success) { 
        ?>
<div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <i class="fa fa-check mx-2"></i>
    <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
</div>
<?php }?>
<div class="row column_padding_card">
    <div class="col-md-12">
        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
    </div>
</div>
<div class="main-content-container px-3 pt-1 overall_content">
    <div class="content-wrapper">
        <div class="row p-0 column_padding_card">
            <div class="col column_padding_card">
                <div class="card card-small card_heading_title p-0 m-b-1">
                    <div class="card-body p-2">
                        <div class="row c-m-b">
                            <div class="col-lg-7 col-12 col-md-7 box-tools">
                                <span class="page-title">
                                    <i class="material-icons">send</i> Notification
                                </span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-12 text-right">
                             <a onclick="window.history.back();"
                                    class="btn primary_color mobile-btn float-right text-white"
                                    value="Back"><i class="fa fa-arrow-circle-left"></i> Back </a>                     
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-0 column_padding_card">
            <div class="col column_padding_card">
                <div class="card card-small mb-4">
                    <div class="card-body p-1 pb-2 text-center ">
                        <!-- <form method="POST" action="<?php echo base_url() ?>push_notification/sendNotification" id="formSmsBulk"> -->
                        <?php echo form_open_multipart('push_notification/sendNotification',array('id' => 'pn_form'));?>

                            <div id="errorMsg"></div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="card text-center">
                                        <div class="card-header bg-info h4 text-black">
                                        Choose send options
                                        </div>
                                        <div class="card-body" style="padding-bottom:10px;">
                                            <div class="row">
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12">
                                                    <label style="float:left" for="user_name_select">Select By User</label>
                                                    <select class="form-control" name="user_name" id="user_name_select"
                                                        required>                                                       
                                                        <option value="student" selected>Student</option>
                                                        <option value="selectedStud">Selected Student</option>
                                                        <option value="staff">Staff</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 for-student">
                                                    <label style="float:left" for="term_name_select">Select By Term</label>
                                                    <select class="form-control" name="term_name" id="term_name_select">                
                                                        <option value="ALL">ALL</option>                                                       
                                                        <option value="I PUC">I PUC</option>
                                                        <option value="II PUC">II PUC</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 for-student">
                                                    <label style="float:left"  for="stream_name_select">Select By Stream</label>
                                                    <select data-live-search="true" class="form-control"
                                                        name="stream_name" id="stream_name_select">
                                                        <option value="ALL">ALL</option>
                                                        <?php 
                                                            if(!empty($streams)){
                                                                foreach ($streams as $stream)
                                                                {
                                                                    echo "<option value='".$stream->stream_name."'>".$stream->stream_name."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>                                            
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 for-student">
                                                    <label style="float:left"  for="section_name_select">Select By Section Name</label>
                                                    <select data-live-search="true" class="form-control"
                                                        name="section_name" id="section_name_select">
                                                        <option value="ALL">ALL</option>
                                                        <?php 
                                                            if(!empty($sections)){
                                                                foreach ($sections as $section)
                                                                {
                                                                    echo "<option value='".$section->section_name."'>".$section->section_name."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 for-selected-student">
                                                    <label style="float:left"  for="student_select">Select By Student</label>
                                                    <select data-live-search="true" class="form-control selectpicker"
                                                        name="student_id[]" id="student_name_select" multiple>
                                                        <!-- <option value="ALL">ALL</option> -->
                                                        <?php 
                                                            if(!empty($allStudentInfo)){
                                                                foreach ($allStudentInfo as $stud)
                                                                {
                                                                    echo "<option value='".$stud->student_id."'>".$stud->student_id." - ".$stud->student_name." - ".$stud->term_name." - ".$stud->stream_name." - ".$stud->section_name."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="card text-center">
                                        <div class="card-header bg-info h4 text-black">
                                            Message
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                <label style="float:left" for="msg_subject">Subject</label>
                                                <input class="form-control" name="msg_subject" id="msg_subject" autocomplete="off" placeholder="Write subject here" value="<?= set_value('msg_subject') ?>" required> 
                                            </div>  
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12">  
                                                <label style="float:left" for="message">Message</label>                                                           
                                                <textarea class="form-control" rows="6"
                                                    placeholder="Write messages here...(Messages above 159 characters will be sent as 2 texts)"
                                                    id="message" name="message" required ><?= set_value('message') ?></textarea>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                <label style="float:left" for="msg_subject">Upload a File</label>
                                                <input class="form-control" type="file"  accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" name="msg_file" id="msg_file" > 
                                            </div> 
                                            <div class="form-group col-lg-12 col-md-12 col-sm-12" style="padding-bottom:30px;">
                                                <div class="form-check">
                                                    <input style="float:left" type="checkbox" class="form-check-input" id="send_email_option" name="send_email_option">
                                                    <label style="float:left" class="form-check-label" for="send_email_option">Send email also</label>
                                                </div>
                                            </div>
                                            <input  type="submit" class="btn btn-success font-weight-bold btn-block" value="Send" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function studentSetUp(){
    $('.for-student').show();
    $("#term_name_select").prop('required',true);
}
function clearStudentSetUp(){
    $('.for-student').hide();
    $("#term_name_select").prop('required',false);
    $("#term_name_select").val("ALL");
    $("#stream_name_select").val("ALL");
    $("#section_name_select").val("ALL");
}
    jQuery(document).ready(function() {
        $('.for-selected-student').hide();
        if($("#user_name_select").val()=="staff"){
            clearStudentSetUp();
        }        
        $("#user_name_select").on('change', function() {
            if($(this).val()==="staff"){
                clearStudentSetUp();
                $('.for-selected-student').hide();
            }else if($(this).val()==="selectedStud"){
                clearStudentSetUp();
                $('.for-selected-student').show();
            }else{
                studentSetUp();
                $('.for-selected-student').hide();
            }
        });    
    });
</script>