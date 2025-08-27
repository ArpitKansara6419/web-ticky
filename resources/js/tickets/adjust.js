import { fetchWrapper } from "../helper.js";

var editAdjustModal = new Modal(document.getElementById('work_hours_edit_adjust_modal'), {}, {
    override: true
});
var addAdjustModal = new Modal(document.getElementById('work_hours_add_adjust_modal'), {}, {
    override: true
});
var editAdjustForm = $('#form_edit_adjust');
var addAdjustForm = $('#form_add_adjust');

$(document).on('click', '.open_Edit_adjust_model', async function() {
    
    let ticketWorkId = $(this).data('ticket_work_id');

    editAdjustModal.toggle();
    try{
        var data = {id: ticketWorkId};

        var response = await fetchWrapper.get(
            `${EDIT_TICKET_WORK_ADJUST}${ticketWorkId}`,
        );

        console.log(response)

        editAdjustForm.find('[name="start_time"]').val(response.start_time ?? '');
        editAdjustForm.find('[name="end_time"]').val(response.end_time ?? '');
        
        editAdjustForm.attr('action', `${UPDATE_TICKET_WORK_ADJUST}${ticketWorkId}`);
    }catch(error){
        showErrorToast(error.message)
    }
});

editAdjustForm.on('submit', async function(e){
    e.preventDefault();
    try{
        // var data = editAdjustForm.serialize();
        var form = this;
        var formdata = Object.fromEntries(new FormData(form).entries());

        var response = await fetchWrapper.post(
            $(this).attr('action'),
            formdata
        );

        editAdjustModal.toggle();
        if(response.status === true){
            showSuccessToast(response.message);
            setTimeout(function(){
                location.reload();
            }, 800);
        }
    }catch(error){
        showSuccessToast(error)
    }
})

addAdjustForm.on('submit', async function(e){
    e.preventDefault();
    try{
        // var data = editAdjustForm.serialize();
        var form = this;
        var formdata = Object.fromEntries(new FormData(form).entries());

        var response = await fetchWrapper.post(
            $(this).attr('action'),
            formdata
        );

        
        if(response.status === true){
            showSuccessToast(response.message);
            setTimeout(function(){
                location.reload();
            }, 800);
            addAdjustModal.toggle();
        }else{
            showSuccessToast(response.message);
        }
        
    }catch(error){
    
        showErrorToast(error)
    }
})

$(document).on('click', '.close_edit_adjust_modal', function(){
    editAdjustModal.toggle();
});

$(document).on('click', '.toggleModal', async function(){
    addAdjustModal.toggle();
});