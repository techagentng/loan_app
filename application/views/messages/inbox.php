<?php $this->load->view("partial/header"); ?>

<style>
    #tbl_loans_transactions td:nth-child(5),
    #tbl_loans_transactions td:nth-child(6) {
        text-align: right;
    }
    .dataTables_info {
        float:left;
    }
</style>

<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.3/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>

<div class="title-block">
    <h3 class="title">Inbox</h3>
    <p class="title-description">
        Add, update & delete inbox
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

                                    <table class="table table-hover table-bordered" id="tbl_inbox">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; width: 1%"></th>
                                                <th style="text-align: center">Subject</th>
                                                <th style="text-align: center">Sender Name</th>
                                                <th style="text-align: center">Date</th>                            
                                            </tr>
                                        </thead>
                                    </table>

                                    <?= $tbl_inbox; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_open('messages/ajax', 'id="frmMessageDelete"', ["type" => 2]); ?>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#tbl_inbox_filter").prepend("<a href='<?= site_url('messages/view/-1') ?>' class='btn btn-primary pull-left'>New Message</a>");
        $("#tbl_inbox_filter input[type='search']").attr("placeholder", "Type your search here");
        $("#tbl_inbox_filter input[type='search']").removeClass("input-sm");

        $(document).on("click", ".btn-delete", function () {
            var $this = $(this);
            alertify.confirm("Are you sure you wish to delete this message?", function () {
                var url = $("#frmMessageDelete").attr("action");
                var params = $("#frmMessageDelete").serialize();
                params += '&ids=' + $this.attr("data-message-id");
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