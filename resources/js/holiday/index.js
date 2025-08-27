import { fetchWrapper } from "../helper.js";

var datatable_id = "holiday-table";



var holiday_list_datatable = $("#" + datatable_id).DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    lengthChange: false,
    pagingType: "full_numbers",
    ajax: {
        url: HOLIDAY_DATATABLE,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        data: function(d) {
            var searchValue = $('#search').val();
            d['search[value]'] = searchValue;
            d['country'] = $('#country_id').val();
        }
    },
    columns: [{
            title: "Title",
            data: "title",
            name: "title",
            width: "22%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "Holiday Type",
            data: "type",
            name: "type",
            width: "27%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "Country",
            data: "country_name",
            name: "country_name",
            width: "12%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "Date",
            data: "date",
            name: "date",
            width: "14%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },

        {
            title: "Status",
            data: "status",
            name: "status",
            width: "6%",
            orderable: false,
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("px-6 py-3 ");
            },
        },
        {
            title: "",
            data: "action",
            name: "action",
            orderable: false,
            searchable: false,
            width: "6%",
            createdCell: function(td, cellData, rowData, row, col) {
                $(td).addClass("action-cell text-end d-flex justify-content-end m-3");
            },
        },
    ],
    headerCallback: function(thead, data, start, end, display) {
        setTimeout(() => {
            $(thead).find("th").first().removeClass("dt-ordering-asc");
            $(thead).find("th").last().addClass("d-flex justify-content-end");

            $(thead).find("th").addClass('bg-blue-100  dark:bg-gray-900 px-6 py-3');

            $(thead).addClass(
                'text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400'
            )
        }, 10);
    },
    createdRow: function(row, data, dataIndex) {
        $('.dt-layout-table').next().addClass('pagination-show-entries');
        $(row).addClass(
            "odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200"
        );
    },

    drawCallback: async function(settings) {
        if (typeof initializeDropdowns === 'function') {
            await initializeDropdowns();
        } else {
            console.warn('initializeDropdowns is not defined');
        }
    }
});

window.holiday_list_datatable = holiday_list_datatable;

const $targetEledit = document.getElementById('static-modal');
const customerModal = new Modal($targetEledit, {}, {
    id: 'static-modal',
    override: true
});

$('.close-modal').on('click', function() {
    customerModal.toggle();
});

$("#search").on('input', function() {
    holiday_list_datatable.ajax.reload();
});
$(document).on('click', '.del-button', function() {
    if (confirm("Are you sure?")) {
        var holiday_id = $(this).data('holiday_id');
        $.ajax({
            url: HOLIDAT_REMOVE + holiday_id,
            type: 'DELETE',
            dataType: 'json',
            data: typeof post_parameter !== "undefined" ? post_parameter : {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showSuccessToast(response.message);
                var currentPage = $("#" + datatable_id).DataTable().page.info().page;
                holiday_list_datatable.page(currentPage).draw(false);
            }
        });
    }
});

$(document).on('change', '#country_id', function() {
    holiday_list_datatable.ajax.reload();
});

$(document).on('click', '.holiday_active_inactive', async function() {
    if(confirm("Are you sure?")){
        var holiday_id = $(this).data('holiday_id');
        try{
            var response = await fetchWrapper.post(
                HOLIDAY_ACTIVE_INACTIVE+holiday_id,
            );
            showSuccessToast(response.message);
            holiday_list_datatable.ajax.reload();
        }catch(error){
            showErrorToast(error.message)
        }
    }
    
});
