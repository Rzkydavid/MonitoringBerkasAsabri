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
                title: "Tidak ada yang dipilih",
                text: "Silakan pilih terlebih dahulu..",
            });
            return;
        }

        // Confirmation dialog
        Swal.fire({
            title: `Apakah anda ingin terima ${ids.length} item?`,
            text: "Tindakan ini tidak dapat dibatalkan.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#35e54cff",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya,terima!",
        }).then((result) => {
            if (result.isConfirmed) {
                Notiflix.Loading.standard("Memuat...");

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
                            text: "Terjadi kesalahan.",
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
            title: `Apakah anda ingin menolak ${ids.length} item.)?`,
            text: "Tindakan ini tidak dapat dibatalkan.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e53935",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, reject!",
        }).then((result) => {
            if (result.isConfirmed) {
                Notiflix.Loading.standard("Memuat...");

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

    $(document).on("click", ".btn-action", function () {
        const id = $(this).data("id");
        const action = $(this).data("action");

        let url = "";
        if (action === "reject") {
            url = "/pending-task/reject";
        } else {
            url = "/pending-task/accept";
        }

        // if (!confirm("Are you sure?")) return;

        let confirmButtonColor = action === "reject" ? "#e53935" : "#35e54cff";

        Swal.fire({
            title: `Apakah anda ingin ${action} klaim ini?`,
            text: "Tindakan ini tidak dapat dibatalkan.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Ya, ${action}!`,
        }).then((result) => {
            if (result.isConfirmed) {
                Notiflix.Loading.standard("Memuat...");

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        id: id,
                        _token: CSRF_TOKEN,
                    },
                    success: function (res) {
                        Notiflix.Loading.remove();

                        if (res.success) {
                            Swal.fire({
                                icon: "success",
                                title: `${
                                    action.charAt(0).toUpperCase() +
                                    action.slice(1)
                                }ed`,
                                text: res.message,
                            });
                            $("#table").DataTable().ajax.reload(null, false);
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
