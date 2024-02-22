import axios from 'axios'
import type { AxiosResponse } from 'axios'
import type { Article } from '@/types/article'

/**
 * Fetch a single article
 * @param {string} id - The ID of the article to fetch
 * @returns {Promise<Article>} - A promise that resolves to an article
 * @throws {Error} - Throws an error if the fetch fails
 */
export async function fetchArticle(id: string): Promise<Article> {
  const response: AxiosResponse = await axios.get(`http://127.0.0.1:8000/api/articles/${id}`)
  if (response.status === 200) {
    return response.data
  } else {
    throw new Error('Failed to fetch article')
  }
}
