<?php $this->load->view("partial/header"); ?>

<link rel="stylesheet" href="<?php echo site_url('plugins/assets/js/drag-drop-upload/jquery-simple-upload.css') ?>">
<script src="<?php echo site_url('plugins/assets/js/drag-drop-upload/jquery-simple-upload.js') ?>"></script>

<style>
    #plugin_manager_wrapper table th:nth-child(2),
    #plugin_manager_wrapper table td:nth-child(2) {
        width: 180px !important;
    }
    #plugin_manager_wrapper table th:nth-child(4),
    #plugin_manager_wrapper table td:nth-child(4) {
        width: 80px !important;
    }
    #plugin_manager_wrapper .dt-buttons.btn-group {
        width:100%;
    }
</style>


<div class="title-block">
    <h3 class="title"> 

        Plugins - Install

    </h3>
    <p class="title-description">
        Install & un-install plugins
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
                                <div class="inqbox-content">
                                    <div class="tabs-container">

                                    </div>

                                    <table class="table table-bordered" id="plugin_manager">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Status</th>                            
                                            </tr>
                                        </thead>        
                                    </table>

                                    <?= $plugin_manager_table; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<input type="file" name="file" id="basic" style="display: none;">

<?php echo form_open(); ?>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $(document).on("click", "#btn-browse-plugin", function () {
            $("input[type='file']").trigger('click');
        });

        $(document).on("click", ".btn-activate", function () {
            var $this = $(this);
            alertify.confirm("Are you sure you wish to Activate this plugin?", function () {
                var url = '<?php echo site_url('plugins/activate'); ?>/' + $this.data("plugin-id");
                var params = {
                    softtoken: $("input[name='softtoken']").val()
                };
                $.post(url, params, function (data) {
                    if (data.status == "OK")
                    {
                        $("#plugin_manager").DataTable().ajax.reload();
                    }
                }, "json");
            });
        });

        $(document).on("click", ".btn-uninstall-plugin", function () {
            var $this = $(this);
            alertify.confirm("Are you sure you wish to uninstall this plugin?", function () {
                var url = '<?php echo site_url('plugins/uninstall'); ?>/' + $this.data("plugin-id");
                var params = {
                    softtoken: $("#token_hash").val()
                };
                $.post(url, params, function (data) {
                    if (data.status == "OK")
                    {
                        $("#plugin_manager").DataTable().ajax.reload();
                    }
                }, "json");
            });
        });

        $(document).on("click", ".btn-deactivate", function () {
            var $this = $(this);
            alertify.confirm("Are you sure you wish to Deactivate this plugin?", function () {
                var url = '<?php echo site_url('plugins/deactivate'); ?>/' + $this.data("plugin-id");
                var params = {
                    softtoken: $("#token_hash").val()
                };
                $.post(url, params, function (data) {
                    if (data.status == "OK")
                    {
                        $("#plugin_manager").DataTable().ajax.reload();
                    }
                }, "json");
            });
        });

        // Must choose file directly, No need for popup
        $("#plugin_manager_wrapper .dt-buttons.btn-group").html("<div><button class='btn btn-primary pull-left' id='btn-browse-plugin'>Browse</button> &nbsp;<div class='pull-left' style='margin-left: 5px;' id='basic_progress'></div><div id='basic_message' style='margin-left:5px;'></div></div>");

        $('#basic').simpleUpload({
            //dropZone: '#basic_drop_zone',
            progress: '#basic_progress',
            // url to send the data
            url: '<?php echo site_url('plugins/upload'); ?>',
            // submit method
            method: 'post',
            // header key/value pairs
            headers: {},
            // data type
            // e.g. 'application/json'
            dataType: null,
            // AJAX parameters
            params: {
                softtoken: $("#token_hash").val()
            },
            // timeout
            timeout: 0,
            // allows async uploading
            async: true
        }).on('upload:done', function (e, file, i) {
            $("#plugin_manager").DataTable().ajax.reload();
        }).on('upload:fail', function (e, file, i) {
            $('#basic_message').prepend('<p>fail: ' + file.name + '</p>');
        });
    });

    function dt_plugins_init_complete(settings, json) {

    }
</script>

<?php $this->load->view("partial/footer"); ?>