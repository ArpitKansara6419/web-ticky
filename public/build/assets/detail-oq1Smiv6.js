const r=document.getElementById("customer-detail-static-modal"),n=new Modal(r,{},{id:"customer-detail-static-modal",override:!0});$(".close-modal").on("click",function(){n.toggle()});function c(s){const e=document.getElementById("customerDocuments");if(e.innerHTML="",s.length<=0){e.innerHTML+=`
                        <div class="w-full ">
                            <strong class="font-medium text-md">No Documents</strong>
                        </div>`;return}s.forEach(t=>{e.innerHTML+=`
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Name</span>
                                    <strong class="font-medium text-md">${t.title??"-"}</strong>
                                </div>
                              
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Expiry</span>
                                    <strong class="font-medium text-md">${t.doc_expiry??"-"}</strong>
                                </div>

                                  <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Doc Link</span>
                                    <a href="${window.location.origin}/storage/${t.doc}" target="_blank" class="text-blue-600 hover:underline break-all">
                                        <img src="/assets/pdf-icon.png" class="w-8 h-8" alt=""> 
                                    </a>
                                </div>
                            `})}function o(s,e){$(s).text(e||"-")}function l(s){const e=document.getElementById("authPersonDetails");if(e.innerHTML="",s.length<=0){e.innerHTML+=`
                        <div class="w-full ">
                            <strong class="font-medium text-md">No Details are available.</strong>
                        </div>`;return}s.forEach(t=>{var a=t.person_email?t.person_email:"-";e.innerHTML+=`
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Name</span>
                                    <strong class="font-medium text-md authPerson">${t.person_name}</strong>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Email</span>
                                    <strong class="font-medium text-md authPersonEmail">${a}</strong>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-md text-gray-500">Contact</span>
                                    <strong class="font-medium text-md authPersonContact">${t.person_contact_number}</strong>
                                </div>
                            `})}$(document).on("click",".customer-viewBtn",function(){const s=$(this).data("customer-id");n.toggle(),$.ajax({url:`/get-customer-details/${s}`,type:"GET",success:function(e){const t=e.customer;console.log("customerData:",e),o(".customerCode","#"+t.customer_code),o(".customerName",t.name),o(".customerType",t.customer_type),o(".companyRegNo",t.company_reg_no),o(".vatNo",t.vat_no),o(".customerEmail",t.email),o(".customerAddress",t.address),o(".customerStatus",t.status),c(t.customer_docs),l(t.authorised_persons),t.profile_image?$(".customerImage").attr("src",`/storage/${t.profile_image}`):$(".customerImage").attr("src","/user_profiles/user/user.png")},error:function(e,t,a){console.error("Error:",a),alert("An error occurred. Please try again.")}})});
