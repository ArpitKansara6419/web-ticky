const $targetEledit = document.getElementById("customer-detail-static-modal");
const customerModal = new Modal(
    $targetEledit,
    {},
    {
        id: "customer-detail-static-modal",
        override: true,
    }
);

$(".close-modal").on("click", function () {
    customerModal.toggle();
});

function renderCustomerDocuments(documents) {
    const container = document.getElementById("customerDocuments");
    container.innerHTML = ""; // Clear previous content

    // Add each document
    if (documents.length <= 0) {
        container.innerHTML += `
                        <div class="w-full ">
                            <strong class="font-medium text-md">No Documents</strong>
                        </div>`;
        return;
    }
    documents.forEach((doc) => {
        container.innerHTML += `
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Name</span>
                                    <strong class="font-medium text-md">${
                                        doc.title ?? "-"
                                    }</strong>
                                </div>
                              
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Expiry</span>
                                    <strong class="font-medium text-md">${
                                        doc.doc_expiry ?? "-"
                                    }</strong>
                                </div>

                                  <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Doc Link</span>
                                    <a href="${
                                        window.location.origin
                                    }/storage/${
            doc.doc
        }" target="_blank" class="text-blue-600 hover:underline break-all">
                                        <img src="/assets/pdf-icon.png" class="w-8 h-8" alt=""> 
                                    </a>
                                </div>
                            `;
    });
}

function setText(selector, value) {
    $(selector).text(value || "-");
}

function setAuthorisedPersonDetails(authorisedPersons) {
    const container = document.getElementById("authPersonDetails");
    container.innerHTML = ""; // Clear previous content

    // Add each document
    if (authorisedPersons.length <= 0) {
        container.innerHTML += `
                        <div class="w-full ">
                            <strong class="font-medium text-md">No Details are available.</strong>
                        </div>`;
        return;
    }
    authorisedPersons.forEach((person) => {
        var personEmail = person.person_email ? person.person_email : "-";
        container.innerHTML += `
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Name</span>
                                    <strong class="font-medium text-md authPerson">${person.person_name}</strong>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Email</span>
                                    <strong class="font-medium text-md authPersonEmail">${personEmail}</strong>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Contact</span>
                                    <strong class="font-medium text-md authPersonContact">${person.person_contact_number}</strong>
                                </div>
                            `;
    });
}

$(document).on("click", ".customer-viewBtn", function () {
    const customerId = $(this).data("customer-id");
    customerModal.toggle();

    $.ajax({
        url: `/get-customer-details/${customerId}`,
        type: "GET",
        success: function (response) {
            const c = response.customer;
            console.log("customerData:", response);

            setText(".customerCode", "#" + c.customer_code);
            setText(".customerName", c.name);
            setText(".customerType", c.customer_type);
            setText(".companyRegNo", c.company_reg_no);
            setText(".vatNo", c.vat_no);
            setText(".customerEmail", c.email);
            // setText('.authPerson', c.auth_person);
            // setText('.authPersonEmail', c.auth_person_email);
            // setText('.authPersonContact', c.auth_person_contact);
            setText(".customerAddress", c.address);
            setText(".customerStatus", c.status);
            renderCustomerDocuments(c.customer_docs);

            setAuthorisedPersonDetails(c.authorised_persons);

            // Profile Image
            if (c.profile_image) {
                // Ensure you're using the correct URL format
                $(".customerImage").attr("src", `/storage/${c.profile_image}`);
            } else {
                $(".customerImage").attr("src", "/user_profiles/user/user.png");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        },
    });
});
