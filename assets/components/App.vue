<template>
  <div id="app">
    <nav>
      <router-link to="/">Home</router-link> |
      <router-link to="/add-expense">Add Expense</router-link> |
      <router-link to="/settlement">Settlement</router-link> |
      <router-link v-if="!isLoggedIn" to="/login">Login</router-link> |
      <router-link v-if="!isLoggedIn" to="/register">Register</router-link>
      <a v-else href="#" @click.prevent="logout">Logout</a>
    </nav>

    <router-view @login-success="onLoginSuccess"></router-view>
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