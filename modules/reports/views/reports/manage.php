<?php $this->load->view("partial/header"); ?>
<style>
    table#datatable td:nth-child(6) {
        width: 15%;
    }   
</style>
<div id="title_bar">
    <div id="title" class="float_left"><i class="fa fa-sitemap" style="font-size: 40px; color:#e80a0a"></i> <?php echo $this->lang->line('common_list_of') . ' ' . $this->lang->line('module_' . $controller_name); ?></div>
    <div id="new_button">
        <?php
        echo anchor("$controller_name/view/-1", "<div class='btn btn-primary' style='float: left; margin-right:20px;'><span>" . $this->lang->line($controller_name . '_new') . "</span></div>", array('class' => 'thickbox none', 'data-toggle' => 'modal', 'data-target' => '#loan_type_modal', 'title' => $this->lang->line($controller_name . '_new')));
        ?>
    </div>
</div>

<?php echo $this->pagination->create_links(); ?>
<div id="table_action_header">
    <div class="row">
        <div class="col-xs-3">
            <?php echo anchor("$controller_name/delete", $this->lang->line("common_delete"), array('id' => 'delete', 'class' => 'btn btn-primary')); ?>
        </div>
        <div class="col-xs-3">
            &nbsp;
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loan_type_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="row table-body">
    <div class="col-md-12">
        <table id="datatable" class="table table-hover table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th style="text-align: center; width: 1%"><input type="checkbox" class="select_all_" /></th>
                    <?php foreach($fields as $field): ?>
                    <th style="text-align: center"><?= $this->lang->line($field) ?></th>
                    <?php endforeach; ?>
                    
                    <th style="text-align: center; width: 1%">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript">
    $(document).ready(function ()
    {

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $("#datatable").dataTable({
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0, 3]}
            ],
            "processing": true,
            "serverSide": true,
            "aLengthMenu": [[50, 100, 200, 100000], [50, 100, 200, "All"]],
            "iDisplayLength": 50,
            "order": [1, "desc"],
            "ajax": {
                "url": "<?php echo site_url("$controller_name/data") ?>"
            },
            "initComplete": function (settings, json) {
                $("#datatable_filter").find("input[type='search']").attr("placeholder", "<?= $this->lang->line("common_search") ?>");
            }
        });

        enable_delete('<?php echo $this->lang->line($controller_name . "_confirm_delete") ?>', '<?php echo $this->lang->line($controller_name . "_none_selected") ?>');

        $('#generate_barcodes').click(function ()
        {
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

    function post_form_submit(response)
    {
        if (!response.success)
        {
            set_feedback(response.message, 'error_message', true);
        }
        else
        {
            set_feedback(response.message, 'success_message', false);
            $('#datatable').dataTable()._fnAjaxUpdate();
            $('#loan_type_modal').modal("hide");
        }
    }
</script>