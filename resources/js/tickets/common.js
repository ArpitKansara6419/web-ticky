export const COMMON_COLUMNS = [
    /*{
        title: "CODE",
        data: "ticket_code",
        name: "ticket_code",
        width: "20%",
        orderable: false,
        createdCell: function (td, cellData, rowData, row, col) {
            $(td).addClass("px-6 py-3 ");
        },
    },*/
    {
        title: "TICKET",
        data: "lead_and_task",
        name: "lead_and_task",
        width: "10%",
        orderable: false,
        searchable: true,
        createdCell: function (td, cellData, rowData, row, col) {
            $(td).addClass("px-6 py-3 ");
        },
    },
    {
        title: "LOCATION",
        data: "location_div",
        name: "location_div",
        width: "14%",
        orderable: false,
        searchable: true,
        createdCell: function (td, cellData, rowData, row, col) {
            $(td).addClass("px-6 py-3 ");
        },
    },
    {
        title: "DATE & TIME",
        data: "date_and_time_div",
        name: "date_and_time_div",
        width: "18%",
        orderable: false,
        createdCell: function(td, cellData, rowData, row, col) {
            $(td).addClass("px-6 py-3 ");
        },
    },
    {
        title: "CUSTOMER",
        data: "customer_div",
        name: "customer_div",
        width: "14%",
        orderable: false,
        createdCell: function (td, cellData, rowData, row, col) {
            $(td).addClass("px-6 py-3 ");
        },
    },
    {
        title: "Engineer Assigned",
        data: "engineer_assigned_div",
        name: "engineer_assigned_div",
        width: "14%",
        orderable: false,
        createdCell: function (td, cellData, rowData, row, col) {
            $(td).addClass("px-6 py-3 ");
        },
    },

    {
        title: "Status",
        data: "status_div",
        name: "status_div",
        width: "10%",
        orderable: false,
        createdCell: function (td, cellData, rowData, row, col) {
            $(td).addClass("px-6 py-3 ");
        },
    },

    {
        title: "",
        data: "action",
        name: "action",
        orderable: false,
        searchable: false,
        width: "4%",
        createdCell: function (td, cellData, rowData, row, col) {
            $(td).addClass(
                "action-cell text-end d-flex justify-content-end m-3"
            );
        },
    },
];
