<template>
  <div id="answers-pdf">
    <!-- Heading -->
    <div class="flex justify-center gap-2 w-full pb-2">
      <div class="flex justify-between gap-4">
        <div class="w-20 h-20 mt-[18px]">
          <img :src="logoUrl" alt="Ncst Logo"/>
        </div>
        <div class="flex flex-col justify-center items-center mb-2 mt-1">
          <span class="font-bold text-[18px]">NATIONAL COLLEGE OF SCIENCE AND TECHNOLOGY</span>
          <span>Amafel Building Aguinaldo Highway, Dasmariñas, Cavite</span>
          <span>Tel. no. (1234-1234)</span>
          <a href="https://ncst.edu.ph/" target="_blank">www.ncst.edu.ph</a>
          <span class="text-[16px] font-bold mt-2">CSD Department</span>
          <span class="text-[16px] font-bold"> </span>
          <span class="text-[16px] font-bold">2nd Semester SY: 2024-2025</span>
          <span class="text-[16px] font-bold">Set A</span>
        </div>
      </div> 
    </div>
    <!-- Grid -->
    <div class="grid pb-2" :class="`grid-cols-${state.columns}`" :style="{ gridTemplateColumns: `repeat(${state.columns}, minmax(0, 1fr))` }">
      <div v-for="(chunk, colIndex) in state.chunksToShow" :key="colIndex" class="col-span-1">
        <div v-for="(ans, index) in chunk" :key="index">
          <span>{{ index + 1 + colIndex * maxChunkSize }}. {{ ans }}</span>
        </div>
      </div>
    </div>
  </div>
  <!-- Button to download PDF -->
  <button class="btn-primary w-full mt-4" @click="downloadAnswerKeysPDF(set)">Download PDF</button>
  <div v-if="state.downloadStatus" :class="{'text-green-500': state.downloadStatus === 'success', 'text-red-500': state.downloadStatus === 'error'}">
    {{ state.downloadMessage }}
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import html2pdf from 'html2pdf.js';

// Sample data
const setA = [
    'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
    'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
    'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
];
 
const setB = [
    'b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b',
    'b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b',
    'b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b','b',
]

const setC = [
    'c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c',
    'c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c',
    'c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c','c',
]

// Define the maximum number of elements to display per column
const maxChunkSize = 33;
const maxElementsPerColumn = 33 * 7; // 33 elements per column * 7 columns

// Reactive state to store the columns and chunks to show
const state = reactive({
  columns: 1,
  chunksToShow: [],
  downloadStatus: '',
  downloadMessage: ''
});

// Logo URL
const logoUrl = ref('/storage/Images/ncstLogo.png');

// Options for PDF generation
var opt = {
  margin:       [0.1,0.4,0.2,0.4],
  filename:     'myfile.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2 },
  jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
};

// Function to process answers and update the reactive state
function processAnswers(answers) 
{
  // Helper function to chunk array
  function chunkArray(arr, size) {
    const chunks = [];
    for (let i = 0; i < arr.length; i += size) {
      chunks.push(arr.slice(i, i + size));
    }
    return chunks;
  }

  // Split answers into chunks of maxChunkSize elements
  const chunks = chunkArray(answers, maxChunkSize);

  // Calculate the number of columns based on the number of chunks
  state.columns = Math.min(7, chunks.length);

  // Determine how many chunks to display
  state.chunksToShow = chunks.slice(0, Math.ceil(maxElementsPerColumn / maxChunkSize));
}

// Method to download PDF
const saveAnswerKeysToPDF = async (keyToCorrection, setName) => {
  try {
    processAnswers(keyToCorrection);
    const element = document.getElementById('answers-pdf');
    opt.filename = `Set_${setName}_Answers.pdf`;
    await html2pdf().set(opt).from(element).save();
    state.downloadStatus = 'success';
    state.downloadMessage = 'PDF downloaded successfully!';
  } catch (error) {
    state.downloadStatus = 'error';
    state.downloadMessage = `Error downloading PDF: ${error.message}`;
  }
};

// Initialize the state with the default answers
//processAnswers(testAnswers);

const set = 'A B C'

async function downloadAnswerKeysPDF(arr,filename)
{
    let selectedSet = arr.split(' ')

    for (const set of selectedSet) {
    switch (set) {
      case 'A':
        await saveAnswerKeysToPDF(setA, 'Answer-A');
        break;
      case 'B':
        await saveAnswerKeysToPDF(setB, 'Answer-B');
        break;
      case 'C':
        await saveAnswerKeysToPDF(setC, 'Answer-C');
        break;
    }
  } 
}
</script>

<style scoped>
.grid {
  gap: 1rem;
}
</style>
