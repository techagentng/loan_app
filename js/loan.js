$(document).ready(function () {

    // Uploader scripts
    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'pickfiles', // you can pass in id...
        container: document.getElementById('container'), // ... or DOM Element itself
        url: $("#site_url").val() + '/loans/upload',
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
    
    uploader.bind('BeforeUpload', function(up, file) {
        up.settings.multipart_params = {
            loan_id: $("#pickfiles").data("loan-id"),
            softtoken: $("input[name='softtoken']").val(),
            linker: $("#linker").val()
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
        $("#filelist").append("<li><a href=\"uploads/loan-" + myData.loan_id + "/" + myData.filename + "\" target=\"_blank\" title=\"" + myData.filename + "\"><img src=\"" + myData.icon + "\" /></a><span class=\"close remove-file\" data-file-id=\"" + myData.id + "\" title=\"Remove this file\"><i class=\"fa fa-times-circle\"></i></span></li>");
    });

    uploader.init();
});


