<?php $this->load->view("partial/header"); ?>
<style>
    table#datatable td:nth-child(6) {
        width: 5%;
    }   
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="inqbox float-e-margins">
            <div class="inqbox-content">
                <h2>My Mail</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?= site_url(); ?>">Home</a>
                    </li>
                    <li>
                        <a>Mailbox</a>
                    </li>
                    <li class="active">
                        <strong>Inbox</strong>
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
                    Inbox
                </h5>
                <div class="inqbox-tools">
                    <?= anchor("messages/delete", $this->lang->line("common_delete"), array('id' => 'delete', 'class' => 'btn btn-primary btn-xs', 'style' => 'color:white')); ?>
                    <?= anchor("messages/view/-1", "<div class='btn btn-primary btn-xs' style='float: left; margin-right:10px;'><span>" . $this->lang->line('message_new') . "</span></div>"); ?>
                </div>
            </div>
            <div class="inqbox-content table-responsive">
                <table id="datatable" class="table table-hover table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 1%"><input type="checkbox" class="select_all_" /></th>
                            <th style="text-align: center"><?= $this->lang->line('message_header') ?></th>                   
                            <th style="text-align: center"><?= $this->lang->line('message_sender_name') ?></th>
                            <th style="text-align: center">Date</th>                            
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
    $(document).ready(function () {

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $("#datatable").dataTable({
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0,4]}
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
                "url": "<?php echo site_url("messages/data_inbox") ?>",
                data: {employee_id: '<?= isset($_GET['employee_id']) ? $_GET['employee_id'] : false; ?>'},
                type: 'get'
            }
        });

        $(document).on("change", "#sel-staff", function () {
            location.href = "<?= site_url($this->uri->segment(1)) ?>?employee_id=" + $(this).val();
        });

        enable_delete('<?php echo $this->lang->line($controller_name . "_confirm_delete") ?>', '<?php echo $this->lang->line($controller_name . "_none_selected") ?>');

        $('#generate_barcodes').click(function () {
            var selected = get_selected_values();
            if (selected.length == 0)
            {
                alert('<?php echo $this->lang->line('items_must_select_item_for_barcode'); ?>');
                return false;
            }

            $(this).attr('href', 'index.php/item_kits/generate_barcodes/' + selected.join(':'));
        });

        $(".select_all_").click(function () {
            if ($(this).is(":checked"))
            {
                $(".select_").prop("checked", true);
            }
            else
            {
                $(".select_").prop("checked", false);
            }
        });

    });


</script>