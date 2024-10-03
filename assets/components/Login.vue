<template>
  <div class="login">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required>
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required>
      </div>
      <button type="submit">Login</button>
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