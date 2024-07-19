<template>
    <DashboardLayout>
        <div class="flex items-center justify-between border-bot-only py-2 mb-4">
            <span class="text-[20px] font-bold text-gray-500">Questions Page </span> 
            <div class="relative">
                <input  v-model="searchField" type="text" placeholder="search" class="rounded-md">
                <svg class="absolute top-3 right-2 w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>
            </div> 
        </div>
       <!-- {{ problemSetFormattedData(problemSets[0].content) }}  -->
        <div v-if="$page.props.flash.error" > {{ errorMessage($page.props.flash.error) }}</div>
        <div v-if="problemSetForm.errors.content" > {{ errorMessage(problemSetForm.errors.content) }}</div>
        <div v-if="$page.props.flash.success">{{ successMessage($page.props.flash.success) }}</div>
             <!-- md screen-->
            <div class=" hidden md:block">
                <div class="grid grid-cols-10 items-center my-2 ">
                    <div class="col-span-1">
                        <label>Subject Code:</label>
                    </div>
                    
                    <div class="flex  col-span-8  gap-8 ">
                        
                        <select  v-model="selectedSubjectCode" @change="handleSubjectCodeChange" class=" border-blue-500 rounded-md ">
                            <option value="" selected hidden>
                                Subject Code 
                            </option>
                            <option v-for="code in data.subjectCodes" :value="code">
                                {{ code.name }}
                            </option>
                        </select>
                        <div class="flex flex-row  gap-3" > 
                            <span class="flex items-center"> Term: </span>   
                            <div class="flex items-center gap-4 hover:cursor-pointer  " :class="{'pointer-events-none ': allTermsSelected}">
                                <input v-model="prelim" type="checkbox" id="prelim" class="hover:cursor-pointer "  />
                                <label for="prelim" class="hover:cursor-pointer" >Prelim</label>
                            </div>
                            <div class="flex items-center gap-4 hover:cursor-pointer" :class="{'pointer-events-none ': allTermsSelected}">
                                <input v-model="midTerm" type="checkbox" id="midterm" class="hover:cursor-pointer" />
                                <label for="midterm" class="hover:cursor-pointer">Midterm</label>
                            </div>
                            <div class="flex items-center gap-4 hover:cursor-pointer" :class="{'pointer-events-none ': allTermsSelected}">
                                <input v-model="prefinal" type="checkbox" id="prefinal" class="hover:cursor-pointer" />
                                <label for="prefinal" class="hover:cursor-pointer">Prefinal</label>
                            </div>
                            <div class="flex items-center gap-4 hover:cursor-pointer" :class="{'pointer-events-none ': allTermsSelected}">
                                <input v-model="final" type="checkbox" id="final" class="hover:cursor-pointer" />
                                <label for="final" class="hover:cursor-pointer">Final</label>
                            </div>
                            
                            
                            
                        </div>
                    </div>
                    
                   
                    <div class="flex items-center gap-2">
                        <span>
                            Total Count: 
                        </span>
                        <span >
                            {{ questionTotalCoumt }}
                        </span>
                    </div>
                </div>
                
                <div class=" grid grid-cols-10 items-center mb-2 gap-2">
                    <div class="col-span-1">
                        <span class="flex flex-nowrap">
                            Description: 
                        </span>
                    </div>
                    
                    <div class="col-span-6 w-full" :class="{'col-span-8':!isAdminOrCoAdmin, 'col-span-6':isAdminOrCoAdmin}" >
                        <input type="text" :value="selectedSubjectCode.description" class="w-full bg-gray-100 rounded-md" disabled />
                        <span class="col-span-1">
                        </span>
                    </div>
                    <div class=" flex w-full gap-2 " :class="{'col-span-1':!isAdminOrCoAdmin, 'col-span-3':isAdminOrCoAdmin }"><!--andito ako 1-->
                        <button @click="handleProblemSetButtonClicked" v-if="user.role === 'admin'" type="button" class="text-center btn-primary p-2 w-full hover:cursor-pointer">+ Problem Set</button>
                        <button @click="handleAddQuestionModal" type="button" class="btn-primary p-2 w-full">+ New</button>
                    </div>
                    
                </div>
                
                
            </div>
             <!-- md screen-->

            <!-- mobile screen-->
            <div class="flex  flex-col md:hidden ">
                <div class="flex flex-col">
                    <div class="flex flex-col gap-3 mb-4">
                        <label>Subject Code:</label>

                        <select  v-model="selectedSubjectCode" @change="handleSubjectCodeChange" class=" border-blue-500 rounded-md ">
                            <option value="" selected hidden>
                                Subject Code
                            </option>
                            <option v-for="code in data.subjectCodes" :value="code">
                                {{ code.name }}
                            </option>
                        </select>

                        <div class="col-span-1">
                            <span class="flex flex-nowrap">
                                Description: 
                            </span>
                        </div>
                        
                        <div class="col-span-6 w-full" :class="{'col-span-8':!isAdminOrCoAdmin, 'col-span-6':isAdminOrCoAdmin}" >
                            <input type="text" :value="selectedSubjectCode.description" class="w-full bg-gray-100 rounded-md" disabled />
                            <span class="col-span-1">
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex  flex-col  gap-3 mb-4 ">
                        
                        <div class="flex flex-row  gap-3" > 
                            <span class="flex items-center"> Term: </span>   
                            <div class="flex items-center gap-4 hover:cursor-pointer  " :class="{'pointer-events-none ': allTermsSelected}">
                                <input v-model="prelim" type="checkbox" id="prelim" class="hover:cursor-pointer "  />
                                <label for="prelim" class="hover:cursor-pointer" >Prelim</label>
                            </div>
                            <div class="flex items-center gap-4 hover:cursor-pointer" :class="{'pointer-events-none ': allTermsSelected}">
                                <input v-model="midTerm" type="checkbox" id="midterm" class="hover:cursor-pointer" />
                                <label for="midterm" class="hover:cursor-pointer">Midterm</label>
                            </div>
                            <div class="flex items-center gap-4 hover:cursor-pointer" :class="{'pointer-events-none ': allTermsSelected}">
                                <input v-model="prefinal" type="checkbox" id="prefinal" class="hover:cursor-pointer" />
                                <label for="prefinal" class="hover:cursor-pointer">Prefinal</label>
                            </div>
                            <div class="flex items-center gap-4 hover:cursor-pointer" :class="{'pointer-events-none ': allTermsSelected}">
                                <input v-model="final" type="checkbox" id="final" class="hover:cursor-pointer" />
                                <label for="final" class="hover:cursor-pointer">Final</label>
                            </div>
                            
                          
                        </div>
                        <div class="flex items-center gap-2">
                            <span>
                                Total Count: 
                            </span>
                            <span >
                                {{ questionTotalCoumt }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class=" items-center mb-2 gap-2">
                    
                    <div class=" flex flex-col w-full " >
                        <button @click="handleProblemSetButtonClicked" v-if="user.role === 'admin'" type="button" class="text-center btn-primary p-2 w-full hover:cursor-pointer">+ Problem Set</button>
                        <button @click="handleAddQuestionModal" type="button" class="btn-primary p-2 w-full">+ New</button>
                    </div>
                    
                </div>
                
                
            </div>
            <!-- mobile screen-->
            <!--TABLE--> 
            <div v-if="!searchField">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-200 uppercase bg-blue-900 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No.</th>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Question</th>
                                <th scope="col" class="px-6 py-3">Term</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Author</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                
                            

                                
                                <th  v-if="isAdmin" scope="col" class="flex justify-center px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr v-for="(question ,index ) in getDisplayedQuestions() " :key="index" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                {{ getQuestionTotalCount(filteredQuestionByCode.length) }} 
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ index + 1 + (currentPage - 1) * itemsPerPage }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ question.id }}
                                </th>
                                <th scope="row" class="  px-2 py-4 font-medium text-gray-900 text-justify dark:text-white">
                                    {{ question.question }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ question.term }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ question.type }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ question.author.name }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ formatDate(question.created_at) }}
                                </th>
                            
                            
                                <td  v-if="$page.props.user.role === 'admin'" class="px-6 py-4 text-center ">
                                    <div  class="flex flex-col   lg:flex-row lg:justify-center  lg:space-x-4">
                                        <button @click="showQuestionInfoModal(question)" class="btn-primary p-2">Info</button>
                                        <button  @click="deleteConfirmation(question.id)" class=" btn-warning my-2">Delete </button>
                                        <Link :href="route('question.update.show',{id:question.id})" type="button" class="btn-success my-2">
                                            Update
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center mt-4 items-center gap-4">
                    <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"class="btn-pagination flex items-center gap-1">
                        <i class="pi pi-angle-double-left"></i> Prev
                    </button>
                    <div>Page {{ currentPage }} of {{ totalPages }}</div>
                    <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages" class="btn-pagination flex items-center gap-1">
                        Next <i class="pi pi-angle-double-right"></i> 
                    </button>
                </div>
            </div>

            <div v-else>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-200 uppercase bg-blue-900 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No.</th>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Question</th>
                                <th scope="col" class="px-6 py-3">Term</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Author</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                
                                

                                
                                <th  v-if="isAdmin" scope="col" class="flex justify-center px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody >
                            
                            <tr v-for="(question ,index ) in searchFieldData " :key="index" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                {{ getQuestionTotalCount(filteredQuestionByCode.length) }} 
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ index + 1 + (currentPageSearch - 1) * itemsPerPageSearch }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ question.id }}
                                </th>
                                <th scope="row" class="  px-2 py-4 font-medium text-gray-900 text-justify dark:text-white">
                                {{ question.question }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ question.term }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ question.type }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ question.author.name }}
                                </th>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ formatDate(question.created_at) }}
                                </th>
                            
                            
                                <td  v-if="$page.props.user.role === 'admin'" class="px-6 py-4 text-center ">
                                    <div  class="flex flex-col   lg:flex-row lg:justify-center  lg:space-x-4">
                                        <button @click="showQuestionInfoModal(question)" class="btn-primary p-2">Info</button>
                                        <button  @click="deleteConfirmation(question.id)" class=" btn-warning my-2">Delete </button>
                                        <Link :href="route('question.update.show',{id:question.id})" type="button" class="btn-success my-2">
                                            Update
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center gap-4 mt-4 items-center">
                    <button @click="goToPageSearch(currentPageSearch - 1)" :disabled="currentPageSearch === 1" class="btn-pagination flex items-center gap-1">
                        <i class="pi pi-angle-double-left"></i> Prev 
                    </button>
                    <div>Page {{ currentPageSearch }} of {{ totalPagesSearch }}</div>
                    <button @click="goToPageSearch(currentPageSearch + 1)" :disabled="currentPageSearch === totalPagesSearch" class="btn-pagination flex items-center gap-1">
                        Next <i class="pi pi-angle-double-right"></i>
                    </button>
                </div>
            </div>
        
            <!--TABLE-->
         
        

        <!--Question Info Modal-->
        <Dialog v-model:visible="infoModalOpen" modal  :style="{ width: '80rem' }">
            <!--header-->
            <div class="border  flex justify-between items-center bg-blue-900 p-4 pl-2 pr-4 rounded-tl-md rounded-tr-md">
                <div class="flex items-center gap-2">
                    <img :src="logoUrl" alt="error" class="w-20 h-20">
                    <span class="text-gray-100 text-xl">Question information</span>
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
            <!--header-->
           
            <!--BODY-->
            <div class=" border-t-0 rounded-bl-md rounded-br-md p-2">
                <div class="flex justify-between my-4 border-b-2 pb-2">
                    <div class="flex flex-col">
                        <div>
                            <span class="text-lg font-semibold"> Subject Code : </span>
                            <span>{{ selectedSubjectCode.name }} </span> 
                        </div>
                        <div>
                            <span class="text-lg font-semibold"> Description : </span>
                            <input class="" :placeholder="selectedSubjectCode.description"/>
                            
                        </div>
                        
                    </div>
                    <div class="flex flex-col">
                        <div class="pr-4">
                        <span class="text-lg font-semibold">Type: </span>  {{ viewQuestionInfo.type }}
                        </div>
                        <div class="pr-4">
                        <span class="text-lg font-semibold">Id:</span>  {{ viewQuestionInfo.id }}
                        </div>
                    </div>
                </div>
                

                
                <div class="flex flex-col lg:flex-row gap-2  h-full">
                     <!--Left box-->
                    <div class="w-full lg:w-[60%] border flex flex-col border-gray-900 p-2 rounded-md shadow-md">
                        <textarea class="w-full rounded-md" cols="40" rows="5" :value="viewQuestionInfo.question">
                        </textarea>
                        <div class="flex justify-center items-center lg:justify-start  mt-auto mb-2 ">
                            <div class="flex flex-col  justify-center items-center gap-2 mt-2  p-2 border border-gray-900 rounded-md shadow-md">
                                <img :src="viewQuestionInfo.attached_image ? optionUrl+viewQuestionInfo.attached_image:optionUrl+'no_image.png' " alt="error" class="border border-gray-400  shadow-md rounded-md max-h-[100px] max-w-[100px] mb-2 "/>
                                <span class="bg-gray-200 p-2 rounded-md" v-if="viewQuestionInfo.attached_image">
                                    Attached Image
                                </span>
                                <span v-else class="bg-gray-200 p-2 rounded-md text-red-500">No Attached Image</span>
                                
                            </div>
                        </div>
                    </div>
                    <!--Left box-->
                    {{ getCorrectAnswer(viewQuestionInfo.options) }} 
                    
                    
                    <!--right box-->
                    <div class="w-full lg:w-[40%] border flex flex-col rounded-md border-gray-900 px-2 bg-gray-100 shadow-md py-2">
                        <!--text option-->
                        <div v-if="textTab" >
                            
                            <div class="flex items-center gap-2 mb-2" v-for="option in viewQuestionInfo.options" :key="option.id">                    
                                <input type="radio" :name="`options`" :id="`option_${option.id}`" :value="option.id" v-model="correctAnswer" />
                                <textarea cols="30" rows="2" class="w-full" :value="option.option">  </textarea>
                            </div>
                        </div>
                        <!--text option-->

                        <!--image option-->
                        <div v-if="imageTab" class="grid grid-cols-2  " >
                            
                            <div class=" gap-2 mb-2 col-span-2 md:col-span-1 h-[140px]  flex items-center justify-center" v-for="option in viewQuestionInfo.options" :key="option.id">                    
                                <input type="radio" :name="`options`" :id="`option_${option.id}`" :value="option.id" v-model="correctAnswer" />
                                <div class="border p-2 border-gray-400 ">
                                    <img :src="optionUrl+option.option" class="max-h-[100px] max-w-[100px]"/>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                    
                    
                    <!--right box-->
                </div>
            </div>
            <!--BODY-->


           
            <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="username" class="font-semibold w-6rem"></label>
                
               
            </div>
            
           
        </Dialog>

        <!--Question Info Modal-->

        <!--ADD QUESTION MODAL-->

        <Dialog v-model:visible="addQuestionModal" modal :style="{ width: '80rem', height:'100vh' }">
            <ModalHeader :title="'New Question'"> 
                <form @submit.prevent="submit"> 
                    <!--FORM HEADEAR-->
                    <div class="flex flex-col md:flex-row md:justify-between w-full mb-2 mt-4">
                        <div class="w-full" >
                            <div class="flex w-full flex-col md:items-center  md:flex-row gap-2">
                                <span class="w-full font-semibold text-lg py-2 max-w-[150px] " >Subject Code : </span>
                                <input  class="rounded-md border-gray-400 bg-gray-200 shadow-md hover:cursor-pointer p-2"  :placeholder="selectedSubjectCode.name" disabled/>
                            </div>
                        </div>
                        <div class="w-full  flex  md:justify-end ">
                            <div class="flex w-full justify-end  flex-col md:items-center  md:flex-row gap-2 ">
                                <span class="font-semibold text-lg py-2  " >Term : </span>
                                <input v-model="form.term" class="rounded-md border-gray-400 bg-gray-200 shadow-md hover:cursor-pointer p-2"  :placeholder="selectedTerm" disabled/>
                            </div>
                        </div>
            
                    </div>   
                    
                    <div class=" flex w-full mb-4 flex-col lg:flex-row ">
                        <div class="flex w-full flex-col md:items-center  md:flex-row gap-2">
                            <span class="w-full font-semibold text-lg py-2 max-w-[150px] " >Description : </span>
                            <input  class="w-full rounded-md border-gray-400 bg-gray-200 shadow-md hover:cursor-pointer p-2"  :placeholder="selectedSubjectCode.description" disabled/>
                        </div>
                    </div>
                    <hr class="border-gray-400  mb-4">
                    <!--FORM HEADEAR-->

                    <div class="flex flex-col lg:flex-row gap-2  h-full">
                        <!--left container-->
                        <div class="w-full lg:w-[60%] border flex flex-col border-gray-900 p-2 rounded-md shadow-md">
                            <div>
                                <textarea v-model="form.question" cols="50" rows="5" class="w-full rounded-md" placeholder="Type your question here." required></textarea>
                            </div>
                            <div class="flex justify-center items-center lg:justify-start  mt-auto mb-2">
                                <div class="flex flex-col w-[160px] justify-center items-center gap-2"> 
                                    <img :src="attachedImagePreviewUrl || (imageUrl + 'image_attachment.png') " alt="Image attachment" class="border border-gray-400 rounded-md shadow-md max-w-[150px] max-h-[150px]"/>
                                    <input @change="handleAttachedImageChange"  type="file" ref="fileInput" accept=".jpg, .jpeg" hidden/>
                                    
                                    <button @click="triggerFileInput" type="button" class="bg-gray-200 hover:bg-gray-300 border border-gray-800 p-2 max-w-[150px] w-full rounded-md">Attach Image</button>
                                </div>
                                <span v-if="attachedImageValidator" class="text-red-500 ml-2">{{ attachedImageValidator }}</span>
                            </div>
                        </div>
                        <!--left container-->

                        <!--right container-->
                        <div class="w-full lg:w-[40%] border flex flex-col rounded-md border-gray-900 px-2 shadow-md">
                            <div class="flex flex-col flex-grow " >
                                <div v-if="textTab" class=" flex-grow border-b-2 border-gray-300 rounded-b-md shadow-sm p-2 ">
                                    <div v-for="(option,index) in options" :key="index" class="flex items-center gap-2 py-2 " >
                                        <input 
                                        type="radio" 
                                        :id="`option_${index+1}`" 
                                        name="options" 
                                        :value="index"
                                        v-model="selectedOption"
                                        @change="markCorrectOption(index)"
                                        :required="textTab"
                                        />
                                        <textarea v-model="options[index].option" cols="10" rows="2" class="w-full" :required="textTab"></textarea>
                                    </div>
                                </div>
                                <div v-if="imageTab" class=" flex-grow border-b-2 border-gray-300 rounded-b-md shadow-sm p-2">
                                    <div class="grid grid-cols-2 p-2">
                                        <div  class="col-span-2 lg:col-span-1 flex items-center justify-center   py-2 gap-2 ">
                                            <input v-model="selectedOption" type="radio"  id="image_opton_0" name="image_options" value="0" @change="markCorrectOption(0)"  />
                                            <div class="flex flex-col ml-2">
                                                <input  @change="handleImageOptionFileChange_0"  type="file" hidden ref="imageOption_0" accept=".jpg, .jpeg" />                               
                                                <img  :src="imageOptionURL_0 || imageUrl+'no_image.png'" alt="Image option" class="border border-gray-400 rounded-md shadow-md max-w-[122px] max-h-[122px]"/>
                                                <button @click="triggerImageOptionsFileInput(0)" type="button" class="bg-gray-200 hover:bg-gray-300 p-2 mt-2 border border-gray-800 rounded-md">Add Image</button>
                                                
                                            </div>
                                        </div>   
                                        
                                        <div  class="col-span-2 lg:col-span-1 flex items-center justify-center   py-2 gap-2 ">
                                            <input v-model="selectedOption" type="radio"  id="image_opton_1" name="image_options" value="1" accept=".jpg, .jpeg" @change="markCorrectOption(1)"/>
                                            <div class="flex flex-col ml-2">
                                                <input @change="handleImageOptionFileChange_1" type="file" hidden ref="imageOption_1"   />                               
                                                <img :src="imageOptionURL_1 || imageUrl+'no_image.png'" alt="Image option" class="border border-gray-400 rounded-md shadow-md max-w-[122px] max-h-[122px]"/>
                                                <button @click="triggerImageOptionsFileInput(1)" type="button" class="bg-gray-200 hover:bg-gray-300 p-2 mt-2 border border-gray-800 rounded-md">Add Image</button>
                                            </div>
                                        </div> 

                                        <div  class="col-span-2 lg:col-span-1 flex items-center justify-center   py-2 gap-2 ">
                                            <input v-model="selectedOption" type="radio"  id="image_opton_2" name="image_options" value="2"  accept=".jpg, .jpeg" @change="markCorrectOption(2)" />
                                            <div class="flex flex-col ml-2">
                                                <input @change="handleImageOptionFileChange_2" type="file" hidden ref="imageOption_2"   />                               
                                                <img :src="imageOptionURL_2 || imageUrl+'no_image.png'" alt="Image option" class="border border-gray-400 rounded-md shadow-md max-w-[122px] max-h-[122px]"/>
                                                <button @click="triggerImageOptionsFileInput(2)" type="button" class="bg-gray-200 hover:bg-gray-300 p-2 mt-2 border border-gray-800 rounded-md">Add Image</button>
                                            </div>
                                        </div> 

                                        <div  class="col-span-2 lg:col-span-1 flex items-center justify-center   py-2 gap-2 ">
                                            <input v-model="selectedOption" type="radio"  id="image_opton_3" name="image_options" value="3" accept=".jpg, .jpeg"  @change="markCorrectOption(3)"/>
                                            <div class="flex flex-col ml-2">
                                                <input @change="handleImageOptionFileChange_3" type="file" hidden ref="imageOption_3"   />                               
                                                <img :src="imageOptionURL_3 || imageUrl+'no_image.png'" alt="Image option" class="border border-gray-400 rounded-md shadow-md max-w-[122px] max-h-[122px]" />
                                                <button @click="triggerImageOptionsFileInput(3)" type="button" class="bg-gray-200 hover:bg-gray-300 p-2 mt-2 border border-gray-800 rounded-md">Add Image</button>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="ml-6 mb-2">
                                    <button type="button" @click="textTabClicked" class=" p-2 border border-t-0 border-gray-400 rounded-bl-lg  hover:font-bold hover:text-gray-400 " :class="{'bg-blue-800 text-white hover:text-gray-100 hover:font-normal': textTab, 'bg-gray-200 ': !textTab}">Text</button>
                                    <button type="button" @click="imageTabClicked" class="p-2 border border-t-0 border-gray-400 rounded-br-lg  hover:font-bold hover:text-gray-400 " :class="{'bg-blue-800 text-white hover:text-gray-100 hover:font-normal': imageTab, 'bg-gray-200 ': !imageTab}">Image</button>
                                    <div>
                                    <span class="text-red-500">{{ imageOptionValidator }}</span> 
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <!--right container-->
                    </div>
                    <div class="my-4">
                        <button type="submit" class="bg-blue-800 hover:bg-blue-700 rounded-md w-full p-2 text-gray-100"  :disabled="form.processing">Submit</button>
                    </div>
                </form>
            </ModalHeader>
            
        </Dialog>
        <!-- ADD QUESTION MODAL-->


        <!--problem set modal-->
        <Dialog v-model:visible="addProblemSetModal" modal :style="{ width: '60rem'}">
            <!--header-->
            <div class="border  flex justify-between items-center bg-blue-900 p-4 pl-2 pr-4 rounded-tl-md rounded-tr-md">
                <div class="flex items-center gap-2">
                    <img :src="logoUrl" alt="error" class="w-20 h-20">
                    <span class="text-gray-100 text-xl">Problem Set</span><!--andito ako 2-->
                </div>
                <div class=" hidden md:block  ">
                    <div class="flex justify-end text-[30px] text-gray-100">
                        {{ capitalizeFirstLetter(user.name) }}
                    </div>
                    <div class="flex justify-end text-gray-100">
                        {{ capitalizeFirstLetter(user.role) }} 
                    </div>
                </div>
                
            </div>
            <div class="w-full pt-6">
                <div class=" border-gray-400 relative">
                   
                    <form @submit.prevent="submitProbSet">
                        <div class="mb-2 w-full "> 
                            <div class="flex flex-col md:flex-row justify-between ">
                                <div class="flex gap-4 items-center justify-between ">
                                    <span class="text-[18px] font-semibold ">Subject Code: </span>
                                    <input type="text" :placeholder="selectedSubjectCode.name" disabled class="rounded-md bg-gray-200"/>
                                </div>
                                <div class="flex gap-4 items-center justify-between" >
                                    <span class="text-[18px] font-semibold ">Term: </span>
                                    <input type="text" :placeholder="selectedTerm[0]" disabled class="rounded-md bg-gray-200"/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="my-2">
                            <hr/>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="content" class="font-semibold text-[18px]">Content:</label>    
                            <textarea  v-model="problemSetForm.content" id="content" class="rounded-md w-full" rows="8" cols="50" placeholder="Enter text here" required> </textarea>
                        </div>

                        <button @click="handleResetButton" type="button" class="btn-primary p-2 w-full">Reset</button>
                        <button type="submit" class="btn-primary p-2 w-full" :disabled="form.processing">Submit</button>
                    </form>
                </div>
            </div>
            <!--header-->
        </Dialog>
         <!--problem set modal-->
         <!-- andito ako 44-->
         <div v-if="showData" class="hidden">
                {{ announcementMessage()}}
         </div>
         
       
         
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue';
import {ref,watch,onMounted, computed} from 'vue'
import { Link,usePage,router,useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2/dist/sweetalert2.js'
import ModalHeader from '../Components/ModalHeader.vue';
import { useConvertText } from '../Composables/useConvertText';
import { capitalizeFirstLetter } from '../Composables/capitalizeFirstLetter';


function getCorrectAnswer(options)
{
    options.forEach((option) => {
        if (option.isCorrect === 'true') {
            correctAnswer.value = option.id;
        }
    });
    // options.foreach((option)=>{
    //     if(option.isCorrect)
    //     {
    //         correctAnswer.value = option.id
    //     }
    // })
}
const isAdminOrCoAdmin = computed(()=>{
    if(user.role === 'admin' || user.role === 'co-admin')
    {
        return true
    }
    else
    {
        return false
    }
})
const { convertedText, convertText } = useConvertText();

const correctAnswer= ref('')
const logoUrl = ref('/storage/Images/ncstLogo.png');
const optionUrl = ref('/storage/Images/');
const searchField = ref('')

const currentPageSearch     = ref(1)
const itemsPerPageSearch    = ref(2)
const filteredSearchData = ref([])
const searchFieldData = computed(() => { 
    
    let searchTerm = searchField.value.toLowerCase().trim()
    
    let startSearch = (currentPageSearch.value-1) * itemsPerPageSearch.value
    let endSearch   = startSearch + itemsPerPageSearch.value

    if(hasFilteredTerm.value)
    {
        
        filteredSearchData.value = filteredQuestionByTerm.value.filter(question =>{
            return question.term.toLowerCase().includes(searchTerm) || question.type.toLowerCase().includes(searchTerm) || question.author.name.toLowerCase().includes(searchTerm) || question.question.toLowerCase().includes(searchTerm)
        })
        console.log('filtered search data length: ')
        console.log(filteredSearchData.value.length)
        return filteredSearchData.value.slice(startSearch,endSearch)
        
    }
    else
    {
        filteredSearchData.value = filteredQuestionByCode.value.filter(question =>{
            return question.term.toLowerCase().includes(searchTerm) || question.type.toLowerCase().includes(searchTerm) || question.author.name.toLowerCase().includes(searchTerm) || question.question.toLowerCase().includes(searchTerm)
        })
        console.log('filtered search data length: ')
        console.log(filteredSearchData.value.length)
        return filteredSearchData.value.slice(startSearch,endSearch)
        
    }
     
    
})

const totalPagesSearch = computed(() => {
  
  const questionsToPaginate = filteredSearchData.value
  console.log( 'total pages : '+ Math.ceil(questionsToPaginate.length / itemsPerPageSearch.value))
  return Math.ceil(questionsToPaginate.length / itemsPerPageSearch.value);
});
const goToPageSearch = (page) => {
  if (page >= 1 && page <= totalPagesSearch.value) {
    currentPageSearch.value = page;
  }
};



const data = defineProps({
    subjectCodes:Array,
    problemSets:Array,
    announcements:Array,
})

const user = usePage().props.user
const isAdmin = ref(false);


onMounted(()=>{ // andito ako mounted
    if(user.role === 'admin')
    {
        isAdmin.value = true;
    }
    
    


    successAlertCounter.value = 0 
    selectedTerm.value = []
    if(localStorage.getItem('selectedTerm'))
    {
        selectedTerm.value = JSON.parse(localStorage.getItem('selectedTerm'));

        selectedTerm.value.forEach((term)=>{
            switch(term)
            {
                case 'prelim':
                    prelim.value = true
                    updateFilteredArray()
                    break
                case 'mid-term':
                    midTerm.value = true
                    updateFilteredArray()
                    break
                case 'pre-final':
                    prefinal.value = true
                    updateFilteredArray()
                    break
                case 'final':
                    final.value = true
                    updateFilteredArray()
            }
        })
    }   
    
    if(localStorage.getItem('subjectCode'))
    {
        let id = JSON.parse(localStorage.getItem('subjectCode'))
        
        if(data.subjectCodes.length)
        {
            data.subjectCodes.forEach((val)=>{
                if(val.id === id)
                {
                    selectedSubjectCode.value = val
                    return;
                }
            } )
        }
        
    }
    checkDataDisplay();

})



function formatDate(dateString){
    const date = new Date(dateString)

    const options = { year: 'numeric', month: 'long', day: 'numeric' };

    return date.toLocaleDateString('en-US', options);
}
const infoModalOpen = ref(false);
const viewQuestionInfo = ref('');
const showQuestionInfoModal = (question)=>{
    infoModalOpen.value = !infoModalOpen.value
    viewQuestionInfo.value = question

    if(question.type === 'text')
    {
        
        textTab.value = true;
        imageTab.value = false;
    }
    
    if(question.type === 'image')
    {
        imageTab.value = true
        textTab.value = false
    }
    
}

watch(infoModalOpen,(val)=>{
    if(!val){
        viewQuestionInfo.value =''
    }
})

const prelim = ref(false);
const midTerm = ref(false);
const prefinal = ref(false);
const final = ref(false);

const selectedSubjectCode = ref('');
const currentSubjectCodes = ref('')
const filteredQuestionByCode = ref([]); 
// Define computed property to hold filtered array
const filteredQuestionByTerm = ref([]);
const hasFilteredTerm = ref(false);

watch(selectedSubjectCode, (val)=>{
    
    filteredQuestionByCode.value = val.questions
    
    questionTotalCoumt.value = 0

})

function handleSubjectCodeChange(event)
{
    localStorage.setItem('subjectCode',selectedSubjectCode.value.id);
}
const myArray = [
    {
        name: 'myPrelim',
        term: 'prelim',
    },
    {
        name: 'myMidterm',
        term: 'mid-term',
    },
    {
        name: 'myPrelim',
        term: 'pre-final',
    },
    {
        name: 'myFinal',
        term: 'final',
    }
]


// Watcher for each checkbox
const selectedTerm = ref([]);
const selectedTermValidator = ref('');
watch(prelim, (val)=>{
    if(val)
    {
        if(!selectedTerm.value.includes('prelim'))
        {
            selectedTerm.value.push('prelim') 
            localStorage.setItem('selectedTerm',JSON.stringify(selectedTerm.value))
        }
    }

    if(!val)
    {
        
       selectedTerm.value =  selectedTerm.value.filter(val => val !== 'prelim');
       localStorage.setItem('selectedTerm',JSON.stringify(selectedTerm.value))
    }
    
    updateFilteredArray()
});
watch(midTerm, (val)=>{

    if(val)
    {
        if(!selectedTerm.value.includes('mid-term'))
        {
            selectedTerm.value.push('mid-term')
            localStorage.setItem('selectedTerm',JSON.stringify(selectedTerm.value))
        }
    }
    if(!val)
    {
        
       selectedTerm.value =  selectedTerm.value.filter(val => val !== 'mid-term');
       localStorage.setItem('selectedTerm',JSON.stringify(selectedTerm.value))
       
    }

    updateFilteredArray()
});

watch(prefinal, (val)=>{
    if(val)
    {
        if(!selectedTerm.value.includes('pre-final'))
        {
            selectedTerm.value.push('pre-final')
            localStorage.setItem('selectedTerm',JSON.stringify(selectedTerm.value))
        }
        
    }
    if(!val)
    {
       selectedTerm.value =  selectedTerm.value.filter(val => val !== 'pre-final');
       localStorage.setItem('selectedTerm',JSON.stringify(selectedTerm.value))
       
    }
    updateFilteredArray()
} );
watch(final, (val)=>{
    if(val)
    {
        if(!selectedTerm.value.includes('final'))
        {
            selectedTerm.value.push('final')
            localStorage.setItem('selectedTerm',JSON.stringify(selectedTerm.value))
        }
        
    }
    if(!val)
    {
       selectedTerm.value =  selectedTerm.value.filter(val => val !== 'final');
       localStorage.setItem('selectedTerm',JSON.stringify(selectedTerm.value))
       
    }

    updateFilteredArray()
});


// Function to filter myArray based on checkbox values
function updateFilteredArray() {
        
        if(prelim.value || midTerm.value || prefinal.value || final.value)
        {
            
            
            filteredQuestionByTerm.value = filteredQuestionByCode.value.filter(item => {
                return (
                    (prelim.value && item.term === 'prelim') ||
                    (midTerm.value && item.term === 'mid-term') ||
                    (prefinal.value && item.term === 'pre-final') ||
                    (final.value && item.term === 'final')
                );
            });

            hasFilteredTerm.value = true; 
        }
        else
        {
            hasFilteredTerm.value = false;
            filteredQuestionByTerm.value = [];
        }
    
}


// Initial call to updateFilteredArray
updateFilteredArray();


const allTerm = ref(false);
const allTermsSelected = ref(false)
watch(allTerm,(val)=>{
        
        prelim.value = val
        midTerm.value = val
        prefinal.value = val
        final.value = val
        allTermsSelected.value = val
})
const questionTotalCoumt = ref('')
function getQuestionTotalCount(count){
    questionTotalCoumt.value = count
}


const currentPage = ref(1);
const itemsPerPage = ref(5);
function getDisplayedQuestions(){ 

    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;

    const questionsToPaginate = hasFilteredTerm.value ? filteredQuestionByTerm.value : filteredQuestionByCode.value;

    return questionsToPaginate.slice(start, end);
}

const totalPages = computed(() => {
  const questionsToPaginate = hasFilteredTerm.value ? filteredQuestionByTerm.value : filteredQuestionByCode.value;
  return Math.ceil(questionsToPaginate.length / itemsPerPage.value);
});
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const textTab = ref(true)
const imageTab = ref(false)

// function handleTabMenu(tab)
// {
//     if(tab === 'text')
//     {
        
//         textTab.value = true;
//         imageTab.value = false;
//     }
    
//     if(tab === 'image')
//     {
//         imageTab.value = true
//         textTab.value = false
//     }
// }


// sweet alert logic

const deleteConfirmation = (questionId)=> 
    { 
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick:false,
            allowEscapeKey:false,
            customClass: {
                popup: 'swal-popup-custom' // Adding a custom class
            }
            }).then((result) => {
                if(result.isConfirmed)
                {
                    const deleteUrl = route('questions.delete',{id: questionId })
                    
                    router.delete(deleteUrl);
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

    const page = usePage()
    const tempSuccessMessage = computed(()=> page.props.flash.success)
    const displayed = ref(false);
    
    watch(tempSuccessMessage,(newVal)=>{
        convertText(newVal)
        console.log(convertedText)
        successMessage(convertedText.value);
    }  )

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
            
                location.reload()
            //    if(page.props.flash.action)
            //    {
            //         location.reload();
            //    }
                
            }
        })
     
        
    }
    
    // old errorMessage
    // function errorMessageOld(message) {
    //     Swal.fire({
    //         icon: "error",
    //         title: "Oops...",
    //         text: message + '!',
    //         allowOutsideClick:false,
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             location.reload();
    //         }
    //     })
    // }
    
    function errorMessage(message) {
        let localError = localStorage.getItem('error');
        if(localError !== message)
        {
            let msg = message.split('.');
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: msg[0],
                allowOutsideClick:false,
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.setItem('error',message);
                }
            })
        }
        
    }

    function errorMessage2(message) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: message,
            allowOutsideClick:false,
        })
    }

    

    // add question logic *************************************
    const imageUrl  = ref('/storage/images/');
    const addQuestionModal = ref(false)
    const handleAddQuestionModal = ()=>{
        if(selectedSubjectCode.value === '')
        {
            selectedTermValidator.value = 'Subject Code is required.'
            errorMessage2(selectedTermValidator.value)
            return
        }

        if(selectedTerm.value.length < 1)
        {
            selectedTermValidator.value = 'Term is required.'
            errorMessage2(selectedTermValidator.value)
            return
        }

        if(selectedTerm.value.length === 1)
        {
            selectedTermValidator.value = ''
            addQuestionModal.value = true
        }
        else
        {
            selectedTermValidator.value = 'Multiple terms are not allowed.'
            errorMessage2(selectedTermValidator.value)
        }
    }

    // add problem set logic ***************************** start
    const addProblemSetModal = ref(false)
    const problemSetContent = ref('')
    const problemSetForm = useForm({
        content:'',
        subject_code_id:'',
        term:'',
    })
    
    const handleProblemSetButtonClicked = ()=>{ // andito ako 3
        
        if(selectedTerm.value.length < 1)
        {
            selectedTermValidator.value = 'Term is required.'
            errorMessage2(selectedTermValidator.value)
            return
        }

        if(selectedTerm.value.length === 1)
        {
            findProblemSet(data.problemSets)
            selectedTermValidator.value = ''
            addProblemSetModal.value = true
        }
        else
        {
            selectedTermValidator.value = 'Multiple terms are not allowed.'
            errorMessage2(selectedTermValidator.value)
        }
        
    }
    
    const handleResetButton = ()=>{
        problemSetForm.content = ''
        
    }

    const submitProbSet = ()=>{
        problemSetForm.term = selectedTerm.value[0]
        problemSetForm.subject_code_id = selectedSubjectCode.value.id
        
        problemSetForm.post(route('problem.set.store'),{
            preserveScroll:true,
            preserveState:true,
            onSuccess: ()=> addProblemSetModal.value = false,
            onError: ()=> addProblemSetModal.value = false,
        })
    }

    function findProblemSet(data)
    {
        let tempData = data.filter((set)=> set.term.toString() === selectedTerm.value.toString() && set.subject_code_id === selectedSubjectCode.value.id)
        //console.log(tempData.length)
        problemSetForm.content = tempData.length ? tempData[0].content : ''
        return 
    }
     // add problem set logic ***************************** end
    const form = useForm({
        question:'',
        type:'text',
        term:selectedTerm,
        attached_image:'',
        subject_code_id:'',
        author_id:user.id,
        options:[],
       
    })

    //watchers
    watch(selectedSubjectCode,(code)=>{
        form.subject_code_id = code.id
        updateFilteredArray()
    })

 

