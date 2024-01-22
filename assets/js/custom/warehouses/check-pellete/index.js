pallet_id = [];

function addPellete(value) {

    $.ajax({
        type: "GET",
        url: route,
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
                    $('#pallet_error').append('<ul class="mt-2 ml-2 text-danger"><li>Pellete already checked</li></ul>')
                } else {
                    $('#searchPellete').val(data.pellete.pellete_no);
                    $('.row-check').append(`<div class="col-sm-3"><div class="card"><div class="card-body"><div class="row text-center">Pellete # : ${(data.pellete != null) ? data.pellete.pellete_no : ''}</div><div class="row text-center">Pellete Status : ${(data.pellete.status != null) ? data.pellete.status : ''}</div><div class="row text-center">Client Name : ${(data.purchase != null) ? data.purchase.customer : ''}</div>${(data.batch != null) ? `<div class="row text-center">Batch # : ${data.batch.batch_no}</div>` : `<div class="row text-center text-danger">Batch # : </div>`}${(data.product != null) ? `<div class="row text-center">Part Name : ${data.product.name}</div>` : `<div class="row text-center text-danger">Part Name : 'not in production'</div>`}<div class="row text-center">Weight : ${(data.pellete.weight != null) ? data.pellete.weight : (data.pellete.previous_weight != null) ? data.pellete.previous_weight : ''}</div><div class="row text-center">PCS : ${(data.pellete.pcs != null) ? data.pellete.pcs : (data.pellete.previous_pcs != null) ? data.pellete.previous_pcs : ''}</div>`);
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
    var value = $('#searchPellete').val();
    addPellete(value);
});
