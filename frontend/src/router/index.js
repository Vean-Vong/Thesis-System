import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import routes from '~pages'
import { useAuthStore } from '../store/module/auth.module'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    ...setupLayouts(routes),
    {
      path: '/request-update/preview/birth/:id',
      name: 'birth.preview',
      component: () => import('../views/preview/birth/[id].vue')
    },
    {
      path: '/request-update/preview/birth-copy/:id',
      name: 'birth-copies.preview',
      component: () => import('../views/preview/birth-copy/[id].vue')
    },
    {
      path: '/request-update/preview/married/:id',
      name: 'married.preview',
      component: () => import('../views/preview/married/[id].vue')
    },
    {
      path: '/request-update/preview/married-copy/:id',
      name: 'married-copy.preview',
      component: () => import('../views/preview/married-copy/[id].vue')
    },
    {
      path: '/request-update/preview/death/:id',
      name: 'death.preview',
      component: () => import('../views/preview/death/[id].vue')
    },
    {
      path: '/request-update/preview/death-copy/:id',
      name: 'death-copy.preview',
      component: () => import('../views/preview/death-copy/[id].vue')
    },
    
  ],
  scrollBehavior() {
    return { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  const guestRoute = ['login', 'register']  
  if (useAuthStore().authenticated && useAuthStore().accessToken) {
      if (guestRoute.includes(to.name)) next({ name: 'index' })
      else next()
  } else {
      if (guestRoute.includes(to.name)) next()
      else next({ name: 'login' })
  }
})


export default router
