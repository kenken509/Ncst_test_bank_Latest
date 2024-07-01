<template>
    <p v-if="error" class=" text-red-400 mx-2">{{ error }}</p>
</template>

<script setup>

defineProps({
    error:String
})

</script>