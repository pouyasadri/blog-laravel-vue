<template>
  <div v-if="articles.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 m-4">
    <div v-for="article in articles" :key="article.id" class="p-6 border rounded-lg h-[30rem]">
      <img :src="articleImageUrl(article)" :alt="article.title"
           class="w-full h-48 mb-4 object-scale-down">

      <h2 class="text-xl text-black font-bold my-2 h-20">{{ article.title }}</h2>
      <p class="text-gray-700 line-clamp-3" v-html="article.content"></p>
      <div class="flex m-4 justify-between ">
        <button
          @click="readMore(article)"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline m-2">
          Read More
        </button>
        <span class="py-2 px-4 bg-neutral-200 rounded-2xl m-2">{{ article.category }}</span>
      </div>
    </div>
  </div>
  <div v-if="links.length">
    <nav class="flex justify-center items-center space-x-4">
      <template v-for="link in links">
        <a v-if="link.url != null" @click="changePage(link.url)"
           :class="link.active ? 'bg-blue-500 text-white px-4 py-2 rounded' : 'hover:underline'"
           class="px-4 py-2 rounded cursor-pointer" :key="link.label" v-html="link.label"></a>
      </template>
    </nav>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import type { Article } from '@/types/article'
import type { Link } from '@/types/link'
import { fetchArticles } from '@/services/fetchArticlesService'

/**
 * BlogArticles component
 * Displays a list of articles with pagination
 */
export default defineComponent({
  setup() {
    const articles = ref<Article[]>([])
    const links = ref<Link[]>([])
    const router = useRouter()

    const articleImageUrl = (article: Article) => `http://localhost:8000/storage/images/articles/${article.image}`

    const readMore = (article: Article) => {
      router.push({ name: 'ArticleView', params: { id: article.id } })
    }

    const changePage = async (url: string) => {
      const { articles: fetchedArticles, links: fetchedLinks } = await fetchArticles(url)
      articles.value = fetchedArticles
      links.value = fetchedLinks
    }

    onMounted(async () => {
      const { articles: fetchedArticles, links: fetchedLinks } = await fetchArticles()
      articles.value = fetchedArticles
      links.value = fetchedLinks
    })

    return { articles, links, readMore, changePage, articleImageUrl }
  }
})
</script>
