check_machines(check_machine);

function check_machines(check_machines) {
    if (check_machines != null) {
        if (check_machines.status == 1) {
            $('#play').attr('disabled', 'disabled');
            $('#pause').removeAttr('disabled');
            $('#stop').removeAttr('disabled');
            $('#play').css('background', '#FF8A8A');
            $('#pause').css('background', '#FFF');
            $('#stop').css('background', '#FFF');
        } else if (check_machines.status == 2) {
            $('#pause').attr('disabled', 'disabled');
            $('#play').removeAttr('disabled');
            $('#stop').attr('disabled', 'disabled');
            $('#play').css('background', '#FFF');
            $('#stop').css('background', '#FFF');
            $('#pause').css('background', '#FF8A8A');
        } else if (check_machines.status == 3) {
            $('#stop').attr('disabled', 'disabled');
            $('#pause').attr('disabled', 'disabled');
            $('#play').removeAttr('disabled');
            $('#pause').css('background', '#FFF');
            $('#play').css('background', '#FFF');
            $('#stop').css('background', '#FF8A8A');
        }
    } else {
        $('#stop').attr('disabled', 'disabled');
        $('#pause').attr('disabled', 'disabled');
        $('#play').removeAttr('disabled');
        $('#pause').css('background', '#FFF');
        $('#play').css('background', '#FFF');
        $('#stop').css('background', '#FF8A8A');
    }
}

function machineStarter(status, press_id) {
    var batch_id = $("#batch_id").val();
    var machine_id = $("#machine_id").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: data,
        data: {
            "batch_id": batch_id,
            "press_id": press_id,
            "machine_id": machine_id,
            "status": status,
        },
        success: function (data) {
            $("#msg").html(data.message);
            check_machines(data.check_machine);
        }
    });
}
