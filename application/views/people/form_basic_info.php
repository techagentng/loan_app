


            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right">
                    <?php echo form_label($this->lang->line('common_photo') . ':', 'photo_url'); ?>
                </label>
                <div class="col-sm-10">

                    <?php if ($person_info->person_id > 0): ?>

                        <?php if (trim(trim($person_info->photo_url) !== "") && file_exists(FCPATH . "/uploads/profile-" . $person_info->person_id . "/" . $person_info->photo_url)): ?>
                            <img id="img-pic" src="<?= base_url("uploads/profile-" . $person_info->person_id . "/" . $person_info->photo_url); ?>" style="height:99px" />
                        <?php else: ?>
                            <img id="img-pic" src="http://via.placeholder.com/80x80" style="height:99px" />
                        <?php endif; ?>
                        <div id="filelist"></div>
                        <div id="progress" class="overlay"></div>

                        <div class="progress progress-task" style="height: 4px; width: 15%; margin-bottom: 2px; display: none">
                            <div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" role="progressbar" class="progress-bar progress-bar-info">

                            </div>                                    
                        </div>

                        <div id="container">
                            <a id="pickfiles" href="javascript:;" class="btn btn-default btn-secondary" data-person-id="<?= $person_info->person_id; ?>"><?= $this->lang->line("common_browse"); ?></a> 
                        </div>

                    <?php else: ?>

                        <div class="alert alert-info"><i class="fa fa-info-circle"></i> Upload photo will be available after adding this user</div>

                    <?php endif; ?>

                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_first_name') . ':', 'first_name', array('class' => 'required')); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'first_name',
                                'id' => 'first_name',
                                'value' => $person_info->first_name,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_last_name') . ':', 'last_name', array('class' => 'required')); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'last_name',
                                'id' => 'last_name',
                                'value' => $person_info->last_name,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_email') . ':', 'email'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'email',
                                'id' => 'email',
                                'value' => $person_info->email,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_phone_number') . ':', 'phone_number'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'phone_number',
                                'id' => 'phone_number',
                                'value' => $person_info->phone_number,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
        
            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_address_1') . ':', 'address_1'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'address_1',
                                'id' => 'address_1',
                                'value' => $person_info->address_1,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_address_2') . ':', 'address_2'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'address_2',
                                'id' => 'address_2',
                                'value' => $person_info->address_2,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_city') . ':', 'city'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'city',
                                'id' => 'city',
                                'value' => $person_info->city,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_state') . ':', 'state'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'state',
                                'id' => 'state',
                                'value' => $person_info->state,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_zip') . ':', 'zip'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'zip',
                                'id' => 'zip',
                                'value' => $person_info->zip,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_country') . ':', 'country'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_input(
                            array(
                                'name' => 'country',
                                'id' => 'country',
                                'value' => $person_info->country,
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="control-label col-sm-2 text-xs-right"><?php echo form_label($this->lang->line('common_comments') . ':', 'comments'); ?></label>
                <div class="col-sm-10">
                    <?php
                    echo form_textarea(
                            array(
                                'name' => 'comments',
                                'id' => 'comments',
                                'value' => $person_info->comments,
                                'rows' => '5',
                                'cols' => '17',
                                'class' => 'form-control'
                            )
                    );
                    ?>
                </div>
            </div>
        





