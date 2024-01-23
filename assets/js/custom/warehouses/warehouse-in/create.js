$(document).ready(function () {
    if (typeof (Storage) !== "undefined" && sessionStorage.getItem("savedWareHouseIn")) {
        var savedWareHouseIn = sessionStorage.getItem("savedWareHouseIn");
        var customError = $('.custom-error:visible');
        if (savedWareHouseIn && customError.length > 0) {
            $("#myTable1 tbody").html(savedWareHouseIn);

            $('#myTable1 tbody .weight').each(function () {
                const inputId = $(this).attr('id');
                const storedValue = sessionStorage.getItem(inputId);
                if (storedValue !== null) {
                    $(this).val(storedValue).trigger('input');
                }
            });
        }
    }
    $('#myTable1').dataTable();
});

pallet_id = [];

function addPellete(value) {

    $.ajax({
        type: "GET",
        url: route_pellete,
        data: {
            search: value,
        },
        success: function (data) {
            $('#exampleModal').modal('hide');
            if (data == 'Not Get') {
                $('#pallet_error').find('ul').remove();
                $('#pallet_error').append('<ul class="mt-2 ml-2 text-danger"><li>Pellete not found</li></ul>')
            } else {
                if (pallet_id.includes(data.pellete.id)) {
                    $('#pallet_error').find('ul').remove();
                    $('#pallet_error').append('<ul class="mt-2 ml-2 text-danger"><li>Pellete already here</li></ul>')
                } else {
                    $length = $('#myTable1 tbody tr').length;
                    $('#myTable1').dataTable().fnDestroy();
                    $('#myTable1 tbody').append(`<tr><td><input value="${data.pellete.id}" hidden name="pelletes[${$length}][id]" class="form-control pellete_id">${data.pellete.pellete_no}</td><td><input readonly name="pelletes[${$length}][weight]" type="number" value="${data.pellete.weight}" class="form-control weight" id="weight_${$length}}"></td><td><input readonly name="pelletes[${$length}][pcs]" type="number" class="form-control pcs" value="${data.pellete.pcs}"></td><td><a class="delete_row"><iconify-icon icon="fluent:delete-dismiss-24-filled" width="20" height="20" style="color: red;"></iconify-icon><a></td></tr>`);
                    $('#myTable1').dataTable();
                    pallet_id.push(data.pellete.id);
                    $('#pallet_error').find('ul').remove();
                }
            }
        },
    });

}


function onScanSuccess(qrCodeMessage) {
    addPellete(qrCodeMessage);
}

var html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
        fps: 10,
        qrbox: 250
    });
html5QrcodeScanner.render(onScanSuccess);

$("#search").click(function () {
    $(this).attr('disabled', 'disabled');
    var value = $('#searchPellete').val();
    addPellete(value);
    $(this).removeAttr('disabled');
});

$(document).on('click', '.delete_row', function () {
    $('#myTable1').dataTable().fnDestroy();
    temp = $(this).closest('tr').find('.pellete_id').val();
    pallet_id = pallet_id.filter(function (item) {
        return item !== parseInt(temp);
    });
    $(this).closest('tr').remove();
    $('#myTable1').dataTable();
});

$('#batch_id').on('change', function () {
    const id = $(this).val();
    $.ajax({
        type: 'GET',
        url: route,
        data: {
            "id": id
        },
        success: function (data) {
            if (data.weight_unit) {
                $('#weight_unit').val(data.weight_unit[0]);
            } else {
                $('#weight_unit').val(0);
            }
            if (data.production) {
                $('#part_name').val(data.production.product.name);
                $('#customer_name').val(data.production.purchase_order.customer);
            } else {
                $('#part_name').val('not in production');
                $('#customer_name').val('not in production');
            }
        }
    });
});

$('.submit').click(function () {

    if (typeof (Storage) !== "undefined") {
        var tbody = $('#myTable1 tbody').html();
        sessionStorage.setItem("savedWarehouseIn", tbody);

        $('#myTable1 tbody tr').each(function () {
            const inputIds = $(this).find("td:eq(1) input").attr('id');
            const inputValues = $(this).find("td:eq(1) input").val();
            sessionStorage.setItem(inputIds, inputValues);
        });
    }

    $(this).closest('form').submit();

});

$(document).on('input', '.weight', function () {
    var weight = $(this).val();
    var pcs = $("#weight_unit").val();
    if (pcs == 0) {
        $(this).closest('tr').find('.pcs').val(0);
    } else {
        var total = parseInt(weight / pcs);
        $(this).closest('tr').find('.pcs').val(total.toFixed(0));
    }
});
