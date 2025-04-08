import { Page } from '@inertiajs/inertia'

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $page: Page<InertiaPageProps>
  }
}

export interface InertiaPageProps {
  auth?: {
    user?: {
      id: number
      name: string
      email: string
    }
  }
  post?: Post
  posts?: Post[]
  author?: Author
  category?: Category
  meta?: Meta
}

export interface Post {
    id: number
    title: string
    slug: string
    body: string
    created_at: string
    updated_at: string
  }
  
  export interface Author {
    id: number
    name: string
    bio?: string
  }
  
  export interface Category {
    id: number
    name: string
    slug: string
  }
  
  export interface Meta {
    title?: string
    description?: string
    keywords?: string[]
  }