<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Update Subject Codes Page </span>             
        </div>
         <!-- hasDivision || {{ hasDivision }} || selected dep: {{ selectedDepartment.name }} || selected Div: {{ selectedDivision }} || Description:  {{ form.description.length }} -->
    <div>
        <!-- selected department: {{ selectedDepartment }}  || codeToUpdate: {{ data.codeToUpdate }} -->
        <form @submit.prevent="submitConfirmation">
            <div v-if="form.errors.name">{{ errorMessage(form.errors.name) }}</div>
            <div class="flex w-full flex-col w-full md:w-[50%] pt-2">

                <label for="subCode" class="my-2 text-lg font-semibold text-gray-600">Subject Code :</label>
                <input v-model="form.name" type="text" id="subCode" placeholder="Enter subject code" class="rounded rounded-lg border border-blue-700 my-2" required/>
                
                <label for="department" class="my-2 text-lg font-semibold text-gray-600">Department :</label>
                <select v-model="selectedDepartment" id="department" class=" border border-blue-500 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 my-2 hover:cursor-pointer" required>
                    <option value="" selected hidden >Select a department</option>
                    <option v-for="dep in data.departments" :key="dep.id" :value="dep" class=" text-lg ">
                        {{ dep.name }}
                    </option>
                </select>

                <label :class="{'hidden':!hasDivision}" for="department" class="my-2 text-lg font-semibold text-gray-600">Division :</label>
                <select  v-model="selectedDivision" 
                    :class="{'hidden':!hasDivision}"
                    class=" border border-blue-500 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 my-2 hover:cursor-pointer"
                    :required="hasDivision"
                    >
                    <option value="" hidden selected  >Select a division</option>
                    <option v-for="div in selectedDepartment.divisions" :key="div.key" :value="div">
                        {{ div.name }}
                    </option>
                </select>
                {{ descriptionLengthValidator }}
                 <label for="description" class="my-2 text-lg font-semibold text-gray-600">Description : </label>
                <span v-if="descriptionLengthValidator" class="text-red-500">{{ descriptionLengthValidator }}</span>
                <textarea required v-model="form.description" id="description" name="subDescription" rows="4" cols="10" class="bg-gray-50 border border-blue-500 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 my-2 hover:cursor-pointer">

                </textarea> 
               
                
                <button   type="submit" class="btn-primary " :disabled="form.processing">Update</button>
            </div>
        </form>
    </div>
    </DashboardLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import DashboardLayout from '../DashboardLayout.vue';
import {ref, watch, onMounted} from 'vue'
import Swal from 'sweetalert2/dist/sweetalert2.js'

onMounted(() => {
    checkDivision();
});

function checkDivision()
{
    if(data.codeToUpdate.division_id)
    {
        selectedDivision.value = data.codeToUpdate.division
        
        hasDivision.value = true;
    }
}

const data = defineProps({
    codeToUpdate:Object,
    departments:Array,
})


const form = useForm({
    id:data.codeToUpdate.id,
    name:data.codeToUpdate.name,
    description:data.codeToUpdate.description,
    department_id:'',
    division_id:'',
})

const userExistingDepartment = data.departments.filter((val)=> val.id === data.codeToUpdate.department_id )
const userExistingDivision = ref('')
//const subjectDescription = ref('')
const selectedDepartment = ref(userExistingDepartment[0])
const selectedDivision = ref('')
const hasDivision = ref(false);
const descriptionLengthValidator = ref('')

watch(selectedDepartment,(val)=>{

    if(val.divisions.length > 1)
    {
        hasDivision.value = true
    }
    else
    {
        
        hasDivision.value = false
        selectedDivision.value =''
    }
    
})

const validator = ()=>{
    if(form.description.length > 50)
    {
        
        descriptionLengthValidator.value = "Description could not exceed 20 characters."
    }
    else
    {
        
        descriptionLengthValidator.value = ''
    }
}

const submitConfirmation = ()=> 
    { 
        validator();

        if(descriptionLengthValidator.value === '')
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
                    
                    
                    form.department_id = selectedDepartment.value.id
                    form.division_id = selectedDivision.value.id

                    form.put(route('subject.codes.update'));
                    
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