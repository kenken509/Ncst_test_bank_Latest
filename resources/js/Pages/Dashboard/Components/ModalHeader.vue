<template>
    <div class="header  fixed top-14 left-0 right-0 border flex justify-between items-center ml-6 mr-10 bg-blue-900 p-4 pl-2 pr-4 rounded-tl-md rounded-tr-md">
        <div class="flex items-center gap-2">
            <img :src="logoUrl" alt="error" class=" w-16 h-16">
            <span class="text-gray-100 text-xl">{{ title }}</span>
        </div>
        <div class="flex  flex-col hidden md:block ">
            <div class="flex justify-end text-[30px] text-gray-100">
                {{ capitalizeFirstLetter(user.name) }}
            </div>
            <div class="flex justify-end text-gray-100">
                {{ capitalizeFirstLetter(user.role) }}
            </div>
            
        </div>
       
    </div>
    <div class=" pt-[100px] px-1  ">
        <slot></slot>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3'
import {ref} from 'vue'
import { capitalizeFirstLetter } from '../Composables/capitalizeFirstLetter';

const user = usePage().props.user
const logoUrl = ref('/storage/Images/ncstLogo.png');

const props = defineProps({
    title:String,

})
</script>

<style scoped>
/* .header {
    z-index: 1000; 
} */

.content {
    padding-top: 6rem; /* Adjust based on the height of the fixed header */
}
</style>