<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Download Backup</span>
        </div>
        
        <div class="w-full h-[100vh] flex justify-center">
            <div class="flex flex-col items-center w-full md:w-[400px] mt-10 md:mt-28 relative">
                <ProgressSpinner v-if="isLoading" class="absolute top-[-100px]" />
                <button @click="downloadBackup" type="button" class="btn-primary p-4 w-full shadow-md">Download</button>
                <span>Click here to download</span>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue';
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const isLoading = ref(false);
const page = usePage();

const downloadBackup = () => { 
    isLoading.value = true;
    router.visit(route('backup.download'), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                
                triggerDownload(page.props.flash.success);
                
            }
        },
        onFinish: () => {
            isLoading.value = false;
        }
    });
};
var counter = 1
const triggerDownload = (url) => {
    console.log(counter)
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'NCST_TEST_BANK_BACKUP_FILE_'+dateFormat()+'.sql');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    counter++
};

function dateFormat() {
    const date = new Date();
    
    // Extract components
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    
    // Construct the formatted date string
    const formattedDate = `${day}-${month}-${year}-${hours}-${minutes}-${seconds}`;
    
    return formattedDate;
}

</script>
