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
        </style>
    </head>
    <body>
        <div class="loans_pdf_company_name">
            <img id="img-pic" src="<?= (trim($this->config->item("logo")) !== "") ? base_url("uploads/logo/" . $this->config->item('logo')) : base_url("uploads/common/no_img.png"); ?>" style="height:99px" />
            <h3><?= $company_name; ?></h3>
            <h4>
                <?= $company_address; ?><br/>
                <?= "Tel. No. " . $phone . " Fax " . $fax . " Email " . $email; ?>
            </h4>
        </div>

        <div class="loans_pdf_title">
            <h4><?= $this->lang->line("loans_disclosure_title"); ?></h4>
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
                <td>Flexible</td>
                <td><?= $this->lang->line("loan_type_term"); ?></td>
                <td><?= $c_payment_scheds["term"] . " " . $c_payment_scheds["term_period"]; ?></td>
                <td>Interest Rate</td>
                <td><?= $c_payment_scheds['interest_rate']; ?>%</td>
            </tr>
            <tr>
                <td><?= $this->lang->line("loans_apply_date"); ?></td>
                <td><?= date("m/d/Y", $loan->loan_applied_date); ?></td>
                <td><?= $this->lang->line("loans_payment_date"); ?></td>
                <td><?= date("m/d/Y", $loan->loan_payment_date); ?></td>
                <td><?= $this->lang->line("loan_type_penalty"); ?></td>
                <td><?=($c_payment_scheds["penalty"] > 0 ? $c_payment_scheds["penalty"]."%" : "__");?></td>
            </tr>
        </table>

        <div>
            <label><?= strtoupper($this->lang->line("loan_type_payment_sched")); ?></label>
            <ul class="checkbox-grid">
                <li>[<?=($c_payment_scheds["payment_sched"]==="daily")?"x":" "?>] <label for="text1">Daily</label></li>
                <li>[<?=($c_payment_scheds["payment_sched"]==="weekly")?"x":" "?>] <label for="text1">Weekly</label></li>
                <li>[<?=($c_payment_scheds["payment_sched"]==="biweekly")?"x":" "?>] <label for="text1">BiWeekly</label></li>
                <li>[<?=($c_payment_scheds["payment_sched"]==="monthly")?"x":" "?>] <label for="text1">Monthly</label></li>
                <li>[<?=($c_payment_scheds["payment_sched"]==="bimonthly")?"x":" "?>] <label for="text1">BiMonthly</label></li>
                <li>[<?=($c_payment_scheds["payment_sched"]==="yearly")?"x":" "?>] <label for="text1">Yearly</label></li>
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
                <td><?= ucwords($c_payment_scheds["payment_sched"]." Payment"); ?>:</td>
                <td style="text-align: right"><?= $repayment_amount; ?></td>
            </tr>
        </table>        

        <div>
            <?= strtoupper($this->lang->line("loan_type_acknowledgment")); ?>

            <table class="table">
                <tr>
                    <td style="height: 200px"><?= $this->lang->line("loan_type_prepared_by") ?>:</td>
                    <td>&nbsp;</td>
                    <td><?= $this->lang->line("loan_type_checked_by") ?>:</td>
                    <td>&nbsp;</td>
                    <td><?= $this->lang->line("loan_type_approved_by") ?>:</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><?= $this->lang->line("loan_type_noted_by") ?>:</td>
                    <td>&nbsp;</td>
                    <td><?= $this->lang->line("loan_type_received_by") ?>:</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>

    </body>

</html>