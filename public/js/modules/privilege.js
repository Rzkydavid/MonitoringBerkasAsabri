$(document).ready(function () {
    $("#btnPopulate").on("click", function () {
        Notiflix.Loading.standard("Populating...");

        $.ajax({
            url: "/privileges/populate",
            method: "GET",
            data: $(this).serialize(),
            headers: {
                "X-CSRF-TOKEN": CSRF_TOKEN,
            },
            success: function (res) {
                // HIDE LOADING
                Notiflix.Loading.remove();

                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: res.message || `Privileges populated successfully!`,
                }).then(() => {
                    window.location.href = BACK_URL;
                });
            },
            error: function (err) {
                // HIDE LOADING
                Notiflix.Loading.remove();

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: err.responseJSON?.message || "Something went wrong",
                });
            },
        });
    });
});
