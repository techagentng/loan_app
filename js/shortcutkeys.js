$(document).ready(function () {

    var is_payment = false;
    var is_enter = false;

    $(document).on("click", '#register tbody tr', function (e) {
        

        var nth = 6;
        
        if($(e.target).prop("tagName") !== "INPUT")
        {
            highlight($(this).index());
        }

        if ($(e.target).prop("tagName") !== "TD")
        {
            nth = $(e.target).parent().parent().index();
        }
        else
        {
            nth = $('td', this).index(e.target);
        }

        if (nth === 4 && $("#toggle_box").val() === "0")
        {
            $("#toggle_box").val("1");
            changeQuantity();            
        }

        if (nth === 5 && $("#toggle_box").val() === "0")
        {
            $("#toggle_box").val("1");
            changeDiscount();
        }

    });

    $(document).on("blur", ".inp-quantity", function () {
        
        if (!is_enter)
        {
            $(this).parent().parent().submit();
        }
        
        is_enter = false;
        
    });
    
    $(document).on("blur", ".inp-discount", function () {
        if (!is_enter)
        {
            $(this).parent().parent().submit();
        }
        
        is_enter = false;
       
    });

    $(document).on("focus", "input:text", function () {
        $(this).select();
    });


    $(document).on("click", "#btn-checkout", function () {
        is_payment = true;
    });

    // keypress
    $(document).keydown(function (e) {

        console.log(e.keyCode);
        if (e.keyCode === 18)
        {
            e.preventDefault();
        }

        if (e.keyCode === 77 && e.ctrlKey) {
            e.preventDefault();
            $("#reg-mode").focus();
            is_payment = false;
        }

        if (e.keyCode === 67 && e.ctrlKey) {
            e.preventDefault();
            $("#customer").focus();
            is_payment = false;
        }

        if (e.keyCode === 79 && e.ctrlKey) {
            e.preventDefault();
            $("#btn-add-customer").trigger("click");
        }

        if (e.keyCode === 82 && e.ctrlKey) {
            e.preventDefault();
            $('#a-remove-customer')[0].click();
            is_payment = false;
        }

        if (e.keyCode === 83 && e.ctrlKey) {
            e.preventDefault();
            $("#item").focus();
            is_payment = false;
        }

        if (e.keyCode === 80 && e.ctrlKey) {
            e.preventDefault();
            $("#btn-checkout").trigger("click");
        }

        if (e.keyCode === 27) {
            e.preventDefault();
            $(".close").trigger("click");
        }

        $(".close, .md-close").click(function () {
            is_payment = false;
        });

        if (e.keyCode === 86 && e.ctrlKey) {
            e.preventDefault();
            $("#btn-suspended-sales").trigger("click");
        }

        if (e.keyCode === 35) {
            e.preventDefault();
            $("#finish_sale_button").trigger("click");
        }

        if (e.keyCode === 36) {
            e.preventDefault();
            $("#suspend_sale_button").trigger("click");
        }

        if (e.keyCode === 46 && e.shiftKey) {
            e.preventDefault();
            $("#cancel_sale_button").trigger("click");
        }

        if (e.keyCode === 112) {
            e.preventDefault();
        }

        if (e.keyCode === 113) { // change quantity
            e.preventDefault();
            changeQuantity();
            is_payment = false;
        }

        if (e.keyCode === 114) { // change discount
            e.preventDefault();
            changeDiscount();
            is_payment = false;
        }

        if (e.keyCode === 115) {
            e.preventDefault();
            $("#sales_invoice_number").focus();
            is_payment = true;
        }

        if (e.keyCode === 116 && is_payment) // F5 select payments
        {
            e.preventDefault();
            $("#payment_types").focus();

            var element = $("#payment_types")[0], worked = false;
            if (document.createEvent) { // all browsers
                var e = document.createEvent("MouseEvents");
                e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                worked = element.dispatchEvent(e);
            } else if (element.fireEvent) { // ie
                worked = element.fireEvent("onmousedown");
            }
            if (!worked) { // unknown browser / error
                alert("It didn't worked in your browser.");
            }

            is_payment = true;
        }

        if (e.keyCode === 117) {
            e.preventDefault();
            $("#amount_tendered").focus();
            is_payment = true;
        }
        
        if (e.keyCode === 13 && !is_payment)
        {
            is_enter = true;
        }

        if (e.keyCode === 13 && is_payment) {
            e.preventDefault();
            $("#add_payment_button").trigger("click");
        }

        if (e.keyCode === 76 && e.altKey) {
            $("#logout_button")[0].click();
        }

        if (e.keyCode === 38 && !is_payment)
        {
            $('#goto_prev').trigger('click');
        }

        if (e.keyCode === 40 && !is_payment)
        {
            $('#goto_next').trigger('click');
        }

        // for payments window 
        if (e.keyCode === 38 && is_payment)
        {
            $('#goto_prev2').trigger('click');
        }

        if (e.keyCode === 40 && is_payment)
        {
            $('#goto_next2').trigger('click');
        }

        if (e.keyCode === 13 && is_payment)
        {
            e.preventDefault();
        }

        if (e.keyCode === 46 && !e.shiftKey) // delete
        {
            e.preventDefault();

            if (!is_payment)
            {
                var index = $('#register tbody tr.highlight').index();
                $('#register tbody tr:eq(' + index + ') td:first-child').find("a").focus();
            }

            var focused = $(':focus');
            if (focused.hasClass("a-delete"))
            {
                deleteRow(focused);
            }
        }

        if (e.keyCode === 13)
        {
            var focused = $(':focus');
            if (focused.hasClass("a-delete"))
            {
                e.preventDefault();
            }
        }

    });

    highlight(0);
    highlight2(0);
});

