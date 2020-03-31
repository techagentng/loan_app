<?php $this->load->view("partial/header"); ?>
<style>
    table#datatable td:nth-child(6), td:nth-child(7) {
        text-align: right
    }
    table#datatable td:nth-child(10), 
    td:nth-child(11), 
    td:nth-child(12),
    td:nth-child(13),
    td:nth-child(14){
        text-align: center
    }
</style>


<div class="hide-staff" style="display: none;">
    <select class="form-control input-sm hidden-xs" id="sel-staff">
        <option value="0">Select staff</option>
        <?php foreach ($staffs as $staff): ?>
            <option value="<?= $staff->person_id; ?>" <?= ((isset($_GET['employee_id'])) && $_GET['employee_id'] === $staff->person_id) ? 'selected="selected"' : ""; ?>><?= $staff->first_name . " " . $staff->last_name; ?></option>
        <?php endforeach; ?>
    </select>&nbsp;
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="inqbox float-e-margins">
            <div class="inqbox-content">
                <h2>
                    <i class="fa fa-file" style="font-size: 40px; color:#1b926c"></i> <?php echo $this->lang->line('common_list_of') . ' ' . $this->lang->line('module_' . $controller_name); ?>
                </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?= site_url(); ?>">Home</a>
                    </li>
                    <li>
                        <a>Loans</a>
                    </li>
                    <li class="active">
                        <strong>List</strong>
                    </li>
                </ol>
            </div>
        </div>
    </div>    
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="inqbox float-e-margins">
            <div class="inqbox-title border-top-danger">
                <h5>
                    <?= $this->lang->line('module_' . $controller_name); ?>
                </h5>
                <div class="inqbox-tools">
                    &nbsp;
                </div>
            </div>
            <div class="inqbox-content table-responsive">

                <div class="row table-body">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-hover table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 1%"><input type="checkbox" class="select_all_" /></th>
                                    <th style="text-align: center"><?= $this->lang->line('common_id') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_type') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_account') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_description') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_amount') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_balance') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_customer') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_agent') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_approver') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_apply_date') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_payment_date') ?></th>
                                    <th style="text-align: center"><?= $this->lang->line('loans_status') ?></th>
                                    <th style="text-align: center; width: 1%"><?= $this->lang->line("common_action"); ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div id="feedback_bar"></div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loan_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>


<script type="text/javascript">
    $(document).ready(function ()
    {
        var count_show = 0;

        $('body').on('hidden.bs.modal', '.modal', function ()
        {
            $(this).removeData('bs.modal');
            count_show = 0;
        });

        $('body').on('shown.bs.modal', '.modal', function ()
        {
            if (count_show === 0)
            {
                $.ajax({
                    type: "GET",
                    url: $("#site_url").val() + "js/loan.js?v=<?= time(); ?>",
                    dataType: "script",
                    cache: true
                });
            }
            count_show++;
        });


        $(document).on("change", "#filter_by", function ()
        {
            oTable.fnReloadAjax('<?php echo site_url("loans/data") ?>' + "/" + $(this).val());
        });


        var oTable = $("#datatable").dataTable({
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0, 13]}
            ],
            "language": {
                "url": "<?php echo site_url($this->config->item('language') . ".json"); ?>"
            },
            "processing": true,
            "serverSide": true,
            "aLengthMenu": [[50, 100, 200, 100000], [50, 100, 200, "<?= $this->lang->line("common_all") ?>"]],
            "iDisplayLength": 50,
            "order": [1, "desc"],
            "ajax": {
                "url": "<?php echo site_url("loans/overdues") ?>"
            }
        });

        enable_delete('<?php echo $this->lang->line($controller_name . "_confirm_delete") ?>', '<?php echo $this->lang->line($controller_name . "_none_selected") ?>');

        $(".select_all_").click(function ()
        {
            if ($(this).is(":checked"))
            {
                $("input[name='chk[]']").prop("checked", true);
            }
            else
            {
                $("input[name='chk[]']").prop("checked", false);
            }
        });

    });

    function post_loan_form_submit(response)
    {
        if (!response.success)
        {
            set_feedback(response.message, 'error_message', true);
        }
        else
        {
            set_feedback(response.message, 'success_message', false);
            $('#datatable').dataTable()._fnAjaxUpdate();
            $('#loan_modal').modal("hide");
        }
    }
</script>