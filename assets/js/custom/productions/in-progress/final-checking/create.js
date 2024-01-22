let first = true;
$(document).ready(function () {
    $('#batch_id').trigger('change');
    if (typeof (Storage) !== "undefined" && sessionStorage.getItem("savedFinalChecking2")) {
        var savedFinalChecking2 = sessionStorage.getItem("savedFinalChecking2");
        var savedFinalChecking3 = sessionStorage.getItem("savedFinalChecking3");
        var customError = $('.custom-error:visible');
        if (savedFinalChecking2 && customError.length > 0) {
            $("#myTable4 tbody").html(savedFinalChecking2);

            $('#myTable4 tbody .weight').each(function () {
                const inputId = $(this).attr('id');
                const storedValue = sessionStorage.getItem(inputId);
                if (storedValue !== null) {
                    $(this).val(storedValue).trigger('input');
                }
            });
        }
        if (savedFinalChecking3 && customError.length > 0) {
            $("#myTable6 tbody").html(savedFinalChecking3);

            $('#myTable6 tbody .weight').each(function () {
                const inputId = $(this).attr('id');
                const storedValue = sessionStorage.getItem(inputId);
                if (storedValue !== null) {
                    $(this).val(storedValue).trigger('input');
                }
            });
        }
    }
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
                    <td><a class="delete_row"><iconify-icon icon="fluent:delete-dismiss-24-filled" width="20" height="20" style="color: red;"></iconify-icon></a></td></tr>`);

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
                    <td><input type='hidden' value='${id}' name="good[${$length}][id]"/>${pellete}</td>
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
                    <td><input type='hidden' value='${id}' name="reject[${$length}][id]"/>${pellete}</td>
                    <td><input type="number" name="reject[${$length}][weight]" id="weight3_${$length}" class="form-control weight"></td>
                    <td><input type="text" readonly name="reject[${$length}][pcs]" class="form-control pcs"></td>
                    <td><a class="delete_row2"><iconify-icon icon="fluent:delete-dismiss-24-filled" width="20" height="20" style="color: red;"></iconify-icon></a></td></tr>`);

        $("#myTable6 tbody").append(newRow);

        checkedRow.remove();

    });
    $('#myTable5').dataTable();
    $('#myTable6').dataTable();
});

$(document).on("click", ".delete_row", function () {

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

$('.submit').click(function () {

    if (typeof (Storage) !== "undefined") {
        var tbody2 = $('#myTable4 tbody').html();
        var tbody3 = $('#myTable6 tbody').html();
        sessionStorage.setItem("savedFinalChecking2", tbody2);
        sessionStorage.setItem("savedFinalChecking3", tbody3);

        $('#myTable4 tbody tr').each(function () {
            const inputIds = $(this).find("td:eq(1) input").attr('id');
            const inputValues = $(this).find("td:eq(1) input").val();
            sessionStorage.setItem(inputIds, inputValues);
        });

        $('#myTable6 tbody tr').each(function () {
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
