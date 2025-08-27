var approvedTable = "approved-table";
var approvedDatatable = $("#" + approvedTable).DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    lengthChange: false,
    pagingType: "full_numbers",
    ajax: {
        url: ENGINEER_LEAVE_DATATABLE,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        data: function (d) {
            // var searchValue = $("#offered_search").val();
            // d["search[value]"] = searchValue;
            d["leave_approve_status"] = 'approved';
        },
    },
    columns: [
        // {
        //     title: "Sr.",
        //     data: null,
        //     width: "2%",
        //     orderable: false,
        //     render: function(data, type, row, meta) {
        //         var pageInfo = pendingDatatable.page.info();
        //         return pageInfo.start + meta.row + 1; // Start index + row index
        //     },
        //     createdCell: function(td, cellData, rowData, row, col) {
        //         $(td).addClass("px-6 py-3 ");
        //     },
        // },
        {
            title: "Eng. Name",
            data: "engineer_name",
            name: "engineer_name",
            width: "20%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "Date",
            data: "leave_date_div",
            name: "leave_date_div",
            width: "20%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "Days",
            data: "days_div",
            name: "days_div",
            width: "20%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "Type",
            data: "type_div",
            name: "type_div",
            width: "20%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "Document",
            data: "document_div",
            name: "document_div",
            width: "20%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "Status",
            data: "status_div",
            name: "status_div",
            width: "20%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },

        // {
        //     title: "",
        //     data: "action",
        //     name: "action",
        //     orderable: false,
        //     searchable: false,
        //     width: "18%",
        //     createdCell: function(td, cellData, rowData, row, col) {
        //         $(td).addClass("action-cell text-end d-flex justify-content-end m-3");
        //     },
        // },
    ],
    headerCallback: function (thead, data, start, end, display) {
        setTimeout(() => {
            $(thead).find("th").first().removeClass("dt-ordering-asc");
            $(thead).find("th").last().addClass("d-flex justify-content-end");

            $(thead)
                .find("th")
                .addClass("bg-blue-100  dark:bg-gray-900 px-6 py-3");

            $(thead).addClass(
                "text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
            );
        }, 10);
    },
    createdRow: function (row, data, dataIndex) {
        $(".dt-layout-table").next().addClass("pagination-show-entries");
        $(row).addClass(
            "odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200"
        );
    },

    drawCallback: function (settings) {
        if (typeof initializeDropdowns === "function") {
            initializeDropdowns();
        } else {
            console.warn("initializeDropdowns is not defined");
        }
    },
});

$(document).on('click', '.approved-tab',function() {
    approvedDatatable.ajax.reload();
});