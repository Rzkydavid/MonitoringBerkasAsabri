$(document).ready(function () {
    $("#menuTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: MENU_URL,
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            { data: "name", name: "name" },
            { data: "route", name: "route" },
            { data: "parent_name", name: "parent_name" },
            { data: "order", name: "order" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
    });
});
