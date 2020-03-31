<?php $this->load->view("partial/header"); ?>

<?php echo form_open('config/save/', array('id' => 'config_form', 'class' => 'form-horizontal')); ?>

<div class="title-block">
    <h3 class="title"> 

        Company Settings

    </h3>
    <p class="title-description">
        Setup company information
    </p>
</div>

<div class="section">
    <div class="row sameheight-container">
        <div class="col-lg-12">

            <div class="card">

                <div class="card-block">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="inqbox float-e-margins">

                                <div class="inqbox-content">

                                    <div class="tabs-container">

                                        <ul class="nav nav-tabs nav-tabs-bordered">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#tab-info">Company Information</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-smtp">SMTP Setup</a></li>                      

                                        </ul>

                                        <div class="tab-content">
                                            <div id="tab-info" class="tab-pane fade in active show">
                                                <div style="text-align: center">
                                                    <div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
                                                    <ul id="error_message_box"></ul>
                                                </div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('config_logo') . ':', 'logo', array('class' => 'wide')); ?></label>
                                                    <div class="col-sm-10">
                                                        <img id="img-pic" src="<?= (trim($this->config->item("logo")) !== "") ? base_url("uploads/logo/" . $this->config->item('logo')) : base_url("uploads/common/no_img.png"); ?>" style="height:99px" />
                                                        <div id="filelist"></div>
                                                        <div id="progress" class="overlay"></div>

                                                        <div class="progress progress-task" style="height: 4px; width: 15%; margin-bottom: 2px; display: none">
                                                            <div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" role="progressbar" class="progress-bar progress-bar-info">

                                                            </div>                                    
                                                        </div>

                                                        <div id="container">
                                                            <a id="browsefile" href="javascript:;" class="btn btn-default">Browse</a> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('config_company') . ':', 'company', array('class' => 'wide required')); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        echo form_input(
                                                                array(
                                                                    'name' => 'company',
                                                                    'id' => 'company',
                                                                    'value' => $this->config->item('company'),
                                                                    'class' => 'form-control'
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('config_address') . ':', 'address', array('class' => 'wide required')); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        echo form_textarea(
                                                                array(
                                                                    'name' => 'address',
                                                                    'id' => 'address',
                                                                    'rows' => 4,
                                                                    'cols' => 17,
                                                                    'value' => $this->config->item('address'),
                                                                    'class' => 'form-control'
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('config_phone') . ':', 'phone', array('class' => 'wide required')); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        echo form_input(
                                                                array(
                                                                    'name' => 'phone',
                                                                    'id' => 'phone',
                                                                    'value' => $this->config->item('phone'),
                                                                    'class' => 'form-control'
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('config_currency_symbol') . ':', 'currency_symbol', array('class' => 'wide')); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        echo form_input(array(
                                                            'name' => 'currency_symbol',
                                                            'id' => 'currency_symbol',
                                                            'class' => 'form-control',
                                                            'style' => 'width:15%',
                                                            'value' => $this->config->item('currency_symbol')));
                                                        ?>

                                                        <div>
                                                            <?php echo form_label($this->lang->line('config_currency_side') . ':', 'currency_side'); ?>
                                                            <?php
                                                            echo form_checkbox(array(
                                                                'name' => 'currency_side',
                                                                'id' => 'currency_side',
                                                                'value' => 'currency_side',
                                                                'checked' => $this->config->item('currency_side')));
                                                            ?>    
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('common_email') . ':', 'email', array('class' => 'wide')); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        echo form_input(
                                                                array(
                                                                    'name' => 'email',
                                                                    'id' => 'email',
                                                                    'value' => $this->config->item('email'),
                                                                    'class' => 'form-control'
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('config_fax') . ':', 'fax', array('class' => 'wide')); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        echo form_input(
                                                                array(
                                                                    'name' => 'fax',
                                                                    'id' => 'fax',
                                                                    'value' => $this->config->item('fax'),
                                                                    'class' => 'form-control'
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('config_website') . ':', 'website', array('class' => 'wide')); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        echo form_input(
                                                                array(
                                                                    'name' => 'website',
                                                                    'id' => 'website',
                                                                    'value' => $this->config->item('website'),
                                                                    'class' => 'form-control'
                                                                )
                                                        );
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-2 control-label"><?php echo form_label($this->lang->line('config_language') . ':', 'language', array('class' => 'wide required')); ?></label>
                                                    <div class="col-sm-10">                                    
                                                        <?php
                                                        echo form_dropdown('language_used', array(
                                                            "au" => "English",
                                                            "za" => "Afrikaans",
                                                            "al" => "Albanian",
                                                            "sa" => "Arabic",
                                                            "am" => "Armenian",
                                                            "az" => "Azerbaijani",
                                                            "by" => "Belarusian",
                                                            "bg" => "Bulgarian",
                                                            "cn" => "Chinese",
                                                            "hr" => "Croatian",
                                                            "cz" => "Czech",
                                                            "dk" => "Danish",
                                                            "be" => "Dutch",
                                                            "ee" => "Estonian",
                                                            "fi" => "Finnish",
                                                            "fr" => "French",
                                                            "ge" => "Georgian",
                                                            "de" => "German",
                                                            "gr" => "Greek",
                                                            "il" => "Hebrew",
                                                            "in" => "Hindi",
                                                            "hu" => "Hungarian",
                                                            "is" => "Icelandic",
                                                            "id" => "Indonesian",
                                                            "it" => "Italian",
                                                            "jp" => "Japanese",
                                                            "kz" => "Kazakh",
                                                            "kr" => "Korean",
                                                            "kz" => "Kyrgyz",
                                                            "lv" => "Latvian",
                                                            "lt" => "Lithuanian",
                                                            "mk" => "Macedonian",
                                                            "my" => "Malay",
                                                            "mn" => "Mongolian",
                                                            "no" => "Norwegian",
                                                            "pl" => "Polish",
                                                            "pt" => "Portuguese",
                                                            "ro" => "Romanian",
                                                            "ru" => "Russian",
                                                            "sr" => "Serbian",
                                                            "sk" => "Slovak",
                                                            "sl" => "Slovenian",
                                                            "af" => "Somali",
                                                            "es" => "Spanish",
                                                            "ke" => "Swahili",
                                                            "se" => "Swedish",
                                                            "th" => "Thai",
                                                            "tr" => "Turkish",
                                                            "ua" => "Ukrainian",
                                                            "pk" => "Urdu",
                                                            "uz" => "Uzbek",
                                                            "vn" => "Vietnamese",
                                                                ), $this->config->item('language_used'), "class='form-control notranslate'");
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">
                                                        <?php echo form_label($this->lang->line('config_timezone') . ':', 'timezone', array('class' => 'wide required')); ?>
                                                    </label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        echo form_dropdown('timezone', array(
                                                            'Pacific/Midway' => '(GMT-11:00) Midway Island, Samoa',
                                                            'America/Adak' => '(GMT-10:00) Hawaii-Aleutian',
                                                            'Etc/GMT+10' => '(GMT-10:00) Hawaii',
                                                            'Pacific/Marquesas' => '(GMT-09:30) Marquesas Islands',
                                                            'Pacific/Gambier' => '(GMT-09:00) Gambier Islands',
                                                            'America/Anchorage' => '(GMT-09:00) Alaska',
                                                            'America/Ensenada' => '(GMT-08:00) Tijuana, Baja California',
                                                            'Etc/GMT+8' => '(GMT-08:00) Pitcairn Islands',
                                                            'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US & Canada)',
                                                            'America/Denver' => '(GMT-07:00) Mountain Time (US & Canada)',
                                                            'America/Chihuahua' => '(GMT-07:00) Chihuahua, La Paz, Mazatlan',
                                                            'America/Dawson_Creek' => '(GMT-07:00) Arizona',
                                                            'America/Belize' => '(GMT-06:00) Saskatchewan, Central America',
                                                            'America/Cancun' => '(GMT-06:00) Guadalajara, Mexico City, Monterrey',
                                                            'Chile/EasterIsland' => '(GMT-06:00) Easter Island',
                                                            'America/Chicago' => '(GMT-06:00) Central Time (US & Canada)',
                                                            'America/New_York' => '(GMT-05:00) Eastern Time (US & Canada)',
                                                            'America/Havana' => '(GMT-05:00) Cuba',
                                                            'America/Bogota' => '(GMT-05:00) Bogota, Lima, Quito, Rio Branco',
                                                            'America/Caracas' => '(GMT-04:30) Caracas',
                                                            'America/Santiago' => '(GMT-04:00) Santiago',
                                                            'America/La_Paz' => '(GMT-04:00) La Paz',
                                                            'Atlantic/Stanley' => '(GMT-04:00) Faukland Islands',
                                                            'America/Campo_Grande' => '(GMT-04:00) Brazil',
                                                            'America/Goose_Bay' => '(GMT-04:00) Atlantic Time (Goose Bay)',
                                                            'America/Glace_Bay' => '(GMT-04:00) Atlantic Time (Canada)',
                                                            'America/St_Johns' => '(GMT-03:30) Newfoundland',
                                                            'America/Araguaina' => '(GMT-03:00) UTC-3',
                                                            'America/Montevideo' => '(GMT-03:00) Montevideo',
                                                            'America/Miquelon' => '(GMT-03:00) Miquelon, St. Pierre',
                                                            'America/Godthab' => '(GMT-03:00) Greenland',
                                                            'America/Argentina/Buenos_Aires' => '(GMT-03:00) Buenos Aires',
                                                            'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
                                                            'America/Noronha' => '(GMT-02:00) Mid-Atlantic',
                                                            'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
                                                            'Atlantic/Azores' => '(GMT-01:00) Azores',
                                                            'Europe/Belfast' => '(GMT) Greenwich Mean Time : Belfast',
                                                            'Europe/Dublin' => '(GMT) Greenwich Mean Time : Dublin',
                                                            'Europe/Lisbon' => '(GMT) Greenwich Mean Time : Lisbon',
                                                            'Europe/London' => '(GMT) Greenwich Mean Time : London',
                                                            'Africa/Abidjan' => '(GMT) Monrovia, Reykjavik',
                                                            'Europe/Amsterdam' => '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
                                                            'Europe/Belgrade' => '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
                                                            'Europe/Brussels' => '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris',
                                                            'Africa/Algiers' => '(GMT+01:00) West Central Africa',
                                                            'Africa/Windhoek' => '(GMT+01:00) Windhoek',
                                                            'Asia/Beirut' => '(GMT+02:00) Beirut',
                                                            'Africa/Cairo' => '(GMT+02:00) Cairo',
                                                            'Asia/Gaza' => '(GMT+02:00) Gaza',
                                                            'Africa/Blantyre' => '(GMT+02:00) Harare, Pretoria',
                                                            'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
                                                            'Europe/Minsk' => '(GMT+02:00) Minsk',
                                                            'Asia/Damascus' => '(GMT+02:00) Syria',
                                                            'Europe/Moscow' => '(GMT+03:00) Moscow, St. Petersburg, Volgograd',
                                                            'Africa/Addis_Ababa' => '(GMT+03:00) Nairobi',
                                                            'Asia/Tehran' => '(GMT+03:30) Tehran',
                                                            'Asia/Dubai' => '(GMT+04:00) Abu Dhabi, Muscat',
                                                            'Asia/Yerevan' => '(GMT+04:00) Yerevan',
                                                            'Asia/Kabul' => '(GMT+04:30) Kabul',
                                                            'Asia/Baku' => '(GMT+05:00) Baku', /* GARRISON ADDED 4/20/2013 */
                                                            'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
                                                            'Asia/Tashkent' => '(GMT+05:00) Tashkent',
                                                            'Asia/Kolkata' => '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi',
                                                            'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
                                                            'Asia/Dhaka' => '(GMT+06:00) Astana, Dhaka',
                                                            'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
                                                            'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
                                                            'Asia/Bangkok' => '(GMT+07:00) Bangkok, Hanoi, Jakarta',
                                                            'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
                                                            'Asia/Hong_Kong' => '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
                                                            'Asia/Irkutsk' => '(GMT+08:00) Irkutsk, Ulaan Bataar',
                                                            'Australia/Perth' => '(GMT+08:00) Perth',
                                                            'Australia/Eucla' => '(GMT+08:45) Eucla',
                                                            'Asia/Tokyo' => '(GMT+09:00) Osaka, Sapporo, Tokyo',
                                                            'Asia/Seoul' => '(GMT+09:00) Seoul',
                                                            'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
                                                            'Australia/Adelaide' => '(GMT+09:30) Adelaide',
                                                            'Australia/Darwin' => '(GMT+09:30) Darwin',
                                                            'Australia/Brisbane' => '(GMT+10:00) Brisbane',
                                                            'Australia/Hobart' => '(GMT+10:00) Hobart',
                                                            'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
                                                            'Australia/Lord_Howe' => '(GMT+10:30) Lord Howe Island',
                                                            'Etc/GMT-11' => '(GMT+11:00) Solomon Is., New Caledonia',
                                                            'Asia/Magadan' => '(GMT+11:00) Magadan',
                                                            'Pacific/Norfolk' => '(GMT+11:30) Norfolk Island',
                                                            'Asia/Anadyr' => '(GMT+12:00) Anadyr, Kamchatka',
                                                            'Pacific/Auckland' => '(GMT+12:00) Auckland, Wellington',
                                                            'Etc/GMT-12' => '(GMT+12:00) Fiji, Kamchatka, Marshall Is.',
                                                            'Pacific/Chatham' => '(GMT+12:45) Chatham Islands',
                                                            'Pacific/Tongatapu' => '(GMT+13:00) Nuku\'alofa',
                                                            'Pacific/Kiritimati' => '(GMT+14:00) Kiritimati'
                                                                ), $this->config->item('timezone') ? $this->config->item('timezone') : date_default_timezone_get(), "class='form-control'");
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>


                                                <div class="form-group row">
                                                    <label class="col-lg-2 control-label">
                                                        Date Format:
                                                    </label>
                                                    <div class="col-lg-10">
                                                        <select class="form-control" name="date_format">
                                                            <option value="d/m/Y" <?= $this->config->item('date_format') == 'd/m/Y' ? 'selected="selected"' : '' ?>><?= date('d/m/Y') ?> (day/month/Year)</option>
                                                            <option value="m/d/Y" <?= $this->config->item('date_format') == 'm/d/Y' ? 'selected="selected"' : '' ?>><?= date('m/d/Y') ?> (month/day/Year)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>


                                            </div>
                                            <div id="tab-smtp" class="tab-pane fade in">
                                                <div style="text-align: center">
                                                    <div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
                                                </div>

                                                <input type="hidden" name="smtp_id" value="<?= $smtp_info->smtp_id; ?>" />
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">
                                                        SMTP Host:
                                                    </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="smtp_host" class="form-control" value="<?= $smtp_info->smtp_host; ?>" />
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">
                                                        SMTP Port:
                                                    </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="smtp_port" class="form-control" value="<?= $smtp_info->smtp_port; ?>" />
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">
                                                        SMTP User:
                                                    </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="smtp_user" class="form-control" value="<?= $smtp_info->smtp_user; ?>" />
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">
                                                        SMTP Password:
                                                    </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="smtp_pass" class="form-control" value="<?= $smtp_info->smtp_pass; ?>" />
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button type="button" class="btn btn-default btn-secondary" data-dismiss="modal" id="btn-close">Close</button>
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


        </div>
    </div>    
</div>

<?php echo form_close(); ?>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" src="<?= base_url(); ?>/js/config.js"></script>


<script type='text/javascript'>

//validation and submit handling
    $(document).ready(function () {
        var settings = {
            submitHandler: function (form) {
                $("#submit").prop("disabled", true);
                $("#submit").val('Saving...');

                $(form).ajaxSubmit({
                    success: function (response) {
                        $("#config_modal").modal("hide");
                        $("#submit").prop("disabled", false);
                        $("#submit").val("<?= $this->lang->line('common_submit') ?>");

                        if (response.success)
                        {
                            set_feedback(response.message, 'success_message', false);
                        } else
                        {
                            set_feedback(response.message, 'error_message', true);
                        }
                    },
                    dataType: 'json',
                    type: 'post'
                });
            },
            rules: {
                company: "required",
                address: "required",
                phone: "required",
                default_tax_rate: {
                    required: true,
                    number: true
                },
                email: "email",
                return_policy: "required",
                stock_location: "required"

            },
            messages: {
                company: "<?php echo $this->lang->line('config_company_required'); ?>",
                address: "<?php echo $this->lang->line('config_address_required'); ?>",
                phone: "<?php echo $this->lang->line('config_phone_required'); ?>",
                default_tax_rate: {
                    required: "<?php echo $this->lang->line('config_default_tax_rate_required'); ?>",
                    number: "<?php echo $this->lang->line('config_default_tax_rate_number'); ?>"
                },
                email: "<?php echo $this->lang->line('common_email_invalid_format'); ?>",
                return_policy: "<?php echo $this->lang->line('config_return_policy_required'); ?>",
                stock_location: "<?php echo $this->lang->line('config_stock_location_required'); ?>"

            }
        };

        $('#config_form').validate(settings);

    });
</script>
