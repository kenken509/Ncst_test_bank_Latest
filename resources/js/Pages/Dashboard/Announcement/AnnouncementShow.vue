<template>
    <DashboardLayout>
        <div class="flex items-center justify-end border-bot-only py-2 mb-4">
            
            <div class="relative">
                <input  v-model="searchField" type="text" placeholder="search" class="rounded-md">
                <svg class="absolute top-3 right-2 w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>
            </div> 
        </div>
        <div v-if="$page.props.flash.success" class="hidden">
            {{ successMessageDB = $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash.error" class="hidden">
            {{ errorMessageDB = $page.props.flash.error }}
        </div>
        <div class="flex justify-between items-center">
            <span class="text-[20px] font-bold text-gray-500">Announcements </span> 
            <button @click="handleNewButtonClicked" type='button' class="btn-primary py-2 px-6 hover:cursor-pointer">+ New</button>
        </div>
        <div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 rounded-t-md overflow-hidden dark:text-gray-400">
                <thead class="text-xs text-gray-200 uppercase bg-blue-900 dark:bg-gray-700 dark:text-gray-400 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">No.</th>
                        <th scope="col" class="px-6 py-3">Content</th>
                        <th scope="col" class="px-6 py-3">Start Date</th>
                        <th scope="col" class="px-6 py-3">End DAte</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th  v-if="$page.props.user.role === 'admin'" scope="col" class="flex justify-center px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(announcement,index) in paginatedData" :key="index" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-100 hover:cursor-pointer">
                        <td class=" align-middle px-6 py-3 text-black  ">
                            {{ index+1 }}
                        </td>
                        <td class=" align-middle px-6 py-3 text-black ">
                           <span>{{ announcement.content }}</span> 
                        </td>
                        <td class=" align-middle px-6 py-3 text-black">
                           <span>{{ announcement.start_date }}</span> 
                        </td>
                        <td class=" align-middle px-6 py-3 text-black ">
                           <span> {{ announcement.end_date }} </span>
                        </td>
                        <td class=" align-middle px-6 py-3 text-black ">
                           <span v-if="isExpired(announcement.end_date)" class="text-red-500"> Expired </span>
                           <span v-else class="text-green-700"> Active </span>
                        </td>
                        <td  v-if="$page.props.user.role === 'admin'" class="px-6 py-4 text-center ">
                                <div  class="flex flex-col   lg:flex-row lg:justify-center  lg:space-x-4">
                                    <button @click="handleDeleteButtonClicked(announcement.marking)"  class=" btn-warning my-2">Delete </button>
                                    <button  @click="handleUpdateButtonClicked(announcement.marking)" type="button" class="btn-success my-2">
                                        Update
                                    </button>
                                </div>
                        </td>
                    </tr>
                </tbody>
                
            </table>
        </div>

        <div class="flex items-center justify-center gap-4 mt-2">
            <button @click="prevPage" :disabled="currentPage === 1" class="btn-pagination flex items-center gap-1">
                <i class="pi pi-angle-double-left"></i> Prev
            </button>
            <span>{{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="btn-pagination flex items-center gap-1">
                 Next <i class="pi pi-angle-double-right"></i>
            </button>
        </div>

        <!--add announcement modal-->
        <Dialog v-model:visible="announcementModalOpen" modal  :style="{ width: '50rem' }">
            <div class="header w-full w-[91%] md:w-[94%]  fixed top-14 left-0 right-0 border flex justify-between items-center ml-6 mr-10 bg-blue-900 p-4 pl-2 pr-4 rounded-tl-md rounded-tr-md">
                <div class="flex items-center gap-2">
                    <img :src="logoUrl" alt="error" class=" w-16 h-16">
                    <span class="text-gray-100 text-xl">New Announcement</span>
                </div>
                <div class="flex  flex-col hidden md:block ">
                    <div class="flex justify-end text-[30px] text-gray-100">
                        {{ capitalizeFirstLetter(user.name) }}
                    </div>
                    <div class="flex justify-end text-gray-100">
                        {{ capitalizeFirstLetter(user.role) }}
                    </div>
                </div>
            </div>
            <div class="w-full mt-24">
                <form @submit.prevent="submit">
                    <div class="mt-6 ">
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
        <!--add announcement modal-->

        <!--update announcement modal-->
        <Dialog v-model:visible="updateAnnouncementModalOpen" modal  :style="{ width: '50rem' }">
            <div class="header w-full w-[91%] md:w-[94%]  fixed top-14 left-0 right-0 border flex justify-between items-center ml-6 mr-10 bg-blue-900 p-4 pl-2 pr-4 rounded-tl-md rounded-tr-md">
                <div class="flex items-center gap-2">
                    <img :src="logoUrl" alt="error" class=" w-16 h-16">
                    <span class="text-gray-100 text-xl">Update Announcement</span>
                </div>
                <div class="flex  flex-col hidden md:block ">
                    <div class="flex justify-end text-[30px] text-gray-100">
                        {{ capitalizeFirstLetter(user.name) }}
                    </div>
                    <div class="flex justify-end text-gray-100">
                        {{ capitalizeFirstLetter(user.role) }}
                    </div>
                </div>
            </div>
            <div class="w-full mt-24 ">
                <form @submit.prevent="update">
                    <div class="mt-6">
                        <label for="announcement" class="font-semibold ">Content: </label>
                        <textarea v-model="updateContent" id="announcement" class="w-full mt-2 rounded-md" rows="4" cols="50" required></textarea> 
                    </div>
                    <div class="grid grid-cols-8 mt-4 w-full ">
                        <div class="flex items-center gap-4 col-span-2 mt-4 md:col-span-1">
                            <label for="startDate" class="font-semibold ">Start Date: </label>
                        </div>
                        <div class="col-span-6 md:col-span-4">
                            <input v-model="updateStartDate" id="startDate" type="date" class="rounded-md mt-4" required/>
                        </div>
                        
                        <div class="flex items-center gap-2 col-span-2 mt-4 md:col-span-1  md:flex md:justify-end">
                            <label for="endDate" class="font-semibold ">End Date: </label>
                        </div>
            
                        <div class="col-span-6 mt-4 md:col-span-2 md:flex md:justify-end">
                            <input v-model="updateEndDate" id="endDate" type="date" class="rounded-md" required :disabled="!updateStartDate"/>
                        </div>
                    </div>
                    
                    <div class="mt-4 border-t-2 border-gray-300 pt-2">
                        <button @click="resetUpdate" type="button" class="btn-primary w-full ">Reset</button>
                        <button type='submit'class="btn-primary w-full" :disabled="form.processing">Update</button>
                    </div>
                    
                </form>
            </div>
        </Dialog>
        <!--update announcement modal-->
    </DashboardLayout>
    
</template>
<script setup>
import {ref, onMounted, watch, computed} from 'vue'
import DashboardLayout from '../DashboardLayout.vue';
import { useForm, usePage,Link, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2/dist/sweetalert2.js'
import { capitalizeFirstLetter } from '../Composables/capitalizeFirstLetter';


const logoUrl = ref('/storage/Images/ncstLogo.png');
const searchField = ref('');


const data = defineProps({
    announcements:Array,
})

const user = usePage().props.user
const form = useForm({
    startDate:'',
    endDate:'',
    content:'',
    author:user.id,
})

const filteredData = computed(()=>{// andito ako 1
    let searchTerm = searchField.value.toLowerCase().trim()
    // name email role  if has division division.name if has department department.name 
    if(!searchTerm)
    {
        return data.announcements
    }
    
    return data.announcements.filter((announcement)=>{
       return announcement.content.toLowerCase().includes(searchTerm) || announcement.start_date.toLowerCase().includes(searchTerm) ||
        announcement.end_date.toLowerCase().includes(searchTerm)
    })
})

const currentPage = ref(1)
const itemsPerPage = ref(5)

const paginatedData = computed(()=>{
    if(searchField.value)
    {
        currentPage.value = 1
    }

    let startIndex = (currentPage.value -1) * itemsPerPage.value
    let endIndex = startIndex + itemsPerPage.value

    return filteredData.value.slice(startIndex,endIndex)
})

function nextPage()
{
    if(currentPage.value < totalPages.value)
    {
        currentPage.value++
    }
}

function prevPage()
{
    if(currentPage.value > 1)
    {
        currentPage.value--
    }
}

const totalPages = computed(()=> Math.ceil(filteredData.value.length/itemsPerPage.value))

// new announcement logic **************************************************88
const announcementModalOpen = ref(false)
const handleNewButtonClicked = ()=>{
    announcementModalOpen.value = true
}   

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

function submitConfirmation(marking)
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

function errorMessage2(message) 
{
    announcementModalOpen.value = false
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: message + '!',
        allowOutsideClick:false,
    }).then((result) => {
        if (result.isConfirmed) {
        }
    })
}

function reset()
{
    startDate.value = null
    endDate.value = null
    content.value = ''
}

function handleDeleteButtonClicked(markings)
{
    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure you want to delete this Announcement?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick:false,
        allowEscapeKey:false,
        }).then((result) => {
            if(result.isConfirmed)
            {
                    
                const deleteUrl = route('announcement.delete',{marking:markings})

                router.delete(deleteUrl);
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


const successMessageDB = ref('');

watch(successMessageDB,(val)=>{
    let trimmedMessage = val.split('.')[0] + '.';
    successMessage(trimmedMessage)
})

const errorMessageDB = ref('')
watch(errorMessageDB,(val)=>{
    let trimmedMessage = val.split('.')[0] + '.';
    errorMessage2(trimmedMessage)
})

function successMessage(message)
    {
        Swal.fire({
            title:'Success',
            text:message,
            icon:'success',
            allowOutsideClick:false,
            allowEscapeKey:false,
        }).then((result)=>{
            if(result.isConfirmed)
            {
                reset();
                
            }
        })
    }

//update announcment logic
const updateContent = ref('')
const updateStartDate = ref('')
const updateEndDate = ref('');

const updateAnnouncementModalOpen = ref(false)
const updateData = ref([])

function handleUpdateButtonClicked(marking)
{
    updateData.value = data.announcements.filter((ann)=> ann.marking === marking);
    updateContent.value = updateData.value[0].content
    updateStartDate.value = updateData.value[0].start_date
    updateEndDate.value = updateData.value[0].end_date

    updateAnnouncementModalOpen.value = true
}

const formUpdate = useForm({
    startDate:'',
    endDate:'',
    content:'',
    marking:'',
    editor:user.id,
})

const update = ()=>{ // andito ako 2

    if(updateStartDate.value)
    {
        let selectedDate = new Date(updateStartDate.value)
        selectedDate.setHours(0, 0, 0, 0);
        currentDate.value.setHours(0, 0, 0, 0);

        if(selectedDate < currentDate.value)
        {
            errorMessageUpdate('"Start Date" cannot be less than today.')

            noError.value = false
            return
        }
        noError.value = true
    }

    if(updateEndDate.value)
    {
        let selectedEndDate = new Date(updateEndDate.value)
        let selectedStartDate = new Date(updateStartDate.value);
        
        selectedStartDate.setHours(0, 0, 0, 0)
        selectedEndDate.setHours(0, 0, 0, 0)

        if(selectedEndDate < selectedStartDate)
        {
            errorMessageUpdate('"End date" cannot be less than start date');
            noError.value = false
            return
        }

        noError.value = true
    }
    
    if(noError.value)
    {
        formUpdate.startDate = updateStartDate.value
        formUpdate.endDate = updateEndDate.value
        formUpdate.content = updateContent.value
        formUpdate.marking = updateData.value[0].marking
        submitConfirmationUpdate()
    }
}

function resetUpdate()
{
    updateStartDate.value = null
    updateEndDate.value = null
    updateContent.value = ''
}

function submitConfirmationUpdate(marking)
{
    updateAnnouncementModalOpen.value = false
    Swal.fire({
        title: "Are you sure?",
        text: "Are you sure you want to update this announcement?",
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
                formUpdate.put(route('announcement.update',{ // andito ako 1
                    preserveScroll:true,
                    preserveState:true,
                    onSuccess:()=>updateAnnouncementModalOpen.value = true
                })); 
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
function errorMessageUpdate(message) 
{
    updateAnnouncementModalOpen.value = false
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: message + '!',
        allowOutsideClick:false,
    }).then((result) => {
        if (result.isConfirmed) {
            updateAnnouncementModalOpen.value = true
        }
    })
}

function isExpired(endDate) 
{
    const today = new Date();
    const end = new Date(endDate);
    return end < today;
};


</script>