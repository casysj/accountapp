<template>
  <div id="app" class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex space-x-4">
            <router-link 
              to="/" 
              class="flex items-center px-3 py-2 text-sm font-medium"
              :class="[$route.path === '/' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:border-b-2 hover:border-gray-300']"
            >
              Home
            </router-link>
            <router-link 
              to="/add-expense" 
              class="flex items-center px-3 py-2 text-sm font-medium"
              :class="[$route.path === '/add-expense' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:border-b-2 hover:border-gray-300']"
            >
              Add Expense
            </router-link>
            <router-link 
              to="/settlement" 
              class="flex items-center px-3 py-2 text-sm font-medium"
              :class="[$route.path === '/settlement' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-gray-900 hover:border-b-2 hover:border-gray-300']"
            >
              Settlement
            </router-link>
          </div>
          <div class="flex items-center">
            <router-link 
              v-if="!isLoggedIn" 
              to="/login" 
              class="px-3 py-2 text-sm font-medium"
              :class="[$route.path === '/login' ? 'text-blue-600' : 'text-gray-700 hover:text-gray-900']"
            >
              Login
            </router-link>
            <router-link 
              v-if="!isLoggedIn" 
              to="/register" 
              class="px-3 py-2 text-sm font-medium"
              :class="[$route.path === '/register' ? 'text-blue-600' : 'text-gray-700 hover:text-gray-900']"
            >
              Register
            </router-link>
            <a 
              v-else 
              href="#" 
              @click.prevent="logout" 
              class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900"
            >
              Logout
            </a>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <router-view @login-success="onLoginSuccess"></router-view>
    </main>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoggedIn: false
    }
  },
  created() {
    this.isLoggedIn = !!localStorage.getItem('token')
  },
  methods: {
    onLoginSuccess() {
      this.isLoggedIn = true
      this.$router.push('/')
    },
    logout() {
      localStorage.removeItem('token')
      this.isLoggedIn = false
      this.$router.push('/login')
    }
  }
}
</script>