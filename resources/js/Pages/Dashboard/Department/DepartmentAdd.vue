<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Add Department Page</span> 
        </div>
        <!-- <div class="mt-4">
            <span class="font-semibold text-2xl mt-10 ">New Department</span>
        </div> -->
        <div v-if="$page.props.flash.error" >{{ errorMessage($page.props.flash.error) }} </div>
        <div v-if="form.errors.departmentName">{{errorMessage(form.errors.departmentName)}}</div>
        
        <div class="w-full flex justify-center md:mt-[100px]">
            
            <div class="flex flex-col align-items-center  gap-3 mb-3  w-full   md:w-[400px] broder broder-gray-300 rounded-md shadow-md p-4 border border-gray-500">
                <span class="border-b border-gray-300 mb-2 ">
                    Information
                </span>
                <label for="username" class="font-semibold w-6rem">Department Name</label>
                <!-- <InputText id="username" class="flex-auto border border-gray-500  " autocomplete="off"  placeholder="Enter department name" /> -->
                <input v-model="form.departmentName" type="text" placeholder="Enter department name" class="flex-auto border border-gray-500 rounded " required/>
                <button @click="submitConfirmation" type="button" class="w-full btn-primary" >Save</button>
            </div>
        </div>

        
            
    </DashboardLayout>
</template>

<script setup>
import {ref} from 'vue'
import DashboardLayout from '../DashboardLayout.vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2/dist/sweetalert2.js'


const form = useForm({
    departmentName:''
})

    const submitConfirmation = ()=> 
    { 
        Swal.fire({
            title: "Submit Confirmation",
            text: "Are you sure?",
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
                    form.post(route('department.store'));
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
const toggleModal = ref(false)
</script>