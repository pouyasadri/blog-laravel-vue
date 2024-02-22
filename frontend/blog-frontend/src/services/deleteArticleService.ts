import axios from 'axios'
import type { AxiosResponse } from 'axios'
import type { Article } from '@/types/article'

/**
 * Delete an article
 * @param {number} id - The ID of the article to delete
 * @returns {Promise<Article[]>} - A promise that resolves to an array of articles after deletion
 * @throws {Error} - Throws an error if the deletion fails
 */
export async function deleteArticle(id: number): Promise<Article[]> {
  const confirmDelete = window.confirm('Are you sure you want to delete this article?')
  if (confirmDelete) {
    try {
      const response: AxiosResponse = await axios.delete(`http://127.0.0.1:8000/api/articles/${id}`)

      if (response.status === 204) {

        return []
      } else {
        throw new Error('Failed to delete article')
      }
    } catch (error: any) {
      if (error.response && error.response.data) {
        throw new Error(`Failed to delete article: ${error.response.data}`)
      } else {
        throw new Error(`Failed to delete article: ${error}`)
      }
    }
  }
  return []
}