const selectedTab = ref('text');
const imageTabClicked = ()=>{

    if(selectedTab.value !== 'image')
    {
        clearOptions();
        clearImageUrls();
    }

    selectedTab.value = 'image'
    imageTab.value = true
    textTab.value = false
    form.type = 'image'
    
}

const textTabClicked = ()=>{

    if(selectedTab.value !== 'text')
    {
        clearOptions();
        clearImageUrls();
    }

    selectedTab.value = 'text';
    textTab.value = true
    imageTab.value = false
    form.type = 'text'


    
}
const fileInput = ref('')





//image logic
const triggerFileInput = ()=>{
    fileInput.value.click();
   
}



const handleAttachedImageChange = (event) => {
   
    const file = event.target.files[0];
    

    if(file)
    {
        if(!['image/jpeg'].includes(file.type))
        {
            attachedImageValidator.value = 'Only JPG files are allowed.'
            form.attached_image = ''
            return;
        }
        
        if(file.size > 1024 * 1024)
        {
            attachedImageValidator.value = 'File size should be less than 1MB.'
            form.attached_image = '';
            return;
        }

        form.attached_image = file;
        attachedImageValidator.value = ''
        attachedImagePreviewUrl.value = URL.createObjectURL(file);
        console.log('attached image: '+form.attached_image);
        console.log('size: '+file.size)
    }
    else
    {
        console.log('no file selected');
        attachedImageValidator.value = 'No file selected.'
    }
    
};


