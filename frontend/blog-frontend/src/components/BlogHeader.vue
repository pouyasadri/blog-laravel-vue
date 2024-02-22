<template>
  <header class="bg-blue-500 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <router-link to="/"><h1 class="text-2xl font-bold">Blog</h1></router-link>
      <nav>
        <ul class="flex space-x-4">
          <li v-for="(item, index) in menuItems" :key="index">
            <router-link :to="item.path" class="hover:underline">{{ item.name }}</router-link>
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'BlogHeader',
  setup() {
    const menuItems = [
      { name: 'Home', path: '/' },
      { name: 'Admin', path: '/admin' }
    ]

    return { menuItems }
  }
})
</script>
