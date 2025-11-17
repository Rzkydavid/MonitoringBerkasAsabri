$(document).ready(function () {
    $("#menuTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: MENU_URL,
        columns: [
            {
                data: "checkbox",
                name: "checkbox",
                orderable: false,
                searchable: false,
            },
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            { data: "name", name: "name" },
            { data: "route", name: "route" },
            { data: "icon", name: "icon" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
    });

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

                            $("#menuTable")
                                .DataTable()
                                .ajax.reload(null, false);
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

    $("#menuTable").on("click", ".edit", function () {
        const id = $(this).data("id");
        window.location.href = `/menus/${id}/edit`;
    });
});
