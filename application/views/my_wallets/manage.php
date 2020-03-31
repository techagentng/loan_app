<?php $this->load->view("partial/header"); ?>
<style>
    table#datatable td:nth-child(6) {
        width: 15%;
    }
    .dataTable th:nth-child(1),
    .dataTable td:nth-child(1) {
        width: 45px !important;
        min-width: 45px !important;
    }
    
    .dataTable th:nth-child(2),
    .dataTable td:nth-child(2)
    {
        width: 110px !important;
        min-width: 110px !important;
        text-align: right;
    }
    
    .dataTable th:nth-child(3),
    .dataTable td:nth-child(3)
    {
        width: 150px !important;
        min-width: 150px !important;
    }
    
    .dataTable th:nth-child(4),
    .dataTable td:nth-child(4),
    .dataTable th:nth-child(5),
    .dataTable td:nth-child(5)
    {
        width: 100px !important;
        min-width: 100px !important;
        text-align: center
    }
</style>


<div class="title-block">
    <h3 class="title">
        My Wallet (<span id="wallet_total"><?php echo to_currency($wallet_total); ?></span>)
        <input type="hidden" id="available_amount" value="<?= $wallet_total ?>" />
    </h3>
    <p class="title-description">

    </p>
</div>


<div class="section">
    <div class="row sameheight-container">

        <div class="col-lg-12">
            <div class="card" style="width:100%">

                <div class="card-block">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="inqbox float-e-margins">
                                
                                <div class="inqbox-content table-responsive">

                                    <table id="tbl_wallets" class="table table-hover table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; width: 1%"></th>
                                                <th style="text-align: center"><?= $this->lang->line('my_wallet_amount') ?></th>
                                                <th style="text-align: center"><?= $this->lang->line('my_wallet_description') ?></th>
                                                <th style="text-align: center"><?= $this->lang->line('my_wallet_type') ?></th>
                                                <th style="text-align: center"><?= $this->lang->line('my_wallet_trans_date') ?></th>                    
                                            </tr>
                                        </thead>
                                    </table>
                                    
                                    <?=$tbl_wallets; ?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="extra-filters" style="display: none;">
    <select class="form-control input-sm hidden-xs" id="sel-staff">
        <option value="0">Select staff</option>
        <?php foreach ($staffs as $staff): ?>
            <option value="<?= $staff->person_id; ?>" <?= ((isset($_GET['employee_id'])) && $_GET['employee_id'] === $staff->person_id) ? 'selected="selected"' : ""; ?>><?= $staff->first_name . " " . $staff->last_name; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div id="feedback_bar"></div>

<?php $this->load->view("partial/footer"); ?>

<?php echo form_open();?>
<?php echo form_close();?>

<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#tbl_wallets_filter").prepend("<a href='<?= site_url('my_wallets/view/-1') ?>' class='btn btn-primary pull-left' data-toggle='modal' data-target='#wallet_modal'>Add amount</a>");
        $("#tbl_wallets_filter input[type='search']").attr("placeholder", "Type your search here");
        $("#tbl_wallets_filter input[type='search']").removeClass("input-sm");
        $("#tbl_wallets_filter").append($(".extra-filters").html());
        
        $("#sel-staff").on("change", function () {
            location.href = "<?= site_url($this->uri->segment(1)) ?>?employee_id=" + $(this).val();
        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $(document).on("change", "#sel-staff", function () {
            location.href = "<?= site_url($this->uri->segment(1)) ?>?employee_id=" + $(this).val();
        });

        $(document).on("click", '.btn-delete', function (event) {
            var $this = $(this);
            
            alertify.confirm('<?php echo $this->lang->line("my_wallet_confirm_delete") ?>', function () {
                var url = '<?= site_url('my_wallets/ajax'); ?>';
                var params = {
                    ids: [$this.attr("data-wallet-id")], 
                    softtoken: $("input[name='softtoken']").val(),
                    type: 2
                };
                
                $.post(url, params, function (response)
                {
                    //delete was successful, remove checkbox rows
                    if (response.success)
                    {
                        $("#tbl_wallets").DataTable().ajax.reload();
                        
                        $("#wallet_total").html(response.wallet_amount);
                        $("#available_amount").val(response.wallet_total);
                        set_feedback(response.message, 'success_message', false);
                    } else
                    {
                        set_feedback(response.message, 'error_message', true);
                    }

                }, "json");
            });
        });

        $(".select_all_").click(function () {
            if ($(this).is(":checked"))
            {
                $(".select_").prop("checked", true);
            } else
            {
                $(".select_").prop("checked", false);
            }
        });

    });
</script>




























<div id="table_action_header">
    <div class="row">
        <div class="col-xs-3">
            <?php echo anchor("$controller_name/delete", $this->lang->line("common_delete"), array('id' => 'delete-wallet', 'class' => 'btn btn-primary')); ?>
        </div>
        <div class="col-xs-9">
            <div class="pull-right">
                <select class="form-control" id="sel-staff">
                    <option value="0">Select staff</option>
                    <?php foreach ($staffs as $staff): ?>
                        <option value="<?= $staff->person_id; ?>" <?= ((isset($_GET['employee_id'])) && $_GET['employee_id'] === $staff->person_id) ? 'selected="selected"' : ""; ?>><?= $staff->first_name . " " . $staff->last_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="wallet_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


