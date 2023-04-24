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
            <span class="float-left">10TH/FIRST TERM MARK INFO(optional)</span>
        </th>
    </tr>
    <tr style="background: #337ab7; color:white;">
        <th>SUBJECT</th>
        <th width="120">MAX MARKS</th>
        <th>10TH/FIRST TERM MARKS SCORED</th>
    <!-- <th>9TH STD PERCENTAGE MARK</th> -->
    </tr>
</thead>

<tbody>
    <tr class="p-2 table-primary">
        <th class="text-center">GROUP I</th>
        <th></th>
        <th></th>
        <!-- <th></th> -->
    </tr>
    <tr>
        <th>
            <input id="subject_name" type="text" name="subject_name[]" required class="form-control required  input-sm required" placeholder="Enter Language I"  autocomplete="off" />
        </th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required  input-sm" value="100" readonly autocomplete="off"/></th>
        <td><input type="number" onkeypress="return isNumber(event)" required id="subject_obtained" type="text" name="subject_obtained[]" class="form-control required  input-sm obtnained_mark" placeholder="Enter First Term Mark" autocomplete="off"  /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr>
        <th>
            <input id="subject_name" type="text" name="subject_name[]" required class="form-control required  input-sm text-uppercase" placeholder="Language II" autocomplete="off" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly autocomplete="off"/></th>
        <td><input type="number" onkeypress="return isNumber(event)" required id="subject_obtained" type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter First Term Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr>
        <th>
            <input id="subject_name" type="text" name="subject_name[]" required class="form-control required  input-sm" placeholder=" Enter Subject Name(HISTORY, CIVICS AND GEOGRAPHY)"  autocomplete="off" />
        </th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly autocomplete="off"/></th>
        <td><input type="number" onkeypress="return isNumber(event)" id="subject_obtained" required type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter First Term Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr class="p-2 table-primary">
        <th class="text-center">GROUP II</th>
        <th></th>
        <th></th>
        <!-- <th></th> -->
    </tr>
    <tr>
        <th>
            <select class="form-control required" id="subject_name" name="subject_name[]" autocomplete="off" required >
                <option value="">SELECT SUBJECT</option>
                <option value="MATHEMATICS">MATHEMATICS</option>
                <option value="SCIENCE">SCIENCE</option>
                <option value="ECONOMICS">ECONOMICS</option>
                <option value="COMMERCIAL STUDIES">COMMERCIAL STUDIES</option>
                <option value="A MODERN FOREIGN LANGUAGE">A MODERN FOREIGN LANGUAGE</option>
                <option value="A CLASSICAL LANGUAGE">A CLASSICAL LANGUAGE</option>
                <option value="ENVIRONMENTAL SCIENCE">ENVIRONMENTAL SCIENCE</option>
            </select>
            <!-- <input id="subject_name" type="text" name="subject_name[]" class="form-control required  input-sm" placeholder="Subject Name" autocomplete="off" required/> -->
        </th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly autocomplete="off"/></th>
        <td><input type="number" onkeypress="return isNumber(event)" id="subject_obtained" required type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter First Term Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
    <tr>
        <th>
            <select class="form-control required" id="subject_name" name="subject_name[]" autocomplete="off" required>
                <option value="">SELECT SUBJECT</option>
                <option value="MATHEMATICS">MATHEMATICS</option>
                <option value="SCIENCE">SCIENCE</option>
                <option value="ECONOMICS">ECONOMICS</option>
                <option value="COMMERCIAL STUDIES">COMMERCIAL STUDIES</option>
                <option value="A MODERN FOREIGN LANGUAGE">A MODERN FOREIGN LANGUAGE</option>
                <option value="A CLASSICAL LANGUAGE">A CLASSICAL LANGUAGE</option>
                <option value="ENVIRONMENTAL SCIENCE">ENVIRONMENTAL SCIENCE</option>
            </select>
            <!-- <input id="subject_name" type="text" name="subject_name[]" class="form-control required  input-sm" placeholder="Subject Name" autocomplete="off" required/> -->
        </th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly autocomplete="off"/></th>
        <td><input type="number" onkeypress="return isNumber(event)" id="subject_obtained" required type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter First Term Mark" autocomplete="off" /></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  required/></td> -->
    </tr>
     <!-- <tr style="background:yellow;">
        <th> -->
            <!-- <input id="subject_name" type="text" name="subject_name[]" class="form-control   input-sm" placeholder="Subject Name(Elective Subject)" autocomplete="off" required/> -->
        <!-- </th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control input-sm" value="100" readonly autocomplete="off" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control input-sm" placeholder="Enter 10th Standard Mark" autocomplete="off" required/></td>
    </tr> -->
    <!--<tr>
        <th><input id="7_subject_name" type="text" name="7_subject_name" class="form-control required  input-sm" placeholder="Subject Name" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="7_subject_max_mark" type="text" name="7_subject_max_mark" class="form-control required  input-sm" value="100" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="7_subject_obtained" type="text" name="7_subject_obtained" class="form-control required  input-sm" placeholder="Enter 10th Standard Mark" /></td>
    </tr>
    <tr>
        <th><input id="8_subject_name" type="text" name="8_subject_name" class="form-control required  input-sm" placeholder="Subject Name" /></th>
        <th><input onkeypress="return isNumber(event)" maxlength="3" id="8_subject_max_mark" type="text" name="8_subject_max_mark" class="form-control required  input-sm" value="100" /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="8_subject_obtained" type="text" name="8_subject_obtained" class="form-control required  input-sm" placeholder="Enter 10th Standard Mark" /></td>
    </tr> -->
</tbody>

</table>
</div>
<!-- <b style="font-size:15px; color:red">Percentage(%) will be calculated for the 5 core subjects only. Elective is excluded.</b> -->
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover( { "container":"body", "trigger":"focus", "html":true });
    $('[data-toggle="popover"]').mouseenter(function(){
        $(this).trigger('focus');
    });
});
</script>
<script>

$('.obtnained_mark').keyup(function(){

  if ($(this).val() > 100 && $(this).val().length>1){
    alert("Please Enter Valid Marks");
    $(this).val('');
  }
});

</script>