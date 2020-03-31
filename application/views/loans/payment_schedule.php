<html>
    <head>
        <link rel="stylesheet" rev="stylesheet" href="<?php echo base_url(); ?>bootstrap3/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>font-awesome-4.3.0/css/font-awesome.min.css" />
        <style>
            ul.checkbox-grid li {
                display: block;
                float: left;
                width: 40%;
                text-decoration: none;
            }

            .loans_pdf_company_name, .loans_pdf_title{
                text-align: center;
            }
            .custom_table td {
                border: 1px solid black;
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="loans_pdf_company_name">
            <img id="img-pic" src="<?= (trim($this->config->item("logo")) !== "") ? base_url("/uploads/logo/" . $this->config->item('logo')) : base_url("uploads/common/no_img.png"); ?>" style="height:99px" />
            <h3><?= $company_name; ?></h3>
            <h4>
                <?= $company_address; ?><br/>
                <?= "Tel. No. " . $phone . " Fax " . $fax . " Email " . $email; ?>
            </h4>
        </div>

        <div class="loans_pdf_title">
            <h4><?= $this->lang->line("loans_schedule_title"); ?></h4>
        </div>

        <table class="table">
            <tr>
                <td><?= $this->lang->line("common_full_name"); ?></td>
                <td><?= $customer_name; ?></td>
                <td><?= $this->lang->line("common_address_present"); ?></td>
                <td colspan="3"><?= $customer_address; ?></td>
            </tr>

            <tr>
                <td><?= $this->lang->line("loans_type"); ?></td>
                <td><?= $loan_type->name; ?></td>
                <td><?= $this->lang->line("loan_type_term"); ?></td>
                <td><?= $loan_type->term . " " . $loan_type->term_period_type; ?></td>
                <td><?= ($loan_type->term_period_type === "year") ? "APR" : "Fixed"; ?></td>
                <td><?= $apr ?>%</td>
            </tr>
            <tr>
                <td><?= $this->lang->line("loans_apply_date"); ?></td>
                <td><?= date("m/d/Y", $loan->loan_applied_date); ?></td>
                <td><?= $this->lang->line("loans_payment_date"); ?></td>
                <td><?= date("m/d/Y", $loan->loan_payment_date); ?></td>
                <td><?= $this->lang->line("loan_type_penalty"); ?></td>
                <td>__</td>
            </tr>
        </table>

        <div>
            <label><?= strtoupper($this->lang->line("loan_type_payment_sched")); ?></label>
            <ul class="checkbox-grid">
                <?php foreach ($schedules as $schedule): ?>
                    <?php if ($schedule->name === $period_type): ?>
                        <li>[x] <label for="text1"><?= $this->lang->line("common_" . $schedule->name); ?></label></li>
                    <?php else: ?>
                        <li>[ ] <label for="text1"><?= $this->lang->line("common_" . $schedule->name); ?></label></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <table class="table loans_pdf_loan_amount">
            <tr>
                <td><?= strtoupper($this->lang->line("loans_amount")) ?></td>
                <td style="text-align: right"><?= $loan_amount; ?></td>
            </tr>
            <tr>
                <td colspan="2"><?= $this->lang->line("loan_type_less_charge") ?>:</td>
            </tr>

            <?php foreach ($misc_fees as $misc_fee): ?>
                <tr>
                    <td><?= $misc_fee[0]; ?></td>
                    <td style="text-align: right"><?= $misc_fee[1]; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td><?= strtoupper($this->lang->line("loan_type_total_deduction")) ?></td>
                <td style="text-align: right"><?= $total_deductions; ?></td>
            </tr>
            <tr>
                <td><?= strtoupper($this->lang->line("loan_type_net_proceed")) ?></td>
                <td style="text-align: right"><?= $net_loan; ?></td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <td><?= $payment_sched; ?>:</td>
                <td style="text-align: right"><?= $repayment_amount; ?></td>
            </tr>
        </table>    
        
        <div>
            <table width="100%" class="custom_table">
                <tr>
                    <td align="center"><strong>Schedule</strong></td>
                    <td align="center"><strong>Balance</strong></td>
                    <td align="center"><strong>Interest</strong></td>
                    <td align="center"><strong>Payment</strong></td>
                </tr>
            <?php foreach($breakdown_data as $tmp):?>
                <tr>
                    <td>&nbsp;&nbsp;<?=$tmp["month"];?></td>
                    <td align="right"><?=$tmp["balance"];?>&nbsp;&nbsp;</td>
                    <td align="center"><?=$tmp["interest"];?>%</td>
                    <td align="right"><?=$tmp["pay"];?>&nbsp;&nbsp;</td>
                </tr>
            <?php endforeach;?>
            </table>
        </div>

    </body>

</html>