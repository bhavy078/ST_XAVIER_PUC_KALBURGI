<meta name="viewport" content="width=device-width, initial-scale=1.0">

<html>
    <title> Registration Success Page</title>
    <head>
        <link rel="stylesheet" id="main-stylesheet" data-version="1.0.0" href="<?php echo base_url(); ?>assets/dist/styles/shards-dashboards.1.0.0.min.css">
        <link href="<?php echo base_url(); ?>assets/dist/css/style.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Sweet Alert -->
        <script src="<?=base_url()?>assets/plugins/sweetalert/sweetalert.min.js"></script>
        <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    </head>
    <body style="background: #819194 !important;"></body>
</html>
<script>
  $(document).ready(()=>{
    let year = "<?=date('Y')."-"; ?>";
    year += ''+"<?=date('y')+1; ?>"
    Swal.fire({
        title: '<b class="title_blue" style="margin-top: -15px !important;">SXPUC<b>',
        html: '<h4>Thank You</h4><b><span style="color:black;font-size:26px">Successfully Registered <br>Please Login and fill the Application Form</span></b<',
        imageUrl: '<?=base_url().INSTITUTION_LOGO; ?>',
        imageHeight: 120,
        imageWidth: 120,
        imageAlt: 'College Logo',
        confirmButtonText: 'Login',
        backdrop: false,
        //showCancelButton: true,
        // cancelButtonColor: '#d33',
        footer: '<b>&copy;'+ year +'<a href="javascript:void(0);"><span class="title_green"> School</span><span class="title_blue">phins</span></a> The Wings of an Education.</b>'
    }).then(result=>{
        console.log(result);
        if (result.isConfirmed) {
            location.href = "<?=base_url()?>"
        }
    })
  });
</script>