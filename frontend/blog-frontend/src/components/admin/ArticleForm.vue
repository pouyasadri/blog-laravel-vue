<template>
  <div class="bg-neutral-100 w-1/2 p-4 mx-auto mb-10 shadow-2xl rounded-2xl m-5">
    <form @submit.prevent="submitForm">
      <div class="mb-4 ">
        <label class="block text-gray-700 text-xl font-bold mb-2" for="title">
          Title
        </label>
        <input
          v-model="title"
          class="shadow appearance-none text-base border rounded-xl w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="title"
          type="text"
          placeholder="Enter title"
        />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-xl font-bold mb-2" for="content">
          Content
        </label>
        <QuillEditor
          v-model:content="content" contentType="html"
          class="bg-white border rounded-xl w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="content" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-xl font-bold mb-2" for="category">
          Category
        </label>
        <input
          v-model="category"
          class="shadow appearance-none border  text-base rounded-xl w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="category"
          type="text"

          placeholder="Enter category"
        />

      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-xl font-bold mb-2" for="category">
          Status
        </label>
        <select
          v-model="status"
          class="shadow appearance-none border  text-base rounded-xl w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="status">
          <option value="draft">Draft</option>
          <option value="published">Published</option>
          <option value="archive">Archive</option>
        </select>

      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-xl font-bold mb-2" for="image">
          Image
        </label>
        <input
          class="shadow appearance-none bg-white border rounded-xl w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          id="image"
          type="file"
          @change="onFileChange"
        />
      </div>
      <div class="flex items-center justify-between">
        <button
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
          type="submit"
        >
          Save
        </button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, toRefs, watch } from 'vue'
import axios from 'axios'

/**
 * BlogArticleForm component
 * This component provides a form for creating or editing an article.
 */
export default defineComponent({
  props: {
    article: Object
  },
  setup(props) {
    /**
     * Reactive state for the form fields
     */
    const state = reactive({
      title: '',
      content: '',
      category: '',
      status: 'draft',
      image: null as File | null
    })

    /**
     * Handle file change event
     * @param {Event} e - The event object
     */
    const onFileChange = (e: Event) => {
      const files = (e.target as HTMLInputElement).files
      if (files) {
        state.image = files[0]
      }
    }
    watch(() => props.article, (newArticle) => {
      if (newArticle) {
        state.title = newArticle.title
        state.content = newArticle.content
        state.category = newArticle.category
        state.status = newArticle.status
        // Handle the image field as needed
      }
    }, { immediate: true })
    /**
     * Submit the form
     */
    const submitForm = async () => {
      const formData = new FormData()
      formData.append('title', state.title)
      formData.append('content', state.content)
      formData.append('category', state.category)
      formData.append('status', state.status)
      if (state.image) {
        formData.append('image', state.image)
      }
      try {
        let response
        if (props.article) {
          // If an article is being edited, send a PUT request
          //TODO: Fix the URL
          response = await axios.put(`http://127.0.0.1:8000/api/articles/${props.article.id}`, formData)
        } else {
          // Otherwise, send a POST request
          response = await axios.post('http://127.0.0.1:8000/api/articles', formData)
        }

        if (response.status === 200 || response.status === 201) {
          state.title = ''
          state.content = ''
          state.category = ''
          state.image = null
        } else {
          console.error('Failed to submit form')
        }
      } catch (error: any) {
        if (error.response && error.response.data) {
          console.error('Failed to submit form', error.response.data)
        } else {
          console.error('Failed to submit form', error)
        }
      }
    }

    // Return the state and methods to use in the template
    return {
      ...toRefs(state),
      onFileChange,
      submitForm
    }
  }
})
</script>
