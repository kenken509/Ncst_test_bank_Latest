<template>
    <div class="flex min-h-screen ">
        <!-- sidebar -->
        <div 
            class="
                bg-blue-950 
                text-blue-100 
                w-64 
                fixed 
                inset-y-0 
                left-0 
                transform 
                transition 
                duration-200 
                ease-in-out
                z-50
                "
            :class="{'-translate-x-full': !showSideBar, 'translate-x-0': showSideBar}"
        >
            <Link href="/" class="flex items-center py-3 pl-2 space-x-2">
                <img :src="logoUrl" class="w-10 h-10"></img>
                <span class="text-2xl font-extrabold text-white">Test Bank</span>
            </Link>
            <nav>
                <Link v-if="user.role==='admin' || user.role==='co-admin'" :href="route('dashboard.show')" class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900 mt-2">
                    <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z" clip-rule="evenodd"/>
                    </svg>
                    <span>Dashboard</span>
                </Link>
                <div v-if="user.role==='admin'">
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900" >
                        <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M18.458 3.11A1 1 0 0 1 19 4v16a1 1 0 0 1-1.581.814L12 16.944V7.056l5.419-3.87a1 1 0 0 1 1.039-.076ZM22 12c0 1.48-.804 2.773-2 3.465v-6.93c1.196.692 2 1.984 2 3.465ZM10 8H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6V8Zm0 9H5v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3Z" clip-rule="evenodd"/>
                        </svg>

                        
                        <div  class="w-full">
                            <button @click="announcmentToggleMenu" class="flex w-full items-center justify-between pr-8 ">
                                Announcement 
                                <svg v-if="showAnnouncmentMenu" class="w-6 h-6  text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                </svg>
                                <svg v-else class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                                
                            </button>
                            
                        </div>
                    </div>
                    <ul 
                        v-if="showAnnouncmentMenu"
                        
                    >
                        
                        <Link v-if="user.role === 'admin'" :href="route('announcement.show')">
                            <li @click="toggleBackground('ann1')" :class="{'bg-blue-900':clickedItem == 'ann1'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M18.458 3.11A1 1 0 0 1 19 4v16a1 1 0 0 1-1.581.814L12 16.944V7.056l5.419-3.87a1 1 0 0 1 1.039-.076ZM22 12c0 1.48-.804 2.773-2 3.465v-6.93c1.196.692 2 1.984 2 3.465ZM10 8H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6V8Zm0 9H5v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3Z" clip-rule="evenodd"/>
                                </svg>


                                Announcement
                            </li>
                        </Link>
                        <!-- <Link v-if="user.role === 'admin'" :href="route('announcement.add')">
                            <li @click="toggleBackground('ann2')" :class="{'bg-blue-900':clickedItem == 'ann2'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                                </svg>
                                Add
                            </li>
                        </Link> -->
                       
                        


                        <!-- <li @click="toggleBackground('dep3')" :class="{'bg-blue-900':clickedItem == 'dep3'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                            <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0Zm-2 9a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1Zm13-6a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-4Z" clip-rule="evenodd"/>
                            </svg>
                            Delete
                        </li>
                        <li @click="toggleBackground('dep4')" :class="{'bg-blue-900':clickedItem == 'dep4'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                            <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                            </svg>

                            Update
                        </li> -->
                    </ul>
                </div>
                <!-- DEPARTMENT MANAGEMENT -->
                <div v-if="user.role==='admin' || user.role==='co-admin'">
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900" >
                        <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M5.005 10.19a1 1 0 0 1 1 1v.233l5.998 3.464L18 11.423v-.232a1 1 0 1 1 2 0V12a1 1 0 0 1-.5.866l-6.997 4.042a1 1 0 0 1-1 0l-6.998-4.042a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1ZM5 15.15a1 1 0 0 1 1 1v.232l5.997 3.464 5.998-3.464v-.232a1 1 0 1 1 2 0v.81a1 1 0 0 1-.5.865l-6.998 4.042a1 1 0 0 1-1 0L4.5 17.824a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                            <path d="M12.503 2.134a1 1 0 0 0-1 0L4.501 6.17A1 1 0 0 0 4.5 7.902l7.002 4.047a1 1 0 0 0 1 0l6.998-4.04a1 1 0 0 0 0-1.732l-6.997-4.042Z"/>
                        </svg>
                        
                        <div class="w-full">
                            <button @click="depMgmtToggleMenu" class="flex w-full items-center justify-between pr-8 ">
                             Department Mgmt 
                                <svg v-if="showDepMgmtMenu" class="w-6 h-6  text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                </svg>
                                <svg v-else class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                                
                            </button>
                            
                        </div>
                    </div>
                    <ul 
                        v-if="showDepMgmtMenu"
                        
                    >
                        <Link :href="route('departments.show')">
                            <li @click="toggleBackground('dep1')" :class="{'bg-blue-900':clickedItem == 'dep1'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                            
                                <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M5.005 10.19a1 1 0 0 1 1 1v.233l5.998 3.464L18 11.423v-.232a1 1 0 1 1 2 0V12a1 1 0 0 1-.5.866l-6.997 4.042a1 1 0 0 1-1 0l-6.998-4.042a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1ZM5 15.15a1 1 0 0 1 1 1v.232l5.997 3.464 5.998-3.464v-.232a1 1 0 1 1 2 0v.81a1 1 0 0 1-.5.865l-6.998 4.042a1 1 0 0 1-1 0L4.5 17.824a1 1 0 0 1-.5-.866v-.81a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                                    <path d="M12.503 2.134a1 1 0 0 0-1 0L4.501 6.17A1 1 0 0 0 4.5 7.902l7.002 4.047a1 1 0 0 0 1 0l6.998-4.04a1 1 0 0 0 0-1.732l-6.997-4.042Z"/>
                                </svg>
                                Departments
                            </li>
                        </Link>
                        <Link v-if="user.role === 'admin'" :href="route('department.add')">
                            <li @click="toggleBackground('dep2')" :class="{'bg-blue-900':clickedItem == 'dep2'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                                </svg>
                                
                                Add
                            </li>
                        </Link>
                       
                        


                        <!-- <li @click="toggleBackground('dep3')" :class="{'bg-blue-900':clickedItem == 'dep3'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                            <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 8 0 4 4 0 0 1-8 0Zm-2 9a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1Zm13-6a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-4Z" clip-rule="evenodd"/>
                            </svg>
                            Delete
                        </li>
                        <li @click="toggleBackground('dep4')" :class="{'bg-blue-900':clickedItem == 'dep4'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                            <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                            </svg>

                            Update
                        </li> -->
                    </ul>
                </div>
                <!-- DEPARTMENT MANAGEMENT -->

                <!-- DIVISION MANAGEMENT -->
                <div v-if="user.role==='admin' || user.role==='co-admin'">
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900" >
                        <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.5 3a3.5 3.5 0 0 0-3.456 4.06L8.143 9.704a3.5 3.5 0 1 0-.01 4.6l5.91 2.65a3.5 3.5 0 1 0 .863-1.805l-5.94-2.662a3.53 3.53 0 0 0 .002-.961l5.948-2.667A3.5 3.5 0 1 0 17.5 3Z"/>
                        </svg>  


                        <div class="w-full">
                            <button @click="divMgmtToggleMenu" class="flex w-full items-center justify-between pr-8 ">
                             Division Mgmt 
                                <svg v-if="showDivMgmtMenu" class="w-6 h-6  text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                </svg>
                                <svg v-else class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>

                            </button>
                            
                        </div>
                    </div>
                    <ul 
                        v-if="showDivMgmtMenu"
                        
                    >
                        <Link  :href="route('division.show')">
                            <li @click="toggleBackground('div1')" :class="{'bg-blue-900':clickedItem == 'div1'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                            
                                <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.5 3a3.5 3.5 0 0 0-3.456 4.06L8.143 9.704a3.5 3.5 0 1 0-.01 4.6l5.91 2.65a3.5 3.5 0 1 0 .863-1.805l-5.94-2.662a3.53 3.53 0 0 0 .002-.961l5.948-2.667A3.5 3.5 0 1 0 17.5 3Z"/>
                                </svg>

                                Divisions
                            </li>
                        </Link>
                        <Link  v-if="$page.props.user.role === 'admin'" :href="route('division.add')">
                            <li @click="toggleBackground('div2')" :class="{'bg-blue-900':clickedItem == 'div2'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                                </svg>

                                Add
                            </li>
                        </Link>
                        
                       
                    </ul>
                </div>
                <!-- DIVISION MANAGEMENT -->


                <!-- USER MANAGEMENT MENU-->
                <div v-if="user.role==='admin' || user.role==='co-admin'">
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900" >
                        <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd"/>
                        </svg>
                        <div class="w-full">
                            <button @click="userManagementToggleMenu" class="flex w-full items-center justify-between pr-8 ">
                                Users Mgmt 
                                <svg v-if="showUserManagementMenu" class="w-6 h-6  text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                </svg>
                                <svg v-else class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <ul 
                        v-if="showUserManagementMenu"
                        
                    >
                        <Link :href="route('users.show')">
                            <li @click="toggleBackground('user1')" :class="{'bg-blue-900':clickedItem === 'user1'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd"/>
                                </svg>
                                Users
                            </li>
                        </Link>
                        <Link v-if="user.role === 'admin'" :href="route('user.add')">
                            <li @click="toggleBackground('user2')" :class="{'bg-blue-900':clickedItem === 'user2'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                                </svg>
                                Add
                            </li>
                        </Link>
                        
                    </ul>
                </div>
                <!-- USER MANAGEMENT MENU-->

                <!-- SUBJECT CODE MGMT-->
                <div v-if="user.role==='admin' || user.role==='co-admin'">
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900" >
                        <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 8-4 4 4 4m8 0 4-4-4-4m-2-3-4 14"/>
                        </svg>
                        
                        <div class="w-full">
                            <button  @click="subjectCodesToggleMenu" class="flex w-full items-center justify-between pr-8 ">
                                Subject Codes
                                <svg v-if="showSubjectCodesMenu" class="w-6 h-6  text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                </svg>
                                <svg v-else class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                        
                    </div>
                    <ul 
                        v-if="showSubjectCodesMenu"        
                        >
                            <Link :href="route('subject.codes.show')">
                                <li @click="toggleBackground('subjectCode1')" :class="{'bg-blue-900':clickedItem === 'subjectCode1'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                    <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd"/>
                                    </svg>
                                    Codes
                                </li>
                            </Link>
                            <Link v-if="user.role==='admin'" :href="route('subject.codes.add.show')">
                                <li @click="toggleBackground('subjectCode2')" :class="{'bg-blue-900':clickedItem === 'subjectCode2'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                    <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                                    </svg>
                                    Add
                                </li>
                            </Link>
                            
                        </ul>
                </div>
                <!-- SUBJECT CODE MGMT-->

                <!--Question bank-->
                <div>
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900" >
                        <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.008-3.018a1.502 1.502 0 0 1 2.522 1.159v.024a1.44 1.44 0 0 1-1.493 1.418 1 1 0 0 0-1.037.999V14a1 1 0 1 0 2 0v-.539a3.44 3.44 0 0 0 2.529-3.256 3.502 3.502 0 0 0-7-.255 1 1 0 0 0 2 .076c.014-.398.187-.774.48-1.044Zm.982 7.026a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2h-.01Z" clip-rule="evenodd"/>
                        </svg>


                        <div class="w-full">
                            <button  @click="questionBankToggleMenu" class="flex w-full items-center justify-between pr-8 ">
                                Question Bank
                                <svg v-if="showUserManagementMenu" class="w-6 h-6  text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                </svg>
                                <svg v-else class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                       
                    </div>
                    <ul 
                        v-if="showQuestionBankMenu"        
                        >
                            <Link :href="route('questions.show')">
                                <li @click="toggleBackground('questionBank1')" :class="{'bg-blue-900':clickedItem === 'questionBank1'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                    <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd"/>
                                    </svg>
                                    Questions
                                </li>
                            </Link>
                            <!-- <Link  :href="route('question.add')">
                                <li @click="toggleBackground('questionBank2')" :class="{'bg-blue-900':clickedItem === 'questionBank2'}" class="flex pl-10 items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                    <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                                    </svg>
                                    Add
                                </li>
                            </Link> -->
                            
                        </ul>
                </div>
                <!--question bank-->

                <!-- test gen-->
                
                <div v-if="user.role==='admin'">
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900" >
                        <i class="pi pi-book"></i>

                        <div class="w-full">
                            <button  @click="toggleTestGeneratorMenu" class="flex w-full items-center justify-between pr-8 ">
                                Test Generator
                                <svg v-if="showUserManagementMenu" class="w-6 h-6  text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                </svg>
                                <svg v-else class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                       
                    </div>
                    <!-- <ul 
                    v-if="showTestGeneratorMenu"       
                    >
                        <Link :href="route('testGen.show')">
                            <li @click="toggleBackground('testGenerator1')" :class="{'bg-blue-900':clickedItem === 'testGenerator1'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <i class="pi pi-cog"></i>
                                <span>Generate</span>
                            </li>
                        </Link>
                    </ul> -->
                    <ul 
                    v-if="showTestGeneratorMenu"       
                    >
                        <Link :href="route('testGen.show.new')">
                            <li @click="toggleBackground('testGenerator2')" :class="{'bg-blue-900':clickedItem === 'testGenerator2'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <i class="pi pi-cog"></i>
                                <span>Generate</span>
                            </li>
                        </Link>
                    </ul>
                </div>
                <!-- test gen-->

                <!--back up-->
                <div v-if="user.role==='admin' || user.role==='co-admin'">
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900" >
                        <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 7.205c4.418 0 8-1.165 8-2.602C20 3.165 16.418 2 12 2S4 3.165 4 4.603c0 1.437 3.582 2.602 8 2.602ZM12 22c4.963 0 8-1.686 8-2.603v-4.404c-.052.032-.112.06-.165.09a7.75 7.75 0 0 1-.745.387c-.193.088-.394.173-.6.253-.063.024-.124.05-.189.073a18.934 18.934 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.073a10.143 10.143 0 0 1-.852-.373 7.75 7.75 0 0 1-.493-.267c-.053-.03-.113-.058-.165-.09v4.404C4 20.315 7.037 22 12 22Zm7.09-13.928a9.91 9.91 0 0 1-.6.253c-.063.025-.124.05-.189.074a18.935 18.935 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.074a10.163 10.163 0 0 1-.852-.372 7.816 7.816 0 0 1-.493-.268c-.055-.03-.115-.058-.167-.09V12c0 .917 3.037 2.603 8 2.603s8-1.686 8-2.603V7.596c-.052.031-.112.059-.165.09a7.816 7.816 0 0 1-.745.386Z"/>
                        </svg>

                        <div class="w-full">
                            <button  @click="toggleBackUpMenu" class="flex w-full items-center justify-between pr-8 ">
                                Back Up
                                <svg v-if="showBackUpMenu" class="w-6 h-6  text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                                </svg>
                                <svg v-else class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <ul 
                    v-if="showBackUpMenu"       
                    >
                        <Link href="">
                            <li @click="toggleBackground('backup1')" :class="{'bg-blue-900':clickedItem === 'backup1'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <i class="pi pi-download"></i>
                                <Link :href="route('backup.show')">Download</Link>
                            </li>
                        </Link>
                        <Link href="">
                            <li @click="toggleBackground('backup2')" :class="{'bg-blue-900':clickedItem === 'backup2'}" class="flex pl-10  items-center gap-2 py-2 hover:bg-blue-900 hover:cursor-pointer">
                                <i class="pi pi-upload"></i>
                                <input type="file" ref="fileInput" @change="handleFileUpload"  class="hidden">
                                <Link  :href="route('backup.restore.show')" >Upload</Link> 
                            </li>
                        </Link>
                    </ul>
                </div>
                <!--back up-->

                <div class="border-b-2 border-gray-700 my-2">
                
                </div>   
                <button @click="logout" class="w-full"  as="button" method="delete"><!--andito ako 11-->
                    <div class="flex items-center py-3 pl-3 space-x-2 hover:bg-blue-900">
                        <svg class="w-6 h-6 text-gray-300 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
                        </svg>
                        <span>Logout</span>
                    </div>
                </button>
            </nav>
        </div>
        
        <!-- header and content -->
        <div class="flex-1 transition-all duration-200 ease-in-out" :class="{'ml-0': !showSideBar, 'ml-64': showSideBar}">
            <div class="flex items-center justify-between bg-blue-950 shadow px-2 py-4">
                <button @click="menuBtnClick">
                    <svg class="w-6 h-6 text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
                    </svg>
                </button>
                <div class="flex flex-col justify-end ">
                    <span class="text-gray-200 pr-2 mr-5 text-end md:text-xl">
                        Welcome back, 
                        <span>
                            {{ capitalizeFirstLetter(user.name) }} 
                        </span>
                    </span>
                    <div v-if="user.role === 'admin'" class=" gap-2 justify-end mr-5 pr-2 hidden md:flex">
                        <span  class="text-role text-gray-200">ADMINISTRATOR</span>
                    </div>
                    <div v-if="user.role === 'co-admin'" class=" gap-2 justify-end mr-5 pr-2 hidden md:flex">
                        <span class="text-role text-gray-200">CO-ADMIN</span>
                    </div>
                    <div v-if="user.role === 'faculty'" class=" gap-2 justify-end mr-5 pr-2 hidden md:flex">
                        <span v-if="user.division" class="text-role text-gray-200">{{ user.division.name.toUpperCase() }}</span>
                        <span v-else class="text-role text-gray-200">{{ user.department.name.toUpperCase() }}</span>
                        <span class="text-role text-gray-200">{{ user.role.toUpperCase() }}</span>
                    </div>
                    <div v-if="user.role === 'department head'" class=" gap-2 justify-end mr-5 pr-2 hidden md:flex">
                        <span v-if="user.division" class="text-role text-gray-200">{{ user.division.name.toUpperCase() }} </span>
                        <span v-else class="text-role text-gray-200">{{ user.department.name.toUpperCase() }}</span>
                        <span class="text-role text-gray-200">{{ user.role.toUpperCase() }}</span>
                    </div>


                    
                    
                </div>
                
            </div>
            <div class="p-8  ">
                <slot></slot>
            </div>
        </div>

        <!-- <CustomModal :isOpen="customModalOpen" @close="closeModal">
            TEST Generator : {{testGenModalOpen}}
        </CustomModal> -->
    </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import CustomModal from '../Global Component/CustomModal.vue';
