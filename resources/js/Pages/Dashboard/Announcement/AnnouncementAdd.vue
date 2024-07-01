<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">New Announcement </span> 
            
        </div>
        
        <Dialog v-model:visible="announcementModalOpen" modal  :style="{ width: '50rem' }">
            <div class=" w-full">
                <h2 class="text-xl font-bold mb-2">New Announcement</h2>
                <div class="w-full border-b border-gray-500 "></div>
            </div> 
            <div class="w-full mt-6 ">
                <form @submit.prevent="submit">
                    <div class="mt-6">
                        <label for="announcement" class="font-semibold ">Content: </label>
                        <textarea v-model="content" id="announcement" class="w-full mt-2 rounded-md" rows="4" cols="50" required></textarea> 
                    </div>
                    <div class="grid grid-cols-8 mt-4 w-full ">
                        <div class="flex items-center gap-4 col-span-2 mt-4 md:col-span-1">
                            <label for="startDate" class="font-semibold ">Start Date: </label>
                        </div>
                        <div class="col-span-6 md:col-span-4">
                            <input v-model="startDate" id="startDate" type="date" class="rounded-md mt-4" required/>
                        </div>
                        
                        <div class="flex items-center gap-2 col-span-2 mt-4 md:col-span-1  md:flex md:justify-end">
                            <label for="endDate" class="font-semibold ">End Date: </label>
                        </div>
                        <div class="col-span-6 mt-4 md:col-span-2 md:flex md:justify-end">
                            <input v-model="endDate" id="endDate" type="date" class="rounded-md" required :disabled="!startDate"/>
                        </div>
                    </div>
                    
                    <div class="mt-4 border-t-2 border-gray-300 pt-2">
                        <button @click="reset" type="button" class="btn-primary w-full ">Reset</button>
                        <button type='submit'class="btn-primary w-full" :disabled="form.processing">Save</button>
                    </div>
                    
                </form>
            </div>
        </Dialog>
            
        
    </DashboardLayout>
    
</template>
<script setup>
import {ref, onMounted} from 'vue'
import DashboardLayout from '../DashboardLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2/dist/sweetalert2.js'

const announcementModalOpen = ref(false)

onMounted(()=>{
    announcementModalOpen.value = true
})

const user = usePage().props.user
const form = useForm({
    startDate:'',
    endDate:'',
    content:'',
    author:user.id,
})

const startDate = ref('')
const endDate = ref('')
const content = ref('')
const noError = ref(true);

const currentDate = ref(new Date()) 

const submit = ()=>{

    
    if(startDate.value)
    {
        let selectedDate = new Date(startDate.value)
        selectedDate.setHours(0, 0, 0, 0);
        currentDate.value.setHours(0, 0, 0, 0);

        if(selectedDate < currentDate.value)
        {
            errorMessage('"Start Date" cannot be less than today.')

            noError.value = false
            return
        }
        noError.value = true
    }

    if(endDate.value)
    {
        let selectedEndDate = new Date(endDate.value)
        let selectedStartDate = new Date(startDate.value);
        
        selectedStartDate.setHours(0, 0, 0, 0)
        selectedEndDate.setHours(0, 0, 0, 0)

        if(selectedEndDate < selectedStartDate)
        {
            errorMessage('"End date" cannot be less than start date');
            noError.value = false
            return
        }

        noError.value = true
    }
    
    if(noError.value)
    {
        form.startDate = startDate.value
        form.endDate = endDate.value
        form.content = content.value
        
        submitConfirmation()
    }
    
}

function submitConfirmation()
{
    announcementModalOpen.value = false
    Swal.fire({
            title: "Are you sure?",
            text: "Are you sure you want to create this new Announcement?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, create it!",
            allowOutsideClick:false,
            allowEscapeKey:false,
            }).then((result) => {
                if(result.isConfirmed)
                {
                     form.post(route('announcement.store',{
                        preserveScroll:true,
                        preserveState:true,
                        onSuccess:()=>announcementModalOpen.value = true
                     })); 

                    //router.delete(deleteUrl);
                }

                if(result.isDismissed)
                {
                    Swal.fire({
                        title:'Canceled',
                        text:'Your action was canceled!',
                        icon:'error',
                        confirmButtonColor: '#3085d6',
                    })
                }
        });
}
function errorMessage(message) 
{
    announcementModalOpen.value = false
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: message + '!',
        allowOutsideClick:false,
    }).then((result) => {
        if (result.isConfirmed) {
            announcementModalOpen.value = true
        }
    })
}

function reset()
{
    startDate.value = null
    endDate.value = null
    content.value = ''
}
</script>