function changeQuantity()
{
    var index = $('#register tbody tr.highlight').index();
    $("#quantity-" + index).hide();
    $("#quantity-" + index).html("");
    $("#input-quantity-" + index).show();
    $("#input-quantity-" + index).find("input").focus();
}

function changeDiscount()
{
    var index = $('#register tbody tr.highlight').index();
    $("#discount-" + index).hide();
    $("#discount-" + index).html("");
    $("#input-discount-" + index).show();
    $("#input-discount-" + index).find("input").focus();
}

function highlight(tableIndex) {
    // Just a simple check. If .highlight has reached the last, start again
    if ((tableIndex + 1) > $('#register tbody tr').length)
        tableIndex = 0;

    // Element exists?
    if ($('#register tbody tr:eq(' + tableIndex + ')').length > 0)
    {
        // Remove other highlights
        $('#register tbody tr').removeClass('highlight');

        // Highlight your target
        $('#register tbody tr:eq(' + tableIndex + ')').addClass('highlight');
        $('#register tbody tr:eq(' + tableIndex + ') td:first-child').find("a").focus();
        $("#cur_cursor").val(tableIndex);
    }
}

function highlight2(tableIndex) {
    console.log(tableIndex + " " + $('#all-payments tbody tr').length)
    // Just a simple check. If .highlight has reached the last, start again
    if ((tableIndex + 1) > $('#all-payments tbody tr').length)
        tableIndex = 0;

    // Element exists?
    if ($('#all-payments tbody tr:eq(' + tableIndex + ')').length > 0)
    {
        // Remove other highlights
        $('#all-payments tbody tr').removeClass('highlight');

        // Highlight your target
        $('#all-payments tbody tr:eq(' + tableIndex + ')').addClass('highlight');
        $('#all-payments tbody tr:eq(' + tableIndex + ') td:first-child').find("a").focus();
    }
}

$('#goto_first').click(function () {
    highlight(0);
});

$('#goto_prev').click(function () {
    highlight($('#register tbody tr.highlight').index() - 1);
});

$('#goto_next').click(function () {
    highlight($('#register tbody tr.highlight').index() + 1);
});

$('#goto_last').click(function () {
    highlight($('#register tbody tr:last').index());
});



$('#goto_prev2').click(function () {
    highlight2($('#all-payments tbody tr.highlight').index() - 1);
});

$('#goto_next2').click(function () {
    highlight2($('#all-payments tbody tr.highlight').index() + 1);
});
