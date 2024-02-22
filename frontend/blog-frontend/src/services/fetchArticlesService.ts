// services/fetchArticlesService.ts
import type { Article } from '@/types/article'
import type { Link } from '@/types/link'
import axios from 'axios'
import type { AxiosResponse } from 'axios'

interface ArticlesResponse {
  articles: Article[]
  links: Link[]
}

export async function fetchArticles(url: string = 'http://127.0.0.1:8000/api/articles'): Promise<ArticlesResponse> {
  const response: AxiosResponse = await axios.get(url)
  const data = await response.data
  return { articles: data.data, links: data.links }
}
