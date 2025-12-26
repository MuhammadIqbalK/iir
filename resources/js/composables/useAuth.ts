import { $api } from '@/utils/api'
import { useCookie } from '@core/composable/useCookie'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'

export const useAuth = () => {
  const router = useRouter()
  const accessToken = useCookie('accessToken')

  const isAuthenticated = computed(() => !!accessToken.value)
  const user = ref<any>(null)

  /**
   * Login user
   */
  const login = async (credentials: { username: string; password: string }) => {
    const response = await $api('/api/login', {
      method: 'POST',
      body: credentials,
    })

    // Store access token in cookie
    accessToken.value = response.token

    return response
  }

  /**
   * Logout user
   */
  const logout = async () => {
    try {
      await $api('/api/logout', {
        method: 'POST',
      })
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      // Clear access token
      accessToken.value = null
      user.value = null

      // Redirect to login page
      router.push('/login')
    }
  }

  /**
   * Fetch current user
   */
  const fetchUser = async () => {
    try {
      const response = await $api('/api/me')
      user.value = response
      return response
    } catch (error) {
      console.error('Fetch user error:', error)
      if (error.statusCode === 401) {
        // Unauthorized, clear token and redirect to login
        accessToken.value = null
        user.value = null
        router.push('/login')
      }
      return null
    }
  }

  return {
    isAuthenticated,
    user,
    login,
    logout,
    fetchUser,
  }
}
