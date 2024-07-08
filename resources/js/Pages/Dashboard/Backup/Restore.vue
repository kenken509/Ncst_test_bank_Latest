<template>
    <DashboardLayout>
      <div class="flex items-center justify-between border-bot-only py-2 mb-4">
        <span class="text-[20px] font-bold text-gray-500">Database Upload </span>
      </div>
      <div v-if="$page.props.flash.success">{{ successMessage($page.props.flash.success)  }}</div>
      <div v-if="$page.props.flash.error">{{ submitErrorMessage($page.props.flash.error)  }}</div>
      <div class="w-full flex justify-center">
        <div class="flex justify-center items-start flex-col mt-[100px] border border-gray-400 p-8 rounded-md shadow-md">
          <form @submit.prevent="submit">
            <input
              id="inputFile"
              ref="fileInput"
              accept=".sql"
              type="file"
              class="w-full rounded-md border border-gray-400 mb-2"
              @change="handleFileChange"
            />
            <button
              type="button"
              class="bg-blue-800 text-white p-2 rounded-md my-2 w-full"
              @click="reset"
            >
              Reset
            </button>
            <button
              type="submit"
              class="bg-blue-800 text-white p-2 rounded-md my-2 w-full"
              :disabled="form.processing"
            >
              Upload
            </button>
          </form>
        </div>
      </div>
    </DashboardLayout>
  </template>
  
  <script setup>
  import DashboardLayout from '../DashboardLayout.vue';
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import Swal from 'sweetalert2/dist/sweetalert2.js'
  
  const fileInput = ref(null);
  const form = useForm({
    database: null,
  });
  
  const hasError = ref(true)
  function handleFileChange(event) {
    const file = event.target.files[0];
    console.log(file)

   
    if (!file) 
    {
        fileValidationError('No file selected');
        hasError.value = true
        return;
    }
  
    if (!file.name.toLowerCase().endsWith('.sql')) 
    {
        fileValidationError('Only .sql files are allowed');
        hasError.value = true
        return;
    }
  
    if (file.size > 50 * 1024 * 1024) 
    { // 50MB in bytes
        fileValidationError('File size exceeds 50MB');
        hasError.value = true
        return;
    }
  
    
    form.database = file;
    hasError.value = false
  }
  
  function reset() 
  {
    if (fileInput.value) 
    {
        fileInput.value.value = '';
        form.database = null;
    }
  }

  

  const submit = ()=>{
    if(!hasError.value)
    {
       form.post(route('backup.restore.restore'),{
            preserveState:true,
            preserveScroll:true,
       })
    }
    else
    {
        fileValidationError('Please select a correct file');
    }
  }

  //swal 
  function fileValidationError(message) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: message + '!',
            allowOutsideClick:false,
        }).then((result) => {
            if (result.isConfirmed) {
                reset()
            }
        })
    }

  function submitErrorMessage(message)
  {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: message + '!',
            allowOutsideClick:false,
        }).then((result) => {
            if (result.isConfirmed) {
                reset()
                location.reload();
            }
        })
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
  </script>
  