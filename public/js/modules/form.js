$(document).ready(function () {
    $("#btnBack").on("click", function () {
        window.location.href = BACK_URL;
    });

    $("#btnReset").on("click", function () {
        // Clear text fields
        $("#form").find("input[type=text]").val("");

        // Clear menu ID (switch to create mode)
        $("#id").val("");

        // Reset floating labels
        $(".input-group-outline").removeClass("is-filled");
    });

    $("#form").on("submit", function (e) {
        e.preventDefault();

        let id = $("#id").val();
        let url = id ? UPDATE_URL : STORE_URL;

        // SHOW LOADING
        Notiflix.Loading.standard("Saving...");

        $.ajax({
            url: url,
            method: id ? "PUT" : "POST",
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
                    text: res.message || `${RESOURCE_NAME} saved successfully!`,
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

    $(".input-group-outline input").each(function () {
        if ($(this).val()) {
            $(this).parent().addClass("is-filled");
        }
    });
});
