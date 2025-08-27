import { fetchWrapper } from "../helper.js";

/**
 * All Notifications
 */
let allNotiPage = 1;
let allNotiLoading = false;
let allNotiEndOfData = false;

var allNotificationBody = $("#all");
export const resetallNotiPagination = () => {
    allNotificationBody.html("");
    allNotiPage = 1;
    allNotiLoading = false;
    allNotiEndOfData = false;
}

export const fetchAllNoti = async () => {
    
    var filter = {};

    if (allNotiLoading || allNotiEndOfData) return;
    allNotiLoading = true;

    var response = await fetchWrapper.post(
        `${NOTI_LAZY_LOAD}?page=${allNotiPage}`,
        filter
    );

    console.log("response => ", response)

    if (response.notifications.data.length === 0) {
        allNotiEndOfData = true;
        if(typeof response.notifications.total != 'undefined' && response.notifications.total === 0){
            allNotificationBody.append(response.html);
        }
        
    } else {


        allNotificationBody.append(response.html);
        allNotiPage++;
        allNotiLoading = false;
    }
}

$(document).ready(async function () {
    await fetchAllNoti();
    $("#all").on("scroll", function () {
        const container = $(this).get(0);
        if (
            container.scrollTop + container.clientHeight >=
            container.scrollHeight - 5
        ) {
            fetchAllNoti();
        }
    });
});

$("#all-tab").on('click', async function(){
    await resetallNotiPagination();
    await fetchAllNoti();
});


/**
 * Unread Notifications
 */
let unreadNotiPage = 1;
let unreadNotiLoading = false;
let unreadNotiEndOfData = false;

var unreadNotificationBody = $("#unread");
export const resetunreadNotiPagination = () => {
    unreadNotificationBody.html("");
    unreadNotiPage = 1;
    unreadNotiLoading = false;
    unreadNotiEndOfData = false;
}

export const fetchunreadNoti = async () => {
    
    var filter = {};
    filter.is_read = false;
    if (unreadNotiLoading || unreadNotiEndOfData) return;
    unreadNotiLoading = true;

    var response = await fetchWrapper.post(
        `${NOTI_LAZY_LOAD}?page=${unreadNotiPage}`,
        filter
    );

    if (response.notifications.data.length === 0) {
        unreadNotiEndOfData = true;
        if(typeof response.notifications.total != 'undefined' && response.notifications.total === 0){
            unreadNotificationBody.append(response.html);
        }
        
    } else {


        unreadNotificationBody.append(response.html);
        unreadNotiPage++;
        unreadNotiLoading = false;
    }
}

$(document).ready(async function () {
    await fetchunreadNoti();
    $("#unread").on("scroll", function () {
        const container = $(this).get(0);
        if (
            container.scrollTop + container.clientHeight >=
            container.scrollHeight - 5
        ) {
            fetchunreadNoti();
        }
    });
});

$("#unread-tab").on('click', async function(){
    await resetunreadNotiPagination();
    await fetchunreadNoti();
});

/**
 * Read Notifications
 */
let readNotiPage = 1;
let readNotiLoading = false;
let readNotiEndOfData = false;

var readNotificationBody = $("#read");
export const resetreadNotiPagination = () => {
    readNotificationBody.html("");
    readNotiPage = 1;
    readNotiLoading = false;
    readNotiEndOfData = false;
}

export const fetchreadNoti = async () => {
    
    var filter = {};
    filter.is_read = true;

    if (readNotiLoading || readNotiEndOfData) return;
    readNotiLoading = true;

    var response = await fetchWrapper.post(
        `${NOTI_LAZY_LOAD}?page=${readNotiPage}`,
        filter
    );

    if (response.notifications.data.length === 0) {
        readNotiEndOfData = true;
        if(typeof response.notifications.total != 'undefined' && response.notifications.total === 0){
            readNotificationBody.append(response.html);
        }
        
    } else {


        readNotificationBody.append(response.html);
        readNotiPage++;
        readNotiLoading = false;
    }
}

$(document).ready(async function () {
    await fetchreadNoti();
    $("#read").on("scroll", function () {
        const container = $(this).get(0);
        if (
            container.scrollTop + container.clientHeight >=
            container.scrollHeight - 5
        ) {
            fetchreadNoti();
        }
    });
});

$("#read-tab").on('click', async function(){
    await resetreadNotiPagination();
    await fetchreadNoti();
});

/**
 * MARK AS READ FUNCTIONALITY
 */
$(document).on('click', '.mark_as_read', async function(){
    var response = await fetchWrapper.get(
        $(this).data('href')
    );
    $(".all_count").html(response.all_count);
    $(".read_count").html(response.read_count);
    $(".unread_count").html(response.unread_count);
    showSuccessToast(response.message);
    $(this).remove()
});

$(document).on('click', '.mark-all-as-read', async function(){
    var response = await fetchWrapper.get(
        $(this).data('href')
    );
    $(".all_count").html(response.all_count);
    $(".read_count").html(response.read_count);
    $(".unread_count").html(response.unread_count);
    showSuccessToast(response.message);
    $(".mark_as_read").remove()
    // $(this).remove();
});