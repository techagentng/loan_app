<?php $this->load->view("partial/header"); ?>

<?php echo form_open($controller_name . '/save/' . $id, array('id' => $controller_name . '_form', 'class' => 'form-horizontal')); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="inqbox float-e-margins">
            <div class="inqbox-content">
                <h2><?php echo $this->lang->line('common_list_of') . ' ' . $this->lang->line('module_' . $controller_name); ?></h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?= site_url(); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?= site_url(strtolower($this->lang->line('module_' . $controller_name))); ?>"><?= ucwords($this->lang->line('module_' . $controller_name)); ?></a>
                    </li>
                    <li class="active">
                        <strong>Add</strong>
                    </li>
                </ol>
            </div>
        </div>
    </div>    
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="inqbox float-e-margins">
            <div class="inqbox-title">
                <h5>
                    <?php echo $this->lang->line("info"); ?>
                </h5>
                <div class="inqbox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#">Config option 1</a>
                        </li>
                        <li><a href="#">Config option 2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="inqbox-content">

                <div style="text-align: center">
                    <div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
                    <ul id="error_message_box"></ul>
                </div>


                <?php foreach ($fields as $field) : ?>
                    <?php if ($field === "module_name" || $field === "module_desc") : ?>
                        <div class="form-group"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line($field) . ':', 'name', array('class' => 'wide required')); ?></label>
                            <div class="col-sm-10">
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
                        <div class="hr-line-dashed"></div>
                    <?php endif; ?>
                <?php endforeach; ?>


                <div class="form-group"><label class="col-sm-2 control-label"><?php echo form_label('Field <span class="f_number">1</span>:', 'field1', array('class' => 'wide required')); ?></label>
                    <div class="col-sm-10">
                        <?php
                        echo form_input(
                                array(
                                    'name' => "field[]",
                                    'id' => 'field1',
                                    'value' => "",
                                    'class' => 'form-control'
                                )
                        );
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>


                <button class="btn btn-primary" type="button">Add Field</button>

            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
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
    </div>
</div>


<?php
echo form_close();
?>


<?php $this->load->view("partial/footer"); ?>
<script type="text/javascript" src="<?= site_url() ?>/js/plugin.js"></script>

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