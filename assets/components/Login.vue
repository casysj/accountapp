<template>
  <div class="login max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    <form @submit.prevent="login" class="space-y-4">
      <div>
        <label for="email" class="block mb-1 font-medium text-gray-700">Email:</label>
        <input type="email" id="email" v-model="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label for="password" class="block mb-1 font-medium text-gray-700">Password:</label>
        <input type="password" id="password" v-model="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Login</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    async login() {
      try {
      const response = await axios.post('/api/login_check', {
        username: this.email,
        password: this.password
      });
        localStorage.setItem('token', response.data.token);
        this.$emit('login-success');
        this.$router.push('/');
      } catch (error) {
        console.error('Login failed', error);
      }
    }
  }
}
</script>