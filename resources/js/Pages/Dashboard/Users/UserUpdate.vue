<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">User Update Page </span> 
       </div>
       <!-- this is the user to update : {{ data.userToUpdate }} || user exist dep : {{ userExistingDepartment}}  || isAdmin: {{ isAdmin }} -->
       <div>
            <form>
                <div class=" mb-4 mt-6">
                  <span class=" font-semibold text-lg text-gray-500">User Information:</span> 
                </div>
                 
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col align-items-center gap-3 mb-3 col-span-2 md:col-span-1 relative">
                        <label for="username" class="font-semibold w-6rem">Email </label>
                        <input v-model="updateUserForm.email" type="email" placeholder="Enter email" class="flex-auto border border-gray-500 rounded " required/>
                        <span v-if="updateUserValidatorEmail" class="absolute inset-y-20 text-red-500" >{{ updateUserValidatorEmail }} </span> 
                    </div>
                    
                    <div class="flex flex-col align-items-center gap-3 mb-3 col-span-2 md:col-span-1 relative">
                        <label for="username" class="font-semibold w-6rem">Name</label>
                        <input v-model="updateUserForm.name" type="text" placeholder="Enter name" class="flex-auto border border-gray-500 rounded " required/>
                        <span v-if="updateUserValidatorName" class="absolute inset-y-20 text-red-500" >{{ updateUserValidatorName }} </span> 
                    </div>
                    
                    <div class="flex flex-col align-items-center gap-3 mb-3 col-span-2 md:col-span-1 relative">
                        <label for="role" class="font-semibold w-6rem">Role</label>
                        <select v-model="updateUserRole" id="role" class="w-full rounded border-gray-500" required>
                            <option value="" selected hidden>Select a role</option>
                            <option v-for="(role, index) in roles" :key="index">{{ role }}</option>
                        </select>
                        <span v-if="updateUserValidatorRole" class="absolute inset-y-20 text-red-500 absolute" >{{ updateUserValidatorRole }} </span> 
                    </div>
                    
                    <div  class="flex flex-col align-items-center gap-3 mb-3 col-span-2 md:col-span-1 relative  "  >
                        
                        <label for="departmentName" class="font-semibold w-6rem">Department </label>
                        <select v-model="updateUserDepartment"  id="departmentName" class="w-full rounded border-gray-500 " required :class="{'pointer-events-none':isAdmin || !updateUserRole, ' opacity-40':isAdmin || !updateUserRole, 'bg-gray-300':isAdmin || !updateUserRole}"  >
                            <option value="" selected hidden>Select a department</option>
                            <option v-for="dep in data.departments" :key="dep.id" :value="dep">{{ dep.name }}</option>
                        </select>
                        <span v-if="updateUserValidatorDep" class="absolute inset-y-20 text-red-500 absolute" >{{ updateUserValidatorDep }} </span> 
                    </div>
                   
                    <div v-if="isFaculty && hasDivision" class="flex flex-col align-items-center gap-3 mb-3 col-span-2 relative">
                        <label for="departmentName" class="font-semibold w-6rem">Division</label>
                        <select  v-model="updateUserDivision"  id="departmentName" class="w-full rounded border-gray-500" required>
                            <option value="" selected hidden>Select a division</option>
                            <option v-for="div in updateUserDepartment.divisions" :key="div.id" :value="div">{{ div.name }}</option>
                        </select>
                        <span v-if="updateUserValidatorDiv" class="absolute inset-y-20 text-red-500 absolute" >{{ updateUserValidatorDiv }} </span> 
                    </div>
                </div>
                <!-- isfaculty = {{ isFaculty }} | hasdivision = {{ hasDivision }} || updateUserDepartment = {{ updateUserDepartment.name }} -->
            </form>
           <!-- email: {{ updateUserForm.email }} || name: {{ updateUserForm.name }} || role: {{ updateUserRole }} || department: {{ updateUserDepartment.id }} || division {{ updateUserDivision.id }} -->
            <div class="mt-4">
                <button @click="updateUser" type="button" :disabled="updateUserForm.processing" class="w-full btn-primary " >Update</button>
            </div>
        </div>  
    </DashboardLayout>
    
</template>

<script setup>
import { useForm,router } from '@inertiajs/vue3';
import DashboardLayout from '../DashboardLayout.vue';
import {ref, watch, onMounted} from 'vue'
import Swal from 'sweetalert2/dist/sweetalert2.js'

const searchField = ref('')

const data = defineProps({
    userToUpdate:Object,
    departments:Array,
})

const updateUserForm = useForm({
    id:data.userToUpdate.id,
    email:data.userToUpdate.email,
    name:data.userToUpdate.name,
    role:'',
    department:'',
    division_id:'',
})

 const userExistingDepartment = data.departments.filter((val)=> val.id === data.userToUpdate.department_id )

