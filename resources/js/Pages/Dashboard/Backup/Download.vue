<template>
    <DashboardLayout>
      <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Download Backup </span> 
            
        </div>
    </DashboardLayout>
</template>
<script setup>
import DashboardLayout from '../DashboardLayout.vue';
import { ref, onMounted } from 'vue'
import axios from 'axios';

onMounted(()=>{
    downloadBackup()
})


const downloadBackup = async () => { 
  try {
    const response = await axios({
      url: '/download-database-backup',
      method: 'GET',
      responseType: 'blob', // Important for file download
    });
        let date = dateFormat()
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'NCST_TEST_BANK_DB_BACKUP_'+date+'.sql'); // Adjust filename if necessary
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
  } catch (error) {
    console.error('Error downloading the backup file:', error);
    // Handle error appropriately in your frontend
  }
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