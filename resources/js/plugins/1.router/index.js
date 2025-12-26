import { useCookie } from '@core/composable/useCookie'
import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router/auto'

function recursiveLayouts(route) {
  if (route.children) {
    for (let i = 0; i < route.children.length; i++)
      route.children[i] = recursiveLayouts(route.children[i])

    return route
  }

  return setupLayouts([route])[0]
}

const redirects = [
  {
    path: '/',
    redirect: '/iqc/incoming-part-report',
  },
]

const router = createRouter({
  history: createWebHistory('/'),
  scrollBehavior(to) {
    if (to.hash)
      return { el: to.hash, behavior: 'smooth', top: 60 }

    return { top: 0 }
  },
  extendRoutes: pages => [
    ...redirects,
    ...pages.map(route => recursiveLayouts(route)),
  ],
})

// Navigation Guard - Check authentication
router.beforeEach((to, from, next) => {
  const accessToken = useCookie('accessToken')
  const isAuthenticated = !!accessToken.value

  // Public routes that don't require authentication
  const publicRoutes = ['/login']

  // Check if route is public
  const isPublicRoute = publicRoutes.includes(to.path) || to.meta?.public

  if (!isAuthenticated && !isPublicRoute) {
    // Redirect to login if not authenticated and trying to access protected route
    next('/login')
  } else if (isAuthenticated && isPublicRoute && to.path === '/login') {
    // Redirect to dashboard if already authenticated and trying to access login
    next('/')
  } else {
    // Proceed to route
    next()
  }
})

export { router }
export default function (app) {
  app.use(router)
}
