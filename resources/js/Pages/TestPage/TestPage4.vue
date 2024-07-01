<template>

 QUESTIONS:  >>   {{ questions.length }}

 subject_code: >> {{ subject_code }}

</template>

<script setup>

const data = defineProps({
    questions:Array,
    subject_code:Object,
})

</script>