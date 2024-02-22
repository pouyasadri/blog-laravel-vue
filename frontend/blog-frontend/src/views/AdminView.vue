<script setup lang="ts">

import ArticlesTable from '@/components/admin/ArticlesTable.vue'
import ArticleForm from '@/components/admin/ArticleForm.vue'
import { ref } from 'vue'
const selectedArticle = ref(null)
</script>

<template>
  <main>
    <ArticlesTable @edit-article="selectedArticle = $event"/>
    <ArticleForm :article="selectedArticle"/>
  </main>
</template>

<style scoped>

</style>
