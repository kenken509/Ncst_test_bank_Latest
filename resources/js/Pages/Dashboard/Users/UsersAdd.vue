<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Add User Page</span> 
            <div class="relative">
                <input v-model="searchField" type="text" placeholder="search" class="rounded-md">
                <svg class="absolute top-3 right-2 w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>
            </div> 
        </div>{{ addNewUserForm.errors.email }}
        <div v-if="$page.props.flash.success" >{{ successMessage($page.props.flash.success) }} </div>
        <div v-if="$page.props.flash.error" >{{ errorMessage($page.props.flash.error) }} </div>
        <div v-if="addNewUserForm.errors.email">{{errorMessage(addNewUserForm.errors.email)}}</div>
        
        <div>
            <form>
                <div class=" mb-4 mt-6">
                  <span class=" font-semibold text-lg text-gray-500">User Information:</span> 
                </div>
                 
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col align-items-center gap-3 mb-3 col-span-2 md:col-span-1 relative">
                        <label for="username" class="font-semibold w-6rem">Email </label>
                        <input v-model="addNewUserForm.email" type="email" placeholder="Enter email" class="flex-auto border border-gray-500 rounded " required/>
                        <span v-if="addNewUserValidatorEmail" class="absolute inset-y-20 text-red-500" >{{ addNewUserValidatorEmail }} </span> 
                    </div>
                    
                    <div class="flex flex-col align-items-center gap-3 mb-3 col-span-2 md:col-span-1 relative">
                        <label for="username" class="font-semibold w-6rem">Name</label>
                        <input v-model="addNewUserForm.name" type="text" placeholder="Enter name" class="flex-auto border border-gray-500 rounded " required/>
                        <span v-if="addNewUserValidatorName" class="absolute inset-y-20 text-red-500" >{{ addNewUserValidatorName }} </span> 
                    </div>
                    
                    <div class="flex flex-col align-items-center gap-3 mb-3 col-span-2 md:col-span-1 relative">
                        <label for="role" class="font-semibold w-6rem">Role</label>
                        <select v-model="addUserRole" id="role" class="w-full rounded border-gray-500" required>
                            <option value="" selected hidden>Select a role</option>
                            <option v-for="(role, index) in roles" :key="index">{{ role }}</option>
                        </select>
                        <span v-if="addNewUserValidatorRole" class="absolute inset-y-20 text-red-500 absolute" >{{ addNewUserValidatorRole }} </span> 
                    </div>
                    
                    <div  class="flex flex-col align-items-center gap-3 mb-3 col-span-2 md:col-span-1 relative  "  >
                        
                        <label for="departmentName" class="font-semibold w-6rem">Department </label>
                        <select v-model="addUserDepartment"  id="departmentName" class="w-full rounded border-gray-500 " required :class="{'pointer-events-none':isAdmin || !addUserRole, ' opacity-40':isAdmin || !addUserRole, 'bg-gray-300':isAdmin || !addUserRole}"  >
                            <option value="" selected hidden>Select a department</option>
                            <option v-for="dep in data.departments" :key="dep.id" :value="dep">{{ dep.name }}</option>
                        </select>
                        <span v-if="addNewUserValidatorDep" class="absolute inset-y-20 text-red-500 absolute" >{{ addNewUserValidatorDep }} </span> 
                    </div>
                   
                    <div v-if="isFaculty && hasDivision" class="flex flex-col align-items-center gap-3 mb-3 col-span-2 relative">
                        <label for="departmentName" class="font-semibold w-6rem">Division</label>
                        <select  v-model="addUserDivision"  id="departmentName" class="w-full rounded border-gray-500" required>
                            <option value="" selected hidden>Select a division</option>
                            <option v-for="div in addUserDepartment.divisions" :key="div.id" :value="div">{{ div.name }}</option>
                        </select>
                        <span v-if="addNewUserValidatorDiv" class="absolute inset-y-20 text-red-500 absolute" >{{ addNewUserValidatorDiv }} </span> 
                    </div>
                </div>
                <!-- isfaculty = {{ isFaculty }} | hasdivision = {{ hasDivision }} || addUserDepartment = {{ addUserDepartment.name }} -->
            </form>
           <!-- email: {{ addNewUserForm.email }} || name: {{ addNewUserForm.name }} || role: {{ addUserRole }} || department: {{ addUserDepartment.id }} || division {{ addUserDivision.id }} -->
            <div class="mt-4">
                <button @click="submitNewUser" type="button" :disabled="addNewUserForm.processing" class="w-full btn-primary " >Save</button>
            </div>
        </div>    
        
    </DashboardLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import DashboardLayout from '../DashboardLayout.vue';
