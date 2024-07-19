<template>
    
    <div class="flex flex-col h-[100vh] justify-center items-center">
        <div v-if="form.errors.email">
            <InputError :error="form.errors.email"/>
        </div>
       
        <div class="flex flex-col w-full xs:w-[95%]  md:w-[35%] text-gray-300  border border-gray-400 rounded-md">
            <div class="flex  flex-col w-full items-center justify-center bg-blue-900 text-gray-200  rounded-t-md pl-2 shadow-md">
                <Link href="/" class="flex justify-center items-center pt-4 " >
                    <img :src="logoUrl" alt="error" class="w-[50px] h-[50px]"/>
                </Link>
                <div class="  py-2 xs:text-sm md:text-xl">
                    LOGIN PAGE
                </div>
            </div>
           
            <div class="flex justify-center items-center w-full  pt-4 ">
                <form @submit.prevent="login" class="w-full px-4 ">
                    <div class="flex flex-col  gap-5 ">
                        <div class=" relative">
                            
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input v-model="form.email" type="email" id="email" class="bg-gray-50  border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 py-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="email" required />
                            <i class="pi pi-envelope text-gray-500 text-1 absolute top-[38px] left-3"></i>
                        </div>
                        <div class="relative">
                            <label for="passWord" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input v-model="form.password" :type="showPassword ? 'text':'password'" id="passWord" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 py-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="password" required />
                            <i v-if="showPassword" @click="handleShowPassword" class="pi pi-eye-slash text-gray-500 text-1 absolute top-[38px] left-3 hover:cursor-pointer" ></i>
                            <i v-else @click="handleShowPassword" class="pi pi-eye text-gray-500 text-1 absolute top-[38px] left-3 hover:cursor-pointer" ></i>
                        </div>
                    </div>
                    
                    <div class="mt-4 pl-2">
                        <span class="text-gray-500">Forgot password? </span> <button type="button" @click="toggleShowForgotPWModal" class="text-blue-500">click here</button>
                    </div>
                    
                    <!-- submit button-->
                    <div class="mt-4">
                        <button 
                            type="submit" 
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        >   Login
                        </button>
                    </div>
                    <!-- submit button-->
                </form>
                
            </div>  
            
        </div>

        <Dialog v-model:visible="showForgotPWModal" modal  :style="{ width: '35rem' }">
            <div class="flex  flex-col w-full items-center justify-center bg-blue-900 text-gray-200  rounded-t-md pl-2 shadow-md">
                <div class="flex justify-center items-center pt-4 ">
                    <img :src="logoUrl" alt="error" class="w-[50px] h-[50px]"/>
                </div>
                <div class="  py-2 xs:text-sm md:text-xl ">
                    Forgot Password
                </div>
            </div>
            <div class="mt-4">
                <form @submit.prevent="submitForgotEmail">
                    <div class="flex flex-col ">
                        <label for="forgotEmail " class="font-semibold">Email: </label>
                        <input v-model="forgotEmailForm.email" id="forgotEmail" type="email" placeholder="Enter Email" class="rounded-md mt-2" required/>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="w-full btn-primary" :diabled="forgotEmailForm.processing" >Submit</button>
                    </div>
                </form>
            </div>
        </Dialog>
        <div v-if="$page.props.flash.error">{{ errorMessage($page.props.flash.error) }}</div>
        <div v-if="$page.props.flash.success" class="hidden">{{ tempSuccessMessage = $page.props.flash.success }}</div>
    </div>
    
</template>

<script setup>
import {ref,watch} from 'vue'
import { useForm, Link } from '@inertiajs/vue3';
import InputError from '../Global Component/InputError.vue';
import Swal from 'sweetalert2/dist/sweetalert2.js'

const showPassword = ref(false)

function handleShowPassword(){
    showPassword.value = !showPassword.value
}
const logoUrl = ref('/storage/Images/ncstLogo.png')
const showForgotPWModal =ref(false)

function toggleShowForgotPWModal()
{
    showForgotPWModal.value = !showForgotPWModal.value
}

const form = useForm({
    email:null,
    password:null,
})

const forgotEmailForm = useForm({
    email:'',
})


const login = ()=> {
    form.post(route('login.store'))
}

const counter = ref(0);

setInterval(() => {
    counter.value++
}, 1000);

const submitForgotEmail = ()=>{
    forgotEmailForm.post(route('reset.password.verify'),{
        preserveState:true,
        preserveScroll:true,
        onFinish:()=>{ 
            showForgotPWModal.value = false 
            forgotEmailForm.email = ''
        }
    })
}


function errorMessage(message) {
        showForgotPWModal.value = !showForgotPWModal
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

const tempSuccessMessage = ref('')
watch(tempSuccessMessage,(message)=>{
    let mess = message.split('.')

    successMessage(mess[0])
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
        
            // location.reload()
        //    if(page.props.flash.action)
        //    {
        //         location.reload();
        //    }
            
        }
    })
    
}
</script>