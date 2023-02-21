<!DOCTYPE html>
<html>
    <head>
      <meta charset="UTF-8">
      <title><?php echo TAB_TITLE; ?> : New Registration</title>
      <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
      <!-- icons -->
      
      <link rel="icon" href="<?php echo base_url(); ?>assets/dist/img/dolphin_logo.png"> 
        <link rel="apple-touch-icon" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon" sizes="57x57" href="images/ico/apple-touch-icon-57-precomposed.png">
        
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" id="main-stylesheet" data-version="1.0.0" href="<?php echo base_url(); ?>assets/dist/styles/shards-dashboards.1.0.0.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/styles/extras.1.0.0.min.css">
      
        <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
       <link href="<?php echo base_url(); ?>assets/dist/css/style.css" rel="stylesheet" type="text/css" />
        <!-- FontAwesome 4.3.0 -->
        <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="https://unpkg.com/material-components-web@6.0.0/dist/material-components-web.min.css" rel="stylesheet">
        <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
        </script>
    </head>
    <body class="login-page">
      <div class="loader">
        <!-- <img id="loader_img" src="<?php echo base_url(); ?><?php echo LOADER_IMG; ?>" class="img-fluid" alt="loader"> -->
      </div>
      <div class="row margin_left_right_null">
        <div class="card mx-auto registration_card">
          <div class="card-header pb-0 pt-2 bottom_color" >
            <div class="col-xs-12 text-center">
              <h6 class="mb-2 font_color"><b><?php echo CARD_TITLE;?></b>
                <span class="float-right mb-1">
                  <a href="#" title="REGISTRATION <i class='far fa-question-circle'></i>" data-toggle="popover" data-trigger="focus" data-content="1. Applicants name and date of birth must be strictly as per 10th standard records. All fields marked with * are compulsory. <br/> I. Applicant’s name (Same will appear in the application form by default) <br/> II. Date of Birth (Same will appear in the application form by default) <br/> 2. All official communications will be sent to this email id."><span class="badge badge-primary">Help <i class="far fa-question-circle"></i></span></a>
                </span>
              </h6>
            </div>
          </div>
          <div class="card-body pt-1 pb-2">
            <div class="col-xs-12 text-center">
              <img src="<?php echo base_url(); ?><?php echo INSTITUTION_LOGO; ?> " height="70px">
            </div>
            <div class="col-xs-12 text-center mb-0">
              <span><b style="font-size: 25px;"><?php echo SUB_TITLE;?></b></span>
            </div>
            <?php $this->load->helper('form'); ?>
            <div class="row">
                <div class="col-md-12">
                    <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                </div>
            </div>
            <?php
            $this->load->helper('form');
            $warning = $this->session->flashdata('warning');
            $error = $this->session->flashdata('error');
            $send = $this->session->flashdata('send');
            $notsend = $this->session->flashdata('notsend');
            $unable = $this->session->flashdata('unable');
            $invalid = $this->session->flashdata('invalid');
            if($warning)
            {
                ?>
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('warning'); ?>                    
                </div>
            <?php }
            else if($error)
            {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
            <?php }

            if($send)
            {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $send; ?>                    
                </div>
            <?php }

            if($notsend)
            {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $notsend; ?>                    
                </div>
            <?php }
            
            if($unable)
            {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $unable; ?>                    
                </div>
            <?php }

            if($invalid)
            {
                ?>
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $invalid; ?>                     
                </div>
            <?php } ?>
            <form action="<?php echo base_url(); ?>studentRegistrationToDB " method="post" id="newUserRegistration">

           
              <div class="form-group"> 
                <label class="name mdc-text-field mdc-text-field--filled ">
                  <span class="mdc-text-field__ripple"></span>
                   <input type="text" style="text-transform: uppercase;" onkeydown="return alphaOnly(event)" name="name" id="name" class="mdc-text-field__input text-capitalize" aria-labelledby="my-label-id"  autocomplete="off" required/>
                  <span class="mdc-floating-label" id="my-label-id">Full Name (As per 10th standard records)</span>
                  <span class="mdc-line-ripple"></span>
                </label>
              </div>

              <div class="form-group"> 
                 <label class="dob mdc-text-field mdc-text-field--filled">
                  <span class="mdc-text-field__ripple"></span>
                   <input name="dob" id="dob" class="mdc-text-field__input datepicker" type="text" aria-labelledby="my-label-id" autocomplete="off"  required/>
                  <span id="my-label-id datePicker" class="mdc-floating-label">Date of Birth</span>
                  <span class="mdc-line-ripple"></span>
                </label>
              </div>
              <div class="form-group">
                <label class="mobile mdc-text-field mdc-text-field--filled ">
                    <span class="mdc-text-field__ripple"></span>
                     <input type="tel" pattern="[0-9]*" maxlength="10" name="mobile" id="mobile" class="mdc-text-field__input" onkeypress="return isNumber(event)" aria-labelledby="my-label-id" autocomplete="off" required/>
                    <span class="mdc-floating-label" id="my-label-id">Mobile Number</span>
                    <span class="mdc-line-ripple"></span>
                  </label>
              </div>

              <div class="form-group"> 
                <label class="email mdc-text-field mdc-text-field--filled ">
                  <span class="mdc-text-field__ripple"></span>
                   <input type="email"  name="email" id="email" class="mdc-text-field__input"  aria-labelledby="my-label-id" autocomplete="off" required/>
                  <span class="mdc-floating-label" id="my-label-id">Email</span>
                  <span class="mdc-line-ripple"></span>
                </label>
              </div>

              <div class="form-group">
                <span class="float-right mt-1"><a href="#" title="Help <i class='far fa-question-circle'></i>" data-toggle="popover" data-trigger="focus" data-content="State Board/OTHER: Enter Hall Ticket Number <br/> ICSE: Enter Roll Number <br/> CBSE: Enter Unique Register Number"><span class="badge badge-primary">Help <i class="far fa-question-circle"></i></span></a></span>
                <label class="registration_number mdc-text-field mdc-text-field--filled">
                  <span class="mdc-text-field__ripple"></span>
                   <input type="text" name="registration_number" id="registration_number" class="mdc-text-field__input" maxlength="30" aria-labelledby="my-label-id" autocomplete="off" required/>
                  <span class="mdc-floating-label" id="my-label-id">10th/Unique Registration (Hall Ticket) Number</span>
                  <span class="mdc-line-ripple"></span>
                </label>
              </div>

              <div class="form-group">
                <div class="mdc-select mdc-select-board mdc-select--required">
                  <div class="mdc-select__anchor demo-width-class" aria-required="true">
                    <span class="mdc-select__ripple"></span>
                    <input type="text"  class="mdc-select__selected-text" name="sslc_board_name" id="sslc_board_name" value="" required>
                    <i class="mdc-select__dropdown-icon"></i>
                    <span class="mdc-floating-label">10th Board Name</span>
                    <span class="mdc-line-ripple"></span>
                  </div>
                  <div class="mdc-select__menu mdc-menu mdc-menu-surface demo-width-class">
                    <ul class="mdc-list">
                      <!-- <li class="mdc-list-item" data-value="" >
                        <span class="mdc-list-item__text">
                          Select Board Name
                        </span>
                      </li> -->
                      <?php if(!empty($boardInfo)){
                        foreach($boardInfo as $board){  ?>
                          <li class="mdc-list-item" data-value="<?php echo $board->row_id; ?>">
                            <span class="mdc-list-item__text">
                              <?php echo $board->board_name; ?>
                            </span>
                          </li>
                      <?php } } ?>
                    </ul>
                  </div>
                </div>
            </div>
              
              <div class="form-group other_board_name_text">
                 <label class="other_board_name mdc-text-field mdc-text-field--filled ">
                  <span class="mdc-text-field__ripple"></span>
                   <input type="text" name="other_board_name" id="other_board_name" class="mdc-text-field__input" maxlength="10" style="text-transform: uppercase;" aria-labelledby="my-label-id" autocomplete="off">
                  <span class="mdc-floating-label" id="my-label-id">Other Board Name</span>
                  <span class="mdc-line-ripple"></span>
                 </label>
              </div>

              <div class="form-group">
                <label class="password mdc-text-field mdc-text-field--filled ">
                  <span class="mdc-text-field__ripple"></span>
                   <input type="password"  name="password" id="password" class="mdc-text-field__input" aria-labelledby="my-label-id" onkeyup="checkPass(); return false;" autocomplete="off" required/>
                  <span class="mdc-floating-label" id="my-label-id">New Password</span>
                  <span class="mdc-line-ripple"></span>
                </label>
              </div>
            
              <div class="form-group">

                <label class="cpassword mdc-text-field mdc-text-field--filled ">
                  <span class="mdc-text-field__ripple"></span>
                   <input type="password"  name="cpassword" id="cpassword" class="mdc-text-field__input equalTo" aria-labelledby="my-label-id" autocomplete="off" required/>
                  <span class="mdc-floating-label" id="my-label-id">Confirm Password</span>
                  <span class="mdc-line-ripple"></span>
                </label>
              </div>
              <div class="row mt-2">
                <div class="col-sm-6  col-md-12">
                  <button class="mdc-button mdc-button--raised btn_submit btn-block" type="submit">
                    <span class="mdc-button__label"> Register </span>
                  </button>
                 </div>
              </div>

              <div class="row">
                <div class="col-sm-6 col-md-6 ">
                  <a target="_blank" href="<?php echo base_url() ?>assets/downloads/student_SJPUC_UserGuide.pdf" class="float-left" style="margin-top: 10px;">Click here for help <i class="far fa-question-circle"></i></a><br>
                </div>
                <div class="col-sm-6 col-md-6 ">
                  <a href="<?php echo base_url() ?>" class="float-right" style="margin-top: 10px;">Back to Login</a><br>
                </div>
            </div>
         
          </form>
           </div>

          <div class="card-footer py-2">
            <div class="col-xs-12 text-center py-1">
              <span class="">&copy;<script>document.write(new Date().getFullYear())</script>-<?php echo date('y')+1; ?> <a href="javascript:void(0);"><span class="title_green">School</span><span class="title_blue">phins</span></a> The Wings of an Education.</span>
            </div>
          </div>
        </div>
      </div>

    </body>
