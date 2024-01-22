function generate(value) {
    var qr = qrcode(0, 'H');
    qr.addData(value);
    qr.make();
    var img = qr.createImgTag(5, 10);
    $('#qrcode').html(img);
}

$('.submit').click(function () {
    var src = $('#qrcode').find('img').attr('src');
    $('.hidden').val(src);
    $(this).closest('form').submit();
});

$('.pellete').trigger('input');
