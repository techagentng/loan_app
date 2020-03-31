<?php $this->load->view("partial/header"); ?>

<?php echo form_open('employees/save/' . $person_info->person_id, array('id' => 'employee_form', 'class' => 'form-horizontal')); ?>
<input type="hidden" id="person_id" value="<?= $person_info->person_id ?>" />

<div class="title-block">
    <h3 class="title"> 

        <?php if ($person_info->person_id > 0): ?>
            Edit Employee
        <?php else: ?>
            New Employee
        <?php endif; ?>

    </h3>
    <p class="title-description">
        Employee basic information
    </p>
</div>

<div class="section">
    <div class="row sameheight-container">
        <div class="col-lg-12">

            <div class="card">

                <div class="card-block">
                    <div class="inqbox float-e-margins">

                        <div class="inqbox-content">

                            <div class="tabs-container">
                                <ul class="nav nav-tabs nav-tabs-bordered">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tab-personal-details">Personal Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-credentials">Permissions</a>
                                    </li>
                                </ul>
                                <div class="tab-content tabs-bordered">
                                    <div id="tab-personal-details" class="tab-pane fade in active show">
                                        <div style="text-align: center">
                                            <div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
                                        </div>
                                        <?php $this->load->view("people/form_basic_info"); ?>
                                    </div>
                                    <div id="tab-credentials" class="tab-pane fade in">

                                        <br/>
                                        
                                                    
                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('employees_username') . ':', 'username'); ?></label>
                                            <div class="col-sm-10">
                                                <?php if ($person_info->person_id > 0): ?>

                                                    <input type="text" name="username1" id="username1" class="form-control" value="<?= $person_info->username; ?>" disabled="disabled" />
                                                    <input type="hidden" name="username" id="username" value="<?= $person_info->username; ?>" />

                                                <?php else: ?>
                                                    <input type="text" class="form-control" name="username" id="username" value="<?= $person_info->username; ?>" />
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="hr-line-dashed"></div>

                                        <?php
                                        $password_label_attributes = $person_info->person_id == "" ? array('class' => 'required') : array();
                                        ?>

                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('employees_password') . ':', 'password', $password_label_attributes); ?></label>
                                            <div class="col-sm-10">
                                                <?php
                                                echo form_password(array(
                                                    'name' => 'password',
                                                    'id' => 'password',
                                                    'class' => 'form-control'
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="hr-line-dashed"></div>

                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('employees_repeat_password') . ':', 'repeat_password', $password_label_attributes); ?></label>
                                            <div class="col-sm-10">
                                                <?php
                                                echo form_password(array(
                                                    'name' => 'repeat_password',
                                                    'id' => 'repeat_password',
                                                    'class' => 'form-control'
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="hr-line-dashed"></div>

                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 text-xs-right" style="color:red">
                                                Role:
                                            </label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="role_id" id="role_id">
                                                    <?php foreach ($roles as $role): ?>
                                                        <option value="<?= $role->role_id; ?>" <?= ($role->role_id === $person_info->role_id) ? "selected='selected'" : ""; ?>><?= $role->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="control-label col-sm-2 text-xs-right">
                                                Can approve loan transaction:
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" value="1" id="can_approve_loan" name="can_approve_loan" <?= $person_info->can_approve_loan ? "checked='checked'" : ""; ?> />
                                            </div>
                                        </div>
                                                    
                                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <div>
                            <button type="button" class="btn btn-default btn-secondary" data-dismiss="modal" id="btn-close"><?= $this->lang->line("common_close"); ?></button>
                            <?php
                            echo form_submit(array(
                                'name' => 'submit',
                                'id' => 'submit',
                                'value' => $this->lang->line('common_submit'),
                                'class' => 'btn btn-primary')
                            );
                            ?>
                        </div>
                    </div>
                </div>


            </div>


        </div>
    </div>    
</div>


<?php
echo form_close();
?>

<div id="feedback_bar"></div>

<?php $this->load->view("partial/footer"); ?>

<script src="<?php echo base_url(); ?>js/people.js?v=<?= time(); ?>"></script>

<script>
    $(document).ready(function () {
        var person_id = $("#person_id").val();

        var settings = {
            submitHandler: function (form) {
                $("#submit").prop("disabled", true);
                $(form).ajaxSubmit({
                    success: function (response)
                    {
                        post_person_form_submit(response);
                        $("#submit").prop("disabled", false);
                    },
                    dataType: 'json',
                    type: 'post'
                });
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") === "grants[]")
                {
                    error.appendTo('#error_message_box');
                } else
                {
                    error.insertAfter(element);
                }
            },
            rules: {
                first_name: "required",
                last_name: "required",
                username: {
                    required: true,
                    minlength: 5
                },
                password: {
                    required: true,
                    minlength: 8
                },
                repeat_password: {
                    equalTo: "#password"
                },
                email: "email", "grants[]": {
                    required: function (element) {
                        var checked = false;
                        $("ul#permission_list > li > input:checkbox").each(function () {
                            if ($(this).is(":checked"))
                            {
                                var has_children = false;
                                $("ul > li > input:checkbox", $(this).parent()).each(function ()
                                {
                                    has_children = true;
                                    checked |= $(this).is(":checked");
                                });
                                if (has_children && !checked)
                                {
                                    return false;
                                }
                            }
                        });
                        return !checked;
                    },
                    minlength: 1
                }
            },
            messages: {
                first_name: "<?php echo $this->lang->line('common_first_name_required'); ?>",
                last_name: "<?php echo $this->lang->line('common_last_name_required'); ?>",
                email: "<?php echo $this->lang->line('common_email_invalid_format'); ?>",
                username: {
                    required: "<?php echo $this->lang->line('employees_username_required'); ?>",
                    minlength: "<?php echo $this->lang->line('employees_username_minlength'); ?>"
                },
                password: {
                    required: "<?php echo $this->lang->line('employees_password_required'); ?>",
                    minlength: "<?php echo $this->lang->line('employees_password_minlength'); ?>"
                },
                repeat_password: {
                    equalTo: "<?php echo $this->lang->line('employees_password_must_match'); ?>"
                },
                "grants[]": "<?php echo $this->lang->line('employees_grants_required'); ?>"
            }
        };

        if (person_id !== "")
        {
            settings["rules"]["password"]["required"] = false;
        }

        // Validation 
        $('#employee_form').validate(settings);

        function post_person_form_submit(response)
        {
            if (!response.success)
            {
                set_feedback(response.message, 'error_message', true);
            } else
            {
                set_feedback(response.message, 'success_message', false);
            }

            $("#employee_form").attr("action", "<?= site_url(); ?>employees/save/" + response.person_id);
        }
    });
</script>