import axios from 'axios';
import { capitalizeFirstLetter } from './Composables/capitalizeFirstLetter';



const customModalOpen = ref(true)


const visible = ref(null)
const showModal = ref(true)

const toggleModal = ()=>{
    showModal.value = !showModal.value
    console.log(showModal.value)
}
const clickedItem = ref(null)
const showSideBar = ref(true);
const user = computed(() => usePage().props.user);
const logoUrl = ref('/storage/Images/ncstLogo.png');
const menuBtnClick = () => {
    showSideBar.value = !showSideBar.value;
};

// Announcement Management
const showAnnouncmentMenu = ref(false)

const announcmentToggleMenu = ()=>{
    showAnnouncmentMenu.value = !showAnnouncmentMenu.value
    clickedItem.value = 0
}

// DEPARTMENT MANAGEMENT LOGIC
const showDepMgmtMenu = ref(false)

const depMgmtToggleMenu = ()=>{
    showDepMgmtMenu.value = !showDepMgmtMenu.value
    clickedItem.value = 0
}

// DIVISION MANAGEMENT LOGIC
const showDivMgmtMenu = ref(false)

const divMgmtToggleMenu = ()=>{
    showDivMgmtMenu.value = !showDivMgmtMenu.value
    clickedItem.value = 0
}

