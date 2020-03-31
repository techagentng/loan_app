<?php echo form_open('my_wallets/save/' . $wallet_info->wallet_id, array('id' => 'wallet_form')); ?>

<div class="modal-header">
    <h4 class="modal-title"><?php echo $this->lang->line("my_wallet_info"); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

</div>
<div class="modal-body">

    <div style="overflow-y: auto" id="div-form">

        <div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
        <ul id="error_message_box"></ul>
        <div class="form-group">
            <?php echo form_label($this->lang->line('my_wallet_amount') . ':', 'amount', array('class' => 'wide required')); ?>
            <div class='form_field'>
                <?php
                echo form_input(
                        array(
                            'name' => 'amount',
                            'type' => 'number',
                            'id' => 'amount',
                            'value' => $wallet_info->amount,
                            'min' => '1',
                            'class' => 'form-control'
                        )
                );
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label($this->lang->line('my_wallet_description') . ':', 'description', array('class' => 'wide')); ?>
            <div class='form_field'>
                <?php
                echo form_textarea(
                        array(
                            'name' => 'description',
                            'id' => 'description',
                            'value' => $wallet_info->descriptions,
                            'rows' => '5',
                            'cols' => '17',
                            'class' => 'form-control'
                        )
                );
                ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo form_label($this->lang->line('my_wallet_type') . ':', 'wallet_type', array('class' => 'wide required')); ?>
            <div class='form_field'>                
                <?php echo form_dropdown('wallet_type', $wallet_types, $wallet_info->wallet_type, 'class="form-control" id="wallet_type" style="width:20%; display: inline"'); ?>
            </div>
        </div>
        
        <div class="form-group" id="div-transfer-to" style="<?=($wallet_info->wallet_type === "transfer")?"":"display:none";?>">
            <?php echo form_label($this->lang->line('my_wallet_transfer') . ':', 'transfer_to', array('class' => 'wide')); ?>
            <div class='form_field'>                
                <?php echo form_dropdown('transfer_to', $all_users, $wallet_info->transfer_to, 'class="form-control" id="wallet_type" style="display: inline"'); ?>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close"><?= $this->lang->line("common_close"); ?></button>
    <?php
    echo form_submit(
            array(
                'name' => 'submit',
                'id' => 'submit',
                'value' => $this->lang->line('common_submit'),
                'class' => 'btn btn-primary'
            )
    );
    ?>
</div>


<?php
echo form_close();
?>
<script type='text/javascript'>

    //validation and submit handling
    $(document).ready(function ()
    {
        $("#wallet_type").on("change", function(){            
            if($(this).val() === "transfer")
            {
                $("#div-transfer-to").slideDown("slow");
            }
            else
            {
                $("#div-transfer-to").slideUp("slow");
            }
        });
        
        $("#div-form").height($(window).height() - 250);

        $('#wallet_form').validate({
            submitHandler: function (form) {
                
                if ($("#wallet_type").val() === "credit" || $("#wallet_type").val() === "transfer")
                {
                    if (parseFloat($("#amount").val()) > parseFloat($("#available_amount").val()))
                    {
                        $("#error_message_box").show();
                        $("#error_message_box").html("<li><strong><?php echo $this->lang->line('my_wallet_exceed_amount'); ?></strong></li>");                        
                        return false;
                    }
                }
                
                $(form).ajaxSubmit({
                    success: function (response)
                    {
                        if (!response.success)
                        {
                            set_feedback(response.message, 'error_message', true);
                        } else
                        {
                            set_feedback(response.message, 'success_message', false);
                            $("#wallet_total").html(response.wallet_amount);
                            $("#available_amount").val(response.wallet_total);
                            $('#datatable').dataTable()._fnAjaxUpdate();
                            $('#wallet_modal').modal("hide");
                        }
                    },
                    dataType: 'json'
                });

            },
            errorLabelContainer: "#error_message_box",
            wrapper: "li",
            rules: {
                amount: "required",
                wallet_type: "required"
            },
            messages: {
                amount: "<?php echo $this->lang->line('my_wallet_amount_required'); ?>",
                wallet_type: "<?php echo $this->lang->line('my_wallet_type_required'); ?>"
            }
        });
        
    });
</script>