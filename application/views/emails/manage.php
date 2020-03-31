<?php $this->load->view("partial/header"); ?>

<style>
    #tbl_loans_transactions td:nth-child(5),
    #tbl_loans_transactions td:nth-child(6) {
        text-align: right;
    }
    .dataTables_info {
        float:left;
    }
    
    .dataTable th:nth-child(1),
    .dataTable td:nth-child(1) {
        width: 86px !important;
        min-width: 86px !important;
    }
    
    .dataTable th:nth-child(2),
    .dataTable td:nth-child(2) {
        width: 120px !important;
        min-width: 120px !important;
    }
    
    .dataTable th:nth-child(3),
    .dataTable td:nth-child(3) {
        width: 140px !important;
        min-width: 140px !important;
    }
    
    .dataTable th:nth-child(4),
    .dataTable td:nth-child(4) {
        width: 110px !important;
        min-width: 110px !important;
    }
</style>

<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.3/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>

<div class="title-block">
    <h3 class="title">List of Email Templates</h3>
    <p class="title-description">
        Add, update & delete email templates
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

                                    <table class="table table-hover table-bordered" id="tbl_templates">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; width: 1%"></th>
                                                <th style="text-align: center">Template Name</th>
                                                <th style="text-align: center">Description</th>
                                                <th style="text-align: center">Created By</th>                            
                                            </tr>
                                        </thead>
                                    </table>

                                    <?= $tbl_templates; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_open('emails/ajax', 'id="frmTemplateDelete"', ["type" => 2]); ?>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#tbl_templates_filter").prepend("<a href='<?= site_url('emails/view/-1') ?>' class='btn btn-primary pull-left'>New Template</a>");
        $("#tbl_templates_filter input[type='search']").attr("placeholder", "Type your search here");
        $("#tbl_templates_filter input[type='search']").removeClass("input-sm");

        $(document).on("click", ".btn-delete", function () {
            var $this = $(this);
            alertify.confirm("Are you sure you wish to delete this template?", function () {
                var url = $("#frmTemplateDelete").attr("action");
                var params = $("#frmTempateDelete").serialize();
                params += '&ids=' + $this.attr("data-template-id");
                $.post(url, params, function (data) {
                    if (data.success)
                    {
                        $("#tbl_inbox").DataTable().ajax.reload();
                    }
                }, "json");
            });
        });
    });
</script>

<?php $this->load->view("partial/footer"); ?>