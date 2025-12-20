$(document).ready(function () {
    $(document).on("click", "#acceptSelected", function () {
        // Collect selected ids
        let ids = [];
        $(".row-checkbox:checked").each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "No items selected",
                text: "Please select items first.",
            });
            return;
        }

        // Confirmation dialog
        Swal.fire({
            title: `Sure want to accept ${ids.length} item(s)?`,
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#35e54cff",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, accept!",
        }).then((result) => {
            if (result.isConfirmed) {
                Notiflix.Loading.standard("Loading...");

                $.ajax({
                    url: ACCEPT_URL,
                    type: "POST",
                    data: {
                        ids: ids,
                        _token: CSRF_TOKEN,
                    },
                    success: function (res) {
                        Notiflix.Loading.remove();

                        if (res.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Accepted",
                                text: res.message,
                            });

                            $("#table").DataTable().ajax.reload(null, false);
                            $("#checkAll").prop("checked", false);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Failed",
                                text: res.message,
                            });
                        }
                    },
                    error: function () {
                        Notiflix.Loading.remove();

                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Something went wrong.",
                        });
                    },
                });
            }
        });
    });

    $(document).on("click", "#rejectSelected", function () {
        // Collect selected ids
        let ids = [];
        $(".row-checkbox:checked").each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "No items selected",
                text: "Please select items first.",
            });
            return;
        }

        // Confirmation dialog
        Swal.fire({
            title: `Sure want to reject ${ids.length} item(s)?`,
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e53935",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, reject!",
        }).then((result) => {
            if (result.isConfirmed) {
                Notiflix.Loading.standard("Loading...");

                $.ajax({
                    url: REJECT_URL,
                    type: "POST",
                    data: {
                        ids: ids,
                        _token: CSRF_TOKEN,
                    },
                    success: function (res) {
                        Notiflix.Loading.remove();

                        if (res.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Rejected",
                                text: res.message,
                            });

                            $("#table").DataTable().ajax.reload(null, false);
                            $("#checkAll").prop("checked", false);
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Failed",
                                text: res.message,
                            });
                        }
                    },
                    error: function () {
                        Notiflix.Loading.remove();

                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Something went wrong.",
                        });
                    },
                });
            }
        });
    });
});
