$(document).ready(function () {
    $("#btnBack").on("click", function () {
        window.history.back();
    });

    $("#btnReset").on("click", function () {
        // Clear text fields
        $("#menuForm").find("input[type=text]").val("");

        // Clear menu ID (switch to create mode)
        $("#menu_id").val("");

        // Reset floating labels
        $(".input-group-outline").removeClass("is-filled");
    });

    $("#menuForm").on("submit", function (e) {
        e.preventDefault();

        let id = $("#menu_id").val();
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
                    text: res.message || "Menu saved successfully!",
                }).then(() => {
                    window.location.href = "/menus";
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
