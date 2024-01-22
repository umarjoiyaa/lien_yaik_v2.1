$(document).ready(function () {
    ajax_call();
});

$('#searchByName').keyup(function () {
    ajax_call();
});

$('#searchByValue').on('keyup change', function () {
    ajax_call();
});

$('#searchUsingComparator').change(function () {
    ajax_call();
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function ajax_call() {
    var searchUsingComparator = $('#searchUsingComparator').val();
    var searchByName = $('#searchByName').val();
    var searchByValue = $('#searchByValue').val();
    $('#myTables').DataTable({
        'destroy': true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': route,
            data: {
                "searchByName": searchByName,
                "searchByValue": searchByValue,
                "searchUsingComparator": searchUsingComparator,
            }

        },
        'columns': [{
                data: 'name'
            },
            {
                data: 'value'
            },
        ]
    });
}
