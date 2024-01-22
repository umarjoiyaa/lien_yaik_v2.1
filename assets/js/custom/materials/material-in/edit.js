$('#myTable1').dataTable();
$('#myTable2').dataTable();

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
                    <td><input type='number' value="1" id="qty" class='form-control' name='items[${$length}][qty]'></td>
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
