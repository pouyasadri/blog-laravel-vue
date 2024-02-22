<script setup lang="ts">
import { RouterView } from 'vue-router'
import BlogHeader from '@/components/BlogHeader.vue'
</script>

<template>
  <header>
    <BlogHeader />
  </header>

  <RouterView />
</template>
