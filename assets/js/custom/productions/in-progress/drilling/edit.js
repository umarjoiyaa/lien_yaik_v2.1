let first = true;
$(document).ready(function () {
    $('#batch_id').trigger('change');
    $('#myTable1').dataTable();
    $('#myTable2').dataTable();
    $('#myTable3').dataTable();
    $('#myTable4').dataTable();
    $('#myTable5').dataTable();
    $('#myTable6').dataTable();
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

$bool1 = true;

$('.add_item1').click(function () {
    if ($bool) {
        $('#myTable3').dataTable().fnDestroy();
        $("#myTable3 tbody").find("input[type=hidden]").each(function () {
            let this1 = $(this);
            $("#myTable4 tbody").find("input[type=hidden]").each(function () {
                let this2 = $(this);
                if (this1.val() == this2.val()) {
                    this1.closest('tr').remove();
                }
            });
        });
        $('#myTable3').dataTable();
        $bool1 = false;
    }
});

$bool2 = true;

$('.add_item2').click(function () {
    if ($bool) {
        $('#myTable5').dataTable().fnDestroy();
        $("#myTable5 tbody").find("input[type=hidden]").each(function () {
            let this1 = $(this);
            $("#myTable6 tbody").find("input[type=hidden]").each(function () {
                let this2 = $(this);
                if (this1.val() == this2.val()) {
                    this1.closest('tr').remove();
                }
            });
        });
        $('#myTable5').dataTable();
        $bool2 = false;
    }
});

$("#addrows").on("click", function () {
    $('#myTable1').dataTable().fnDestroy();
    $('#myTable2').dataTable().fnDestroy();

    $("#myTable1 tbody").find("input[type=checkbox]:checked").each(function () {
        $length = $("#myTable2 tbody tr").length;

        var checkedRow = $(this).closest('tr');

        var id = $(checkedRow).find('td:eq(1) input').val();
        var pellete = $(checkedRow).find('td:eq(1)').text();
        var weight = $(checkedRow).find('td:eq(2)').text();
        var pcs = $(checkedRow).find('td:eq(3)').text();

        var newRow = $(`<tr>
                    <td><input type='hidden' value='${id}' id="pellete_id" name="shotblast[${$length}][id]"/>${pellete}</td>
                    <td><input type="text" readonly name="shotblast[${$length}][weight]" class="form-control" value="${weight}"></td>
                    <td><input type="text" readonly name="shotblast[${$length}][pcs]" class="form-control pcs1" value="${pcs}"></td>
                    <td><a class="delete_row3"><iconify-icon icon="fluent:delete-dismiss-24-filled" width="20" height="20" style="color: red;"></iconify-icon></a></td></tr>`);

        $("#myTable2 tbody").append(newRow);

        checkedRow.remove();

    });
    $('#myTable1').dataTable();
    $('#myTable2').dataTable();
});

$("#addrows1").on("click", function () {
    $('#myTable3').dataTable().fnDestroy();
    $('#myTable4').dataTable().fnDestroy();

    $("#myTable3 tbody").find("input[type=checkbox]:checked").each(function () {
        $length = $("#myTable4 tbody tr").length;

        var checkedRow = $(this).closest('tr');

        var id = $(checkedRow).find('td:eq(1) input').val();
        var pellete = $(checkedRow).find('td:eq(1)').text();

        var newRow = $(`<tr>
                    <td><input type='hidden' value='${id}' id="pellete_id" name="good[${$length}][id]"/>${pellete}</td>
                    <td><input type="number" name="good[${$length}][weight]" id="weight2_${$length}" class="form-control weight"></td>
                    <td><input type="text" readonly name="good[${$length}][pcs]" class="form-control pcs"></td>
                    <td><a class="delete_row1"><iconify-icon icon="fluent:delete-dismiss-24-filled" width="20" height="20" style="color: red;"></iconify-icon></a></td></tr>`);

        $("#myTable4 tbody").append(newRow);

        checkedRow.remove();

    });
    $('#myTable3').dataTable();
    $('#myTable4').dataTable();
});

$("#addrows2").on("click", function () {
    $('#myTable5').dataTable().fnDestroy();
    $('#myTable6').dataTable().fnDestroy();

    $("#myTable5 tbody").find("input[type=checkbox]:checked").each(function () {
        $length = $("#myTable6 tbody tr").length;

        var checkedRow = $(this).closest('tr');

        var id = $(checkedRow).find('td:eq(1) input').val();
        var pellete = $(checkedRow).find('td:eq(1)').text();

        var newRow = $(`<tr>
                    <td><input type='hidden' value='${id}' id="pellete_id" name="reject[${$length}][id]"/>${pellete}</td>
                    <td><input type="number" name="reject[${$length}][weight]" id="weight3_${$length}" class="form-control weight"></td>
                    <td><input type="text" readonly name="reject[${$length}][pcs]" class="form-control pcs"></td>
                    <td><a class="delete_row2"><iconify-icon icon="fluent:delete-dismiss-24-filled" width="20" height="20" style="color: red;"></iconify-icon></a></td></tr>`);

        $("#myTable6 tbody").append(newRow);

        checkedRow.remove();

    });
    $('#myTable5').dataTable();
    $('#myTable6').dataTable();
});

$(document).on("click", ".delete_row3", function () {

    $('#myTable1').dataTable().fnDestroy();
    $('#myTable2').dataTable().fnDestroy();

    var checkedRow = $(this).closest('tr');

    var id = $(checkedRow).find('td:eq(0) input').val();
    var pellete = $(checkedRow).find('td:eq(0)').text();
    var weight = $(checkedRow).find('td:eq(1) input').val();
    var pcs = $(checkedRow).find('td:eq(2) input').val();

    var newRow = $(`<tr>
        <td><input type='checkbox'></td>
        <td><input type='hidden' value='${id}'/>${pellete}</td>
        <td>${weight}</td>
        <td>${pcs}</td>
        </tr>`);

    $("#myTable1 tbody").append(newRow);
    checkedRow.remove();
    $('#myTable1').dataTable();
    $('#myTable2').dataTable();
});

$(document).on("click", ".delete_row1", function () {

    $('#myTable3').dataTable().fnDestroy();
    $('#myTable4').dataTable().fnDestroy();

    var checkedRow = $(this).closest('tr');

    var id = $(checkedRow).find('td:eq(0) input').val();
    var pellete = $(checkedRow).find('td:eq(0)').text();

    var newRow = $(`<tr>
        <td><input type='checkbox'></td>
        <td><input type='hidden' value='${id}'/>${pellete}</td>
        </tr>`);

    $("#myTable3 tbody").append(newRow);
    checkedRow.remove();
    $('#myTable3').dataTable();
    $('#myTable4').dataTable();
});

$(document).on("click", ".delete_row2", function () {

    $('#myTable5').dataTable().fnDestroy();
    $('#myTable6').dataTable().fnDestroy();

    var checkedRow = $(this).closest('tr');

    var id = $(checkedRow).find('td:eq(0) input').val();
    var pellete = $(checkedRow).find('td:eq(0)').text();

    var newRow = $(`<tr>
        <td><input type='checkbox'></td>
        <td><input type='hidden' value='${id}'/>${pellete}</td>
        </tr>`);

    $("#myTable5 tbody").append(newRow);
    checkedRow.remove();
    $('#myTable5').dataTable();
    $('#myTable6').dataTable();
});

$('#batch_id').on('change', function () {
    const id = $(this).val();
    $.ajax({
        type: 'GET',
        url: route,
        data: {
            "id": id,
            "status": 2
        },
        success: function (data) {
            let rows = ``;
            data.shotblast.forEach(element => {
                rows += `<tr><td><input type="checkbox"></td><td><input type="hidden" value="${element.id}">${element.pellete_no}</td><td>${element.weight}</td><td>${element.pcs}</td></tr>`;
            });
            $('#myTable1').dataTable().fnDestroy();
            $('#myTable1 tbody').html(rows);
            $('#myTable1').dataTable();
            if (!first) {
                $('#myTable2').dataTable().fnDestroy();
                $('#myTable2 tbody').html('');
                $('#myTable2').dataTable();
            }
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
