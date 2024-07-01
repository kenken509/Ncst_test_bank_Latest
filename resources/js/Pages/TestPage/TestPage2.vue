<template>
  <div ref="dynamicDiv" class="dynamic-content">
    <!-- Example dynamic content that changes -->
    <p v-for="item in items" :key="item">{{ item }}</p>
  </div>
  <button @click="addItem">Add Item</button>
  <p>Height of div: {{ height }}px</p>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const dynamicDiv = ref(null);
const height = ref(0);

// Example dynamic content
const items = ref(['Item 1', 'Item 2', 'Item 3']);

const addItem = () => {
  items.value.push(`Item ${items.value.length + 1}`);
};

// Use ResizeObserver to automatically update the height when the dynamicDiv ref changes
onMounted(() => {
  const observer = new ResizeObserver((entries) => {
    for (let entry of entries) {
      if (entry.target === dynamicDiv.value) {
        height.value = entry.contentRect.height;
      }
    }
  });

  if (dynamicDiv.value) {
    observer.observe(dynamicDiv.value);
  }

  onBeforeUnmount(() => {
    if (dynamicDiv.value) {
      observer.unobserve(dynamicDiv.value);
    }
  });
});
</script>

<style scoped>
.dynamic-content {
  /* Add your styles for the dynamic content here */
}
</style>
