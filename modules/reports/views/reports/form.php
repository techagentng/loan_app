<?php echo form_open($controller_name . '/save/' . $id, array('id' => $controller_name . '_form')); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?php echo $this->lang->line("info"); ?></h4>

</div>
<div class="modal-body">

    <div style="overflow-y: auto" id="div-form">

        <div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
        <ul id="error_message_box"></ul>


        <?php foreach ($fields as $field) : ?>
            <div class="field_row clearfix">
                <?php echo form_label($this->lang->line($field) . ':', 'name', array('class' => 'wide required')); ?>
                <div class='form_field'>
                    <?php
                    echo form_input(
                            array(
                                'name' => $field,
                                'id' => $field,
                                'value' => $info->$field,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close">Close</button>
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
        $("#div-form").height($(window).height() - 250);

        $('#<?= $controller_name; ?>_form').validate({
            submitHandler: function (form)
            {
                $(form).ajaxSubmit({
                    success: function (response)
                    {
                        post_form_submit(response);
                    },
                    dataType: 'json'
                });

            },
            errorLabelContainer: "#error_message_box",
            wrapper: "li",
            rules:
                    {
                        name: "required"
                    },
            messages:
                    {
                        name: "<?php echo $this->lang->line($controller_name . '_name_required'); ?>",
                    }
        });
    });
</script>