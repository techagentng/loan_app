function blockElement(selector) {
    $(selector).block({
        message: '<div><img src="' + BASE_URL + 'images/ajax-loader-big.gif"/></div>',
        css: {border: '0px', 'background-color': 'transparent', position: 'absolute'},
        overlayCSS: {opacity: 0.04, cursor: 'pointer', position: 'absolute'}
    });
}

function unblockElement(selector) {
    $(selector).unblock();
}

function get_dimensions() {
    var dims = {width: 0, height: 0};

    if (typeof (window.innerWidth) == 'number')
    {
        //Non-IE
        dims.width = window.innerWidth;
        dims.height = window.innerHeight;
    }
    else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight))
    {
        //IE 6+ in 'standards compliant mode'
        dims.width = document.documentElement.clientWidth;
        dims.height = document.documentElement.clientHeight;
    }
    else if (document.body && (document.body.clientWidth || document.body.clientHeight))
    {
        //IE 4 compatible
        dims.width = document.body.clientWidth;
        dims.height = document.body.clientHeight;
    }

    return dims;
}

function set_feedback(text, classname, keep_displayed) {
    // Display a success toast, with a title
    if (classname === "success_message")
    {
        toastr.success(text);
        location.reload();
    }
    else
    {
        toastr.error(text);
    }
}