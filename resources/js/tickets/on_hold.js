import { COMMON_COLUMNS } from './common.js';

var onholdTable = "onhold-table";
var onholdDatatable = $("#" + onholdTable).DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    lengthChange: false,
    pagingType: "full_numbers",
    ajax: {
        url: TICKET_DATATABLE,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        data: function (d) {
            var searchValue = $("#offered_search").val();
            d["search[value]"] = searchValue;
            d["status"] = 'onhold';
        },
    },
    columns:COMMON_COLUMNS,
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
// $("#search").on("input", function () {
//     list_datatable.ajax.reload();
// });