const updateUserValidatorEmail = ref('')
const updateUserValidatorName = ref('')
const updateUserValidatorRole = ref('')
const updateUserValidatorDep = ref('')
const updateUserValidatorDiv = ref('')

const updateUserRole = ref(data.userToUpdate.role);
const updateUserDepartment = ref(userExistingDepartment[0]);
const updateUserDivision = ref('');

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
    updateUserValidatorEmail.value = ''
    updateUserValidatorName.value = ''
    updateUserValidatorRole.value = ''
    updateUserValidatorDep.value = ''
    updateUserValidatorDiv.value = ''
}

const checkRole = (role) => {
  if (role === 'admin' || role === 'co-admin') {
    isAdmin.value = true;
    isDepHead.value = false;
    isFaculty.value = false;
    hasDivision.value = false;
    updateUserDepartment.value = '';
    updateUserDivision.value = '';
    resetValidationError();
  } else {
    isAdmin.value = false;
  }

  if (role === 'department head') {
    isDepHead.value = true;
    isAdmin.value = false;
    isFaculty.value = false;
    hasDivision.value = false;
    updateUserDepartment.value = '';
    updateUserDivision.value = '';
    resetValidationError();
  } else {
    isDepHead.value = false;
  }

  if (role === 'faculty') {
    isFaculty.value = true;
    isAdmin.value = false;
    isDepHead.value = false;
    updateUserDepartment.value = '';
    resetValidationError();
  } else {
    isFaculty.value = false;
    hasDivision.value = false;
    updateUserDivision.value = '';
  }
};


watch(updateUserRole,(val)=>{
    
    if(val === 'admin' || val ==='co-admin')
    {
       
        isAdmin.value = true // disable department
        isDepHead.value = false
        isFaculty.value = false
        hasDivision.value = false;
        updateUserDepartment.value =''
        updateUserDivision.value = ''
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
        updateUserDepartment.value = ''
        updateUserDivision.value = ''
        resetValidationError();
    }

    if(val === 'faculty')
    {
       
        isFaculty.value = true;
        isAdmin.value = false;
        isDepHead.value = false
        updateUserDepartment.value =''
        resetValidationError();
        
    }
    else
    {
       
        isFaculty.value = false;
        hasDivision.value = false;
        updateUserDivision.value = ''
    }
})

watch(updateUserDepartment,(val)=>{
    
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

const updateUser = ()=> {//updateUserForm

updateUserValidatorEmail.value = !updateUserForm.email ? 'Email is required': !isValidEmail(updateUserForm.email) ? 'Invalid email format' : ''
updateUserValidatorName.value = !updateUserForm.name ? 'Name is required': ''
updateUserValidatorRole.value = !updateUserRole.value ? 'Role is required': ''


if(updateUserRole.value === 'department head')
{
    
    
    if(!updateUserDepartment.value)
    {
        
        updateUserValidatorDep.value = 'Department is required';
    }
    else
    {
        
        updateUserValidatorDep.value = ''
    }
}

if(updateUserRole.value === 'faculty')
{
    if(!updateUserDepartment.value)
    {
        updateUserValidatorDep.value = 'Department is required';
    }
    else
    {
        updateUserValidatorDep.value = ''
        if(hasDivision.value)
        {
            if(!updateUserDivision.value)
            {
                updateUserValidatorDiv.value = 'Division is required'
            }
            else
            {
                updateUserValidatorDiv.value = ''
            }
        }
        else
        {
            updateUserValidatorDiv.value = ''
        }
    }
}

    if(updateUserValidatorEmail.value === '' && updateUserValidatorName.value === '' &&  updateUserValidatorRole.value === '' && updateUserValidatorDep.value === '' && updateUserValidatorDiv.value === '')
    {
        
        //email: {{ updateUserForm.email }} || name: {{ updateUserForm.name }} || role: {{ updateUserRole }} || department: {{ updateUserDepartment.id }} || division {{ updateUserDivision.id }}
        updateUserForm.role = updateUserRole.value;
        updateUserForm.department = updateUserDepartment.value.id;
        updateUserForm.division_id = updateUserDivision.value.id;
        
        
        updateConfirmation('user.update.store')
    }

}

onMounted(() => {
  checkRole(updateUserRole.value);
});

// sweet alerts logic

const updateConfirmation = (url)=> 
    { 
        Swal.fire({
            title: "Update Confirmation",
            text: "Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, update it!",
            allowOutsideClick:false,
            allowEscapeKey:false,
            }).then((result) => {
                if(result.isConfirmed)
                {
                     updateUserForm.put(route(url))
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

</script>