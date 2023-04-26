<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $pageTitle; ?></title>
    <link rel="icon"  href="<?php echo base_url(); ?>assets/dist/img/dolphin_logo.png">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" />
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#db5945">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" id="main-stylesheet" data-version="1.0.0" href="<?php echo base_url(); ?>assets/dist/styles/shards-dashboards.1.0.0.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/styles/extras.1.0.0.min.css">
   
    <link href="<?php echo base_url(); ?>assets/dist/css/style.css" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/material-components-web@6.0.0/dist/material-components-web.min.css" rel="stylesheet">
     <!-- FontAwesome 4.3.0 -->
     <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    
    <style>
     .error{
    		color:red;
    		font-weight: normal;
    	}
    </style>
    <script src="https://unpkg.com/material-components-web@6.0.0/dist/material-components-web.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>

	  <!--Custom functions -->
  	<script src="<?=base_url()?>assets/js/helper.js"></script>
    <!-- Loader Script -->
    <script>      
        function showLoader(){
            $(".custom_loader").addClass('active');
            $("#custom_loader_text").css('display','block');
        }
        function hideLoader(){
            $(".custom_loader").removeClass('active');
            $("#custom_loader_text").css('display','none');
        }
        $(document).ready(()=>{
            $("form").on('submit',(evt)=>{
                if($(evt.target).data('download_form')){
                    $.cookie('isDownloading', '1');
                    showLoader();
                    const intervalID = setInterval(() => {
                        if($.cookie('isDownloading')==0){
                            hideLoader();
                            clearInterval(intervalID);
                        }
                    }, 2000);                    
                }else if($(evt.target).data('no_loader')){
                }else{
                    showLoader();
                }
            });
            // $('.navLink').on('click',(evt)=>{
            //     hideLoader();
            //   }else{
            //     showLoader();
            //   }

            $("li.nav-item.show-loader > a[href*='<?=base_url()?>']").on('click',function(){
                showLoader();
            });
            $("#previousloader").on('click',function(){
                showLoader();
            });
            });

           
       
    </script>
    <!-- End of Loader Script -->
    <!-- Loader Style -->
    <style>
      /* Absolute Center Spinner */
      .custom_loader {
        position: fixed;
        z-index: 99999;
        height: 2em;
        width: 2em;
        overflow: visible;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
      }

      /* Transparent Overlay */
      .custom_loader.active:before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.3);
      }

      /* :not(:required) hides these rules from IE9 and below
      .custom_loader.active:not(:required) {
        font: 0/0 a;
        color: transparent;
        text-shadow: none;
        background-color: transparent;
        border: 0;
      } */

      .custom_loader.active:not(:required):after {
        content: '';
        display: block;
        font-size: 40px;
        width: 0.4em;
        height: 0.4em;
        margin-top: -0.5em;
        -webkit-animation: spinner 1500ms infinite linear;
        -moz-animation: spinner 1500ms infinite linear;
        -ms-animation: spinner 1500ms infinite linear;
        -o-animation: spinner 1500ms infinite linear;
        animation: spinner 1500ms infinite linear;
        border-radius: 0.5em;
        -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        box-shadow: rgba(26, 26, 255, 1) 1.5em 0 0 0, rgba(64, 255, 0, 1) 1.1em 1.1em 0 0, rgba(26, 26, 255, 1) 0 1.5em 0 0, rgba(64, 255, 0, 1) -1.1em 1.1em 0 0, rgba(26, 26, 255, 1) -1.5em 0 0 0, rgba(64, 255, 0, 1) -1.1em -1.1em 0 0, rgba(26, 26, 255, 1) 0 -1.5em 0 0, rgba(64, 255, 0, 1) 1.1em -1.1em 0 0;
      }

      /* Animation */

      @-webkit-keyframes spinner {
        0% {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
      @-moz-keyframes spinner {
        0% {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
      @-o-keyframes spinner {
        0% {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
      @keyframes spinner {
        0% {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }
    </style>
    <!-- End of Loader Style --> 

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  </head>
  <body class="hold-transition skin-blue sidebar-mini" style="overflow-x: hidden;">    
    <div class="custom_loader"><span id="custom_loader_text" style="color:#0000ff;font-weight:bold;margin-left: -100%;font-size: 17px;display:none;">Loading...</span></div>
		<div class="wrapper">
        <a href="<?php echo base_url(); ?>" class="logo">
        <div class="container-fluid">
        <div class="row">
        <!-- Main Sidebar -->
        <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
          <div class="main-navbar">
            <nav class="navbar align-items-stretch navbar-light bg-white  flex-md-nowrap border-bottom p-0">
              <a class="navbar-brand w-100 mr-0 pt-1" href="#" style="line-height: 25px;">
                <div class="d-table m-auto mobile_display_none">
                  <img id="main-logo" class="d-inline-block mr-1" style="max-width: 50px;" src="<?php echo base_url(); ?>assets/dist/img/dolphin_logo.png" alt="logo">
                  <h5 class="d-none d-md-inline title_sidenav"><span class="title_green">School</span><span class="title_blue">phins</span></h5>
                  <!-- mobile view -->
                </div>
                <div class="m-auto sidebar_mobile_view">
                  <img id="main-logo" class="d-inline-block logo_mobile_view mr-0" style="max-width: 50px;" src="<?php echo base_url(); ?><?php echo INSTITUTION_LOGO; ?>" alt="logo">
                  <h4 class="d-md-inline title_mobile_view"><?php echo SUB_TITLE; ?></h4>
                </div>
              </a>
              <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
              </a>
            </nav>
          </div>
          <!-- <div class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
            
          </div> -->
          <div class="nav-wrapper">
            <ul class="nav flex-column">
              <li class="nav-item show-loader">
                <a class="nav-link" id="dashboard" href="<?php echo base_url(); ?>dashboard">
                  <i class="fa fa-dashboard"></i>
                  <span>Dashboard</span>
                </a>
              </li>
              <?php if($_SESSION['joined_status'] == true){ ?>
              <li class="nav-item show-loader">
                <a class="nav-link " href="<?php echo base_url(); ?>getFeePaymentInfo">
                    <i class="fas fa-rupee-sign"></i>
                    <span>Fee Paid Info</span>
                </a>
            </li>
              <?php } ?>
             <?php if($_SESSION['submission_status'] == false){ ?> 
              <li class="nav-item show-loader" id="studentPersonalLink">
                <a class="nav-link" href="<?php echo base_url(); ?>viewPersonalDetail">
                  <i class="fa fa-check-circle-o fa-5x admission_icons" id="icons"></i>
                  <span>Personal Details</span>
                </a>
              </li>
              <li class="nav-item show-loader" id="schoolDetailLink">
                <a class="nav-link" href="<?php echo base_url(); ?>viewSchoolDetail">
                  <i class="fa fa-check-circle-o fa-5x admission_icons" aria-hidden="true" id="icon"></i>
                  <span>Academic Details</span>
                </a>
              </li>
              <li class="nav-item show-loader" id="combinationDetailLink">
                <a class="nav-link" href="<?php echo base_url(); ?>viewCombinationDetail">
                  <i class="fa fa-check-circle-o fa-5x admission_icons" aria-hidden="true" id="combination"></i>
                  <span>Combination and Language</span>
                </a>
              </li>
              <!-- <li class="nav-item show-loader" id="paymentDetail">
                <a class="nav-link" href="<?php echo base_url(); ?>paymentDetail">
                  <i class="fa fa-check-circle-o fa-5x admission_icons" aria-hidden="true" id="payment"></i>
                  <span>Payment</span>
                </a>
              </li> -->
              
              <li class="nav-item show-loader">
                <a class="nav-link " href="<?php echo base_url(); ?>profile">
                <i class="material-icons">&#xE7FD;</i>
                  <span>Profile</span>
                </a>
              </li>
    <?php } ?>
              <li class="nav-item">
                <a href="#support" data-toggle="collapse" aria-expanded="false"
                    class="nav-link  dropdown-toggle">
                    <i class="material-icons">headset_mic</i>
                    <span>Support</span>
                </a>
                <ul class="collapse list-unstyled ml-3" id="support">
                    <!-- <li class="nav-item show-loader">
                        <a class="nav-link" href="<?php echo base_url(); ?>helpGuide">
                            <i class="far fa-question-circle"></i>
                            <span>Help Guide</span>
                        </a>
                    </li> -->
                    <li class="nav-item show-loader">
                        <a class="nav-link" href="<?php echo base_url(); ?>contactUs">
                            <i class="fa fa-phone"></i>
                            <span>Grievance</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item show-loader">
                        <a class="nav-link navLink" target="_blank" href="<?php echo base_url(); ?>assets/downloads/FAQ.pdf">
                            <i class="fa fa-question-circle"></i>
                            <span>FAQ</span>
                        </a>
                    </li> -->
                </ul>
              </li>
            </ul>
          </div>
        </aside>
        <!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <!-- <div id="coverScreen" class="loaderScreen text-center">
            <img height="90" src="assets/images/loader.gif" alt="">
          </div> -->
        <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->
            <nav class="navbar align-items-stretch navbar-light heading_bg flex-md-nowrap p-0">
              <form action="#" class="main-navbar__search w-100 d-none d-md-flex d-lg-flex">
                <div class="input-group input-group-seamless ml-3">
                  <img class="mt-1" src="<?php echo base_url(); ?><?php echo INSTITUTION_LOGO; ?>" height="50px">
                  <h4 class="head_title ml-2 mt-2"><?php echo TITLE; ?></h4>
                </div>
              </form>
              <ul class="navbar-nav border-left flex-row header-nav ">
              <li class="nav-item border-right dropdown notifications">
                  <a title="Click here to help guide" class="nav-link nav-link-icon text-center" target="_blank" href="<?php echo base_url(); ?>assets/downloads/STUDENT_GUIDE_FOR_ONLINE_ADMISSION_PROCEDURE_2022.pdf" role="button" id="dropdownMenuLink" >
                    <div class="nav-link-icon__wrapper">
                      <i class="material-icons">help</i>
                    </div>
                  </a>
                 
                </li> 

                <li class="nav-item border-right dropdown notifications">
                  <a class="nav-link nav-link-icon text-center" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="nav-link-icon__wrapper">
                      <i class="material-icons">access_time</i>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-small" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#">
                      <div class="notification__icon-wrapper">
                        <div class="notification__icon">
                          <i class="material-icons">access_time</i>
                        </div>
                      </div>
                      <div class="notification__content">
                        <span class="notification__category">Last Login:</span>
                        <p><?= empty($last_login) ? "First Time Login" : date('d-m-Y h:m:s A',strtotime($last_login)); ?></p>
                      </div>
                    </a>
                  </div>
                </li> 
                <!-- <li class="nav-item border-right dropdown notifications">
                  <a class="nav-link nav-link-icon text-center" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="nav-link-icon__wrapper">
                    <i class="material-icons">notifications</i>
                    <?php 
                    $i = 1;
                    if(!empty($notificationMsg)){
                      foreach($notificationMsg as $notification){ 
                        ?>
                      <span class="badge badge-pill badge-danger"><?php echo $i++; ?></span>
                      <?php  } } ?>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-small" aria-labelledby="dropdownMenuLink">
                    <?php 
                      if(!empty($notificationMsg)){
                      foreach($notificationMsg as $notification){ ?>
                      <a class="dropdown-item" href="#">
                        <div class="notification__icon-wrapper">
                          <div class="notification__icon">
                            <i class="material-icons">notifications_active</i>
                          </div>
                        </div>
                        <div class="notification__content mt-1">
                          <b><?php echo $notification->message; ?></b>
                        </div>
                      </a>
                    <?php }  }else{ ?>
                      <a class="dropdown-item" href="#">
                        <div class="notification__icon-wrapper">
                          <div class="notification__icon">
                            <i class="material-icons">notifications_none</i>
                          </div>
                        </div>
                        <div class="notification__content mt-2">
                          <b class="">Today No Announcement</b>
                        </div>
                      </a>
                    <?php } ?>
                  </div>
                </li> -->
                <li class="nav-item dropdown nav-profile">
                  <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown"
                      href="#" role="button" aria-haspopup="true" aria-expanded="false">
                      <img class="user-avatar rounded-circle mr-2" src="<?php echo base_url(); ?>assets/dist/img/user.png" alt="User Avatar" />
                      
                      <span class="d-none d-md-inline-block"><?php echo substr($name, 0, 5); ?></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-small dropdown-margin">
                    <div class="row  user-header text-center">
                      <div class="col-12 col-lg-12 ">
                        <img class=" rounded-circle text-center "
                            src="<?php echo base_url(); ?>assets/dist/img/user.png" alt="User Avatar"
                            height="100" width="100">
                        
                        <p class="mb-1"> <?php echo $mobile; ?></p>
                        <span style="font-size:12px;"> </span>
                      </div>
                    </div>
                    <hr class="mt-0 mb-1">
                    <!-- Menu Footer-->
                    <div class="row user-footer ">
                      <div class="col-12 col-lg-12 ">
                        <a href="<?php echo base_url(); ?>profile"
                            class="btn  btn-primary profile-btn pull-left "><i
                                class="fa fa-user-circle"></i> Profile</a>
                        <a href="<?php echo base_url(); ?>logout"
                            class="btn btn-danger signout-btn  pull-right"><i
                                class="fa fa-sign-out"></i> Sign out</a>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
              <nav class="nav">
                <a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
                  <i class="material-icons">&#xE5D2;</i>
                </a>
              </nav>
            </nav>
          </div>

        <!-- ChatBot -->
        <!-- <link href="<?=base_url()?>assets/chatbot/helper.css" rel="stylesheet">
        <script src="<?=base_url()?>assets/chatbot/helper.js"></script>        
        <div id="chatWidgetRoot" class="isClosed">
            <div class="chatWidgetContainer">
                <div class="chatWidgetHeader">
                    <div class="chatWidgetHeaderTitle">CHATBOT</div>
                    <div>
                        <svg id="chatCloseButton" fill="#FFFFFF" height="15" viewBox="0 0 15 15" width="15" xmlns="http://www.w3.org/2000/svg" style="margin-right: 15px; margin-top: 6px; vertical-align: middle;">
                            <line x1="1" y1="15" x2="15" y2="1" stroke="white" stroke-width="1"></line>
                            <line x1="1" y1="1" x2="15" y2="15" stroke="white" stroke-width="1"></line>
                        </svg>
                    </div>
                </div>
                <div class="chatWidgetBody">
                    <iframe src="<?=base_url()?>chatBot/chat" width="100%" height="100%" frameborder="0" allowtransparency="true" style="background-color: transparent;">
                    </iframe>
                </div>
            </div>
        </div>        
        <div id="chatOpenButton" class="floating-button floating-button isOpened">
            <i class="far fa-comments"></i>
        </div>         -->
        <!-- End of ChatBot -->	