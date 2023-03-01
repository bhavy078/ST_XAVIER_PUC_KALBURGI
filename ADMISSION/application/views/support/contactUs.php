<style>
    <?php if($_SESSION['application_number_status'] == false){ ?> 
        .main-sidebar .nav #schoolDetailLink, .main-sidebar .nav #combinationDetailLink,
        .main-sidebar .nav #documentDetail, .main-sidebar .nav #paymentDetail{
            display: none !important;
        }

    <?php } 
        if($_SESSION['application_number_status'] == true){ ?> 
            #icons, #icon, #combination, #document, #payment{
            color: #008000 !important;
            }
    <?php } ?>
</style>

</style>
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
<div class="row ">
    <div class="col-12">
        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
    </div>
</div>

<div class="main-content-container container-fluid px-2">
    <div class="card card-small p-0  col-12">
        <div class="card-header p-2 card_head_dashboard">
            <span class="page-title">
                <div class="row font_color">
                    <div class="col-12">
                        <i class="fa fa-phone"></i> Grievance (Only For technical issues)    
                     <!-- <span class="float-right text-dark"><a href="#" title="Help Guide" data-toggle="popover" data-trigger="focus" data-content="Some content"><i class="far fa-question-circle"></i></a></span> -->
                    </div>
                </div>
            </span>
        </div>

        <div class="card-body m-1">
            <form role="form" method="post" action="<?php echo base_url(); ?>saveContactInfo">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="subject mdc-text-field mdc-text-field--filled ">
                                <span class="mdc-text-field__ripple"></span>
                                <input name="subject" id="subject" class="mdc-text-field__input"  type="text" aria-labelledby="my-label-id"  maxlength="100" autocomplete="off" required>
                                <span class="mdc-floating-label" id="my-label-id">Subject</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="message mdc-text-field mdc-text-field--filled mdc-textfield--multiline">
                                <span class="mdc-text-field__ripple"></span>
                                <textarea type="text" id="message" class="mdc-text-field__input" rows="4"  name="message" value="" autocomplete="off"required></textarea>
                                <span class="mdc-floating-label" id="my-label-id">Message</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <button class="mdc-button mdc-button--raised button_success float-right mt-2" type="submit">
                    <span class="mdc-button__label">Submit</span>
                </button>
            </form>
        </div>
            
    </div>
</div>


<script>
    mdc.textField.MDCTextField.attachTo(document.querySelector('.message'));
    mdc.textField.MDCTextField.attachTo(document.querySelector('.subject'));

    $(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
    });
</script>