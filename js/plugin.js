$(document).ready(function () {

    // Uploader scripts
    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'pickfiles', // you can pass in id...
        container: document.getElementById('container'), // ... or DOM Element itself
        url: $("#site_url").val() + '/plugins/upload',
        unique_names: true,
        resize: {width: 500, height: 500, quality: 100},
        max_retries: 3,
        flash_swf_url: 'Moxie.swf',
        silverlight_xap_url: 'Moxie.xap',
        multipart_params: {
            loan_id: $("#pickfiles").data("loan-id"),
            softtoken: $("#token_hash").val(),
            linker: $("#linker").val()
        },
        filters: {
            max_file_size: '10mb',
            mime_types: [
                {title: "Zip files", extensions: "zip"}
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

    uploader.bind('FileUploaded', function (upldr, file, object) {
        location.reload();
    });

    uploader.init();
});


