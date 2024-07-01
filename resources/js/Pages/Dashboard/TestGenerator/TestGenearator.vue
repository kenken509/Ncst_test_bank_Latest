<template>
    <DashboardLayout>
        <Dialog v-model:visible="customModalOpen" modal  :style="{ width: '60rem' }">
            <ModalHeader title="Test Generator">
                
              <!-- random option check: {{ department[0].subject_codes[0].questions[0].options[0] }} -->
               <!-- prelim: {{ isPrelim }} || midter: {{ isMidterm }} || prefinal: {{ isPrefinal }} || final: {{ isFinal }} -->
               <!--prelim: {{ prelimItems }} || midterm: {{ midTermItems }} || prefinal: {{ preFinalItems }} || final: {{ finalItems }}-->
                <!-- PRELIM ITEMS: {{ prelimItems }} || MIDTER ITEMS : {{ midTermItems }} -->
                 <!-- SET A-->
                 <!-- <div class="bg-red-300">
                    <span class="flex justify-center font-bold">TRIAL GENERATED QUESTIONS SET A</span>
                 </div>
                 Key: <span class="text-red-500 font-bold text-[24px]">{{ setAKeyToCorrection }}</span>
                 <div class="border border-black rounded-md p-2">           
                    <table class="w-full ">
                        <thead class="bg-green-100">
                            <tr>
                                <td>
                                    Question
                                </td>
                                <td>
                                    term
                                </td>
                                <td>option a</td>
                                <td>option b</td>
                                <td>option c</td>
                                <td>option c</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="question in setA" :key="question.id">
                                <td>
                                    {{ question.question }}
                                </td>
                                <td>
                                    {{ question.term }}
                                </td>
                                <td>
                                    {{ question.options[0].option }}
                                </td>
                                <td>
                                    {{ question.options[1].option }}
                                </td>
                                <td>
                                    {{ question.options[2].option }}
                                </td>
                                <td>
                                    {{ question.options[3].option }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                 </div> -->
                 <!-- SET A-->
                 
                 <!-- SET B-->
                 <!-- <div class="bg-red-300">
                    <span class="flex justify-center font-bold">TRIAL GENERATED QUESTIONS SET B</span>
                 </div>
                 Key: <span class="text-red-500 font-bold text-[24px]">{{ setBKeyToCorrection }}</span>
                 <div class="border border-black rounded-md p-2">           
                    <table class="w-full ">
                        <thead class="bg-green-100">
                            <tr>
                                <td>
                                    Question
                                </td>
                                <td>
                                    Term
                                </td>
                                <td>option a</td>
                                <td>option b</td>
                                <td>option c</td>
                                <td>option c</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="question in setB" :key="question.id">
                                <td>
                                    {{ question.question }}
                                </td>
                                <td>
                                    {{ question.term }}
                                </td>
                                <td>
                                    {{ question.options[0].option }}
                                </td>
                                <td>
                                    {{ question.options[1].option }}
                                </td>
                                <td>
                                    {{ question.options[2].option }}
                                </td>
                                <td>
                                    {{ question.options[3].option }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                 </div> -->
                 <!-- SET B-->
                 
                 <!-- SET C-->
                 <!-- <div class="bg-red-300">
                    <span class="flex justify-center font-bold">TRIAL GENERATED QUESTIONS SET C</span>
                 </div>
                 Key: <span class="text-red-500 font-bold text-[24px]">{{ setCKeyToCorrection }}</span>
                 <div class="border border-black rounded-md p-2">           
                    <table class="w-full ">
                        <thead class="bg-green-100">
                            <tr>
                                <td>
                                    Question
                                </td>
                                <td>
                                    Term
                                </td>
                                <td>option a</td>
                                <td>option b</td>
                                <td>option c</td>
                                <td>option c</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="question in setC" :key="question.id">
                                <td>
                                    {{ question.question }}
                                </td>
                                <td>
                                    {{ question.term }}
                                </td>
                                <td>
                                    {{ question.options[0].option }}
                                </td>
                                <td>
                                    {{ question.options[1].option }}
                                </td>
                                <td>
                                    {{ question.options[2].option }}
                                </td>
                                <td>
                                    {{ question.options[3].option }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                 </div> -->
                 <!-- SET C-->


                <form @submit.prevent="handlePreviewButtonClicked"> 
                    <div class="w-full">
                        <div class="flex w-full pr-4 gap-2 flex-col  md:items-center md:flex-row py-2 ">
                            <label for="department" class="text-black font-semibold w-full max-w-[150px]">Department: </label>
                            <select id="department" v-model="selectedDepartment" class="rounded-md w-full text-xs "  >
                                <option value=""  disabled hidden>Select department </option>
                                <option v-for="dep in departmentOptions" :key="dep.id" :value="dep">
                                    {{ dep.name }} 
                                </option>
                            </select>
                        </div>
                       
                        <div class="flex w-full pr-4 gap-2 flex-col py-2 md:items-center md:flex-row">
                            <label for="subject_codes" class="text-black font-semibold w-full max-w-[150px]">Subject Code: </label>
                            <select id="subject_codes" v-model="selectedSubjectCode" class="rounded-md w-full text-xs" >
                                <option  value="" hidden disabled>Select subject code </option>
                                <option v-for="code in selectedDepartment.subject_codes" :key="code.id" :value="code">
                                    {{ code.name }}
                                </option>
                            </select>
                        </div>
                        <div class="flex w-full pr-4 gap-2 flex-col py-2 md:items-center md:flex-row">
                            <label for="description" class="text-black font-semibold w-full max-w-[150px]">Subject Code: </label>
                            <input id="description" type="text" :placeholder="selectedSubjectCode.description" class="bg-gray-100 rounded-md w-full text-xs" disabled />
                        </div>
                    </div>
                    <div class="mt-2">
                        <hr>
                    </div>
                    <div class="my-2">
                        <span class="text-black font-semibold">Select Term: set A: </span>
                    </div>
                    <div class="w-full m-auto border px-4 bg-blue-100 rounded-md shadow-inner ">
                        <div class="w-full grid grid-cols-5 gap-2 my-2">
                            <div class="col-span-1 flex items-center gap-2 ">
                                <input v-model="isPrelim" id="prelim_chkbx" type="checkbox" class="hover:cursor-pointer rounded-sm" value="prelim" :disabled="!prelimItems.length"/>
                                <label for="prelim_chkbx" class="hover:cursor-pointer text-black">Prelim</label>
                            </div>
                            <div class="col-span-1 flex items-center ">
                                <label class="text-black">Print : </label>
                            </div>
                            <div class="col-span-3 flex items-center justify-evenly ">
                                <input v-model="prelimQuestionCount" class="max-w-[100px] rounded-md mr-2 shadow-md text-xs" it="testNum" type="number" max="50" min="0" :disabled="!isPrelim" :class="{'bg-gray-200':!isPrelim}" :required="isPrelim"/>  of  <input class="max-w-[100px] rounded-md ml-2  shadow-md text-xs" id="maxTestNum" type="number" :max="prelimItems.length" :placeholder="prelimItems.length" disabled/>
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-5 gap-2 my-2">
                            <div class="col-span-1 flex items-center gap-2 ">
                                <input v-model="isMidterm" id="midterm_chkbx" type="checkbox" class="hover:cursor-pointer rounded-sm" value="prelim" :disabled="!midTermItems.length"/>
                                <label for="midterm_chkbx" class="hover:cursor-pointer text-black">Mid-term</label>
                            </div>
                            <div class="col-span-1 flex items-center ">
                                <label class="text-black">Print : </label>
                            </div>
                            <div class="col-span-3 flex items-center justify-evenly ">
                                <input v-model="midtermQuestionCount" class="max-w-[100px] rounded-md mr-2 shadow-md text-xs" it="testNum" type="number" max="50" min="0" :disabled="!isMidterm" :class="{'bg-gray-200':!isMidterm}" :required="isMidterm"/>  of  <input class="max-w-[100px] rounded-md ml-2  shadow-md text-xs" id="maxTestNum" type="number" :max="midTermItems.length" :placeholder="midTermItems.length" disabled/>
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-5 gap-2 my-2">
                            <div class="col-span-1 flex items-center gap-2 ">
                                <input v-model="isPrefinal" id="prefinal_chkbx" type="checkbox" class="hover:cursor-pointer rounded-sm" value="prelim" :disabled="!preFinalItems.length" />
                                <label for="prefinal_chkbx" class="hover:cursor-pointer text-black">Pre-final</label>
                            </div>
                            <div class="col-span-1 flex items-center ">
                                <label class="text-black">Print :  </label>
                            </div>
                            <div class="col-span-3 flex items-center justify-evenly ">
                                <input v-model="prefinalQuestionCount" class="max-w-[100px] rounded-md mr-2 shadow-md text-xs" it="testNum" type="number" max="50" min="0" :disabled="!isPrefinal" :class="{'bg-gray-200':!isPrefinal}" :required="isPrefinal"/>  of  <input class="max-w-[100px] rounded-md ml-2  shadow-md text-xs" id="maxTestNum" type="number" :max="preFinalItems.length" :placeholder="preFinalItems.length" disabled/>
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-5 gap-2 my-2">
                            <div class="col-span-1 flex items-center gap-2 " >
                                <input v-model="isFinal" id="final_chkbx" type="checkbox" class="hover:cursor-pointer rounded-sm" value="prelim" :disabled="!finalItems.length" />
                                <label for="final_chkbx" class="hover:cursor-pointer text-black">Final</label>
                            </div>
                            <div class="col-span-1 flex items-center ">
                                <label class="text-black">Print : </label>
                            </div>
                            <div class="col-span-3 flex items-center justify-evenly ">
                                <input v-model="finalQuestionCount" class="max-w-[100px] rounded-md mr-2 shadow-md text-xs" it="testNum" type="number" max="50" min="0" :disabled="!isFinal" :class="{'bg-gray-200':!isFinal}" :required="isFinal"/>  of  <input class="max-w-[100px] rounded-md ml-2  shadow-md text-xs" id="maxTestNum" type="number" :max="finalItems.length" :placeholder="finalItems.length" disabled/>
                            </div>
                        </div>
                        <div class="mt-2 ">
                            <hr class="border border-2 border-gray-400">
                        </div>
                        <div class="w-full grid grid-cols-5 gap-2 my-2">
                            <div class="col-span-2 flex items-center gap-2  ">
                                <span  class="hover:cursor-pointer text-black">Total Number of Questions: </span>
                            </div>
                            
                            <div class="col-span-3 flex items-center justify-evenly ">
                                <input v-model="totalQuestionsCount" class="max-w-[100px] rounded-md mr-2 shadow-md text-xs" it="testNum" type="number" max="50" min="0" disabled :class="{'bg-gray-200':!totalQuestionsCount}"/>  of  <input class="max-w-[100px] rounded-md ml-2  shadow-md text-xs" id="maxTestNum" type="number" :max="finalItems.length" :placeholder="totalItems" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <hr class="border border-gray-300">
                    </div>
                    <div class="mt-4 flex gap-2 w-full flex-col md:flex-row justify-between px-2" >
                        <div class="flex gap-2  flex-col md:flex-row md:items-center">
                            <label for="schoolyr" class="font-semibold">SY: </label>
                            <select v-model="selectedSchoolYear"  id="schoolyr" class="rounded-md text-xs" >
                                <option value="" hidden disabled>Select school year</option>
                                <option v-for="(year,index) in schoolYear" :key="index" >
                                    {{ year }}
                                </option>
                            </select>
                        </div>
                        <div class="flex gap-2  flex-col md:flex-row md:items-center">
                            <label for="semester" class="font-semibold">Sem: </label>
                            <select v-model="selectedSemester" id="semester" class="rounded-md text-xs" >
                                <option value="" hidden disabled>Select sem</option>
                                <option v-for="(sem,index) in semesters" :key="index" >
                                    {{ sem }}
                                </option>
                            </select>
                        </div>
                        <div class="flex gap-2  flex-col md:flex-row md:items-center">
                            <label for="term" class="font-semibold">Term: </label>
                            <select v-model="selectedTerm" id="term" class="rounded-md text-xs" >
                                <option value="" hidden disabled>Select a term</option>
                                <option v-for="(term,index) in terms" :key="index" >
                                    {{ term }}
                                </option>
                            </select>
                        </div>
                        <div class="flex gap-2  flex-col md:flex-row md:items-center">
                            <label for="sets" class="font-semibold">Set: </label>
                            <select v-model="selectedSet" id="sets" class="rounded-md text-xs" :disabled="!totalQuestionsCount" >
                                <option value="" hidden disabled>Select a set</option>
                                <option v-for="(set,index) in sets" :key="index" >
                                    {{ set }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <hr class="border border-gray-300">
                    </div>
                    <div class="flex flex-col  w-full mt-5 ">
                        <button @click="handleResetButtonClicked" type="button" class="w-full btn-primary py-2 px-4 m-2 border shadow-md "  >Reset</button>
                        <button @click="saveExam"  type="button" class="w-full btn-primary py-2 px-4 m-2 border shadow-md " >Generate</button>
                        <!-- <button @click="handleSave" type="button" class="w-full btn-primary py-2 px-4 m-2 border shadow-md " >Cancel</button> -->
                        <!-- <button @click="saveAnswerKeysToPDF(testAnswers)" type="button" class="w-full btn-primary py-2 px-4 m-2 border shadow-md " >TEST BUTTON</button> -->
                    </div>
                    
                </form>
                <!-- test answers:{{ testAnswer }} -->
            </ModalHeader>
        </Dialog>
        <div>
            <hr class="border border-2 border-black w-full max-w-[8.5in] max-h-[llin] mb-8 mt-2" />
        </div>
        
        <!--save question set--> 
        <div  id="pdf-convert" class="w-full py-2 ">
            <!--heading-->
            <div class="flex justify-center gap-2 w-full  relative ">
                <div class="flex justify-between gap-4 ">
                    <div class=" w-20 h-20 mt-[18px] absolute z-50 top-0 left-[30px]">
                        <img :src="logoUrl" alt="Ncst Logo"/>
                    </div>
                    <div class="flex flex-col justify-center items-center mb-2 mt-1 ">
                        <span class="font-bold text-[18px]">NATIONAL COLLEGE OF SCIENCE AND TECHNOLOGY</span>
                        <span>Amafel Building Aguinaldo Highway, Dasmariñas, Cavite</span>
                        <span>Tel. no. (1234-1234) </span>
                        <a href="https://ncst.edu.ph/" target="_blank">www.ncst.edu.ph</a>
                        
                        <span v-if="selectedDepartment.division_id"  class="text-[16px] font-bold mt-2"  >
                            {{ getDivisionName(selectedDepartment.id,selectedDepartment.division_id) }} Department
                        </span>
                        <span v-else class="text-[16px] font-bold mt-2"  >{{ selectedDepartment.name }} Department</span>
                        <span class="text-[16px] font-bold">{{ convertTerm(selectedTerm) }} Exam in {{ selectedSubjectCode.name }} {{ selectedSubjectCode.description }} </span>
                        <span class="text-[16px] font-bold" >{{ selectedSemester }} Semester SY: {{ selectedSchoolYear }} </span>
                        <span class="text-[16px] font-bold" >Set {{ displaySetQuestion }}</span>
                        
                    </div>
                </div> 
            </div>
            <!--heading-->
            <div class="mt-4 py-2 ">
                <span class="font-bold text-[16px]">{{ direction }}</span>
            </div>
            <!--questions-->
            <div class="w-full flex flex-wrap whitespace-nowrap flex-col py-2    " v-for="(question,index) in questionSetPdf" :key="question.id"><!-- andito ako 1-->
                <div class="txt-break">
                    <span class="text-[12px] py-2 text-break ">{{index+1}}. {{ question.question }}</span>
                </div>
                
                <!--options-->
                <div v-if="question.type === 'text'" class="flex flex-row whitespace-nowrap gap-3 w-full pl-4 mt-2  " :class="{'flex-col':question.options.some(option => option.option.length > 30)}">
                    
                    <template v-for="(option,index) in question.options" class="">
                        <span class="flex w-full text-[12px] py-2 txt-break">{{ optionLetters[index] }}. {{ option.option }}</span>
                    </template>
                </div>
                
                <div v-if="question.type === 'image'" class="flex flex-row w-full bg-gren-300 ml-2 mt-2  p-2  ">
                    <template v-for="(option,index) in question.options" class="w-full flex flex-row bg-green-300  " >  
                        <div class="w-full flex  gap-2 pb-2 img-break  ">
                            <span class="text-[12px]">{{ optionLetters[index] }}. </span>                
                            <img :src="imageUrl+option.option" alt="image option" class="max-w-[1.5in] max-h-[1.5in] rounded-md border border-black img-break"/>   
                        </div>                    
                    </template>
                </div>
                 <!--options-->   
            </div>
            <!--questions-->
            <div v-if="problemSet.length" class="mt-4 py-4">
                <span v-html="formatText(problemSet[0].content)" class="font-bold text-[12px]"></span>
                 <!--andito ako 3-->
            </div>
        </div>
        <div>
            <hr class="border border-2 border-black w-full max-w-[8.5in] max-h-[llin] my-8" />
        </div>

        <!--save answer keys to pdf-->
        <div  id="answers-pdf" class="w-full max-w-[8.5in] max-h-[llin]">
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
                <span v-if="selectedDepartment.division_id"  class="text-[14px] font-bold mt-2"  >
                    {{ getDivisionName(selectedDepartment.id,selectedDepartment.division_id) }} Department
                </span>
                <span v-else class="text-[14px] font-bold mt-2"  >{{ selectedDepartment.name }} Department</span>
                <span class="text-[14px] font-bold">{{ convertTerm(selectedTerm) }} Exam in {{ selectedSubjectCode.name }} {{ selectedSubjectCode.description }} </span>
                <span class="text-[14px] font-bold">{{ selectedSemester }} Semester SY: {{ selectedSchoolYear }}</span>
                <span class="text-[14px] font-bold mt-2">Set {{ displaySetAnswer }}</span>
                <span class="text-[14px] font-bold">Answer Key</span>
                <span class="text-[12px] ">{{ answerKeyDateFormat() }}</span>
                </div>
            </div> 
            </div>
            <!-- Grid -->
            <div class="grid pb-2" :class="`grid-cols-${state.columns}`" :style="{ gridTemplateColumns: `repeat(${state.columns}, minmax(0, 1fr))` }">
                <div v-for="(chunk, colIndex) in state.chunksToShow" :key="colIndex" class="col-span-1 flex flex-col justify-center">
                    <div v-for="(ans, index) in chunk" :key="index">
                        <span>{{ index + 1 + colIndex * maxChunkSize }}. {{ ans }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- save answer keys to pdf-->
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue';
import Swal from 'sweetalert2/dist/sweetalert2.js'
import ModalHeader from '../Components/ModalHeader.vue'
import {ref,onMounted, computed,watch,reactive} from 'vue'
import { useForm } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import html2pdf from 'html2pdf.js'; // Ensure the correct import path


const customModalOpen = ref(true)
const logoUrl = ref('/storage/Images/ncstLogo.png');
const imageUrl = ref('/storage/Images/')
const questionSetPdf = ref([])
const answerKeySet = ref([])

const closeModal = () => {
   customModalOpen.value = false;
};

onMounted(()=>{
    
})
const data = defineProps({
    department:Array,
})

const form = useForm({
    test:'1',
})
const saveDataContent = ref('');


const openPreviewInNewTab = () => {
    const data = encodeURIComponent(saveDataContent.value);
    const url = `/preview?data=${data}`;
    window.open(url, '_blank');
};

//
const selectedDepartment = ref('');
const selectedSubjectCode = ref('');
const departmentOptions = computed(()=>{
    let options = []

    data.department.forEach((dep)=>{
        if(dep.divisions.length)
        {
            dep.divisions.forEach((div)=>{
                options.push({
                    id:dep.id,
                    division_id:div.id,
                    name:dep.name+' '+div.name,
                    subject_codes:dep.subject_codes.filter((code)=> code.division_id===div.id)
                })
            })
        }
        else
        {
            options.push({
                id:dep.id,
                division_id:null,
                name:dep.name,
                subject_codes:dep.subject_codes,
            })
        }
        
    })

    return options
})


const isPrelim = ref(false);
const termQuestionSelection = ref([])
watch(isPrelim,(val)=>{
    if(val)
    {
        termQuestionSelection.value.push('prelim');
    }
    if(!val)
    {
        prelimQuestionCount.value = ''
        termQuestionSelection.value =  termQuestionSelection.value.filter((term)=>term !== 'prelim')
    }
})
const isMidterm = ref(false);
watch(isMidterm,(val)=>{
    if(val)
    {
        termQuestionSelection.value.push('mid-term');
    }
    if(!val)
    {
        midtermQuestionCount.value = ''
        termQuestionSelection.value =  termQuestionSelection.value.filter((term)=>term !== 'mid-term')
    }
})
const isPrefinal = ref(false);
watch(isPrefinal,(val)=>{

    if(val)
    {
        termQuestionSelection.value.push('pre-final');
    }

    if(!val)
    {
        prefinalQuestionCount.value = ''
        termQuestionSelection.value =  termQuestionSelection.value.filter((term)=>term !== 'pre-final')
    }
})
const isFinal = ref(false);
watch(isFinal,(val)=>{
    if(val)
    {
        termQuestionSelection.value.push('final');
    }
    if(!val)
    {
        finalQuestionCount.value = ''
        termQuestionSelection.value =  termQuestionSelection.value.filter((term)=>term !== 'final')
    }
})


    const prelimItems   = ref([]);
    const midTermItems  = ref([]);
    const preFinalItems = ref([]);
    const finalItems    = ref([]);
    const totalItems    = computed(()=>{
    let count = 0
    count = prelimItems.value.length+midTermItems.value.length+preFinalItems.value.length+finalItems.value.length

    return count
});

const prelimGeneratedQuestions = ref([])
const prelimQuestionCount   = ref('');
watch(prelimQuestionCount,(count)=>{
   
    prelimGeneratedQuestions.value = []
   if(count > prelimItems.value.length)
   {
        prelimQuestionCount.value = ''
   }
   else
   {
        let monitoredRandomNumbers = []

        //solution
        for (let i = 0; i < count; i++) {
            let rand = Math.floor(Math.random() * prelimItems.value.length);

            while (monitoredRandomNumbers.includes(rand)) {
                rand = Math.floor(Math.random() * prelimItems.value.length);
            }

            // store the random question to a temporary data for processing
            let tempData = { ...prelimItems.value[rand], options: [] };
            
            monitoredRandomNumbers.push(rand);

            let optionMonitoredRandomNumber = new Set();

            for (let j = 0; j < 4; j++) {
                let optionRandNumber = Math.floor(Math.random() * 4);

                while (optionMonitoredRandomNumber.has(optionRandNumber)) {
                    optionRandNumber = Math.floor(Math.random() * 4);
                }

                optionMonitoredRandomNumber.add(optionRandNumber);
                tempData.options.push(prelimItems.value[rand].options[optionRandNumber]);
                //console.log('push this: ' + prelimItems.value[rand].options[optionRandNumber].option);
            }

            //console.log('option monitored numbers: ' + Array.from(optionMonitoredRandomNumber));
            prelimGeneratedQuestions.value.push(tempData);
        }
        //solution  
   }

   //console.log(prelimGeneratedQuestions.value)
})

const midTermGeneratedQuestions = ref([])
const midtermQuestionCount  = ref('');
watch(midtermQuestionCount,(count)=>{
    midTermGeneratedQuestions.value = []
    if(count > midTermItems.value.length)
    {
        midtermQuestionCount.value = ''
    }
    else
   {
        let monitoredRandomNumbers = []

        //solution
        for (let i = 0; i < count; i++) {
            let rand = Math.floor(Math.random() * midTermItems.value.length);

            while (monitoredRandomNumbers.includes(rand)) {
                rand = Math.floor(Math.random() * midTermItems.value.length);
            }

            // store the random question to a temporary data for processing
            let tempData = { ...midTermItems.value[rand], options: [] };
            
            monitoredRandomNumbers.push(rand);

            let optionMonitoredRandomNumber = new Set();

            for (let j = 0; j < 4; j++) {
                let optionRandNumber = Math.floor(Math.random() * 4);

                while (optionMonitoredRandomNumber.has(optionRandNumber)) {
                    optionRandNumber = Math.floor(Math.random() * 4);
                }

                optionMonitoredRandomNumber.add(optionRandNumber);
                tempData.options.push(midTermItems.value[rand].options[optionRandNumber]);
                //console.log('push this: ' + prelimItems.value[rand].options[optionRandNumber].option);
            }

            //console.log('option monitored numbers: ' + Array.from(optionMonitoredRandomNumber));
            midTermGeneratedQuestions.value.push(tempData);
        }
        //solution  
    }
 })

const prefinalGeneratedQuestions = ref([])
const prefinalQuestionCount = ref('');
watch(prefinalQuestionCount,(count)=>{
    prefinalGeneratedQuestions.value = []
    if(count > preFinalItems.value.length)
    {
        prefinalQuestionCount.value = ''
    }
    else
   {
        let monitoredRandomNumbers = []

        //solution
        for (let i = 0; i < count; i++) {
            let rand = Math.floor(Math.random() * preFinalItems.value.length);

            while (monitoredRandomNumbers.includes(rand)) {
                rand = Math.floor(Math.random() * preFinalItems.value.length);
            }

            // store the random question to a temporary data for processing
            let tempData = { ...preFinalItems.value[rand], options: [] };
            
            monitoredRandomNumbers.push(rand);

            let optionMonitoredRandomNumber = new Set();

            for (let j = 0; j < 4; j++) {
                let optionRandNumber = Math.floor(Math.random() * 4);

                while (optionMonitoredRandomNumber.has(optionRandNumber)) {
                    optionRandNumber = Math.floor(Math.random() * 4);
                }

                optionMonitoredRandomNumber.add(optionRandNumber);
                tempData.options.push(preFinalItems.value[rand].options[optionRandNumber]);
                //console.log('push this: ' + prelimItems.value[rand].options[optionRandNumber].option);
            }

            //console.log('option monitored numbers: ' + Array.from(optionMonitoredRandomNumber));
            prefinalGeneratedQuestions.value.push(tempData);
        }
        //solution  
    }
 })
const finalGeneratedQuestions = ref([])
const finalQuestionCount    = ref('');
watch(finalQuestionCount,(count)=>{
    finalGeneratedQuestions.value = []
    if(count > finalItems.value.length)
    {
        finalQuestionCount.value = ''
    }
    else
   {
        let monitoredRandomNumbers = []

        //solution
        for (let i = 0; i < count; i++) {
            let rand = Math.floor(Math.random() * finalItems.value.length);

            while (monitoredRandomNumbers.includes(rand)) {
                rand = Math.floor(Math.random() * finalItems.value.length);
            }

            // store the random question to a temporary data for processing
            let tempData = { ...finalItems.value[rand], options: [] };
            
            monitoredRandomNumbers.push(rand);

            let optionMonitoredRandomNumber = new Set();

            for (let j = 0; j < 4; j++) {
                let optionRandNumber = Math.floor(Math.random() * 4);

                while (optionMonitoredRandomNumber.has(optionRandNumber)) {
                    optionRandNumber = Math.floor(Math.random() * 4);
                }

                optionMonitoredRandomNumber.add(optionRandNumber);
                tempData.options.push(finalItems.value[rand].options[optionRandNumber]);
                //console.log('push this: ' + prelimItems.value[rand].options[optionRandNumber].option);
            }

            //console.log('option monitored numbers: ' + Array.from(optionMonitoredRandomNumber));
            finalGeneratedQuestions.value.push(tempData);
        }
        //solution  
    }
 })
const totalQuestionsCount = computed(()=>{
    let count = ''
    count = prelimQuestionCount.value+midtermQuestionCount.value+prefinalQuestionCount.value+finalQuestionCount.value
    return count
})
watch(totalQuestionsCount,(val)=>{
    if(val < 1)
    {
        selectedSet.value = ''
    }
})

const generatedExamQuestions = computed(()=>{
    return [
    ...prelimGeneratedQuestions.value,
    ...midTermGeneratedQuestions.value,
    ...prefinalGeneratedQuestions.value,
    ...finalGeneratedQuestions.value
  ];
})


watch(selectedSubjectCode,(code)=>{
    
    if(code)
    {
        prelimItems.value   = code.questions.filter((question)=> question.term==='prelim')
        midTermItems.value  = code.questions.filter((question)=> question.term==='mid-term')
        preFinalItems.value = code.questions.filter((question)=> question.term==='pre-final')
        finalItems.value    = code.questions.filter((question)=> question.term==='final')
    }
    else
    {
        prelimItems.value   = [];
        midTermItems.value  = [];
        preFinalItems.value = [];
        finalItems.value    = [];
    }
    
})


//watchers
watch(selectedDepartment,(val)=>{
   
    selectedSubjectCode.value   = '';
    isPrelim.value              = false
    isMidterm.value             = false
    isPrefinal.value            = false
    isFinal.value               = false
    selectedSchoolYear.value    = ''
    selectedTerm.value          = ''
    selectedSet.value           = ''
    selectedSemester.value      = ''
   
})

const selectedSchoolYear = ref('')
const schoolYear = ref([
    '2024-2025',
    '2025-2026',
    '2026-2027',
    '2027-2028',
    '2028-2029',
    '2029-2030',
    '2030-2031',
    '2031-2032',
    '2032-2033',
    '2033-2034',
    '2034-2035',
])

const selectedTerm = ref('')
const terms = ref([
    'prelim',
    'mid-term',
    'pre-final',
    'final',
])


const selectedSemester = ref('');
const semesters = ref([
    '1st',
    '2nd',
])
const selectedSet = ref('')
watch(selectedSet,(val)=>{
    //create set a b c

    
})


const sets = ref([
    'A',
    'B',
    'C',
    'A B',
    'A C',
    'A B C',
])

// buttons logic
const handleResetButtonClicked = ()=>{
    selectedDepartment.value=''
}







// question generation logic
// const questionPerTerm = ref({
//     prelim:[],
//     midTerm:[],
//     prefinal:[],
//     final:[],
// })
// function seperateQuestionPerTerm(code){
//     questionPerTerm.value.prelim = code.questions.filter((question)=> question.term === 'prelim')
//     questionPerTerm.value.midTerm = code.questions.filter((question)=> question.term === 'mid-term')
//     questionPerTerm.value.prefinal = code.questions.filter((question)=> question.term === 'pre-final')
//     questionPerTerm.value.final = code.questions.filter((question)=> question.term === 'final')
// }

function testRandom(num)
{
    console.log('im herre');
    let randomizedNumbers = []
    for (let i = 0; i < num; i++) {
        let rand = Math.floor(Math.random() * num);
        
        if (!randomizedNumbers.includes(rand)) {
            randomizedNumbers.push(rand);
            console.log('included');
        } else {
            while (randomizedNumbers.includes(rand)) {
                rand = Math.floor(Math.random() * num);
                console.log('rerandom a number');
            }
            randomizedNumbers.push(rand);
        }
        console.log(randomizedNumbers);
    }

    
}



// saving logic ********************************
const problemSet = ref('')
const saveExam = () => {
    if(!selectedDepartment.value)
    {
        errorMessage('Please select a department.')
        return
    }

    if(!selectedSubjectCode.value)
    {
        errorMessage('Please select a subject code.')
        return;
    }

    if(!termQuestionSelection.value.length)
    {
        errorMessage('Please select at least 1 term for question selection.')
        return
    }

    if(isPrelim.value)
    { 
        
        if(!prelimQuestionCount.value)
        {
            errorMessage('Please define the number of questions coming from prelim questions.')
            return
        }
    }

    if(isMidterm.value)
    { 
        if(!midtermQuestionCount.value)
        {
            errorMessage('Please define the number of questions coming from midterm questions.')
            return
        }
    }

    if(isPrefinal.value)
    { 
        if(!prefinalQuestionCount.value)
        {
            errorMessage('Please define the number of questions coming from prefinal questions.')
            return
        }
    }

    if(isFinal.value)
    { 
        if(!finalQuestionCount.value)
        {
            errorMessage('Please define the number of questions coming from final questions.')
            return
        }
    }

    if(!selectedSchoolYear.value)
    {
        errorMessage('Please select a school year.')
        return
    }

    if(!selectedSemester.value)
    {
        errorMessage('Please select a semester.')
        return
    }

    if(!selectedTerm.value)
    {
        errorMessage("'Please select the exam's term.")
        return
    }

    if(!selectedSet.value)
    {
        errorMessage("'Please select exam's set.")
        return
    }
    
    
    if(generatedExamQuestions.value.length)
    {
        setA.value =  randomizeQuestionSet(generatedExamQuestions.value)
    }
    
    if(setA.value.length)
    {
        setB.value =  randomizeQuestionSet(generatedExamQuestions.value);
    }
    
    if(setB.value.length)
    {
        setC.value = randomizeQuestionSet(generatedExamQuestions.value)
    }
    
    setAKeyToCorrection.value = getCorrectAnswer(setA.value)
    setBKeyToCorrection.value = getCorrectAnswer(setB.value)
    setCKeyToCorrection.value = getCorrectAnswer(setC.value)
    problemSet.value = selectedSubjectCode.value.problem_sets.filter((problemSet)=> problemSet.term === selectedTerm.value); // andito ako 4

    console.log('set a')
    console.log(setA.value)
    console.log('set b')
    console.log(setB.value)
    console.log('set c')
    console.log(setC.value)
    console.log('set A key to correction')
    console.log(setAKeyToCorrection.value)
    console.log('set B key to correction')
    console.log(setBKeyToCorrection.value)
    console.log('set C key to correction')
    console.log(setCKeyToCorrection.value)

    //return 
    // console.log('selected set: ')
    // console.log(selectedSet.value)
    
    
    
    confirmation("Please check if everything is set correctly before proceeding. This will download multiple files. Please allow multiple file download on your browser.")
    
    
  

}

// saving logic ********************************
function convertTerm(term)
{
    let converted = ''
    switch(term)
    {
        case 'prelim':
            converted = 'Prelim'
            break
        case 'mid-term':
            converted = 'Midterm'
            break
        case 'pre-final':
            converted = 'Prefinal'
            break
        case 'final':
            converted = 'final'
            break
        default:
            converted = 'Selected Term'
    }

    return converted
}

function getDivisionName(depId,divId)
{
    let  divisionName = []
    //console.log(data.department)
    // console.log('department id'+depId)
    // console.log("division id: "+divId)
    
    data.department.forEach((dep)=>{
        if(dep.id === depId)
        {
            // dep.divisions.foreach((div)=>{
            //     if(div.id === divId)
            //     {
            //         divisionName.push(div);
            //     }
            // })
            dep.divisions.forEach((div)=>{
                if(div.id === divId)
                {
                    divisionName.push(div)
                }
            })
        }
    })
    //dep.id === depId && dep.division_id === divId

    //console.log('division: '+divisionName[0].divisions[0].name)
    //console.log('division: '+divisionName[0])
   return divisionName[0].name
}

const setA = ref([])
const setB = ref([])
const setC = ref([])
const setAKeyToCorrection = ref([])
const setBKeyToCorrection = ref([])
const setCKeyToCorrection = ref([])
const optionLetters = ['A','B','C','D']
const direction = ref('DIRECTIONS: Multiple choice. Read the statement carefully. Use the provided answer sheet. choose the letter that correspond to the correct answer. STRICLY NO ERASURE.')

const testRandom2 = ()=>{
    setA.value = randomizeQuestionSet(testQuestion);
}
function randomizeQuestionSet(questionSet){
    let question = questionSet;
    let newQuestionSet = []
    let randomQuestionIndexList = []

    for(let i = 0; i < question.length; i++)
    {
        
        
        let randomQuestionIndex = Math.floor(Math.random() * question.length)
        
        

        while(randomQuestionIndexList.includes(randomQuestionIndex))
        {
            randomQuestionIndex = Math.floor(Math.random() * question.length)
        }

        randomQuestionIndexList.push(randomQuestionIndex)
        let tempQuestion = {...question[randomQuestionIndex],options:[]}
        

        let randomOptionIndexList = new Set()

        for(let j = 0 ; j < 4; j++)
        {
            
            let randomOptionIndex = Math.floor(Math.random() * 4)
            
            while(randomOptionIndexList.has(randomOptionIndex))
            {
                 randomOptionIndex = Math.floor(Math.random() * 4)
            }

            randomOptionIndexList.add(randomOptionIndex)
            tempQuestion.options.push(question[randomQuestionIndex].options[randomOptionIndex])
        }
        
        newQuestionSet.push(tempQuestion)
       
    }

    
    return newQuestionSet
}


const testQuestion = [
    {
        id:'1',
        question:'question 1',
        options:[
            {
                option:'option A 1',
                isCorrect:'true'
            },
            {
                option:'option B 1',
                isCorrect:'false',
            },
            {
                option:'option C 1',
                isCorrect:'false',
            },
            {
                option:'option D 1',
                isCorrect:'false',
            }
        ]
    },
    {
        id:'2',
        question:'question 2',
        options:[
            {
                option:'option A 2',
                isCorrect:'false',
            },
            {
                option:'option B 2',
                isCorrect:'true',
            },
            {
                option:'option C 2',
                isCorrect:'false',
            },
            {
                option:'option D 2',
                isCorrect:'false',
            }
        ]
    },
    {
        id:'3',
        question:'question 3',
        options:[
            {
                option:'option A 3',
                isCorrect:'true',

            },
            {
                option:'option B 3',
                isCorrect:'false',
            },
            {
                option:'option C 3',
                isCorrect:'false',
            },
            {
                option:'option D 3',
                isCorrect:'false',
            }
        ]
    },
    {
        id:'4',
        question:'question 4',
        options:[
            {
                option:'option A 4',
                isCorrect:'false',
            },
            {
                option:'option B 4',
                isCorrect:'true',
            },
            {
                option:'option C 4',
                isCorrect:'false',
            },
            {
                option:'option D 4',
                isCorrect:'false',
            }
        ]
    }

]

const testAnswers = [
    'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b',
    'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b',
    'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b',
    'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b',
    'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b',
    'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b', 'a', 'b', 'c', 'd', 'd', 'b', 'c', 'd', 'a', 'a', 'b', 'b',

]
function getCorrectAnswer(questions){
    let answers = []
    const letter = ['a','b','c','d']
   
    questions.forEach((q)=>{
        
       q.options.findIndex((option,index)=> {
            if(option.isCorrect === 'true')
            {
                answers.push(letter[index])
            }
       })
    })

    return answers
}

function saveAnswerKeyToCsV(answers,filename)
{
    let tempData = ['*','*','*','*','*',...answers]

    const blob = new Blob([tempData], { type: 'text/csv' });
    
    // Create a link element
    const link = document.createElement('a');
    // Set the URL of the link to the blob
    link.href = URL.createObjectURL(blob);
    // Set the download attribute of the link
    link.download = filename+'.csv';
    // Append the link to the document
    document.body.appendChild(link);
    // Programmatically click the link to trigger the download
    link.click();
    // Remove the link from the document
    document.body.removeChild(link);
}


async function downloadAnswerKeysCSV(set)
{
    let selectedSet = set.split(' ')

    let dateFormat = answerKeyDateFormat()
    let term    = convertTerm(selectedTerm.value).toString()
    let code    = selectedSubjectCode.value.name.toString()

    let name = term+'-'+code
    for (const set of selectedSet) {
        switch (set) {
        case 'A':
            
            saveAnswerKeyToCsV(setAKeyToCorrection.value, name+'-ANSWER-A-'+dateFormat+'.csv');
            break;
        case 'B':
            
            saveAnswerKeyToCsV(setBKeyToCorrection.value, name+'-ANSWER-B-'+dateFormat+'.csv');
            break;
        case 'C':
            
            saveAnswerKeyToCsV(setCKeyToCorrection.value, name+'-ANSWER-C-'+dateFormat+'.csv');
            break;
        }
    } 

    await new Promise(resolve => setTimeout(resolve, 1000)); // 1-second delay
}

// saveAnswerKeysToPDF logic *******************************************************
// Define the maximum number of elements to display per column
const maxChunkSize = 30;
const maxElementsPerColumn = 30 * 7; // 33 elements per column * 7 columns

// Reactive state to store the columns and chunks to show
const state = reactive({
  columns: 1,
  chunksToShow: [],
  downloadStatus: '',
  downloadMessage: ''
});




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
const saveAnswerKeysToPDF = (keyToCorrection,filename) => {
  
  try {
    // Process the answers to update the state
   
    state.chunksToShow = []
    processAnswers(keyToCorrection);

    const element = document.getElementById('answers-pdf');
    html2pdf().set({
            margin:       [0.1,0.4,0.2,0.4],
            filename:     filename,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        }).from(element).save()
      .then(() => {
        
        state.downloadStatus = 'success';
        state.downloadMessage = 'PDF downloaded successfully!';
      })
      .catch((error) => {
        
        state.downloadStatus = 'error';
        state.downloadMessage = `Error downloading PDF: ${error.message}`;
      });
  } catch (error) {
    
    state.downloadStatus = 'error';
    state.downloadMessage = `Error processing answers: ${error.message}`;
  }
};

function answerKeyDateFormat() {
    const date = new Date();
    
    // Extract components
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    
    // Construct the formatted date string
    const formattedDate = `${day}/${month}/${year} ${hours}.${minutes}.${seconds}`;
    
    return formattedDate;
}

function questionSetDateFormat() {
    const date = new Date();
    
    // Extract components
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    
    // Construct the formatted date strin
    const formattedDate = `${day}-${month}-${year}-${hours}-${minutes}-${seconds}`;
    
    return formattedDate;
}

// Initialize the state with the default answers
// processAnswers(answerKeySet.value);

async function downloadAnswerKeysPDF(set)
{
    let selectedSet = set.split(' ')
    let dateFormat = answerKeyDateFormat()
    let term    = convertTerm(selectedTerm.value).toString()
    let code    = selectedSubjectCode.value.name.toString()

    let name = term+'-'+code
    
    for (const set of selectedSet) {
        switch (set) {
        case 'A':
            displaySetAnswer.value = set
            await saveAnswerKeysToPDF(setAKeyToCorrection.value, name+'-ANSWER-'+set+'-'+dateFormat+'.pdf');
            break;
        case 'B':
            displaySetAnswer.value = set
            await saveAnswerKeysToPDF(setBKeyToCorrection.value, name+'-ANSWER-'+set+'-'+dateFormat+'.pdf');
            break;
        case 'C':
            displaySetAnswer.value = set
            await saveAnswerKeysToPDF(setCKeyToCorrection.value, name+'-ANSWER-'+set+'-'+dateFormat+'.pdf');
            break;
        }
    } 
}

async function downloadQuestionairePDF(set)
{
    
    let selectedSet = set.split('')
    let dateFormat = questionSetDateFormat()
    let term    = convertTerm(selectedTerm.value).toString()
    let code    = selectedSubjectCode.value.name.toString()
    
    let name = term+'-'+code
    
    for (const set of selectedSet) {
        switch (set) {
        case 'A':
            displaySetQuestion.value = set
            await saveQuestionareToPDF(setA.value, name+'-'+set+'-'+dateFormat);
            break;
        case 'B':
            displaySetQuestion.value = set
            await saveQuestionareToPDF(setB.value, name+'-'+set+'-'+dateFormat);
            break;
        case 'C':
            displaySetQuestion.value = set
            await saveQuestionareToPDF(setC.value, name+'-'+set+'-'+dateFormat);
            break;
        }
    } 
}

function saveQuestionareToPDF(questions,filename)
{
    
    questionSetPdf.value = questions
    const element = document.getElementById('pdf-convert'); // Replace 'pdf-content' with the ID of the content you want to convert to PDF
    const opt = {
        margin: [0.4, 0.5, 0.7, 0.4], // [top, left, bottom, right].
        filename:     filename+'.pdf',
        image:        { type: 'jpeg', quality: 0.98 }, //andito ako 33
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }, // 'letter', 'a4', 'legal' or [width, height] for custom size
        pagebreak: { mode: ['avoid-all'] },
    };

    // Function to add footers to each page
    function addFooters(pdf) {
        const pageCount = pdf.internal.getNumberOfPages();
        const currentDate = new Date().toLocaleDateString();
        pdf.setFontSize(8); // Set font size to 10px

        for (let i = 1; i <= pageCount; i++) {
            pdf.setPage(i);
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            // Draw a line at the bottom of the page
            // Draw a thinner line at the bottom of the page
            pdf.setLineWidth(0.01); // Set line width to the smallest visible value
            pdf.line(0.5, pageHeight - 0.6, pageWidth - 0.5, pageHeight - 0.6);

            // Adding current date on the left
            pdf.text(currentDate, 0.5, pageHeight - 0.4);

            // Calculate the width of the text
            const pageText = `Page ${i} of ${pageCount}`;
            const textWidth = pdf.getTextDimensions(pageText).w;

            // Adding page number on the right
            pdf.text(pageText, pageWidth - textWidth - 0.5, pageHeight - 0.4);
        }
    }

    // Generate the PDF
    html2pdf().from(document.getElementById('pdf-convert')).set(opt).toPdf().get('pdf').then((pdf) => {
        addFooters(pdf);
    }).save().then(() => {
        
        console.log('PDF saved successfully.');
    }).catch((error) => {
        
        console.error('Error generating PDF:', error);
    });
}
// saveAnswerKeysToPDF logic *******************************************************

// sweet alert
const generateConfirmedQuestions = ref(false);
const generateConfirmedAnswers = ref(false)
const displaySetQuestion = ref('')
const displaySetAnswer = ref('')
const confirmation = (message)=> 
{ 
    
    customModalOpen.value = false
    Swal.fire({
        title: "Are you sure?",
        text:  message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, proceed!",
        allowOutsideClick:false,
        allowEscapeKey:false,
        customClass: {
            popup: 'swal-popup-custom' // Adding a custom class
        }
        }).then((result) => {
            if(result.isConfirmed)
            {
                
                customModalOpen.value = true;
                if(selectedSet.value)
                { 
                    try
                    {
                        downloadQuestionairePDF(selectedSet.value)
                        downloadAnswerKeysPDF(selectedSet.value)
                        downloadAnswerKeysCSV(selectedSet.value)
                        
                    }
                    catch(err)
                    {
                        errorMessage(err)
                    }
                    
                }
                
                
            }   

            if(result.isDismissed)
            {
                Swal.fire({
                    title:'Canceled',
                    text:'Your action was canceled!',
                    icon:'error',
                    confirmButtonColor: '#3085d6',
                })   

                
            }
    });
}  


function errorMessage(message) {
        customModalOpen.value = false
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: message + '!',
            allowOutsideClick:false,
        }).then((result) => {
            if (result.isConfirmed) {
                customModalOpen.value = true
            }
        })
    }


    const formatText = (text) => {
        return text.replace(/\n/g, '<br>');
    };
</script>



<style scoped>
        /* Hide the spinners for WebKit-based browsers (Chrome, Safari) */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Hide the spinners for Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    .img-break, .txt-break {
        page-break-inside: avoid;
        break-inside: avoid;
    }
    
    /* Ensuring all parent elements are not breaking inside */
     .pdf-convert div, .pdf-convert span, .pdf-convert img {
        page-break-inside: avoid;
        break-inside:avoid;
    } 
</style>