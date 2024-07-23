<template>
  <div class="flex h-screen justify-center items-center flex-col">
    <span v-if="$page.props.flash.error" class="text-red-500">{{ $page.props.flash.error }}</span>
    <div class="flex flex-col w-full xs:w-[95%] md:w-[35%] text-gray-300 border border-gray-400 rounded-md shadow-lg">
      <div class="flex flex-col w-full items-center justify-center bg-blue-900 text-gray-200 rounded-t-md pl-2 shadow-md">
        <div class="flex justify-center items-center pt-4">
          <img :src="logoUrl" alt="error" class="w-[50px] h-[50px] logo-animation" />
        </div>
        <div class="py-2 xs:text-sm md:text-2xl flex justify-center font-bold text-container">
          <span class="char-animation">
            NCST TEST BANK
          </span>
        </div>
      </div>
      <div class="h-[200px] flex justify-center items-center">
        <div v-if="showHandAndLogin" class="flex flex-col items-center">
          <div class="hand-icon hand-animation">&#128071;</div> <!-- Hand pointing down emoji -->
          <Link :href="route('login.show')" class="pb-10 login-animation text-blue-500">
            Click here to log in
          </Link>
        </div>
      </div>
    </div>
    <div class="flex flex-col items-center mt-6 text-gray-500">
      <span class=" text-center">
        Copyright © {{ currentYear }} National College of Science and Technology, Information Technology Office.
      </span>
      <span class=" text-center">
        Developed by <a href="https://www.facebook.com/kenortz.kenneth" target="_blank" class="text-blue-500">Aries Bawar LLesis</a> as an Internship Project.
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const logoUrl = '/storage/Images/ncstLogo.png';
const showHandAndLogin = ref(false);
const date = new Date();
const currentYear = date.getFullYear();

const welcomeText = "NCST TEST BANK".split("");
const halfwayIndex = Math.ceil(welcomeText.length / 2);
let currentCharIndex = 0;

onMounted(() => {
  const interval = setInterval(() => {
    if (currentCharIndex < halfwayIndex) {
      currentCharIndex++;
    } else {
      showHandAndLogin.value = true;
      clearInterval(interval);
    }
  }, 150); // Adjust the speed here for smoother animation
});

</script>

<style scoped>
@keyframes fadeIn {
  0% {
    opacity: 0; /* Start with opacity 0 */
  }
  100% {
    opacity: 1; /* End with opacity 1 (fully visible) */
  }
}
@keyframes slideIn {
  0% {
    transform: translateX(100%);
    opacity: 0;
  }
  100% {
    transform: translateX(0);
    opacity: 1;
  }
}

.logo-animation {
  animation: scaleIn 2.5s ease-in-out forwards; /* Apply scale-in animation to the logo */
}

.text-container {
  overflow: hidden; /* Hide overflow to prevent text from wrapping */
}

.char-animation {
  display: inline-block;
  animation: slideIn 2.5s ease-in-out forwards;
  white-space: nowrap; /* Prevent characters from wrapping */
  opacity: 0; /* Start with opacity 0 */
}

.hidden {
  opacity: 0;
}

.login-animation {
  animation: fadeIn 1s ease-in-out forwards; /* Apply fade-in animation to the login text */
}

.hand-icon {
  font-size: 2rem; /* Adjust the size of the hand icon */
  animation: bounce 2s infinite, fadeIn 1s ease-in-out forwards; /* Apply bounce and fade-in animation */
  margin-bottom: 10px; /* Space between hand icon and link */
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    transform: translateY(0);
  } 
  40% {
    transform: translateY(-20px);
  }
  60% {
    transform: translateY(-10px);
  }
}

@keyframes scaleIn {
  0% {
    opacity: 0;
    transform: scale(0.5);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}


</style>