// USER MANAGEMENT LOGIC
const showUserManagementMenu = ref(false)


const userManagementToggleMenu = () => {
    showUserManagementMenu.value = !showUserManagementMenu.value
    clickedItem.value = 0
}


// subject codes logic
const showSubjectCodesMenu = ref(false)
const subjectCodesToggleMenu = () =>
{
    showSubjectCodesMenu.value = !showSubjectCodesMenu.value
    clickedItem.value = 0
}

const toggleBackground = (index)=> {
    clickedItem.value = index;
} 

// question bank logic
const showQuestionBankMenu = ref(false)

const questionBankToggleMenu = () =>
{
    showQuestionBankMenu.value = !showQuestionBankMenu.value
    clickedItem.value = 0
}

// EXAM GENERATOR
const testGenModalOpen = ref(true);

const closeModal = () => {
    customModalOpen.value = false;
};

const showTestGeneratorMenu = ref(false)

const toggleTestGeneratorMenu = ()=>
{
    showTestGeneratorMenu.value = !showTestGeneratorMenu.value
}

const showBackUpMenu = ref(false)
const toggleBackUpMenu = ()=>
{
    showBackUpMenu.value = !showBackUpMenu.value
}





// upload logic********************************************8
// Define reactive state
const selectedFile = ref(null);
const fileInput = ref(null);
// Function to trigger file input click
const triggerFileUpload = () => { 
    let input = fileInput.value
    if (input) {
        input.dispatchEvent(new MouseEvent('click')); // Dispatch a click event
    }
};

