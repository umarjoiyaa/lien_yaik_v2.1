$(document).ready(function () {
    if (typeof (Storage) !== "undefined" && sessionStorage.getItem("savedProduction")) {
        var savedProduction = sessionStorage.getItem("savedProduction");
        var customError = $('.custom-error:visible');
        if (savedProduction && customError.length > 0) {
            $("#myTable2 tbody").html(savedProduction);

            $('#myTable2 tbody .required_qty').each(function () {
                const inputId = $(this).attr('id');
                const storedValue = sessionStorage.getItem(inputId);
                if (storedValue !== null) {
                    $(this).val(storedValue);
                }
            });
        }
    }
    $('#purchase_order').trigger('change');
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

        var first = $(checkedRow).find('td:eq(1)');
        var second = $(checkedRow).find('td:eq(2)');
        var third = $(checkedRow).find('td:eq(3)')
        var forth = $(checkedRow).find('td:eq(4)');
        var fifth = $(checkedRow).find('td:eq(5)');

        var item = first.find('input').val();
        var item1 = first.text();
        var type = second.text();
        var category = third.text();
        var uom = forth.text();
        var supplier = fifth.text();
        var avail_value = $('#available_quantity').val();

        var newRow = $(`<tr>
                    <td name='type'>${type}</td>
                    <td><input type='hidden' value='${item}' id="item_id" name="items[${$length}][id]"/>${item1}</td>
                    <td name='category'>${category}</td>
                    <td name='uoms'>${uom}</td>
                    <td name='suppliers'>${supplier}</td>
                    <td><input type='number' value="${avail_value}" id="available_qty_${$length}" class='form-control available_qty' name='items[${$length}][available]' readonly></td>
                    <td><input type='number' value="" id="required_qty_${$length}" class='form-control required_qty' name='items[${$length}][required]'></td>
                    <td><input type='number' value="" id="need_${$length}" class='form-control need' name='items[${$length}][need]' readonly></td>
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

    var type = $(checkedRow).find('td:eq(0)').text();
    var id = $(checkedRow).find('td:eq(1) input').val();
    var item = $(checkedRow).find('td:eq(1)').text();
    var categories = $(checkedRow).find('td:eq(2)').text();
    var uoms = $(checkedRow).find('td:eq(3)').text();
    var suppliers = $(checkedRow).find('td:eq(4)').text();

    var newRow = $(`<tr>
        <td><input type='checkbox'></td>
        <td><input type="hidden" value="${id}"/>${item}</td>
        <td>${type}</td>
        <td>${categories}</td>
        <td>${uoms}</td>
        <td>${suppliers}</td>
        </tr>`);

    $("#myTable1 tbody").append(newRow);
    checkedRow.remove();
    $('#myTable1').dataTable();
    $('#myTable2').dataTable();
});

$('#purchase_order').on('change', function () {
    let id = $(this).val();
    if (id != null) {
        $.ajax({
            url: data,
            type: 'GET',
            data: {
                id: id
            },
            success: function (data) {
                $("#part_id").val(data.product_id);
                $("#part_name").val(data.product_name);
                $("#order_unit").val(data.order_unit);
                $("#no_cavity").val(data.cavities);
                $("#order_date").val(moment(data.order_date).format('MM/DD/YYYY'));
                $("#weight_unit").val(data.unit_kg);
                $("#weight-per-mold").val(data.per_mold);
                $("#available_quantity").val((data.value == null) ? 0 : data.value);
                $(".available_qty").val((data.value == null) ? 0 : data.value);
                $('#reject').trigger('input');
            },
            error: function (xhr, status, error) {}
        });
    }
});

function calculateTargetProduce() {
    var orderUnit = parseFloat($('#order_unit').val()) || 0;
    var reject = parseFloat($("#reject").val()) || 0;
    var divisor = reject / 100 + 1;
    var targetProduce = divisor * orderUnit;
    $('#target_produce').val(isNaN(targetProduce) ? '' : targetProduce.toFixed(0)).trigger('input');
}

function calculateRawMaterial() {
    var press = parseFloat($('#press').val()) || 0;
    var weightPerMold = parseFloat($('#weight-per-mold').val()) || 0;
    var rawMaterial = press * weightPerMold;
    $('#raw_material').val(isNaN(rawMaterial) ? '' : rawMaterial.toFixed());
}

function calculateNeededQuantity() {
    var available = parseFloat($(this).closest('tr').find('.available_qty').val()) || 0;
    var required = parseFloat($(this).val()) || 0;
    var need = available - required;
    $(this).parents("tr").find('.need').val(isNaN(need) ? '' : need);
}

function calculateRemainingValues() {
    var targetProduce = parseFloat($("#target_produce").val()) || 0;
    var usedQty = parseFloat($("#used_qty").val()) || 0;
    var targetNeed = targetProduce - usedQty;
    $("#need_produce").val(isNaN(targetNeed) ? '' : targetNeed);

    var noCavity = parseFloat($("#no_cavity").val()) || 1;
    var totalPress = Math.ceil(targetNeed / noCavity);
    $('#press').val(isNaN(totalPress) ? '' : totalPress.toFixed(2)).trigger('input');
}

$('#reject, #order_unit').on('input', calculateTargetProduce);
$('#press, #weight-per-mold').on('input', calculateRawMaterial);
$(document).on("input", ".required_qty, .available_qty", calculateNeededQuantity);
$("#target_produce, #used_qty").on('input', calculateRemainingValues);


$('.submit').click(function () {

    if (typeof (Storage) !== "undefined") {
        var tbody = $('#myTable2 tbody').html();
        sessionStorage.setItem("savedProduction", tbody);

        $('#myTable2 tbody tr').each(function () {
            const inputIds = $(this).find("td:eq(6) input").attr('id');
            const inputValues = $(this).find("td:eq(6) input").val();
            sessionStorage.setItem(inputIds, inputValues);
        });
    }

    $(this).closest('form').submit();

});
