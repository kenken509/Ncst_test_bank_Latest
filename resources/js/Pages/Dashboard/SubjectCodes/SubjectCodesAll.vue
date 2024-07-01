<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Subject Codes </span> 
            <div class="relative">
                <input v-model="searchField" type="text" placeholder="search" class="rounded-md">
                <svg class="absolute top-3 right-2 w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>
            </div> 
        </div>
        <div v-if="$page.props.flash.success" >{{ successMessage($page.props.flash.success) }} </div>
        <div v-if="$page.props.flash.error" >{{ errorMessage($page.props.flash.error) }} </div>
        <!--TABLE--> 
        <div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-200 uppercase bg-blue-900 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Subject Code</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            <th scope="col" class="px-6 py-3">Department</th>
                            <th scope="col" class="px-6 py-3">Division</th>
                            
                            <th  v-if="$page.props.user.role === 'admin'" scope="col" class="flex justify-center px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="code in paginatedData" :key="code.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ code.name }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ code.description }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ code.department.name }}
                            </th>
                            <th  scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                               <span v-if="code.division">
                                    {{ code.division.name }}
                               </span>
                               <span v-else>--</span>
                            </th>
                            
                            
                            
                            
                            <td  v-if="$page.props.user.role === 'admin'" class="px-6 py-4 text-center ">
                                <div  class="flex flex-col   lg:flex-row lg:justify-center  lg:space-x-4">
                                    <button  @click="deleteConfirmation(code.id)" class=" btn-warning my-2">Delete </button>
                                    <Link :href="route('subject.codes.update.show',{id:code.id})" type="button" class="btn-success my-2">
                                        Update
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--TABLE-->
        <div class="flex justify-center items-center gap-4 mt-2">
            <button @click="prevPage" type="button" :disabled="currentPage === 1" class="btn-pagination flex items-center gap-1" >
                <i class="pi pi-angle-double-left"></i>    Prev
            </button>
            <span>{{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" type="button" :disabled="currentPage === totalPages" class="btn-pagination flex items-center gap-1">
                  Next <i class="pi pi-angle-double-right"></i>
            </button>
        </div>
    </DashboardLayout>

</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import {ref,computed} from 'vue'
import Swal from 'sweetalert2/dist/sweetalert2.js'


const data = defineProps({
    codes:Array,
})

const searchField = ref('')
const itemsPerPage = ref(5) // Number of items to display per page
const currentPage = ref(1)

const filteredData = computed(() => {
    let searchTerm = searchField.value.toLowerCase().trim()
    if (!searchTerm) {
        return data.codes
    }

    return data.codes.filter(code => { 
        console.log(code)
      return   code.name.toLowerCase().includes(searchTerm) || code.description.toLowerCase().includes(searchTerm) ||
               code.department.name.toLowerCase().includes(searchTerm) || (code.division ? code.division.name.toLowerCase().includes(searchTerm):null)
                
    })
})

const totalPages = computed(() => Math.ceil(filteredData.value.length / itemsPerPage.value))

const paginatedData = computed(() => {
    if(searchField.value)
    {
        currentPage.value = 1
    }
    const startIndex = (currentPage.value - 1) * itemsPerPage.value
    const endIndex = startIndex + itemsPerPage.value
    return filteredData.value.slice(startIndex, endIndex)
})


function nextPage(){
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

const deleteConfirmation = (codeId)=> 
    { 
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
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
                    const deleteUrl = route('subject.codes.delete',{id: codeId })

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
                location.reload();
            }
        })
    }

    function errorMessage(message) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: message + '!',
            allowOutsideClick:false,
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        })
    }
</script>