import {ref, watch} from 'vue'
import Swal from 'sweetalert2/dist/sweetalert2.js'

const searchField = ref('')

const data = defineProps({
    departments:Array,
})

const addNewUserForm = useForm({
    email:'',
    name:'',
    role:'',
    department:'',
    division_id:'',
})

const addNewUserValidatorEmail = ref('')
const addNewUserValidatorName = ref('')
const addNewUserValidatorRole = ref('')
const addNewUserValidatorDep = ref('')
const addNewUserValidatorDiv = ref('')

const addUserRole = ref('');
const addUserDepartment = ref('');
const addUserDivision = ref('');

const isAdmin = ref(false)
const isDepHead = ref(false)
const isFaculty = ref(false);
const hasDivision = ref(false);

const roles = ref([
    'admin',
    'co-admin',
    'department head',
    'faculty',
])

function resetValidationError(){
    addNewUserValidatorEmail.value = ''
    addNewUserValidatorName.value = ''
    addNewUserValidatorRole.value = ''
    addNewUserValidatorDep.value = ''
    addNewUserValidatorDiv.value = ''
}

watch(addUserRole,(val)=>{
    
    if(val === 'admin' || val ==='co-admin')
    {
       
        isAdmin.value = true // disable department
        isDepHead.value = false
        isFaculty.value = false
        hasDivision.value = false;
        addUserDepartment.value =''
        addUserDivision.value = ''
        resetValidationError();
    }
    else
    {
        isAdmin.value = false 
    }

    if(val === 'department head')
    {
       
        
        isDepHead.value = true;
        isAdmin.value = false;
        isFaculty.value = false;
        hasDivision.value = false;
        addUserDepartment.value = ''
        addUserDivision.value = ''
        resetValidationError();
    }

    if(val === 'faculty')
    {
       
        isFaculty.value = true;
        isAdmin.value = false;
        isDepHead.value = false
        addUserDepartment.value =''
        resetValidationError();
        
    }
    else
    {
       
        isFaculty.value = false;
        hasDivision.value = false;
        addUserDivision.value = ''
    }
})

watch(addUserDepartment,(val)=>{
    
    // Check if 'divisions' property exists and it's not empty
    if (val && Array.isArray(val.divisions) && val.divisions.length > 0) {
        hasDivision.value = true; // Set hasDivision to true
    } else {
        hasDivision.value = false; // Set hasDivision to false
    }
    
})

const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
}

const submitNewUser = ()=> {

addNewUserValidatorEmail.value = !addNewUserForm.email ? 'Email is required': !isValidEmail(addNewUserForm.email) ? 'Invalid email format' : ''
addNewUserValidatorName.value = !addNewUserForm.name ? 'Name is required': ''
addNewUserValidatorRole.value = !addUserRole.value ? 'Role is required': ''


if(addUserRole.value === 'department head')
{
    
    
    if(!addUserDepartment.value)
    {
        
        addNewUserValidatorDep.value = 'Department is required';
    }
    else
    {
        
        addNewUserValidatorDep.value = ''
    }
}

if(addUserRole.value === 'faculty')
{
    if(!addUserDepartment.value)
    {
        addNewUserValidatorDep.value = 'Department is required';
    }
    else
    {
        addNewUserValidatorDep.value = ''
        if(hasDivision.value)
        {
            if(!addUserDivision.value)
            {
                addNewUserValidatorDiv.value = 'Division is required'
            }
            else
            {
                addNewUserValidatorDiv.value = ''
            }
        }
        else
        {
            addNewUserValidatorDiv.value = ''
        }
    }
}

    if(addNewUserValidatorEmail.value === '' && addNewUserValidatorName.value === '' &&  addNewUserValidatorRole.value === '' && addNewUserValidatorDep.value === '' && addNewUserValidatorDiv.value === '')
    {
        
        //email: {{ addNewUserForm.email }} || name: {{ addNewUserForm.name }} || role: {{ addUserRole }} || department: {{ addUserDepartment.id }} || division {{ addUserDivision.id }}
        addNewUserForm.role = addUserRole.value;
        addNewUserForm.department = addUserDepartment.value.id;
        addNewUserForm.division_id = addUserDivision.value.id;
        addNewUserForm.post(route('user.store'));
        
        
    }

}

// sweet alert logic
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