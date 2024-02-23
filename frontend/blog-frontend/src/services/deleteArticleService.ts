import axios from 'axios'
import type { AxiosResponse } from 'axios'

/**
 * Delete an article
 * @param {number} id - The ID of the article to delete
 * @returns {Promise<boolean>} - A promise that resolves to a boolean indicating the success of the deletion
 * @throws {Error} - Throws an error if the deletion fails
 */
export async function deleteArticle(id: number): Promise<boolean> {
  try {
    const response: AxiosResponse = await axios.delete(`http://127.0.0.1:8000/api/articles/${id}`)

    if (response.status === 200) {
      return true
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
