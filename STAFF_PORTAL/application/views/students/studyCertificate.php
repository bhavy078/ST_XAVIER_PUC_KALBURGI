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
    border-bottom: 1px dotted #000;
    text-decoration: none;
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
                    <td style="font-size:14pt" class="text-center">ST XAVIER'S PRE-UNIVERSITY COLLEGE<br>
                        <p style="font-size:8pt">&emsp;&ensp;NH 50, FARHATABAD POST, SIRNOOR KALABURAGI - 585308</p>
                        <!-- <p style="margin-top:-45px;">Phone No – 080-4101 0013</p> -->
                </tr>
                <tr>
                   
                </tr>
            </table>
            <!-- <hr style="color:black"> -->
        </div>
        <div class="row">
            <table>
                <tr>
                    <td width="100%" style="text-align:center;font-size:12pt;font-family:times new roman;;">PROFORMA FOR STUDY CERTIFICATE</td>
                </tr>
            </table>
        </div>
        <div class="row">
        <p style="font-size: 12px;text-align:right;">Date:<?php echo date('d-m-Y')?> </p>
            <p width="100%" style="text-align: justify;font-size: 14pt; font-family: times new roman;">This is to certify that 
            <?php if(!empty($std->gender == "MALE")){ echo 'Sri'; } else{ echo 'Kum';} ?><u>
                <?php echo strtoupper($std->student_name) ?>&nbsp;</u> 
                <?php if(!empty($std->gender == "MALE")){ echo "S/o"; } else{ echo "D/o";} ?>
                  <u>&nbsp;<?php if(!empty($std->father_name)){ echo strtoupper($std->father_name); } else{ echo strtoupper($std->mother_name);}?>&nbsp;</u> 
                has Studied from <u>&nbsp;<?php echo strtoupper($std->classes_from)?>&nbsp;</u> to <u>&nbsp;<?php echo strtoupper($std->classes_to)?>&nbsp;</u> <b>SCIENCE</b> in our institution from 
            <u>&nbsp;<?php echo $std->college_from?>&nbsp;</u> To <u>&nbsp;<?php echo $std->college_to?>&nbsp;</u> academic years.
                
            </p>
            <p width="100%" style="text-align: justify;text-indent: 50pt;font-size: 14pt; font-family: times new roman;"> The mother tongue of the candidate is <u>&nbsp;<?php echo $std->mother_tongue?>&nbsp;</u>as per the admission register of the institution.
	The above details are true and correct to the best of my knowledge.

            </p>
           <div class="oval">
            <p class="oval">Institution Seal</p></div>
            <p style="font-size: 14px;text-align:right;">Signature of Head of the Institution</p>
            <br/>
            <p style="font-size: 14px;text-align:center;">(Name in Block Letters:<?php echo strtoupper($std->student_name) ?>)</p>
               <hr class="hr_line">
               <br/>
               <p style="font-size: 14px;text-align:center;"><br/>COUNTER SIGNED BY ME<br/>Address, Seal & Office Telephone Number<br/>Of the Block Educational Officer / DDPU.
					    <br/>Mobile Number:
</p>

        </div>
        <!-- <div class="row">
        <p style="text-align: justify;font-size: 14pt; font-family: times new roman;">Place :  Mangaluru</p>
            <p style="text-align: justify;font-size: 14pt; font-family: times new roman;line-height:0.8">Date : <?php echo date('d-m-Y')?></p>
        </div> -->
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