<style>
    .break {
        page-break-before: always;
    }

    .break_after {
        page-break-before: none;
    }

    table {
        width: 100% !important;
    }

    div.a {
       
  border-width: 4px;
  border-color: black;
  border-style: double;
}
    /* u {
        border-bottom: 2px dotted #00000;
        text-decoration: none;
        font-weight: bold;
        font-family: timesnewroman;
        font-size: 18px;
    } */

    /*.border{
    border: 2px solid black;
}*/
    .border_full {
        border: 1px solid black;
        /* height: 90% !important; */
    }

    .border_bottom {
        border-bottom: 1px solid black;
    }

    .hr_line {
    
        color: black;
    }

    .table_bordered {
        border-collapse: collapse;
    }

    .table_bordered th,
    .table_bordered td {
        border-top: 1px solid black;
        border-right: 1px solid black;
        padding: 3px;
    }

    .table_bordered th .border_right_none,
    .table_bordered td .border_right_none {
        border-right: 1px solid transparent !important;
    }

    u {    
  
    text-decoration: underline;
}


    div.oval {
        border: 1px solid black;
  width: 30%;
  text-align: center;
  border-radius: 80px / 30px;

}


</style>

<?php

$totalCount = count($studentInfo);
foreach ($studentInfo as $std) {
   
    $totalCount--;

?>

    <div class="container-fluid a" style="padding-right:30px; padding-left:30px;">
        <div class="row">

            <table class="table" style="">
                <tr>
                    <th class="text-center" style="border-right: 1px solid white !important;">
                       <br/> <img src="<?php echo base_url(); ?>assets/dist/img/logo_stxpuc.jpg" width="80" height="70" alt="PHOTO" class="shcm_logo" />
                    </th>
                    <td style="font-size:17pt" class="text-center"><b>&emsp;&ensp;&emsp;&ensp;ST XAVIER'S PRE-UNIVERSITY COLLEGE</b><br>
                        <p style="font-size:10pt">&emsp;&ensp;&emsp;&ensp;&emsp;&ensp;&emsp;&ensp;&emsp;&ensp;
                        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NH 50, SIRNOOR POST, FARHATABAD, KALABURAGI - 585308</b></p>
                        <!-- <p style="margin-top:-45px;">Phone No – 080-4101 0013</p> -->
                </tr>
                <tr>
                   
                </tr>
            </table>
            <!-- <hr style="color:black"> -->
        </div>
        <br>
        <div class="row">
            <table>
                <tr>
                    <td width="100%" style="text-align:center;font-size:16pt;font-family:times new roman;"> <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>STUDY CERTIFICATE</u><b></td>
                </tr>
            </table>
        </div>

        <div class = "row">
            <table>
                <tr>
                    <td width = "50%" style="text-align:left;font-size: 13pt;">Admission No <u><?php echo $std->admission_no?></u></td>       
                    <td width = "50%" style="text-align:right;font-size: 13pt;">Date <u><?php echo date('d-m-Y') ?></u></td>
                </tr>
            </table>
        </div>
        <div class="row">
        
            <p width="100%" style="text-align: justify;font-size: 13pt; font-family: times new roman;">This is to certify that <?php if(!empty($std->gender == "MALE")){ echo "Sri."; } else{ echo "Kum.";} ?>&nbsp;<u><?php echo strtoupper($std->student_name) ?></u>&nbsp;<?php if(!empty($std->gender == "MALE")){ echo "S/o"; } else{ echo "D/o";} ?> <u><?php echo strtoupper($std->father_name) ?></u>
                 was a bonafide student of this Institution/College during the year from <u><?php echo $std->college_from?></u> to <u><?php echo $std->college_to?></u> studying from <u><?php echo $std->classes_from?></u> to <u><?php echo $std->classes_to?></u>. During this studying period <?php if(!empty($std->gender == "MALE")){ echo "his"; } else{ echo "her";} ?>&nbsp; Character was found <?php echo $std->character_conduct?>. <?php if(!empty($std->gender == "MALE")){ echo "His"; } else{ echo "Her";} ?> Date of birth is <u><?php echo date('d-m-Y',strtotime($std->dob)) ?></u> as per <?php if(!empty($std->gender == "MALE")){ echo "his"; } else{ echo "her";} ?>&nbsp; Admission Register No <u><?php echo $std->student_id?></u>.
            </p>
            <!-- <p width="100%" style="text-align: left;font-size: 12pt; font-family: times new roman;"> 
            Admission Registration No. <?php echo $std->admission_no?>
            </p> -->
          <br>
        </div>
        <div class="row">
            <p style="text-align: justify;font-size: 13pt; font-family: times new roman;">Date: <u><?php echo date('d-m-Y')?></u></p>
            <p style="text-align: justify;font-size: 13pt; font-family: times new roman;line-height:0.8">Place : <u>Kalaburagi</u></p>

            <p style="text-align: left;font-size: 13pt; font-family: times new roman;line-height:0.8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> Head Master/Principal</b></p>
        </div>
    </div>
<?php
    if ($totalCount != 0) {
        echo '<div class="break"></div>';
    } else {
        echo '<div class="break_after"></div>';
    }
} ?>
<?php




?>