<style>

.table>tbody>tr>th{
border: 1px solid black !important;

line-height: 0.42857143 !important;
vertical-align: middle !important;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 5px !important;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
}
</style>
<div class="table-responsive">
<table class="table table-bordered text-center">
<thead>
<tr style="background: #337ab7; color:white;">
    <th colspan="4">
        <span class="float-left">10th STANDARD MARK INFO</span>
    </th>
</tr>
<tr style="background: #337ab7; color:white;">
    <th>SUBJECT</th>
    <th width="120">MAX MARKS</th>
    <th>10TH STD MARKS SCORED</th>
    <!-- <th>9TH STD MARKS SCORED</th> -->
</tr>
</thead>

<tbody>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control  input-sm" placeholder="Subject Name" autocomplete="off" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control  input-sm" placeholder="Max Mark" autocomplete="off" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control  input-sm" placeholder="Enter 10th Standard Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control  input-sm" placeholder="Subject Name" autocomplete="off" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control  input-sm" placeholder="Max Mark" autocomplete="off" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control  input-sm" placeholder="Enter 10th Standard Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control  input-sm" placeholder="Subject Name" autocomplete="off" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control  input-sm" placeholder="Max Mark" autocomplete="off" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control  input-sm" placeholder="Enter 10th Standard Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control  input-sm" placeholder="Subject Name" autocomplete="off" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control  input-sm" placeholder="Max Mark" autocomplete="off" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control  input-sm" placeholder="Enter 10th Standard Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control  input-sm" placeholder="Subject Name" autocomplete="off" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control  input-sm" placeholder="Max Mark" autocomplete="off" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control  input-sm" placeholder="Enter 10th Standard Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control  input-sm" placeholder="Subject Name" autocomplete="off" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control  input-sm" placeholder="Max Mark" autocomplete="off" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control  input-sm" placeholder="Enter 10th Standard Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
   
</tbody>

</table>
</div>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover( { "container":"body", "trigger":"focus", "html":true });
    $('[data-toggle="popover"]').mouseenter(function(){
        $(this).trigger('focus');
    }); 
});
</script>