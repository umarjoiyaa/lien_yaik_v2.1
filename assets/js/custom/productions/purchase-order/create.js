$(document).ready(function(){
    $('#input1').trigger('input');
});

$("#input1, #input2").on("input", function () {
    var input1 = parseFloat($("#input1").val()) || 0;
    var input2 = parseFloat($("#input2").val()) || 0;
    var result = input1 * input2;
    $("#result").val(result.toFixed(2));
});
