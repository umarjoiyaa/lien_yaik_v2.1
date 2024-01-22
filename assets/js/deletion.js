/**
 * Destroy Data
 */
$('.delete_row').on('click', function () {
    let Url = $(this).data('delete');
    Swal.fire({
        text: "Are you sure you want to delete Record?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, delete!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then(function (result) {
        if (result.value) {
            var anchor = document.createElement("a");
            anchor.href = Url;
            anchor.click();
        } else if (result.dismiss === 'cancel') {
            Swal.fire({
                text: "Record was not deleted.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        }
    });
});
