
<style>
table{
    width: 100% !important;
}

.break{
    page-break-after
}
.break_b{
    page-break-before: always;
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


<!-- RECEIPT 2 -->
<div class="container-fluid border_full">
    <div class="row">
        <div class="">
            <table class="table text_highlight">
                <tr>
                    <td style="text-align:center;" width="80">
                        <img class="mt-2" width="50" height="50" src="<?php echo INSTITUTION_LOGO; ?>" alt="logo">
                    </td>
                    <td width="200" style="text-align:center;">
                        <b style="font-size: 12px;margin-bottom: 2px;"><?php echo TITLE; ?></b><br/>
                        <!-- <b style="font-size: 13px;margin-bottom: 2px;">Unit of KJES </b><br/>
                        <b style="font-size: 12px;margin-bottom: 2px;"># 23, Vittal Mallya Road, Bengaluru - 560001</b><br/> -->
                        <!-- <b style="font-size: 12px;margin-bottom: 2px;">Bengaluru – 560 001</b><br/> -->
                    </td>
                </tr>
            </table>
            <hr class="border_bottom hr_line">
            <table class="table" style="font-size: 10px;">
                <tr>
                    <td>Name of the child : <?php echo strtoupper($studentInfo->student_name); ?></td>
                    <td>Application/Register no. : <?php if(!empty($studentInfo->register_number) && $studentInfo->term_name == 'II PUC'){ echo $studentInfo->register_number; }else{ echo $studentInfo->application_no; } ?></td>
                </tr>
                <tr>
                    <!-- <td>Name of the father : <?php echo $studentInfo->father_name; ?></td> -->
                    <td>Receipt no.: <?php echo $feeInfo->ref_receipt_no; ?></td>
                    <td>Payment Received Date : <?php echo date('d-m-Y',strtotime($feeInfo->payment_date)); ?></td>
                </tr>
                <tr>
      
                    <!-- <td>Transaction Id : <?php echo $feeInfo->order_id; ?></td> -->
                </tr>
                <tr>
                    <td width="300">Class & Section : <?php echo strtoupper($studentInfo->term_name.' '.$studentInfo->section_name); ?></td>
                    <td >Payment Received Mode : <?php if(!empty($feeInfo->payment_type)){ echo $feeInfo->payment_type; }else{ echo 'Online'; } ?></td>
                </tr>
            </table>
            <table class="table table_bordered" style="font-size: 9px;">
                <tr>
                    <!-- <th width="100">Sl.No.</th> -->
                    <th>Particulars</th>
                    <th>Amount</th>
                </tr> 
                <?php if(!empty($feeStructureInfo)) {
                    $i=1; $total_fee_amt=0;
                    foreach($feeStructureInfo as $fee){ 
                        if($fee->fees_type != 'College Dept Fee' && $fee->fees_type != 'Eligibility Fee'){
                        $total_fee_amt +=  $fee->fee_amount_state_board;
                ?>
                     <tr>
                        <!-- <th style="text-align:center;"><?php echo $i; ?></th> -->
                        <th style="text-align: center;"><?php echo strtoupper($fee->fees_type); ?></th>
                        <th style="text-align: right;"><?php echo number_format($fee->fee_amount_state_board,2); ?></th>
                    </tr>
                   
                <?php $i++; }else{
                    $dept_fee = $fee->fee_amount_state_board;
                } } } ?>  

                
            
                <tr>
                    <th colspan="1">Total Fee</th>
                    <th class="border_right_none" style="text-align: right;"><?php echo number_format($total_fee_amt,2); ?></th>
                </tr>
                <?php if($fee_concession != 0){ ?>
                <tr>
                    <th colspan="1">Fee Concession(-)</th>
                    <th class="border_right_none" style="text-align: right;"><?php echo number_format($fee_concession,2); ?></th>
                </tr>
                <?php } ?>
                <tr>
                    <th colspan="1">Amount Paid</th>
                    <th class="border_right_none" style="text-align: right;"><?php if($feeInfo->payment_count == 1){ $paidAmt = $feeInfo->paid_amount; }else{ $paidAmt =$feeInfo->paid_amount; } echo number_format($paidAmt,2); ?></th>
                </tr>
                <tr>
                    <th colspan="1">Amount Pending</th>
                    <th class="border_right_none" style="text-align: right;"><?php echo number_format($feeInfo->pending_balance,2); ?></th>
                </tr>
                <tr>
                    <td colspan="2"><b>Paid total amount in word: <span style="text-transform: capitalize;"><?php echo strtoupper(getIndianCurrency($feeInfo->paid_amount)).' ONLY'; ?></span></b></td>
                </tr>   
            </table>

        </div>
    </div>
  
</div>
<b style="font-size: 8px;">All fees paid are not transferable or refundable.</b>
<b style="font-size: 8px;">This is an online generated fee Receipt no seal and signature is required.</b>

<?php 
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
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}

?>