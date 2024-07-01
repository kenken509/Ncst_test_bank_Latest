<template>
    <div v-if="isOpen" class="fixed max-h-screen inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="w-full bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto ">
            <button @click="closeModal" class="text-gray-600 hover:text-gray-900 bg-red-300 p-2 rounded-md hover:cursor-pointer">
                Close
            </button>
            <div class="flex justify-between px-2 rounded-t-md bg-blue-900">

              <div class="flex items-center gap-2">
                  <img :src="logoUrl" alt="error" class="w-10 h-10">
                  <span class="text-gray-100 text-xl">{{ title }}</span>
              </div>

              <div class="flex  flex-col  ">
                <div class="flex justify-end text-[30px] text-gray-100">
                    {{ user.name }}
                </div>
                <div class="flex justify-end text-gray-100">
                    {{ user.role }}
                </div>
              </div>
            </div>
            
            <div class="mt-4">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
import {ref} from 'vue'
const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  title:String,
});

const emit = defineEmits(['close']);
const closeModal = () => {
  emit('close');
};

const user = usePage().props.user
const logoUrl = ref('/storage/Images/ncstLogo.png');
</script>
