
<style>
table{
    width: 100% !important;
}

/*.border{
    border: 2px solid black;
}*/
.border_full{
    border: 1px solid black;
    /* height: 90% !important; */
}
.border_bottom{
    border-bottom: 1px solid black;
}
.hr_line{
    margin: 5px 0px;
    color: black;
}

.table_bordered{
    border-collapse: collapse;
}
.table_bordered th,.table_bordered td{
    border-top: 1px solid black;
    border-right: 1px solid black;
    padding: 3px;
}

.table_bordered th .border_right_none,.table_bordered td .border_right_none{
    border-right: 1px solid transparent !important;
}



</style>
  
<?php 
    if(!empty($feeConcession)){
        $concessionAmt = $feeConcession->fee_amt;
    }else{
        $concessionAmt = 0;
    }
    $transaction_id = '';

    if(!empty($studentTransportInfo->order_id)) {
    $transaction_id =  $studentTransportInfo->order_id;
    }
    if(!empty($studentTransportInfo->dd_number)) {
        $transaction_id =  $studentTransportInfo->dd_number;
    }
    if(!empty($studentTransportInfo->transaction_number)) {
        $transaction_id =  $studentTransportInfo->transaction_number;
     }
     if(!empty($studentTransportInfo->upi_ref_no)) {
        $transaction_id =  $studentTransportInfo->upi_ref_no;
     }
     log_message('debug','test'.$studentTransportInfo->month);
     
?>
<!--  -->
<div class="container-fluid border_full">
    <div class="row">
        <div class="">
            <table class="table text_highlight">
                <tr>
                    <td style="text-align:center;" width="80">
                        <img height="100" class="mt-2" width="70" height="70" src="<?php echo INSTITUTION_LOGO; ?>" alt="logo">
                    </td>
                    <td width="300" style="text-align:center;">
                        <b style="font-size: 18px;margin-bottom: 2px;"><?php echo  "ST XAVIER'S PRE–UNIVERSITY COLLEGE, KALABURAGI"; ?></b><br/>
                       
                      
                    </td>
                </tr>
            </table>
            <hr class="border_bottom hr_line">
            <table class="table" style="font-size: 13px;">
                 <tr>
                    <td colspan="2">Receipt No.: <span style="color: red;"><?php echo $studentTransportInfo->ref_receipt_no; ?></span></td>
                </tr>
                
                <tr>
                    <td colspan="2">Name of the student : <?php echo strtoupper($studentTransportInfo->student_name); ?></td>
                </tr>
                <tr>
                    <td>Student ID : <?php echo $studentTransportInfo->student_id;?></td>
                </tr>
                <tr>
                    <td width="220">Class  : <?php echo strtoupper($studentTransportInfo->term_name); ?></td> 
                </tr>
                <tr>
                    <td colspan="2">Month & Year : <?php echo $studentTransportInfo->month .' '. $studentTransportInfo->intake_year;?></td>
                </tr>

                <tr>
                    <td colspan="2">Bus No. : <?php echo $studentTransportInfo->bus_no;?></td>
                </tr>
                <tr>
                    <td colspan="2">Bus Pick Point : <?php echo $studentTransportInfo->route_name;?></td>
                </tr>
                <tr>
                    <td>Date : <?php echo date('d-m-Y',strtotime($studentTransportInfo->created_date_time)); ?></td>
                </tr>
                 <tr>
                    <td>Payment Type : <?php echo $studentTransportInfo->payment_type; ?></td>
                </tr>
                <tr>
                    <?php if($studentTransportInfo->payment_type != "CASH"){ ?>
                    <td>Transaction Id : <?php echo $transaction_id; ?></td>
                    <?php } ?>
                </tr>
                   <!-- </tr> -->
                  <?php if(!empty($neftInfo)) { ?> 
                <tr>
                    <td colspan="2">Neft No.: <?php echo  $neftInfo->neft_number; ?></td>
                </tr>
               <?php } ?>

                 <?php if(!empty($chequeInfo)) { ?> 
                <tr>
                    <td colspan="2">Check No.: <?php echo  $chequeInfo->check_number; ?></td>
                </tr>
               <?php } ?>

                <?php if(!empty($cardInfo)) { ?> 
                <tr>
                    <td colspan="2">Transaction No.: <?php echo  $cardInfo->transaction_number; ?></td>
                </tr>
               <?php } ?>
               <?php if(!empty($challanInfo)) { ?> 
                <tr>
                    <td colspan="2">challan No.: <?php echo  $challanInfo->challan_number; ?></td>
                </tr>
               <?php } ?>
            </table>
            <table class="table table_bordered" style="font-size: 13px;">
                <tr>
                    <th>Particulars</th>
                    <th width="120">Amount</th>
                </tr> 
                <tr>
                    <td style="text-align: center;">TRANSPORT FEE&nbsp;
                    <!-- From: <?php //echo date('d-m-Y',strtotime($studentTransportInfo->from_date)); ?>&nbsp;
                    To: <?php //echo date('d-m-Y',strtotime($studentTransportInfo->to_date)); ?> -->
                    </td>
                     <?php  $transport_rate = $studentTransportInfo->bus_fees; ?>
                    <td style="text-align: center;"><?php echo sprintf('%0.2f', $transport_rate); ?></td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <th style="text-align: center;"><?php echo sprintf('%0.2f', $transport_rate); ?></th>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 13px;"><br><b>Amount in words: <span style="text-transform: none;"><?php echo getIndianCurrency($transport_rate).' only'; ?></span></b></td>
                </tr>
            </table>
            

        </div>
    </div>
  
</div>
<b style="font-size: 11px;">All fees paid are not transferable or refundable.</b><br/>
<b style="font-size: 11px;">This is an online generated fee Receipt no seal and signature is required.</b>

<?php 
//  if($totalStudentCount != 0){
//     echo '<div class="break"></div>';
// }else{
//     echo '<div class="break_after"></div>';
// }

// } 



function getIndianCurrency(float $number) {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}

?>