<template>
  <main v-if="article">
    <div class="w-4/6 mx-auto my-8 p-4 border rounded-md">
      <div class="w-96 flex bg-neutral-200 justify-center mx-auto my-4 rounded-xl shadow-2xl">
        <img :src="articleImageUrl" class="w-96 p-4" :alt="article.title">
      </div>
      <h1 class="font-black text-3xl text-center my-6">{{ article.title }}</h1>
      <p class="w-1/2 mx-auto leading-relaxed text-justify text-md text-xl my-4" v-html="article.content"></p>
      <div class="flex justify-around items-center">
        <div class="flex justify-around items-center">
          <p class="bg-neutral-200 text-gray-800 p-2 rounded mx-2">{{ article.category }}</p>
          <p class="bg-neutral-200 text-gray-800 p-2 rounded mx-2">{{ article.status }}</p>
        </div>
        <div class="text-gray-700 font-light">
          <p>created at : {{ formattedTimestamp.createdAt }}</p>
          <p>updated at : {{ formattedTimestamp.updatedAt }}</p>
        </div>
      </div>
    </div>
  </main>
</template>

<script lang="ts">
import { computed, type ComputedRef, defineComponent, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import type { Article } from '@/types/article'
import { fetchArticle } from '@/services/fetchArticleService'

import type { Timestamp } from '@/types/timestamp'

/**
 * ArticleView component
 * This component provides a view for a single article.
 */
export default defineComponent({

  setup() {
    const article = ref<Article | null>(null)
    const route = useRoute()
    const id = route.params.id

    onMounted(async () => {
      const idValue = Array.isArray(id) ? id[0] : id
      article.value = await fetchArticle(idValue)
    })
    const articleImageUrl = computed(() => `http://localhost:8000/storage/images/articles/${article.value?.image}`)
    const formattedTimestamp: ComputedRef<Timestamp> = computed(() => {
      if (article.value?.created_at && article.value?.updated_at) {
        const createdAt = new Date(article.value.created_at)
        const updatedAt = new Date(article.value.updated_at)
        return {
          createdAt: createdAt.toLocaleDateString(),
          updatedAt: updatedAt.toLocaleDateString()
        }
      }
      return {
        createdAt: '',
        updatedAt: ''
      }
    })

    return { article, articleImageUrl, formattedTimestamp }
  }
})
</script>
