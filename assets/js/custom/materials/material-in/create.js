$(document).ready(function () {
    if (typeof (Storage) !== "undefined" && sessionStorage.getItem("savedMaterialIn")) {
        var savedMaterialIn = sessionStorage.getItem("savedMaterialIn");
        var customError = $('.custom-error:visible');
        if (savedMaterialIn && customError.length > 0) {
            $("#myTable2 tbody").html(savedMaterialIn);

            $('#myTable2 tbody .qty').each(function () {
                const inputId = $(this).attr('id');
                const storedValue = sessionStorage.getItem(inputId);
                if (storedValue !== null) {
                    $(this).val(storedValue);
                }
            });
        }
    }
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

$("#addrows").on("click", function () {
    $('#myTable1').dataTable().fnDestroy();
    $('#myTable2').dataTable().fnDestroy();

    $("#myTable1 tbody").find("input[type=checkbox]:checked").each(function () {
        $length = $("#myTable2 tbody tr").length;

        var checkedRow = $(this).closest('tr');

        var items = $(checkedRow).find('td:eq(1)');
        var second1 = $(checkedRow).find('td:eq(2)');
        var third1 = $(checkedRow).find('td:eq(3)')
        var lastCell = $(checkedRow).find('td:last');

        var item = items.find('input').val();
        var item1 = items.text();
        var category = second1.text();
        var uom = third1.text();
        var supplier = lastCell.text();
        var newRow = $(`<tr>
                    <td><input type='hidden' value='${item}' id="item_id" name="items[${$length}][id]"/>${item1}</td>
                    <td name='category'>${category}</td>
                    <td name='uoms'>${uom}</td>
                    <td name='suppliers'>${supplier}</td>
                    <td><input type='number' value="1" id="qty_${$length}" class='form-control qty' name='items[${$length}][qty]'></td>
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

    var items = $(checkedRow).find('td:eq(0)');
    var categories = $(checkedRow).find('td:eq(1)');
    var uoms = $(checkedRow).find('td:eq(2)');
    var suppliers = $(checkedRow).find('td:eq(3)');

    var item = items.text();
    var id = items.find('input').val();
    var category = categories.text();
    var uom = uoms.text();
    var supplier = suppliers.text();
    var newRow = $(`<tr>
        <td><input type='checkbox'></td>
        <td><input type="hidden" value="${id}"/>${item}</td>
        <td>${category}</td>
        <td>${uom}</td>
        <td>${supplier}</td>
        </tr>`);

    $("#myTable1 tbody").append(newRow);
    checkedRow.remove();
    $('#myTable1').dataTable();
    $('#myTable2').dataTable();
});

$('.submit').click(function () {

    if (typeof (Storage) !== "undefined") {
        var tbody = $('#myTable2 tbody').html();
        sessionStorage.setItem("savedMaterialIn", tbody);

        $('#myTable2 tbody tr').each(function () {
            const inputIds = $(this).find("input[type=number]").attr('id');
            const inputValues = $(this).find("input[type=number]").val();
            sessionStorage.setItem(inputIds, inputValues);
        });
    }

    $(this).closest('form').submit();

});
