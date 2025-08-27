import { fetchWrapper } from "../helper.js";

var $targetEl = document.getElementById('holiday-sync-modal');
var holidaySyncModal = new Modal($targetEl, {}, {
    override: true
});
var yearDropdown = $("#year");
var countrySearch = $("#county_search");
var isOpen = false;

$("#syncBtn").on("click", async function () {
    if(!isOpen){
        isOpen = true;
        await fetchCountries();
    }
    holidaySyncModal.toggle();
});

async function fetchCountries()
{
    try {
        var year = yearDropdown.val();

        var response = await fetchWrapper.get(
            `${HOLIDAY_COUNTRY_LOAD}`,
            {year: year}
        );
        $("#country_list_load").html(response.html);
        countrySearch.trigger('input');
    } catch (error) {
        showErrorToast(error.message)
    }
}

$(".toggleModal").on("click", function () {
    holidaySyncModal.toggle();
});

// SEARCH 
$(document).on('input', '#county_search', function() {
    var filter = $(this).val().toLowerCase().trim();

    $('.country-list').each(function () {
        var $item = $(this);
        var name = $item.find('.country-name').eq(0).text().toLowerCase();
        var match = name.includes(filter);
        if (match) {
            $item.removeClass('hidden');
        } else {
            $item.addClass('hidden');
        }
    });
});

yearDropdown.on("change", async function () {
    isOpen = false;
    await fetchCountries();
});

$(document).on('click', '.sync_holiday_country', async function() {
    var formData = {};
    formData.country = $(this).data('country');
    formData.year = yearDropdown.val();
    formData.country_code = $(this).data('country_code');
    formData.uuid = $(this).data('uuid');
    formData.flag_unicode = $(this).data('flag_unicode');

    if(confirm('Are you sure you want to sync?')) {
        try{
            var response = await fetchWrapper.post(
                SYNC_HOLIDAY,
                formData
            );
            showSuccessToast(response.message);
            holiday_list_datatable.ajax.reload();
            $(this).attr('disabled', true);
            $(this).addClass('disabled:opacity-50 disabled:cursor-not-allowed');
            isOpen = false;
        }catch(error){
            showErrorToast(error.message)
        }
    };
});