// Handle file upload when file is selected 
const handleFileUpload = (event) => {
    console.log('im here')
  const file = event.target.files[0];
  console.log(file);
  // Handle file upload logic here
};

// log out logic ************************************


const logout = async () => {
    try {
    // Make a POST request to your Laravel backend logout endpoint
    await router.post(route('logout'), {}, {
      onFinish: () => {
        // Clear localStorage after logout is completed
        clearLocalStorageOnLogout(); // Call the function to clear localStorage
      }
    });
  } catch (error) {
    console.error('Error during logout:', error);
    // Handle network or other errors
  }
};

// Function to clear localStorage item on logout
const clearLocalStorageOnLogout = () => {
  localStorage.removeItem('dataDisplayedOnce'); // Remove the item from localStorage
  
  if(localStorage.getItem('subjectCode'))
  {
    localStorage.removeItem('subjectCode');
  }
};

//backup logic
onMounted(()=>{
    const page = usePage()

    if(page.props.flash.backUpDonwloadUrl)
    {
        const url = page.props.flash.backUpDonwloadUrl;
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download','backup_file.sql');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); 
    }

    
})

const downloadBackup = () => { 
  
  router.visit(route('backup.download'),{
    onSuccess:()=>{alert('sucessfully back up')},
    onFinish:()=>{alert('yo')}

  })

};
</script>