// validators
const attachedImageValidator = ref('');
const textOptionsValidator = ref('');
const imageOptionValidator = ref('');
const hasError = ref(false);

function imageFileValidator(file,index){
    
    if(file)
    {
        if(!['image/jpeg'].includes(file.type))
        {
            imageOptionValidator.value = 'Only JPG files are allowed.'
            form.attached_image = ''
            return;
        }
        
        if(file.size > 1024 * 1024)
        {
            imageOptionValidator.value = 'File size should be less than 1MB.'
            form.attached_image = '';
            return;
        }

        options.value[index].option = file;
        imageOptionValidator.value = ''

        switch(index)
        {
            case 0:
                imageOptionURL_0.value = URL.createObjectURL(file)
                break;

            case 1:
                imageOptionURL_1.value = URL.createObjectURL(file);
                break;
            
            case 2:
                imageOptionURL_2.value = URL.createObjectURL(file);
                break;
            
            case 3:
                imageOptionURL_3.value = URL.createObjectURL(file);
                break;

            default:
                break;
        }
        
        
    }
    else
    {
        console.log('no file selected');
        attachedImageValidator.value = 'No file selected.'
    }

}

//image urls
const attachedImagePreviewUrl = ref('');
const imageOptionURL_0 = ref('');
const imageOptionURL_1 = ref('');
const imageOptionURL_2 = ref('');
const imageOptionURL_3 = ref('');

