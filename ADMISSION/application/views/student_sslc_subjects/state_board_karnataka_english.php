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
    <th width="150">MAX MARKS</th>
    <th>10TH STD MARKS SCORED</th>
</tr>
</thead>

<tbody>
    <tr>
        <th>
        <div class="form-group">
            <select class="form-control required" id="subject_name_one" name="subject_name[]" autocomplete="off" required>
                    <option value="">SELECT FIRST LANGAUGE</option>
                    <option value="KANNADA">KANNADA</option>
                    <option value="ENGLISH">ENGLISH</option>
                    <option value="SANSKRIT">SANSKRIT</option>
                    <option value="URDU">URDU</option>
                    <option value="HINDI">HINDI</option>
                    <option value="TAMIL">TAMIL</option>
                    <option value="TELUGU">TELUGU</option>
                    <option value="MALAYALAM">MALAYALAM</option>
                    <option value="MARATHI">MARATHI</option>
                    <option value="EXEMPTED">EXEMPTED</option>
            </select>
        </div>
        </th>
        <th><input style="text-transform: uppercase" id="subject_max_mark_one" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="125" readonly /></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained_one" type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark1" placeholder="Enter 10th Standard Mark" autocomplete="off" required/></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  /></td> -->
    </tr>
    <tr>
        <th>
        <div class="form-group">
            <select class="form-control required" id="subject_name" name="subject_name[]" autocomplete="off" required>
                    <option value="">SELECT SECOND LANGAUGE</option>
                    <option value="KANNADA">KANNADA</option>
                    <option value="ENGLISH">ENGLISH</option>
                    <option value="SANSKRIT">SANSKRIT</option>
                    <option value="URDU">URDU</option>
                    <option value="HINDI">HINDI</option>
                    <option value="TAMIL">TAMIL</option>
                    <option value="TELUGU">TELUGU</option>
                    <option value="MALAYALAM">MALAYALAM</option>
                    <option value="MARATHI">MARATHI</option>
                    <!-- <option value="EXEMPTED">EXEMPTED</option> -->
            </select>
        </div>
        </th>
        <th><input id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly/></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter 10th Standard Mark" autocomplete="off" required/></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  /></td> -->
    </tr>
    <tr>
        <th>
        <div class="form-group">
            <select class="form-control required" id="subject_name" name="subject_name[]" autocomplete="off" required>
                    <option value="">SELECT THIRD LANGAUGE</option>
                    <option value="KANNADA">KANNADA</option>
                    <option value="ENGLISH">ENGLISH</option>
                    <option value="SANSKRIT">SANSKRIT</option>
                    <option value="URDU">URDU</option>
                    <option value="HINDI">HINDI</option>
                    <option value="TAMIL">TAMIL</option>
                    <option value="TELUGU">TELUGU</option>
                    <option value="MALAYALAM">MALAYALAM</option>
                    <option value="MARATHI">MARATHI</option>
                    <!-- <option value="EXEMPTED">EXEMPTED</option> -->
            </select>
        </div>
        </th>
        <th><input style="text-transform: uppercase" id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly/></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter 10th Standard Mark" autocomplete="off" required/></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  /></td> -->
    </tr>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control required input-sm" value="MATHEMATICS" readonly/></th>
        <th><input id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly/></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter 10th Standard Mark" autocomplete="off" required/></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  /></td> -->
    </tr>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control required input-sm" value="SCIENCE" readonly/></th>
        <th><input id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly/></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter 10th Standard Mark" autocomplete="off" required/></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  /></td> -->
    </tr>
    <tr>
        <th><input id="subject_name" type="text" name="subject_name[]" class="form-control required input-sm" value="SOCIAL SCIENCE" readonly/></th>
        <th><input id="subject_max_mark" type="text" name="subject_max_mark[]" class="form-control required input-sm" value="100" readonly/></th>
        <td><input maxlength="3" onkeypress="return isNumber(event)" id="subject_obtained" type="text" name="subject_obtained[]" class="form-control required input-sm obtnained_mark" placeholder="Enter 10th Standard Mark" autocomplete="off" required/></td>
        <!-- <td><input maxlength="3" onkeypress="return isNumber(event)" id="obt_mark_9_std" type="text" name="obt_mark_9_std[]" class="form-control  input-sm" placeholder="Enter 9th Standard Mark" autocomplete="off"  /></td> -->
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

    $("#subject_name_one").on('change',function(){
        if(this.value == "EXEMPTED"){
            $('#subject_obtained_one').val('EX');
            $('#subject_max_mark_one').val('EX');
        }else{
            $('#subject_obtained_one').val('');
            $('#subject_max_mark_one').val(125);
        }
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
    $('.obtnained_mark1').keyup(function(){

    if ($(this).val() > 125 && $(this).val().length>1){
    alert("Please Enter Valid Marks");
    $(this).val('');
    }
    });
</script>