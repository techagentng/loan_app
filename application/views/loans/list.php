<?php $this->load->view("partial/header"); ?>

<style>
    td:nth-child(1) {
        white-space: nowrap
    }
    
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
    .dataTable td:nth-child(2)    
    {
        width: 80px !important;
        min-width: 80px !important;
        text-align: center;
    }
    
    .dataTable th:nth-child(3),
    .dataTable td:nth-child(3)
    {
        width: 110px !important;
        min-width: 110px !important;
    }
    .dataTable th:nth-child(4),
    .dataTable td:nth-child(4) 
    {
        width: 200px !important;
        min-width: 200px !important;
    }
    
    .dataTable th:nth-child(5),
    .dataTable td:nth-child(5),
    .dataTable th:nth-child(6),
    .dataTable td:nth-child(6)
    {
        width: 160px !important;
        min-width: 160px !important;
    }
    .dataTable th:nth-child(7),
    .dataTable td:nth-child(7),
    .dataTable th:nth-child(8),
    .dataTable td:nth-child(8),
    .dataTable th:nth-child(9),
    .dataTable td:nth-child(9)
    {
        width: 95px !important;
        min-width: 95px !important;
    }
     
     
    .dataTable th:nth-child(10),
    .dataTable td:nth-child(10), 
    .dataTable th:nth-child(11),
    .dataTable td:nth-child(11), 
    .dataTable th:nth-child(12),
    .dataTable td:nth-child(12) 
    {
        width: 100px !important;
        min-width: 100px !important;
        text-align: center;
    }
    
    .dataTables_scrollBody {
        max-height: fit-content !important;
        height: auto !important;
    }
</style>

<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.3/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>


<div class="title-block">
    <h3 class="title"> 

        Loan - Transactions

    </h3>
    <p class="title-description">
        Add, update & delete loans
    </p>
</div>


<div class="section">
    <div class="row sameheight-container">

        <div class="col-lg-12">
            <div class="card" style="width:100%; min-height: calc(85vh - 160px);">

                <div class="card-block">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="inqbox float-e-margins">

                                <div class="inqbox-content table-responsive">

                                    <table class="table table-hover table-bordered" id="tbl_loans_transactions">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center; width: 1%"></th>
                                                <th style="text-align: center">Trans. ID#</th>
                                                <th style="text-align: center">Interest <br/>Type</th>
                                                <th style="text-align: center">Description</th>
                                                <th style="text-align: center">Proceeds</th>
                                                <th style="text-align: center">Balance</th>
                                                <th style="text-align: center">Client /<br/>Borrower</th>
                                                <th style="text-align: center">Agent</th>
                                                <th style="text-align: center">Approved <br/>By</th>
                                                <th style="text-align: center">Date <br/>Approved</th>
                                                <th style="text-align: center">Next <br/>Payment<br/> Date</th>
                                                <th style="text-align: center">Status</th>                            
                                            </tr>
                                        </thead>
                                    </table>

                                    <?= $tbl_loan_transactions; ?>

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
    <select class="form-control hidden-xs" id="sel-staff">
        <option value="0">Select employee</option>
        <?php foreach ($staffs as $staff): ?>
            <option value="<?= $staff->person_id; ?>" <?= ((isset($_GET['employee_id'])) && $_GET['employee_id'] === $staff->person_id) ? 'selected="selected"' : ""; ?>><?= $staff->first_name . " " . $staff->last_name; ?></option>
        <?php endforeach; ?>
    </select>&nbsp;

    <select id="filter_by" class="form-control hidden-xs">
        <option value="all">All</option>
        <option value="paid">Paid</option>
        <option value="unpaid">Un-Paid</option>
        <option value="overdue">Overdue</option>
    </select>
</div>

<div id="dt-extra-params">
    <input type="hidden" id="status" name="status" value="all" />
    <input type="hidden" id="employee_id" name="employee_id" value="0" />
</div>

<?php echo form_open('loans/ajax', 'id="frmLoansDelete"', ["ajax_type" => 4]); ?>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#tbl_loans_transactions_filter").prepend("<a href='<?= site_url('loans/view/-1') ?>' class='btn btn-primary pull-left'>New Loan</a>");
        $("#tbl_loans_transactions_filter input[type='search']").attr("placeholder", "Type your search here");
        $("#tbl_loans_transactions_filter input[type='search']").removeClass("input-sm");
        $("#tbl_loans_transactions_filter").append($(".extra-filters").html());
        
        $(document).on("change", "#filter_by", function () {
            $("#status").val($(this).val());
            $("#tbl_loans_transactions").DataTable().ajax.reload();
        });

        $(document).on("change", "#sel-staff", function () {
            $("#employee_id").val($(this).val());
            $("#tbl_loans_transactions").DataTable().ajax.reload();
        });

        $(document).on("click", ".btn-delete", function () {
            var $this = $(this);
            alertify.confirm("Are you sure you wish to delete this transaction?", function () {
                var url = $("#frmLoansDelete").attr("action");
                var params = $("#frmLoansDelete").serialize();
                params += '&id=' + $this.attr("data-loan-id");
                $.post(url, params, function (data) {
                    if (data.status == "OK")
                    {
                        $("#tbl_loans_transactions").DataTable().ajax.reload();
                    }
                }, "json");
            });
        });
    });
</script>

<?php $this->load->view("partial/footer"); ?>