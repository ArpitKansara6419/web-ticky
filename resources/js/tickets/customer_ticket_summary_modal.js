import { fetchWrapper } from "../helper.js";

var $targetEl = document.getElementById('customer-ticket-summary-modal');
var customerTicketSummaryModal = new Modal($targetEl, {}, {
    override: true
});

$(".close_customer_summary_modal").on('click', async function(){
    customerTicketSummaryModal.toggle();
});

$(document).on('click', '.open-ticket-work-modal', async function() {

    try{
        customerTicketSummaryModal.toggle();
        let storageLink = STORAGE_LINK;
        let ticketWorkId = $(this).data('ticket-work-id'); // Get the Ticket ID

        var data = {id: ticketWorkId};

        var response = await fetchWrapper.get(
            `${CUSTOMER_PAYOUT_FETCH_POPUP}`,
            data
        );

        $('#customer-ticket-summary-modal-body').html(response.html);
    }catch(error){
        showSuccessToast(error.message)
    }
    // console.log('id in fetch ==', ticketWorkId);
    // $.ajax({
    //     url: CUSTOMER_PAYOUT_FETCH_POPUP,
    //     method: 'GET',
    //     data: {
    //         id: ticketWorkId
    //     },
    //     success: function(response) {
    //         $('#customer-ticket-summary-modal-body').html(response.html); // Update popup content
    //     },
    //     error: function(xhr) {
    //         console.error(xhr.responseText);
    //         alert('Error fetching ticket details. Please try again.');
    //     }
    // });

});

$(document).on('click', '.customer-edit-btn, .customer-save-btn', function() {

    let payoutId = $("#customer-ticket-summary-modal .payout-id-hidden").val(); // Get ID from modal

    console.log("Final Payout ID:", payoutId); // Debugging

    let target = $(this);
    let row = target.closest("tr");
    let amountText = row.find(".amount-text");
    let amountInput = row.find(".amount-input-customer");
    let editBtn = row.find(".customer-edit-btn");
    let saveBtn = row.find(".customer-save-btn");

    if (target.hasClass("customer-edit-btn")) {
        // Enable editing mode
        amountText.addClass("hidden");
        amountInput.removeClass("hidden").focus();
        editBtn.addClass("hidden");
        saveBtn.removeClass("hidden");
    } else if (target.hasClass("customer-save-btn")) {
        // Save new value
        let newValue = amountInput.val();
        amountText.text(newValue).removeClass("hidden");
        amountInput.addClass("hidden");
        editBtn.removeClass("hidden");
        saveBtn.addClass("hidden");

        // Send AJAX request
        let key = row.find(".amount-cell").data("key");

        $.ajax({
            url: UPDATE_AMOUNT_CUSTOMER,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                payoutId: payoutId,
                key: key,
                amount: newValue,
                type: "customer"
            },
            success: function(response) {
                console.log("Updated successfully:", response);
                alert("Amount Updated Successfully");
            },
            error: function(xhr, status, error) {
                console.error("Error updating:", error);
            }
        });
    }
});
