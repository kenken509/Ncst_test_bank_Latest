
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
        
        <div class="flex flex-col align-items-center gap-3 mb-3 mt-10 w-full md:w-1/2">
            <label for="username" class="font-semibold w-6rem">Department Name</label>
            <!-- <InputText id="username" class="flex-auto border border-gray-500  " autocomplete="off"  placeholder="Enter department name" /> -->
            <input v-model="form.departmentName" type="text" placeholder="Enter department name" class="flex-auto border border-gray-500 rounded " required/>
            <button @click="updateConfirmation" type="button" class="w-full btn-primary" >Save</button>
        </div>
    </DashboardLayout>
</template>

<script setup>
import {ref} from 'vue'
import DashboardLayout from '../DashboardLayout.vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2/dist/sweetalert2.js'

const data = defineProps({
    department:Object,
})
const form = useForm({
    departmentId:data.department.id,
    departmentName:data.department.name,
})

    const updateConfirmation = ()=> 
    { 
        Swal.fire({
            title: "Update Confirmation",
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
                    form.put(route('department.update'));
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


