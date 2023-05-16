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

<?php if(!empty($studentsRecords)) {
                        $total_students_selected = count((array)$studentsRecords);
                        foreach($studentsRecords as $record) {  
                            $subject = getSubjectsName($record->stream_name); 
                            $total_students_selected--;
                         } ?>

    <div class="container-fluid a" style="padding-right:30px; padding-left:30px;">
        <div class="row">

            <table class="table" style="">
                <tr>
                    <th class="text-center" style="border-right: 1px solid white !important;">
                       <br/> <img src="<?php echo base_url(); ?>assets/dist/img/logo_stxpuc.jpg" width="80" height="70" alt="PHOTO" class="shcm_logo" />
                    </th>
                    <td style="font-size:17pt" class="text-center"><b><i>&emsp;&ensp;&emsp;&ensp;ST XAVIER'S PRE-UNIVERSITY COLLEGE</i></b><br>
                        <p style="font-size:10pt">&emsp;&ensp;&emsp;&ensp;&emsp;&ensp;&emsp;&ensp;&emsp;&ensp;
                        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NH 218, SIRNOOR POST - 585 308 - GULBARGA DIST.</b></p>
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
                    <td width="100%" style="text-align:center;font-size:16pt;font-family:times new roman;"> <b><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONDUCT CERTIFICATE</i><b></td>
                </tr>
            </table>
        </div>

        <div class="row">
            <table>
                <tr>
                    <td width="50%" style="text-align:left;font-size:14pt;font-family:times new roman;"> C.C. No</td>
                    <td width="50%" style="text-align:right;font-size:14pt;font-family:times new roman;"> Date: <u style=" text-decoration-style:dotted;"><?php echo date('d-m-Y')?><u></td>
                </tr>
            </table>
        </div>
        <div class="row">
        
            <p width="100%" style="text-align: justify;font-size: 14pt; font-family: times new roman;">This is to certify that <?php if(!empty($record->gender == "MALE")){ echo "Mr."; } else{ echo "Ms.";} ?>
                  <u style=" text-decoration-style:dotted;"><?php echo strtoupper($record->student_name) ?></u> has studied in this College in the Pre-University classes from  <u style=" text-decoration-style:dotted;"><?php echo $record->term_name?></u> and that during that time <?php if(!empty($record->gender == "MALE")){ echo "his"; } else{ echo "her";} ?> conduct has been <u style=" text-decoration-style:dotted;">good</u>.
            </p>
            
          <br>
        </div>
        <div class="row">
            <p style="text-align: justify;font-size: 14pt; font-family: times new roman;">Admission No: <u style=" text-decoration-style:dotted;"><?php echo ($record->admission_no) ?></u></p>
            <p style="text-align: justify;font-size: 14pt; font-family: times new roman;line-height:0.8">Class Reg. No: <u style=" text-decoration-style:dotted;"><?php echo ($record->student_id) ?></u> </p>

            <p style="text-align: left;font-size: 14pt; font-family: times new roman;line-height:0.8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i> PRINCIPAL</i></b></p>
        </div>
    </div>
                        <?php if($total_students_selected > 1) { ?>
                            <div class="page_break"></div>
                        <?php } ?>

                        <?php } ?>



<?php 
function getSubjectsName($stream_name){
    //science
    $PCMB = 'Physics,Chemistry,Mathematics,Biology';
    $PCMC = 'Physics,Chemistry,Mathematics,Comp. Science';
    $PCME = 'Physics,Chemistry,Mathematics,Electronics';
    //commarce
    $PEBA = 'Pol. Science,Economics,Business Studies,Accountancy';
    $MEBA = 'Basic Maths,Economics,Business Studies,Accountancy';
    $MSBA = 'Basic Maths,Statistics,Business Studies,Accountancy';
    $CSBA = 'Comp. Science,Statistics,Business Studies,Accountancy';
    $SEBA = 'Statistics,Economics,Business Studies,Accountancy';
    $CEBA = 'Comp. Science,Economics,Business Studies,Accountancy';
    //art
    $HEPS ='History,Economics,Pol. Science,Sociology';
    switch ($stream_name) {
        case "PCMB":
            return  $PCMB;
            break;
        case "PCMC":
            return $PCMC;
            break;
        case "PEBA":
            return $PEBA;
            break;
        case "PCME":
            return $PCME;
            break;
        case "MEBA":
            return $MEBA;
            break;
        case "MSBA":
            return $MSBA;
            break;
        case "CSBA":
            return $CSBA;
            break;
        case "SEBA":
            return $SEBA;
            break;
        case "CEBA":
            return $CEBA;
            break;
        case "HEPS":
            return $HEPS;
            break;
    }
}
?>
