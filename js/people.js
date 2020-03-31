$(document).ready(function () {

    init_profile_uploader();

    init_attachment_uploader();

    // Operation scripts
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

    $("#btn-add-row").click(function () {
        $(".select_all_").prop("checked", false);

        var rowCount = $('#tbl-income-sources tr').length;
        if (rowCount > 1)
        {
            $("#tbl-income-sources tbody").append("<tr>" + $('#tbl-income-sources tr:last').html() + "</tr>");
        }
        else
        {
            $("#tbl-income-sources tbody").append("<tr><td><input type='checkbox' class='select_' /></td><td><input type='text' class='form-control' name='sources[]' /></td><td><input type='number' class='form-control' name='values[]' /></td></tr>");
        }
    });

    $("#btn-del-row").click(function () {
        $('.select_').each(function () {
            if ($(this).is(":checked")) {
                $(this).parent().parent().remove();
            }
        });
    });
});

function init_profile_uploader()
{
    // Uploader scripts
    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'pickfiles', // you can pass in id...
        container: document.getElementById('container'), // ... or DOM Element itself
        url: $("#site_url").val() + '/customers/upload_profile_pic',
        unique_names: true,
        resize: {width: 500, height: 500, quality: 100},
        max_retries: 3,
        flash_swf_url: 'Moxie.swf',
        silverlight_xap_url: 'Moxie.xap',
        filters: {
            max_file_size: '10mb',
            mime_types: [
                {title: "Image files", extensions: "jpg,gif,png"},
                {title: "Zip files", extensions: "zip"}
            ]
        },
        init: {
            PostInit: function () {
                document.getElementById('filelist').innerHTML = '';
            },
            FilesAdded: function (up, files) {
                $(".progress").hide();
                $(".progress-bar").width(0);
                up.start();
            },
            UploadProgress: function (up, file) {
                $(".progress").show();
                $(".progress-bar").width(file.percent);
            },
            UploadComplete: function (up, files) {
                plupload.each(files, function (file) {
                    var src = BASE_URL + "/uploads/profile-" + $("#pickfiles").data("person-id") + "/" + file.target_name;
                    $("#img-pic").attr("src", src);
                });
            },
            Error: function (up, err) {
                document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
            }
        }
    });
    
    uploader.bind('BeforeUpload', function(up, file) {
        up.settings.multipart_params = {
            user_id: $("#pickfiles").data("person-id"),
            softtoken: $("input[name='softtoken']").val()
        };
    });
    
    uploader.bind('FileUploaded', function (upldr, file, object) {
        var myData;
        try {
            myData = eval(object.response);
        } catch (err) {
            myData = eval('(' + object.response + ')');
        }
        
        $("input[name='softtoken']").val(myData.token_hash);
    });

    uploader.init();
}

function init_attachment_uploader()
{
    // Uploader scripts
    var uploader_a = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'pickfiles_a', // you can pass in id...
        container: document.getElementById('container_a'), // ... or DOM Element itself
        url: $("#site_url").val() + '/customers/upload_attachment',
        unique_names: true,
        resize: {width: 500, height: 500, quality: 100},
        max_retries: 3,
        flash_swf_url: 'Moxie.swf',
        silverlight_xap_url: 'Moxie.xap',
        filters: {
            max_file_size: '10mb',
            mime_types: [
                {title: "All files", extensions: "jpg,gif,png,xls,xlsx,csv,doc,docx,pdf"},
            ]
        },
        init: {
            FilesAdded: function (up, files) {
                $(".progress").hide();
                $(".progress-bar").width(0);
                up.start();
            },
            UploadProgress: function (up, file) {
                $(".progress").show();
                $(".progress-bar").width(file.percent);
            },
            Error: function (up, err) {
                console.log("\nError #" + err.code + ": " + err.message);
            }
        }
    });
    
    uploader_a.bind('BeforeUpload', function(up, file) {
        up.settings.multipart_params = {
            customer_id: $("#pickfiles_a").data("customer-id"),
            softtoken: $("input[name='softtoken']").val(),
            linker: $("#linker").val()
        };
    });

    uploader_a.bind('FileUploaded', function (upldr, file, object) {
        var myData;
        try {
            myData = eval(object.response);
        } catch (err) {
            myData = eval('(' + object.response + ')');
        }

        $("input[name='softtoken']").val(myData.token_hash);
        $("#filelist_a").append("<li><a href=\"" + BASE_URL + "uploads/customer-" + myData.customer_id + "/" + myData.filename + "\" target=\"_blank\" title=\"" + myData.filename + "\"><img src=\"" + BASE_URL + myData.icon + "\" /></a><span class=\"close remove-file\" data-file-id=\"" + myData.id + "\" title=\"Remove this file\"><i class=\"fa fa-times-circle\"></i></span></li>");
    });

    uploader_a.init();
}


