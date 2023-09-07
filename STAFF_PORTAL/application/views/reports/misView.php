<style>
.break { page-break-before: always; } 
.break_after { page-break-before: none; } 

table{
    width: 100% !important;
}

u {    
    border-bottom: 2px dotted #00000;
    text-decoration: none;
    font-weight: bold;
    font-family:timesnewroman;
    font-size:16px;
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
    margin: 0px;
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
    <div class="container-fluid border_full" style="padding-right:0px; padding-left:0px;">
        <div class="row" >
            <table style="width: 100%;border-collapse: collapse;">
                <tr>
                    <th style="border: 1px solid black;text-align: center;width: 100px;" colspan="13">MISCELLANEOUS FEE INFO</th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">SL.No.</th>
                    <th style="border: 1px solid black;text-align: center;width: 130px;">Paid Date</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Receipt No.</th>
                    <th style="border: 1px solid black;text-align: center;width: 200px;">Student ID</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Name</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Year</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Term</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Stream</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Miscellaneous Type</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Quantity</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Amount</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;">Total Amount</th>
                </tr>
             
                <?php 
                $filter = array();
                $filter = $dt_filter;
                $j=1;
               // for($i=0; $i<count($miscellaneous);$i++){  
                   // $filter['miscellaneous'] = $miscellaneous[$i];
                  
                    $miscellaneousFeePaidInfo = $fee->getMiscellaneousFeesInfoReport($filter);
                   // log_message('debug','dmif'.print_r($miscellaneousFeePaidInfo,true));
                    foreach($miscellaneousFeePaidInfo as $fee){
                        if(empty($fee->qnty)){
                            $total_amount =  $fee->amount;   
                        }else {
                            $total_amount = $fee->qnty * $fee->amount;
                        }
                ?>  
                <tr>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $j++; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 130px;"><?php echo date('d-m-Y',strtotime($fee->date)); ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $fee->ref_receipt_no; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $fee->student_id; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 200px;"><?php echo $fee->student_name; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $fee->year; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $fee->term; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $fee->stream; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $fee->miscellaneous_type; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $fee->qnty; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $fee->amount; ?></th>
                        <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $total_amount; ?></th>
                </tr>        
                <?php   
                $total += $fee->amount;
                $grandtotal += $total_amount;     
                    }

               // }
                ?>
                 <tr>
                    <th style="border: 1px solid black;text-align: center;width: 100px;" colspan="10">TOTAL</th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $total; ?></th>
                    <th style="border: 1px solid black;text-align: center;width: 100px;"><?php echo $grandtotal; ?></th>
                </tr>
                <!-- <tr>
                    <th style="border: 1px solid black;text-align: left;" colspan="13"><br/></th>
                </tr> -->
            </table>
        </div>
    </div>