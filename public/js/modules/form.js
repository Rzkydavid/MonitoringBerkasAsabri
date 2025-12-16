$(document).ready(function () {
    $("#btnBack").on("click", function () {
        window.location.href = BACK_URL;
    });

    $("#btnReset").on("click", resetForm);

    function resetForm() {
        // Clear normal text inputs
        $("#form").find("input[type=text], input[type=date]").val("");

        // Clear textarea
        $("#form").find("textarea").val("");

        // Reset ID
        $("#id").val("");

        // Reset radio buttons
        $("#form").find("input[type=radio]").prop("checked", false);

        // Reset checkboxes (kalau ada)
        $("#form").find("input[type=checkbox]").prop("checked", false);

        // Reset Choices.js selects
        if (window.choicesInstances) {
            Object.values(window.choicesInstances).forEach((instance) => {
                instance.removeActiveItems(); // unselect
                instance.setChoiceByValue(""); // kembali ke placeholder
            });
        }

        // Reset native selects (fallback)
        $("#form").find("select").prop("selectedIndex", 0);

        // Reset floating labels
        $(".input-group-outline").removeClass("is-filled");

        // Reset datepicker (flatpickr)
        if (window.flatpickrInstances) {
            window.flatpickrInstances.forEach((fp) => fp.clear());
        }
    }

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
                    resetForm();
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
