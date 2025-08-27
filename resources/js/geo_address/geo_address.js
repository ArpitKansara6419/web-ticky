// import moment from "moment-timezone";

const suggestionsList = document.getElementById("suggestions-list");
const inputElement = document.getElementById("add_line_1");

let sessionToken = new google.maps.places.AutocompleteSessionToken();

inputElement.addEventListener('input', debounce(async function () {
    const searchValue = this.value.trim();
    if (!searchValue) {
        suggestionsList.innerHTML = "";
        return;
    }

    try {
        const request = {
            input: searchValue,
            sessionToken: sessionToken,
        };

        const { suggestions } = await google.maps.places.AutocompleteSuggestion.fetchAutocompleteSuggestions(request);

        suggestionsList.innerHTML = ""; // clear previous

        suggestions.forEach((suggestion) => {
            const placePrediction = suggestion.placePrediction;
            const li = document.createElement("li");
            li.textContent = placePrediction.text.text;
            li.style.padding = "5px";
            li.style.cursor = "pointer";
            li.addEventListener("click", async () => {
                inputElement.value = placePrediction.text.text;
                suggestionsList.innerHTML = "";

                // console.log("placePrediction => ", placePrediction)

                $('#city').val('');
                $('#zipcode').val('');
                $('#country').val('');
                $('#latitude').val('');
                $('#longitude').val('');
                $("#timezone").val('');

                // const place = placePrediction.toPlace();
                const place = new google.maps.places.Place({
                    id: placePrediction.placeId,
                });
                await place.fetchFields({
                    fields: ["displayName", "formattedAddress", "location", "postalAddress", "addressComponents"],
                });

                // console.log('place.postalAddress.postalAddress => ', place.postalAddress)
                // console.log('place.postalAddress.addressComponents => ', place.addressComponents)

                // console.log(`Selected: ${place.displayName} at ${place.formattedAddress}`);

                if(place.postalAddress && typeof place.postalAddress.postalCode !== 'undefined'){
                    $('#zipcode').val(place.postalAddress.postalCode);
                    $('#city').val(place.postalAddress.locality);
                }

                for (var i = 0; i < place.addressComponents.length; i++) {
                    var component = place.addressComponents[i];

                    // console.log("component => ", component)
                    
                    
    
                    if (component.Dg.includes('country')) {
                        // console.log(component.longText)
                        var country = component.longText;
                        $('#country').val(country);
                    }
                    if (component.Dg.includes('locality')) {
                        console.log(component.longText)
                        var country = component.longText;
                        $('#city').val(country);
                    }
                    
                }

                

                $('#latitude').val(place.Dg.location.lat);
                $('#longitude').val(place.Dg.location.lng);

                getTimezone(place.Dg.location.lat, place.Dg.location.lng);
            });
            suggestionsList.appendChild(li);
        });
    } catch (error) {
        console.error("Autocomplete fetch error:", error);
    }
}, 300));

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

async function getTimezone(lat, lng) {
    const timestamp = Math.floor(Date.now() / 1000);
    const apiKey = "AIzaSyDDVz2pXtvfL3kvQ6m5kNjDYRzuoIwSZTI";
    var end_point = `https://maps.googleapis.com/maps/api/timezone/json?location=${lat},${lng}&timestamp=${timestamp}&key=${apiKey}`;
    // console.log(end_point)
    const response = await fetch(end_point);
    const data = await response.json();

    if (data.status === "OK") {
        if(data.timeZoneId === "Asia/Calcutta"){
            data.timeZoneId = "Asia/Kolkata";
        }
        $("#timezone").val(data.timeZoneId.trim()).trigger('change');
    } else {
        console.error("Timezone API error:", data);
    }
}