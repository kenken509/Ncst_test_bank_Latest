<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Update Division Page</span> 
            <div class="relative">
                <input v-model="searchField" type="text" placeholder="search" class="rounded-md">
                <svg class="absolute top-3 right-2 w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>
            </div> 
        </div>

        <div v-if="$page.props.flash.success" >{{ successMessage($page.props.flash.success) }} </div>
        <div v-if="$page.props.flash.error" >{{ errorMessage($page.props.flash.error) }} </div>
        <form >

            <div class="flex w-full flex-col w-full md:w-[50%] pt-2">

                <label for="depName" class="my-2 text-lg font-semibold text-gray-600">Division Name :</label>
                <input v-model="form.divisionName" type="text" id="depName" placeholder="Enter division name" class="rounded rounded-lg border border-blue-700 my-2" required/>
                
                
                <label for="departments" class="my-2 text-lg font-semibold text-gray-600" >Department :</label>
                <select v-model="form.departmentId"   id="countries" class="bg-gray-50 border border-blue-500 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 my-2 hover:cursor-pointer" required>
                    <!-- <option value="" selected hidden>Select a department</option> -->
                    <option v-for="dep in data.departments"  :key="dep.id" :value="dep.id" class="text-gray-800" >
                        {{ dep.name }}
                    </option>
                </select>
                
                <button @click="updateConfirmation"   type="button" class="btn-primary ">Update</button>
            </div>
        </form>
        
       
    </DashboardLayout>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import DashboardLayout from '../DashboardLayout.vue';
import {ref, watch} from 'vue'
import Swal from 'sweetalert2'

const searchField = ref('')
const selectedDepartmentId = ref(null)
const data = defineProps({
    division:Object,
    departments:Array,
})

watch(selectedDepartmentId,(val)=>{
    console.log('current selected depId: '+val)
})


const form = useForm({
    divisionName:data.division ? data.division.name : '',
    divisionId:data.division ? data.division.id: '',
    departmentId:data.division ? data.division.department_id : '',
})


const updateConfirmation = ()=> 
    { 
        Swal.fire({
            title: "Are you sure?",
            text: "Are you sure you want to update this division?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, save it!",
            allowOutsideClick:false,
            allowEscapeKey:false,
            }).then((result) => {
                if(result.isConfirmed)
                {
                    const updateUrl = ()=> form.put(route('division.update.store')); 

                    updateUrl();
                    //router.put(updateUrl);
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

//     function successMessage(message)
//     {
//         Swal.fire({
//             title:'Success',
//             text:message,
//             icon:'success',
//             allowOutsideClick:false,
//             allowEscapeKey:false,
//         }).then((result)=>{
//             if(result.isConfirmed)
//             {
//                 location.reload();
                
//             }
//         })
//     }
    
//     function errorMessage(message) {
//         Swal.fire({
//             icon: "error",
//             title: "Oops...",
//             text: message + '!',
//             allowOutsideClick:false,
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 location.reload();
//             }
//         })
//     }
</script>

