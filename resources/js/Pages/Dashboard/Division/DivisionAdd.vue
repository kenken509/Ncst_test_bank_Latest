<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Add Division Page</span> 
            
        </div>

        <div v-if="$page.props.flash.success" >{{ successMessage($page.props.flash.success) }} </div>
        <div v-if="$page.props.flash.error" >{{ errorMessage($page.props.flash.error) }} </div>
        <form @submit.prevent="addConfirmation">

            <div class="flex w-full flex-col w-full md:w-[50%] pt-2">

                <label for="depName" class="my-2 text-lg font-semibold text-gray-600">Division Name :</label>
                <input v-model="form.divisionName" type="text" id="depName" placeholder="Enter division name" class="rounded rounded-lg border border-blue-700 my-2" required/>
                
                
                <label for="departments" class="my-2 text-lg font-semibold text-gray-600" >Department :</label>
                <select v-model="form.departmentId"  id="countries" class="bg-gray-50 border border-blue-500 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 my-2 hover:cursor-pointer" required>
                    <option value="" selected hidden>Select a department</option>
                    <option :value="dep.id" v-for="dep in data.existingDepartment" :key="dep.id" class="text-gray-800">
                        {{ dep.name }}
                    </option>
                </select>
                
                <button  type="submit" class="btn-primary ">Save</button>
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
    existingDepartment:Array,
})

watch(selectedDepartmentId,(val)=>{
    console.log('current selected depId: '+val)
})


const form = useForm({
    divisionName:'',
    departmentId:'',
})


const addConfirmation = ()=> 
    { 
        Swal.fire({
            title: "Are you sure?",
            text: "Are you sure you want to save this new division?",
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
                    const createUrl = form.post(route('division.store')); 

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

