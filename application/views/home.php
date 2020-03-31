
<?php $this->load->view("partial/header"); ?>

<section class="section">
    <div class="row sameheight-container">
        <div class="col col-12 col-sm-12 col-md-4 col-xl-4">
            <div class="card sameheight-item" data-exclude="xs" id="dashboard-history" style="height: 325px;">
                <div class="card-header card-header-sm bordered">
                    <div class="header-block" style="width:100%">
                        <h3 class="title">
                            <h5 style="display:inline-block"><?= $this->lang->line('common_my_wallet'); ?></h5>
                            <span class="label label-primary pull-right">Annual</span>
                        </h3>
                    </div>                   
                </div>
                <div class="card-block">
                    <div class="inqbox float-e-margins">
                        <div class="inqbox-title border-top-primary">

                        </div>
                        <div class="inqbox-content">
                            <h1 class="no-margins"><?php echo $my_wallet; ?></h1>
                            <div class="stat-percent font-bold text-primary">20% <i class="fa fa-level-up"></i></div>
                            <small><?= $this->lang->line('common_total_wallet'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-sm-12 col-md-4 col-xl-4">
            <div class="card sameheight-item" data-exclude="xs" id="dashboard-history" style="height: 325px;">
                <div class="card-header card-header-sm bordered">
                    <div class="header-block" style="width:100%">
                        <h3 class="title">
                            <h5 style="display:inline-block"><?= $this->lang->line('common_loans'); ?></h5>
                            <span class="label label-info pull-right">Annual</span>
                        </h3>
                    </div>                   
                </div>
                <div class="card-block">
                    <div class="inqbox float-e-margins">
                        <div class="inqbox-content">
                            <h1 class="no-margins"><?= $total_loans; ?></h1>
                            <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                            <small><?= $this->lang->line('common_total_loans'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-sm-12 col-md-4 col-xl-4">
            <div class="card sameheight-item" data-exclude="xs" id="dashboard-history" style="height: 325px;">
                <div class="card-header card-header-sm bordered">
                    <div class="header-block" style="width:100%">
                        <h3 class="title">
                            <h5 style="display:inline-block"><?= $this->lang->line('common_borrowers'); ?></h5>
                            <span class="label label-info pull-right">Monthly</span>
                        </h3>
                    </div>                   
                </div>
                <div class="card-block">
                    <div class="inqbox float-e-margins">
                        <div class="inqbox-content">
                            <h1 class="no-margins"><?= $total_borrowers; ?></h1>
                            <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                            <small><?= $this->lang->line('common_borrowers'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="row">
    <div class="col-lg-4">

    </div>

    <div class="col-lg-4">

    </div>
    <div class="col-lg-4">

    </div>
</div>

<?php $this->load->view("partial/footer"); ?>