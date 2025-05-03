import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import routes from '~pages'
import { isUserLoggedIn } from './utils'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [...setupLayouts(routes)],
})

// Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
router.beforeEach(to => {
  const isLoggedIn = isUserLoggedIn()

  document.title = to.meta.title + ' - GANG SAN'

  if (isLoggedIn) {
    if (to.name == 'login' || to.path == '/') {
      // if (canNavigate(to)) {
      //   if (to.meta.redirectIfLoggedIn) return "/";
      // } else {
      //   if (isLoggedIn) return { name: "not-authorized" };
      //   else
      //     return {
      //       name: "login",
      //       query: { to: to.name !== "index" ? to.fullPath : undefined },
      //     };
      // }
      return { name: 'dashboards' }
    }
  } else {
    if (to.name != 'login') {
      return { name: 'login' }
    }
  }
})

export default router
