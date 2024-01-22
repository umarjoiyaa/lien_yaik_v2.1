$(document).ready(function () {

    $('.form-select').select2();

    $('#myTable thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#myTable thead');

    $('#myTable').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            var api = this.api();

            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();

                    $(cell).html('<input type="text" placeholder=" Search ' + title.trim() + '" />');

                    $(
                            'input',
                            $('.filters th').eq($(api.column(colIdx).header()).index())
                        )
                        .off('keyup change')
                        .on('change', function (e) {
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})';

                            api
                                .column(colIdx)
                                .search(
                                    this.value != '' ?
                                    regexr.replace('{search}', '(((' + this.value + ')))') :
                                    '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();

                            $(this).trigger('change');
                        });
                });
        },
    });

    $('.removeDT input').css('display', 'none');

    $('.custom-error').delay(3000).hide('slow');

    var currentURL = window.location.href;

    if (currentURL.includes('dashboard')) {
        $('.has-sub').removeClass('active');
        $('#dashboards').closest('.has-sub').addClass('active').addClass('expand');
        $('#dashboards').closest('.has-sub').find('a').trigger('click');
    } else if (currentURL.includes('material-stock')) {
        $('.has-sub').removeClass('active');
        $('#material-stocks').addClass('active');
    } else if (currentURL.includes('stock-report')) {
        $('.has-sub').removeClass('active');
        $('#stocks').addClass('active');
    } else if (currentURL.includes('report')) {
        $('.has-sub').removeClass('active');
        $('#reports').addClass('active');
    } else if (currentURL.includes('users')) {
        $('.has-sub').removeClass('active');
        $('#users').closest('.has-sub').addClass('active').addClass('expand');
        $('#users').closest('.has-sub').find('a').trigger('click');
    } else if (currentURL.includes('products')) {
        $('.has-sub').removeClass('active');
        $('#products').closest('.has-sub').addClass('active').addClass('expand');
        $('#products').closest('.has-sub').find('a').trigger('click');
    } else if (currentURL.includes('materials')) {
        $('.has-sub').removeClass('active');
        $('#materials').closest('.has-sub').addClass('active').addClass('expand');
        $('#materials').closest('.has-sub').find('a').trigger('click');
    } else if (currentURL.includes('productions')) {
        $('.has-sub').removeClass('active');
        $('#productions').closest('.has-sub').addClass('active').addClass('expand');
        $('#productions').closest('.has-sub').find('[aria-controls="productions"]').trigger('click');
        if (currentURL.includes('in-progress')) {
            $('#inprogresses').closest('.has-sub').addClass('active').addClass('expand');
            $('#inprogresses').closest('.has-sub').find('a').trigger('click');
        }
    } else if (currentURL.includes('warehouses')) {
        $('.has-sub').removeClass('active');
        $('#warehouses').closest('.has-sub').addClass('active').addClass('expand');
        $('#warehouses').closest('.has-sub').find('a').trigger('click');
    }

});
