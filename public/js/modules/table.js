$(document).ready(function () {
    // $("#table").DataTable({
    //     processing: true,
    //     serverSide: true,
    //     ajax: FETCH_URL,
    //     columns: TABLE_COLUMNS,
    // });

    function loadTable(extraParams = {}) {
        const config = {
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: FETCH_URL,
                data: extraParams,
            },
            columns: TABLE_COLUMNS,
        };

        // 👉 If TABLE_ORDER is defined, apply it
        if (typeof TABLE_ORDER !== "undefined" && Array.isArray(TABLE_ORDER)) {
            config.order = TABLE_ORDER;
        }

        $("#table").DataTable(config);
    }

    // Initial load with no params → empty table for this page
    loadTable();

    // allow calling from Blade
    window.reloadTableWithParams = function (params) {
        loadTable(params);
    };

    $(document).on("change", "#checkAll", function () {
        $(".row-checkbox").prop("checked", $(this).is(":checked"));
    });

    $(document).on("change", ".row-checkbox", function () {
        if (!$(this).is(":checked")) {
            $("#checkAll").prop("checked", false);
        }
    });

    $(document).on("click", "#deleteSelected", function () {
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
            title: `Sure want to delete ${ids.length} item(s)?`,
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e53935",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete!",
        }).then((result) => {
            if (result.isConfirmed) {
                Notiflix.Loading.standard("Deleting...");

                $.ajax({
                    url: DELETE_URL,
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
                                title: "Deleted",
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

    $("#table").on("click", ".edit", function () {
        const id = $(this).data("id");
        window.location.href = `/${RESOURCE_NAME}/${id}/edit`;
    });

    $("#period").on("change", function () {
        const period = $(this).val();
        console.log("Selected period:", period);
        if (typeof reloadTableWithParams === "function") {
            reloadTableWithParams({
                period: period,
            });
        }
    });
});
