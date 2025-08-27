const $targetElLead = document.getElementById("static-lead-modal");
const $ModalLead = new Modal(
    $targetElLead,
    {},
    {
        id: "static-lead-modal",
        override: true,
    }
);
$(document).on("click", ".lead-viewBtn",function () {
    const leadId = $(this).data("lead-id");
    $ModalLead.toggle();
    $.ajax({
        url: `/get-lead-details/${leadId}`,
        type: "GET",
        success: function (response) {
            const data = response.leadData;

            $(".leadCode").text("#" + (data.lead_code || "-"));
            $(".taskName").text(data.name || "-");
            $(".leadType").text(data.lead_type || "-");
            $(".endClientName").text(data.end_client_name || "-");
            $(".clientTicketNo").text(data.client_ticket_no || "-");
            $(".taskStartDate").text(data.task_start_date || "-");
            $(".taskEndDate").text(data.task_end_date || "-");
            $(".taskLocation").text(data.task_location || "-");
            $(".taskTime").text(data.task_time || "-");
            $(".scopeOfWork").text(data.scope_of_work || "-");
            $(".rateType").text(data.rate_type || "-");
            $(".hourlyRate").text(data.hourly_rate || "-");
            $(".halfDayRate").text(data.half_day_rate || "-");
            $(".fullDayRate").text(data.full_day_rate || "-");
            $(".monthlyRate").text(data.monthly_rate || "-");
            $(".currencyType").text(data.currency_type || "-");
            $(".travelCost").text(data.travel_cost || "-");
            $(".toolCost").text(data.tool_cost || "-");
        },
    });
});
$(document).on("click", ".close-lead-detail", function () {
    $('#static-lead-modal').hide();
});