</html>
    <script src="<?php echo base_url(); ?>assets/js/forgotPassword.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.9.0/additional-methods.js" integrity="sha256-PxEJjwsgsA8v2qW3s/uSv5J00Yw6DQozL54XRIHcGmY=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/shards-ui@latest/dist/js/shards.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/scripts/extras.1.0.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/scripts/shards-dashboards.1.0.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
    <script src="https://unpkg.com/material-components-web@6.0.0/dist/material-components-web.min.js"></script>

<script>

mdc.textField.MDCTextField.attachTo(document.querySelector('.name'));
mdc.textField.MDCTextField.attachTo(document.querySelector('.mobile'));
const date_picker = mdc.textField.MDCTextField.attachTo(document.querySelector('.dob'));
$(date_picker.input_).on('change',()=>{
  if(date_picker.value) date_picker.valid = true;
  else date_picker.valid = false;
  date_picker.focus();
});

mdc.textField.MDCTextField.attachTo(document.querySelector('.email'));
mdc.textField.MDCTextField.attachTo(document.querySelector('.other_board_name'));
mdc.textField.MDCTextField.attachTo(document.querySelector('.registration_number'));
mdc.textField.MDCTextField.attachTo(document.querySelector('.password'));
mdc.textField.MDCTextField.attachTo(document.querySelector('.cpassword'));
const board_name = mdc.select.MDCSelect.attachTo(document.querySelector('.mdc-select-board'));
board_name.listen('MDCSelect:change', () => {
  if(board_name.value == 4){
    $('.other_board_name_text').show();
  }else{
    $('.other_board_name_text').hide();  
  }
});



