<?php $this->load->view("partial/header"); ?>
<style>
    table#datatable td:nth-child(5), 
    td:nth-child(6) {
        text-align: right
    }
    table#datatable td:nth-child(2),
    td:nth-child(7), 
    td:nth-child(9) {
        white-space: nowrap;
        text-align: center;
    }
</style>

<div class="hide-staff" style="display: none;">
    <select class="form-control input-sm hidden-xs" id="sel-staff">
        <option value="0">Select staff</option>
        <?php foreach ($staffs as $staff): ?>
            <option value="<?= $staff->person_id; ?>" <?= ((isset($_GET['employee_id'])) && $_GET['employee_id'] === $staff->person_id) ? 'selected="selected"' : ""; ?>><?= $staff->first_name . " " . $staff->last_name; ?></option>
        <?php endforeach; ?>
    </select>
</div>

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
                        <a>Payments</a>
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

                    <?php echo anchor("$controller_name/delete", $this->lang->line("common_delete"), array('id' => 'delete', 'class' => 'btn btn-xs btn-danger', 'style' => 'color:white')); ?>
                    <?= anchor("$controller_name/view/-1", "<div class='btn btn-primary btn-xs' style='float: left; margin-right:10px;'><span>" . $this->lang->line($controller_name . '_new') . "</span></div>"); ?>

                </div>
            </div>
            <div class="inqbox-content table-responsive">

                <table id="datatable" class="table table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 1%"><input type="checkbox" class="select_all_" /></th>
                            <th style="text-align: center"><?= $this->lang->line('common_trans_id') ?></th>
                            <th style="text-align: center"><?= $this->lang->line('loans_customer') ?></th>
                            <th style="text-align: center"><?= $this->lang->line('payments_loan') ?></th>
                            <th style="text-align: center"><?= $this->lang->line('loans_balance') ?></th>                    
                            <th style="text-align: center"><?= $this->lang->line('payments_amount') ?></th>
                            <th style="text-align: center"><?= $this->lang->line('payments_date_paid') ?></th>
                            <th style="text-align: center"><?= $this->lang->line('payments_teller') ?></th>
                            <th style="text-align: center; width: 1%"><?= $this->lang->line("common_action"); ?></th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>


<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#datatable").dataTable({
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0, 8]}
            ],
            "language": {
                "url": "<?php echo base_url($this->config->item('language') . ".json"); ?>"
            },
            "processing": true,
            "serverSide": true,
            "aLengthMenu": [[50, 100, 200, 100000], [50, 100, 200, "<?= $this->lang->line("common_all") ?>"]],
            "iDisplayLength": 50,
            "order": [1, "desc"],
            "ajax": {
                "url": "<?php echo site_url("payments/data") ?>",
                data: {employee_id:'<?=isset($_GET['employee_id'])?$_GET['employee_id']:false;?>'},
                type: 'get'
            },
            "initComplete": function (settings, json) {
                $("#datatable_filter").find("input[type='search']").attr("placeholder", "<?= $this->lang->line("common_search"); ?>");
                var el = $(".dataTables_filter").find('label');
                    el.append("&nbsp;");                    
                    el.append($(".hide-staff").html());
            }
        });

        $(document).on("change", "#sel-staff", function(){
            location.href = "<?=site_url($this->uri->segment(1))?>?employee_id=" + $(this).val();
        });
        
        enable_delete('<?php echo $this->lang->line($controller_name . "_confirm_delete") ?>', '<?php echo $this->lang->line($controller_name . "_none_selected") ?>');

        $(".select_all_").click(function () {
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
</script>