// options 
const selectedOption = ref(null)
const options = ref([
    {
        option:'',
        isCorrect:'false',
    },
    {
        option:'',
        isCorrect:'false',
    },
    {
        option:'',
        isCorrect:'false',
    },
    {
        option:'',
        isCorrect:'false',
    }
])

const terms = ([
    'prelim',
    'mid-term',
    'pre-final',
    'final',
])

function clearOptions(){
    const temp = ref([])

    options.value.forEach((option)=>{
        option.option = ''
        option.isCorrect = 'false'
    })
    selectedOption.value = ''
}

function clearImageUrls()
{
    imageOptionURL_0.value = ''
    imageOptionURL_1.value = ''
    imageOptionURL_2.value = ''
    imageOptionURL_3.value = ''
}

const markCorrectOption = (index) => {
    options.value.forEach((option, i) => {
        option.isCorrect = (i === index);
    });
};


const imageOption_0 = ref(null);
const imageOption_1 = ref(null);
const imageOption_2 = ref(null);
const imageOption_3 = ref(null);
const triggerImageOptionsFileInput = (index)=>{
    
    switch(index)
    {
        case 0:
            
            imageOption_0.value.click()
            break;

        case 1:
            imageOption_1.value.click()
            break;
        
        case 2:
            imageOption_2.value.click()
            break;

        case 3:
            imageOption_3.value.click()
            break;

        default:
            break;
    }
    
    
}

