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


if(empty($miscellaneousInfo->qnty)){

    $amount = $miscellaneousInfo->amount;
}else{

    $amount = $miscellaneousInfo->qnty * $miscellaneousInfo->amount;

}

?>

<?php if($miscellaneousInfo->payment_type == 'NEFT'){
            $transaction_id = '&nbsp;'.$miscellaneousInfo->ref_number;
            $payment_mode = '';
        }else if($miscellaneousInfo->payment_type == 'UPI'){
            $transaction_id = '&nbsp;'.$miscellaneousInfo->upi_ref_no;
            $payment_mode = '';
        }else{
            $transaction_id = '';
            $payment_mode = '';
        } 

$copy_name = ['STUDENT COPY','OFFICE COPY'] ?>
<div class="container-fluid border_full">
    <div class="row">
        <div class="">
            <table class="table text_highlight">
                <tr>
                    <!-- <td style="text-align:center;" width="80">
                        <img height="100" class="mt-1" width="70" height="70" src="<?php echo INSTITUTION_LOGO; ?>" alt="logo">
                    </td> -->
                    <td width="300" style="text-align:center;">
                        <b style="font-size: 20px;margin-bottom: 2px;">Karnataka Jesuit Educational Society</b><br/>
                        <b style="font-size: 18px;margin-bottom: 2px;">SIRNOOR Kalaburagi - 585 308</b><br/>
                       
                    </td>
                    <tr><td></td></tr>
                </tr>
            </table>
            <hr class="border_bottom hr_line">
           
            <table class="table" style="font-size: 15px;">
                <tr>
                    <td>Receipt No.: <b><span style="color: red;"><?php echo $miscellaneousInfo->ref_receipt_no; ?></b></td>
                    
                </tr>
                <tr>
                    <td>Date : <?php echo date('d-m-Y',strtotime($miscellaneousInfo->date)); ?></td>
                   
                </tr>
                <tr>
                    <td width="60%">Received From : <b><?php echo strtoupper($miscellaneousInfo->student_name); ?></b></td>
                </tr>
                <tr>
                    <td width="40%">For Class : <?php echo strtoupper($miscellaneousInfo->term.' '.$miscellaneousInfo->stream.' '.$miscellaneousInfo->section_name); ?></td>          
                </tr>
                <tr>
                    <td>Reg. No.: <b><?php echo $miscellaneousInfo->student_id; ?></b></td>
                    
                </tr>
               
                <tr>
                    <td >Amount (In Words): <span style="text-transform: capitalize;"><?php echo $amount_in_words.' only'; ?></span></b></td>
                    
                </tr>
                <tr>
                    <td >Rs.: <b><?php echo $amount; ?></b></td>
                    
                </tr>
                <tr>
                    <td >Towards: <?php echo $miscellaneousInfo->miscellaneous_type; ?></td>
                
                </tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr>
<td style="  text-align: right;">Cashier &nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
            </table>
            

        </div>
    </div>
 
   
</div>
<b style="font-size: 10px;">All fees paid are not transferable or refundable.</b><br/>
<b style="font-size: 10px;">This is an online generated fee Receipt no seal and signature is required.</b>

<?php 
//  if($totalStudentCount != 0){
//     echo '<div class="break"></div>';
// }else{
//     echo '<div class="break_after"></div>';
// }

// }




?>
