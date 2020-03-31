<?php $this->load->view("partial/header"); ?>

<?php echo form_open('loan_types/save/' . $loan_type_info->loan_type_id, array('id' => 'loan_type_form', 'class' => 'form-horizontal')); ?>

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
                        <a href="<?= site_url("loan_types"); ?>"><?= ucwords($this->lang->line('module_' . $controller_name)); ?></a>
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
                    <?php echo $this->lang->line("loan_type_info"); ?>
                </h5>
                <div class="inqbox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
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
                <div class="form-group"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('loan_type_name') . ':', 'name', array('class' => 'wide required')); ?></label>
                    <div class="col-sm-10">
                        <?php
                        echo form_input(
                                array(
                                    'name' => 'name',
                                    'id' => 'name',
                                    'value' => $loan_type_info->name,
                                    'class' => 'form-control'
                                )
                        );
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('loan_type_description') . ':', 'description', array('class' => 'wide')); ?></label>
                    <div class="col-sm-10">
                        <?php
                        echo form_textarea(
                                array(
                                    'name' => 'description',
                                    'id' => 'description',
                                    'value' => $loan_type_info->description,
                                    'rows' => '5',
                                    'cols' => '17',
                                    'class' => 'form-control'
                                )
                        );
                        ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('loan_type_term') . ':', 'term', array('class' => 'wide required')); ?></label>
                    <div class="col-sm-10">
                        <?php
                        echo form_input(
                                array(
                                    'name' => 'term',
                                    'id' => 'term',
                                    'value' => $loan_type_info->term,
                                    'class' => 'form-control',
                                    'style' => 'width: 20%; display: inline',
                                )
                        );
                        ?>       
                        <?php echo form_dropdown('term_period_type', $term_period, $loan_type_info->term_period_type, 'class="form-control" id="term_period_type" style="width:20%; display: inline"'); ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('loan_type_period') . ':', 'term', array('class' => 'wide required')); ?></label>
                    <div class="col-sm-10">
                        <?php echo form_dropdown('payment_schedule', $period, $loan_type_info->payment_schedule, 'class="form-control" style="width:20%; display: inline"'); ?>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>

                <?php for ($i = 1; $i <= 10; $i++) : ?>
                    <div class="form-group"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('loan_type_percent_charge' . $i) . ':', 'percent_charge' . $i, array('class' => 'wide')); ?></label>
                        <div class="col-sm-10">
                            <?php
                            $percent_charge = "percent_charge" . $i;
                            $period_charge = "period_charge" . $i;
                            $period_type = "period_type" . $i;
                            echo form_input(
                                    array(
                                        'name' => 'percent_charge' . $i,
                                        'id' => 'percent_charge' . $i,
                                        'value' => $loan_type_info->$percent_charge,
                                        'type' => "number",
                                        'step' => "any",
                                        'class' => "form-control",
                                        'style' => "width: 20%; display: inline",
                                    )
                            );

                            echo " % ";
                            echo "<span class='per_condition'>";
                            echo " " . $this->lang->line("loan_type_per") . " ";
                            echo form_input(
                                    array(
                                        'name' => 'period_charge' . $i,
                                        'id' => 'period_charge' . $i,
                                        'value' => $loan_type_info->$period_charge,
                                        'type' => "number",
                                        'step' => "any",
                                        'class' => "form-control",
                                        'style' => "width: 20%; display: inline",
                                    )
                            );
                            ?>
                            <?php echo form_dropdown('period_type' . $i, $terms, $loan_type_info->$period_type, 'class="form-control" style="width:20%; display: inline"'); ?>
                            <?php echo "</span>"; ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
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
    </div>
</div>


<?php
echo form_close();
?>

<?php $this->load->view("partial/footer"); ?>

<script type='text/javascript'>

    //validation and submit handling
    $(document).ready(function ()
    {
        var settings = {
            submitHandler: function (form) {
                $("#submit").prop("disabled", true);
                $(form).ajaxSubmit({
                    success: function (response) {
                        console.log(response);
                        post_loan_type_form_submit(response);
                        $("#submit").prop("disabled", false);
                    },
                    dataType: 'json',
                    type: 'post'
                });
            },
            rules: {
                name: "required",
                term: "required"
            },
            messages: {
                name: "<?php echo $this->lang->line('loan_type_name_required'); ?>",
                term: "<?php echo $this->lang->line('loan_type_term_required'); ?>"
            }
        };

        $('#loan_type_form').validate(settings);

        function post_loan_type_form_submit(response)
        {
            if (!response.success)
            {
                set_feedback(response.message, 'error_message', true);
            } else
            {
                set_feedback(response.message, 'success_message', false);
            }

            $("#loan_type_form").attr("action", "<?= site_url(); ?>loan_types/save/" + response.loan_type_id);
        }

        if ($("#term_period_type").val() === "month")
        {
            $(".per_condition").hide();
        } else
        {
            $(".per_condition").show();
        }

        $(document).on("change", "#term_period_type", function () {
            if ($(this).val() === "month")
            {
                $(".per_condition").hide();
            } else
            {
                $(".per_condition").show();
            }
        });
    });
</script>