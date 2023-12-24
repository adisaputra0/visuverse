import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AdminView from '../views/AdminView.vue'
import CategoryView from '../views/CategoryView.vue'
import CategoriesView from '../views/CategoriesView.vue'
import PhotosView from '../views/PhotosView.vue'
import CreateView from '../views/CreateView.vue'
import EditView from '../views/EditView.vue'
import SearchView from '../views/SearchView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/admin',
    name: 'admin',
    component: AdminView
  },
  {
    path: '/category/:category_name',
    name: 'category',
    component: CategoryView
  },
  {
    path: '/categories',
    name: 'categories',
    component: CategoriesView
  },
  {
    path: '/photos/:slug',
    name: 'photos',
    component: PhotosView
  },
  {
    path: '/admin/add',
    name: 'create',
    component: CreateView
  },
  {
    path: '/admin/edit/:slug',
    name: 'edit',
    component: EditView
  },
  {
    path: '/search/:search_text',
    name: 'search',
    component: SearchView
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