const handleImageOptionFileChange_0 = (event) => {
    
    const file = event.target.files[0]

    if(file)
    {
        imageFileValidator(file,0);
        options.value[0].option = file
    }
   
}
const handleImageOptionFileChange_1 = (event) => {
    
    const file = event.target.files[0]

    if(file)
    {
        imageFileValidator(file,1);
        options.value[1].option = file
    }
   
}
const handleImageOptionFileChange_2 = (event) => {
    
    const file = event.target.files[0]

    if(file)
    {
        imageFileValidator(file,2);
        options.value[2].option = file
    }
   
}
const handleImageOptionFileChange_3 = (event) => {
    
    const file = event.target.files[0]

    if(file)
    {
        imageFileValidator(file,3);
        options.value[3].option = file
    }
   
}






// form submission

const submit = ()=>{
    // validate text options if form.type = 'text'
    if(form.type === 'text')
    {
        form.options = options
        submitConfirmation()
    }
    
    if(form.type === 'image')
    {
       
        if(imageOptionURL_0.value === '' || imageOptionURL_1.value === '' || imageOptionURL_2.value === '' || imageOptionURL_3.value === '')
        {
            
            imageOptionValidator.value = 'Fill out all image options.'
            hasError.value = false
        }
        else
        {
            if(selectedOption.value === null || selectedOption.value === '')
            {
                imageOptionValidator.value = "Please select correct answer."
                hasError.value = false
            }
            else
            {
                
                imageOptionValidator.value = ''
                hasError.value = true
            }
        }
        
        

        if(hasError.value)
        {
            form.options = options
            submitConfirmation();
        }
    }
}


