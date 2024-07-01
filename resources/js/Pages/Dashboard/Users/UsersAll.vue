<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Users </span> 
           <div class="relative">
               <input v-model="searchField" type="text" placeholder="search" class="rounded-md">
               <svg class="absolute top-3 right-2 w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                   <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
               </svg>
           </div> 
       </div>
       <div v-if="$page.props.flash.success" >{{ successMessage($page.props.flash.success) }} </div>
        <div v-if="$page.props.flash.error" >{{ errorMessage($page.props.flash.error) }} </div>
        <div v-if="addNewUserForm.errors.email">{{errorMessage(addNewUserForm.errors.email)}}</div>
       <!-- <div class="flex justify-between items-center">
            
            <div >
                
                <button @click="addUserModalVisible=true" class="btn-primary px-4 flex gap-2 items-center "> 
                    <svg class=" w-4 h-4 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                    </svg>                 
                    NEW
                </button>
            </div>
        </div> -->

        
         <!--TABLE-->
          
         <div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-200 uppercase bg-blue-900 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Role</th>
                            <th scope="col" class="px-6 py-3">Department</th>
                            <th scope="col" class="px-6 py-3">Division</th>
                            <th  v-if="$page.props.user.role === 'admin'" scope="col" class="flex justify-center px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in paginatedData" :key="user.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.email }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.name }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.role }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <span v-if="user.department">
                                    {{ user.department.name }}
                                </span>
                                <span v-else>--</span>
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <span v-if="user.division">
                                    {{ user.division.name }}
                                </span>
                                <span v-else>--</span>
                            </th>
                            
                            <td  v-if="$page.props.user.role === 'admin'" class="px-6 py-4 text-center ">
                                <div  class="flex flex-col   lg:flex-row lg:justify-center  lg:space-x-4">
                                    <button @click="deleteConfirmation(user.id)" class=" btn-warning my-2">Delete</button>
                                    <Link  :href="route('user.update', {id:user.id})" type="button" class="btn-success my-2">
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
        <div class="flex items-center justify-center gap-4 mt-2">
            <button @click="prevPage" :disabled="currentPage === 1" class="btn-pagination flex items-center gap-1">
                <i class="pi pi-angle-double-left"></i> Prev
            </button>
            <span>{{ currentPage }} of {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="btn-pagination flex items-center gap-1">
                 Next <i class="pi pi-angle-double-right"></i>
            </button>
        </div>

        

        <!--add user modal-->
        <Dialog v-model:visible="addUserModalVisible" modal header="New User" :style="{ width: '60rem' }">
            <form>
                <div class="border mb-4">
                    
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
                        
                        <label for="departmentName" class="font-semibold w-6rem">Department</label>
                        <select v-model="addUserDepartment"  id="departmentName" class="w-full rounded border-gray-500 " required :class="{'pointer-events-none':isAdmin || !addUserRole, ' opacity-40':isAdmin || !addUserRole, 'bg-gray-300':isAdmin || !addUserRole}"  >
                            <option value="" selected hidden>Select a department</option>
                            <option v-for="dep in data.departments" :key="dep.id" :value="dep">{{ dep.name }}</option>
                        </select>
                        <span v-if="addNewUserValidatorDep" class="absolute inset-y-20 text-red-500 absolute" >{{ addNewUserValidatorDep }} </span> 
                    </div>
                   <!-- isfaculty = {{ isFaculty }} | hasdivision{{ hasDivision }} || addUserDepartment = {{ addUserDepartment.name }} -->
                    <div v-if="isFaculty && hasDivision" class="flex flex-col align-items-center gap-3 mb-3 col-span-2 relative">
                        <label for="departmentName" class="font-semibold w-6rem">Division</label>
                        <select  v-model="addUserDivision"  id="departmentName" class="w-full rounded border-gray-500" required>
                            <option value="" selected hidden>Select a division</option>
                            <option v-for="div in addUserDepartment.divisions" :key="div.id" :value="div">{{ div.name }}</option>
                        </select>
                        <span v-if="addNewUserValidatorDiv" class="absolute inset-y-20 text-red-500 absolute" >{{ addNewUserValidatorDiv }} </span> 
                    </div>

                    
                </div>
                
            </form>
           <!-- email: {{ addNewUserForm.email }} || name: {{ addNewUserForm.name }} || role: {{ addUserRole }} || department: {{ addUserDepartment.id }} || division {{ addUserDivision.id }} -->
            <div class="mt-4">
                <button @click="submitNewUser" type="button" :disabled="addNewUserForm.processing" class="w-full btn-primary " >Save</button>
            </div>
            
        </Dialog>
         <!--add user modal-->

        
    </DashboardLayout>
</template>

<script setup>
import { Link, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2/dist/sweetalert2.js'
import DashboardLayout from '../DashboardLayout.vue'
import { computed, ref, watch } from 'vue'




const data = defineProps({
    users:Array,
    departments:Array,
})

const searchField = ref('')

const filteredData = computed(()=>{// andito ako 1
    let searchTerm = searchField.value.toLowerCase().trim()
    // name email role  if has division division.name if has department department.name 
    if(!searchTerm)
    {
        return data.users
    }
    
    return data.users.filter((user)=>{
       return user.name.toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm) || user.role.toLowerCase().includes(searchTerm) 
       || (user.department ? user.department.name.toLowerCase().includes(searchTerm):null) || (user.division ? user.division.name.toLowerCase().includes(searchTerm):null)
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

const totalPages = computed(()=> Math.ceil(filteredData.value.length/itemsPerPage.value))

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
const roles = ref([
    'admin',
    'co-admin',
    'department head',
    'faculty',
])



//add user logic

const addUserModalVisible = ref(false);
const addNewUserForm = useForm({
    email:'',
    name:'',
    role:'',
    department:'',
    division_id:'',
})
const addUserRole = ref('');
const addUserDepartment = ref('');
const addUserDivision = ref('');


const isAdmin = ref(false)
const isDepHead = ref(false)
const isFaculty = ref(false);
const hasDivision = ref(false);
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

function resetValidationError(){
    addNewUserValidatorEmail.value = ''
    addNewUserValidatorName.value = ''
    addNewUserValidatorRole.value = ''
    addNewUserValidatorDep.value = ''
    addNewUserValidatorDiv.value = ''
}
const addNewUserValidatorEmail = ref('')
const addNewUserValidatorName = ref('')
const addNewUserValidatorRole = ref('')
const addNewUserValidatorDep = ref('')
const addNewUserValidatorDiv = ref('')


// Function to validate email format
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
        }
    }

    
    // if(!addNewUserForm.department)
    // {
    //     if(addNewUserForm.role === 'admin' || addNewUserForm.role === 'co-admin')
    //     {
    //         addNewUserValidatorDep.value = ''
    //     }
    //     else
    //     {
    //         addNewUserValidatorDep.value =  'Department is required.'
           
    //     }
        
    // }
    
    // if(addNewUserForm.department)
    // {
       
    //     addNewUserValidatorDep.value=''
        

    //     if(addNewUserForm.role === 'department head')
    //     {
            
    //         if(!addNewUserForm.role)
    //         {
    //             addNewUserValidatorDep.value =  'Department is required.'
    //         }
    //         else
    //         {
    //             addNewUserValidatorDep.value = ''
    //         }

            
    //         // alert(addNewUserForm.department.name);
    //         // alert('im department head');
    //     }
       
    //     if(addNewUserForm.role === 'faculty')
    //     {
            
    //         const divisions = addNewUserForm.department.divisions;
            
    //         if(divisions.length > 1 && !addNewUserForm.division_id)
    //         {
    //             addNewUserValidatorDiv.value = 'Division is required.'
    //         }
    //         else
    //         {
    //             addNewUserValidatorDiv.value = '';
    //         }
           
    //     }
        
    // }


    
    if(addNewUserValidatorEmail.value === '' && addNewUserValidatorName.value === '' &&  addNewUserValidatorRole.value === '' && addNewUserValidatorDep.value === '' && addNewUserValidatorDiv.value === '')
    {
        
        //email: {{ addNewUserForm.email }} || name: {{ addNewUserForm.name }} || role: {{ addUserRole }} || department: {{ addUserDepartment.id }} || division {{ addUserDivision.id }}
        addNewUserForm.role = addUserRole.value;
        addNewUserForm.department = addUserDepartment.value.id;
        addNewUserForm.division_id = addUserDivision.value.id;
        addNewUserForm.post(route('user.store'));
        addUserModalVisible.value = false;
        
    }
    
    
}



// sweet alerts logic
const deleteConfirmation = (userId)=> 
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
                    const deleteUrl = route('user.delete',{id: userId })

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