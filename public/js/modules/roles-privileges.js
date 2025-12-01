$(function () {
    // Initialize Choices.js using jQuery selector
    if ($("#roleId").length) {
        new Choices("#roleId", {
            searchEnabled: true,
            itemSelectText: "",
        });
    }

    // When role changes → reload the datatable with params
    $("#roleId").on("change", function () {
        const roleId = $(this).val();

        if (typeof reloadTableWithParams === "function") {
            reloadTableWithParams({
                role_id: roleId,
            });
        }
    });

    $(function () {
        let modalTable = null;

        // Open modal
        $("#openAddPrivilegeModal").on("click", function () {
            const roleId = $("#roleId").val();

            if (!roleId) {
                Swal.fire({
                    icon: "warning",
                    text: "Please choose a role first.",
                });
                return;
            }

            let modal = new bootstrap.Modal(
                document.getElementById("addPrivilegeModal")
            );
            modal.show();

            // Init DataTable inside modal
            if (modalTable !== null) {
                modalTable.destroy();
            }

            modalTable = $("#availablePrivilegesTable").DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: AVAILABLE_PRIVILEGES_URL,
                    data: { role_id: roleId },
                },
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
                ],
            });
        });

        // Select all checkboxes
        $(document).on("change", "#selectAllAvail", function () {
            $(".avail-checkbox").prop("checked", $(this).prop("checked"));
        });

        // Apply selected privileges
        $("#applyPrivilegesBtn").on("click", function () {
            const roleId = $("#roleId").val();
            let selected = [];

            $(".avail-checkbox:checked").each(function () {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                Swal.fire({ icon: "warning", text: "No privileges selected." });
                return;
            }

            Notiflix.Loading.standard("Loading...");
            $.ajax({
                url: ADD_ROLE_PRIVILEGES_URL,
                type: "POST",
                data: {
                    role_id: roleId,
                    privileges: selected,
                    _token: CSRF_TOKEN,
                },
                success: function (res) {
                    Notiflix.Loading.remove();
                    if (res.success) {
                        Swal.fire({ icon: "success", text: res.message });

                        // reload main table
                        reloadTableWithParams({ role_id: roleId });

                        // reload modal table
                        modalTable.ajax.reload(null, false);
                    } else {
                        Swal.fire({ icon: "error", text: res.message });
                    }
                },
            });
        });
    });
});
