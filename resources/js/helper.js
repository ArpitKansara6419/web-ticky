// import { hideLoader, showLoader } from "./app";
import axios from "./axios";

export const fetchWrapper = {
    get: request("GET"),
    post: request("POST"),
    put: request("PUT"),
    delete: request("DELETE"),
    patch: request("PATCH"),
};

function request(method) {
    return async (url, body, type = "json", is_blob = false) => {
        // showLoader();
        if (method === "PUT" && type === "form") {
            body["_method"] = "PUT";
        }
        const requestOptions = {
            method: method === "PUT" && type === "form" ? "POST" : method,
            headers: authHeader(url, type),
        };
        if (body) {
            if (method === "GET") {
                requestOptions.params = body;
            } else {
                requestOptions.data =
                    type == "form" ? body : JSON.stringify(body);
            }
        }
        if (is_blob) {
            requestOptions.responseType = "blob";
        }
        return axios(url, requestOptions)
            .then(handleResponse)
            .catch(handleError);
    };
}

// header function handle
function authHeader(url, type) {
    let header = {
        "Content-Type":
            type == "form" ? "multipart/form-data" : "application/json",
    };
    return header;
}

async function handleResponse(response) {
    // hideLoader();
    if (response) {
        return response?.data;
    } else {
        return Promise.reject("something went wrong");
    }
}

async function handleError(error) {
    // hideLoader();
    if (error === null)
        return Promise.reject("Unrecoverable error!! Error is null!");
    const response = error?.response;
    if (error.code === "ERR_NETWORK") {
        return Promise.reject("connection problems..");
    } else if (error.code === "ERR_CANCELED") {
        return Promise.reject("connection canceled..");
    }
    if (response) {
        const statusCode = response?.status;
        const data = response?.data;
        if (statusCode === 404) {
            return Promise.reject(
                "The requested resource does not exist or has been deleted"
            );
        } else if (statusCode === 401) {
            return Promise.reject(data.message);
        } else if (data) {
            const errorData = (data && data.message) || response.status;
            return Promise.reject(errorData);
        } else {
            return Promise.reject("something went wrong");
        }
    }
}

// export const tostMessage = (message, type = "success") => {
//     const toast = Swal.mixin({
//         toast: true,
//         position: "top-end",
//         showConfirmButton: false,
//         timer: 3000,
//     });
//     toast.fire({
//         icon: type,
//         title: message,
//         padding: "10px 20px",
//     });
// };