// sweet alert logic
const successAlertCounter = ref('')
const submitConfirmation = ()=> 
    { 
        
        addQuestionModal.value = false
        successAlertCounter.value++
        Swal.fire({
            title: "Are you sure?",
            text: "Save new question confirmation",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, save it!",
            allowOutsideClick:false,
            allowEscapeKey:false,
            }).then((result) => {
                if(result.isConfirmed)
                {
                    console.log(form)
                    
                    form.term = selectedTerm.value[0]
                    form.post(route('question.store'),{
                        preserveState:true,
                        preserveScroll:true,
                    })

                    displayed.value = false
                }

                if(result.isDismissed)
                {
                    Swal.fire({
                        title:'Canceled',
                        text:'Your action was canceled!',
                        icon:'error',
                        confirmButtonColor: '#3085d6',
                    }).then((res)=>{
                       
                        addQuestionModal.value = true
                    })
                }
        });
    }  

    
    
    
function problemSetFormattedData(data)
{
    return  data.replace(/\r\n/, '<br>').replace(/\n/, '<br>');
}

// announcements logic **********************************8
const userAnnouncement = ref(data.announcements.filter((announcement)=>announcement.user_id === user.id))

// A flag to determine whether to show the data
const showData = ref(false);

// The key used in localStorage to track if the data has been displayed
const displayDataKey = 'dataDisplayedOnce';

const checkDataDisplay = () => {
    
  if (!localStorage.getItem(displayDataKey)) { 
        // Data has not been displayed, set flag to true

        showData.value = true;
        // Set the flag in localStorage
        localStorage.setItem(displayDataKey, 'true');
  } else {
    // Data has already been displayed, set flag to false
    showData.value = false;
  }
  
};

const announcementMessage = async (announcements) => {
  for (const announcement of userAnnouncement.value) {
        await Swal.fire({
            title: 'Announcement',
            text: announcement.content,
            icon: 'info',
            showCloseButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton:false,
            didOpen: () => {
            // This function runs when the modal opens
                const closeButton = Swal.getCloseButton();
                if (closeButton) {
                    closeButton.blur(); // Remove focus from close button
                }
            }
        });
  }
   showData.value = false
};
</script>