$(document).ready(function(){
  // $('[data-toggle="popover"]').popover();  
  $('[data-toggle="popover"]').popover( { "container":"body", "trigger":"focus", "html":true });
  $('[data-toggle="popover"]').mouseenter(function(){
      $(this).trigger('focus');
  }); 
});

  jQuery(document).ready(function(){
    // $('#dob').on('change',function(){
    //   // $(this).removeClass('mdc-floating-label');
    //   // $('#datePicker').addClass('mdc-floating-label--float-above');
    // });

    $('.other_board_name_text').hide(); 

    jQuery('.datepicker').datepicker({
      autoclose: true,
      format : "dd-mm-yyyy",
      endDate: "31/12/2010"
    });

    
    //onchange board name
    $("#sslc_board_name").on('change',function(){
      if(this.value == 4){
        $('.other_board_name_text').show();
      }else{
        $('.other_board_name_text').hide();  
      }
    });
  });

 $(function() {
    $(this).bind("contextmenu", function(e) {
        e.preventDefault();
    });
  }); 

  $(window).on("load", function() {
    preloaderFadeOutTime = 500;
    function hidePreloader() {
      var preloader = $('.loader');
      preloader.fadeOut(preloaderFadeOutTime);
    }
    hidePreloader();
  });
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function alphaOnly(event) {
    var key = event.keyCode;
    return ((key >= 65 && key <= 90) || key == 8 || key == 32);
};
</script>   
