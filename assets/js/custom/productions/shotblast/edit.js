$(document).ready(function () {
    $('#batch_id').trigger('change');
    $('#myTable1').dataTable();
    $('#myTable2').dataTable();
});

$bool = true;

$('.add_item').click(function () {
    if ($bool) {
        $('#myTable1').dataTable().fnDestroy();
        $("#myTable1 tbody").find("input[type=hidden]").each(function () {
            let this1 = $(this);
            $("#myTable2 tbody").find("input[type=hidden]").each(function () {
                let this2 = $(this);
                if (this1.val() == this2.val()) {
                    this1.closest('tr').remove();
                }
            });
        });
        $('#myTable1').dataTable();
        $bool = false;
    }
});


$('#batch_id').on('change', function () {
    const id = $(this).val();
    $.ajax({
        type: 'GET',
        url: route,
        data: {
            "id": id,
            "status": 1
        },
        success: function (data) {
            if (data.weight_unit) {
                $('#weight_unit').val(weight_unit[0]);
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

$("#addrows").on("click", function () {
    $('#myTable1').dataTable().fnDestroy();
    $('#myTable2').dataTable().fnDestroy();

    $("#myTable1 tbody").find("input[type=checkbox]:checked").each(function () {
        $length = $("#myTable2 tbody tr").length;

        var checkedRow = $(this).closest('tr');

        var id = $(checkedRow).find('td:eq(1) input').val();
        var pellete = $(checkedRow).find('td:eq(1)').text();

        var newRow = $(`<tr>
                    <td><input type='hidden' value='${id}' id="pellete_id" name="pelletes[${$length}][id]"/>${pellete}</td>
                    <td><input type="number" name="pelletes[${$length}][weight]" class="form-control weight"></td>
                    <td><input type="text" readonly name="pelletes[${$length}][pcs]" class="form-control pcs"></td>
                    <td><a class="delete_row"><iconify-icon icon="fluent:delete-dismiss-24-filled" width="20" height="20" style="color: red;"></iconify-icon><a></td></tr>`);

        $("#myTable2 tbody").append(newRow);

        checkedRow.remove();

    });
    $('#myTable1').dataTable();
    $('#myTable2').dataTable();
});

$(document).on("click", ".delete_row", function () {

    $('#myTable1').dataTable().fnDestroy();
    $('#myTable2').dataTable().fnDestroy();

    var checkedRow = $(this).closest('tr');

    var id = $(checkedRow).find('td:eq(0) input').val();
    var pellete = $(checkedRow).find('td:eq(0)').text();

    var newRow = $(`<tr>
        <td><input type='checkbox'></td>
        <td><input type='hidden' value='${id}' id="pellete_id" name="pelletes[${$length}][id]"/>${pellete}</td>
        </tr>`);

    $("#myTable1 tbody").append(newRow);
    checkedRow.remove();
    $('#myTable1').dataTable();
    $('#myTable2').dataTable();
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
