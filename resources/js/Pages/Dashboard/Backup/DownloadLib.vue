<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Download Backup </span> 
        </div>
        <div class="w-full h-[100vh] flex justify-center   ">
           
            <div class="flex  flex-col items-center  w-full md:w-[400px] mt-10 md:mt-28 relative ">
                <ProgressSpinner v-if="isLoading"  class="absolute top-[-100px]"  />
                <button @click="downloadBackup" type="button" class="btn-primary p-4 w-full shadow-md">Download</button>
                <span>Click here to download</span>
            </div>
        </div>
        
        
    </DashboardLayout>
</template>
<script setup>
import DashboardLayout from '../DashboardLayout.vue';
import { ref, onMounted } from 'vue'
import axios from 'axios';
import { router,Link,usePage } from '@inertiajs/vue3'

onMounted(()=>{
    const page = usePage()

    
    
    if(page.props.flash.backUpDonwloadUrl)
    {
        const url = page.props.flash.backUpDonwloadUrl;
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download','NCST_TEST_BANK_BACKUP_'+dateFormat()+'.sql');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); 
    }

    isLoading.value = false;
    
})
//old
//  const downloadBackup = async () => { 
//    try {
//      const response = await axios({
//        url: 'download-database-backup',
//       method: 'GET',
//       responseType: 'blob', // Important for file download
//     });
    
//         let date = dateFormat()
//         const url = window.URL.createObjectURL(new Blob([response.data]));
//          const link = document.createElement('a');
//          link.href = url;
//          link.setAttribute('download', 'NCST_TEST_BANK_DB_BACKUP_'+date+'.sql'); // Adjust filename if necessary
//          document.body.appendChild(link);
//         link.click();
//          document.body.removeChild(link); 
//    } catch (error) {
//     console.error('Error downloading the backup file:', error);
//      // Handle error appropriately in your frontend
//    }
//  };

 const isLoading = ref(false);
 const downloadBackup = () => { 
    isLoading.value = true;
    router.visit(route('backup.download'))
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