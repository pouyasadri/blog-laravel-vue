<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
import EditIcon from '@/components/icons/EditIcon.vue'
import TrashIcon from '@/components/icons/TrashIcon.vue'
import type { Article } from '@/types/article'
import { fetchArticles } from '@/services/fetchArticlesService'
import { deleteArticle } from '@/services/deleteArticleService'
import type { Link } from '@/types/link'
import { useToast } from 'vue-toastification'

export default defineComponent({
  components: {
    EditIcon,
    TrashIcon
  },
  setup() {
    const articles = ref<Article[]>([])
    const links = ref<Link[]>([])
    const toast = useToast()
    const changePage = async (url: string) => {
      const { articles: fetchedArticles, links: fetchedLinks } = await fetchArticles(url)
      articles.value = fetchedArticles
      links.value = fetchedLinks
    }
    const handleDeleteArticle = async (id: number) => {
      const confirmDelete = window.confirm('Are you sure you want to delete this article?')
      if (confirmDelete) {
        try {
          const success = await deleteArticle(id)
          if (success) {
            toast.success('Article deleted successfully')
            // Refresh articles or handle UI updates here
          }
        } catch (error: any) {
          toast.error(error.message)
        }
      } else {
        toast.info('Article deletion cancelled')
      }
    }
    // Fetch articles when the component is mounted
    onMounted(async () => {
      const { articles: fetchedArticles, links: fetchedLinks } = await fetchArticles()
      articles.value = fetchedArticles
      links.value = fetchedLinks
    })
    // Return the state and methods to use in the template
    return {
      articles,
      links,
      changePage,
      handleDeleteArticle
    }
  }
})
</script>

<template>
  <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
      <tr>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Title</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Status</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Category</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Timestamp</th>
        <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
      </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
      <tr v-for="article in articles" :key="article.id" class="hover:bg-gray-50">
        <th class="flex gap-3 px-6 py-4 font-normal text-gray-900">
          <div class="relative h-16 w-16">
            <img
              class="h-full w-full rounded-full object-cover object-center"
              :src="`http://localhost:8000/storage/images/articles/${article.image}`" :alt="article.title"
            />
          </div>
          <div class="text-sm">
            <div class="font-medium text-gray-700">{{ article.title }}</div>
            <div class="text-gray-400 w-96 line-clamp-3" v-html="article.content"></div>
          </div>
        </th>
        <td class="px-6 py-4">
          <span
            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold"
            :class="{
    'bg-green-50 text-green-600': article.status === 'published',
    'bg-gray-50 text-gray-600': article.status === 'draft',
    'bg-orange-50 text-orange-600': article.status === 'archive'
  }"
          >
  <span
    class="h-1.5 w-1.5 rounded-full"
    :class="{
      'bg-green-600': article.status === 'published',
      'bg-gray-600': article.status === 'draft',
      'bg-orange-600': article.status === 'archive'
    }"
  ></span>
  {{ article.status }}
</span>
        </td>
        <td class="px-6 py-4">
          <div class="flex gap-2">
            <span
              class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 font-semibold text-indigo-600"
            >
              {{ article.category }}
            </span>

          </div>
        </td>
        <td class="px-6 py-4">
          <div class="text-sm text-gray-500">
            <div>Created at: {{ article.created_at }}</div>
            <div>Updated at: {{ article.updated_at }}</div>
          </div>
        </td>

        <td class="px-6 py-4">
          <div class="flex justify-end gap-4">
            <div class="p-2 bg-neutral-100 rounded hover:shadow hover:bg-neutral-200">
              <TrashIcon @click="handleDeleteArticle(article.id)" class="cursor-pointer" />
            </div>
            <div class="p-2 bg-neutral-100 rounded hover:shadow hover:bg-neutral-200">
              <EditIcon @click="$emit('edit-article', article) " class="cursor-pointer" />
            </div>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
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
