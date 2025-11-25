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
});
