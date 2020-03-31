<script src="http://momentjs.com/downloads/moment.js"></script>


<div style="text-align: center">
    <h3>Calculator</h3>
    <div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
    <ul id="error_message_box"></ul>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">
    Apply Amount:
    </label>
    <div class="col-sm-2">
        <input type="hidden" id="amount" name="amount" value="<?=$loan_info->loan_amount;?>" />
        <?php
        echo form_input(
                array(
                    'name' => 'apply_amount',
                    'id' => 'apply_amount',
                    'value' => $loan_info->apply_amount,
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => 'any',
                )
        );
        ?>
    </div>
</div>


<div class="hr-line-dashed"></div>
<div class="form-group">
    <label class="col-sm-2 control-label">
        Interest Rate:
    </label>
    <div class='col-sm-2'>
        <div class="input-group">
            <input type="text" class="form-control" name="interest_rate" id="interest_rate" value="<?= $loan_info->interest_rate; ?>" />
            <span class="input-group-addon">%</span>
        </div>
        <input type="hidden" id="DTE_Field_interest_type" name="interest_type" value="fixed" />
    </div>
</div>

<div id="div_terms">
    <div class="hr-line-dashed"></div>

    <div class="form-group">
        <label class="col-sm-2 control-label" style="color:red">
            <?= $this->lang->line('loan_type_term'); ?>:
        </label>
        <div class="col-sm-2">
            <input type="text" name="term" id="term" class="form-control" value="<?= $loan_info->payment_term; ?>" />
        </div>
        <div class="col-sm-2">
            <select class="form-control" name="term_period" id="term_period">
                <option value="day" <?= $loan_info->term_period === "day" ? 'selected="selected"' : ''; ?>>Day</option>
                <option value="week" <?= $loan_info->term_period === "week" ? 'selected="selected"' : ''; ?>>Week</option>
                <option value="month" <?= $loan_info->term_period === "month" ? 'selected="selected"' : ''; ?>>Month</option>
                <option value="biweekly" <?= $loan_info->term_period === "biweekly" ? 'selected="selected"' : ''; ?>>Month (Biweekly)</option>
                <option value="year" <?= $loan_info->term_period === "year" ? 'selected="selected"' : ''; ?>>Year</option>
            </select>
        </div>
    </div>

</div>

<div class="hr-line-dashed"></div>
<div class="form-group">
    <label class="col-sm-2 control-label">
        First Payment Date:
    </label>
    <div class='col-sm-2'>
        <div class="input-group date">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>                                        
            <input type="text" class="form-control" name="start_date" id="start_date" value="<?=($loan_info->payment_start_date != '') ? date($this->config->item('date_format'), $loan_info->payment_start_date) : ''?>" />
        </div>
    </div>
</div>


<div class="hr-line-dashed"></div>
<div class="form-group">
    <label class="col-sm-2 control-label">
        Payable Amount:
    </label>
    <div class="col-sm-2">
        <div id="loan-total-amount"><?=$loan_info->loan_amount > 0 ? $loan_info->loan_amount : '0.00'?></div>
    </div>
</div>


<div class="hr-line-dashed"></div>


<div class="form-group">
    <label class="col-sm-2 control-label">
        &nbsp;
    </label>
    <div class="col-sm-4">
        <button class="btn btn-primary" type="button" id="btn-loan-calculator">Calculate</button>
    </div>
</div>
<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label"> &nbsp; </label>
    <div class="col-sm-10">
        <div id="div-payment-scheds"></div>
    </div>
</div>

<script>
    
    function check_term_field()
    {
        if ( $("#DTE_Field_interest_type").val() == "outstanding_interest" ||                 
                $("#DTE_Field_interest_type").val() == "one_time")
        {
            $("#term").val("1");
            $("#term").prop("disabled", true);
            $("#term_period").prop("disabled", true);
            $("#div_terms").slideUp();
        }
        else
        {
            $("#term").prop("disabled", false);
            $("#term_period").prop("disabled", false);
            $("#div_terms").slideDown();
        }
    }
    
    function calculate_amount()
    {
        var url = '<?= site_url('loans/ajax'); ?>';
        var params = {
            softtoken: $("input[name='softtoken']").val(),
            InterestType: $("#DTE_Field_interest_type").val(),
            NoOfPayments: $("#term").val(),
            ApplyAmt: $("#apply_amount").val(),
            TotIntRate: $('#interest_rate').val(),
            InstallmentStarted: $('#start_date').val(),
            PayTerm: $("#term_period").val(),
            ajax_type:1,
            exclude_sundays: $("#exclude_sundays").is(":checked") ? 1 : 0,
            penalty_value: $("#penalty_value").val(),
            penalty_type: $("#hid-penalty-type").val(),
        };
        $.post(url, params, function(data){
            if ( data.status == "OK" )
            {
                $html = '<table class="table table-bordered">';
                $html += '<thead>';
                $html += '<tr>';
                $html += '<th style="text-align:center">Date</th>';
                $html += '<th style="text-align:center">Amount to Pay</th>';                
                $html += '<th style="text-align:center">Principal Amount</th>';
                $html += '<th style="text-align:center">Interest ($)</th>';
                $html += '<th style="text-align:center">Balance</th>';
                $html += '</tr>';
                $html += '</thead>';
                $html += '<tbody>';
                var total_amount = 0;
                for ( var i=0; i < data.scheds.length; i++ )
                {
                    $html += '<tr>';
                    $html += '<td style="text-align:center">' + data.scheds[i]['payment_date'] + '</td>';
                    $html += '<td style="text-align:center">' + (parseFloat(data.scheds[i]['payment_amount'])).toFixed(2) + '</td>';                    
                    $html += '<td style="text-align:center">' + (parseFloat(data.scheds[i]['payment_amount']) - parseFloat(data.scheds[i]['interest'])).toFixed(2) + '</td>';
                    $html += '<td style="text-align:center">' + (parseFloat(data.scheds[i]['interest'])).toFixed(2) + '</td>';
                    $html += '<td style="text-align:center">' + (parseFloat(data.scheds[i]['payment_balance'])).toFixed(2) + '</td>';
                    $html += '</tr>';
                    
                    total_amount += data.scheds[i]['payment_amount'];
                }
                $html += '</tbody>';
                $html += '</table>';
                
                $("#loan-total-amount").html( total_amount.toFixed(2) );
                $("#amount").val( total_amount.toFixed(2) );
                $("#div-payment-scheds").html( $html );
            }
        }, "json");
    }
    
    $(document).ready(function(){
        
        $(document).on('change', "#DTE_Field_interest_type", function(){
            $("#DTE_Field_InterestType").val($(this).val());
            check_term_field();
        });
        
        <?php if ( $loan_info->loan_id > 0 ): ?>
            calculate_amount();
        <?php endif; ?>
        
        $(document).on('click', '#btn-loan-calculator', function () {

            if ($("#start_date").val() == '')
            {
                alertify.alert("Start Date is a required field");
                return false;
            }
            
            if ($("#term").val() == '')
            {
                alertify.alert("Term is a required field");
                return false;
            }            
            
            calculate_amount();            
        });
    });
    
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
</script>