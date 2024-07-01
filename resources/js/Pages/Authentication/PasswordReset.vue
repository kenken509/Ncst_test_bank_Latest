<template>
    <div class="w-full h-[100vh] flex justify-center items-center">
      <div class="xs:w-[95%] md:w-[35%] flex flex-col justify-center border border-gray-200 shadow-md rounded-md">
        <div class="flex flex-col items-center justify-center bg-blue-900 text-gray-200 rounded-t-md pl-2 shadow-md">
          <div class="flex justify-center items-center pt-4">
            <img :src="logoUrl" alt="error" class="w-[50px] h-[50px]" />
          </div>
          <div class="flex justify-center py-2 xs:text-sm md:text-xl w-full">
            Reset Password
          </div>
        </div>
        <div class="w-full border py-2 px-4">
          <form @submit.prevent="submitForm">
            <div class="mt-6">
              <label for="password">Password:</label>
              <input v-model="inputPassword" id="password" type="password" class="w-full rounded-md" placeholder="Enter new password" required />
            </div>
            <div class="mt-4 flex flex-col gap-2">
              <label for="confirmPassword" class="">Confirm Password:</label>
              <input v-model="inputConfirmedPassword" id="confirmPassword" type="password" class="w-full rounded-md" placeholder="Confirm password" required />
            </div>
            <div class="mt-6">
              <button type="submit" class="btn-primary p-2 w-full" :disabled="form.processing">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/vue3';
  import { ref } from 'vue';
  import Swal from 'sweetalert2/dist/sweetalert2.js';
  
  const data = defineProps({
    userId: Number,
  });
  
  const logoUrl = ref('/storage/Images/ncstLogo.png');
  
  const form = useForm({
    userId: data.userId,
    password: '',
  });
  
  const inputPassword = ref('');
  const inputConfirmedPassword = ref('');
  
  const submitForm = () => {
    if (!validatePassword(inputPassword.value)) {
      errorMessage('Password must be at least 8 characters long and include uppercase, lowercase, digit, and special character.');
      console.log('Password validation failed:', inputPassword.value);
      return;
    }
  
    if (inputPassword.value !== inputConfirmedPassword.value) {
      errorMessage('Passwords do not match.');
      console.log('Passwords do not match:', inputPassword.value, inputConfirmedPassword.value);
      return;
    }
    
    
    // Proceed with form submission
    form.password = inputPassword.value;
    form.post(route('reset.password.store',{
        preserveState:true,
        preserveScroll:true,
    })); // Adjust the URL to your needs
  };
  
  function validatePassword(password) {
    // Custom validation function for password strength
    if (password.length < 8) {
      return false;
    }
  
    let hasUpperCase = false;
    let hasLowerCase = false;
    let hasDigit = false;
    let hasSpecialChar = false;
  
    for (let char of password) {
      if (char >= 'A' && char <= 'Z') {
        hasUpperCase = true;
      } else if (char >= 'a' && char <= 'z') {
        hasLowerCase = true;
      } else if (char >= '0' && char <= '9') {
        hasDigit = true;
      } else {
        // Assuming special characters are anything not alphanumeric
        hasSpecialChar = true;
      }
    }
  
    return hasUpperCase && hasLowerCase && hasDigit && hasSpecialChar;
  }
  
  function errorMessage(message) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: message,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        location.reload();
      }
    });
  }
  </script>
  