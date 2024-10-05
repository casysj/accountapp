<template>
  <div class="user-registration max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">사용자 등록</h2>
    <form @submit.prevent="registerUser" class="space-y-4">
      <div>
        <label for="username" class="block mb-1 font-medium text-gray-700">사용자명:</label>
        <input type="text" id="username" v-model="user.name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label for="email" class="block mb-1 font-medium text-gray-700">이메일:</label>
        <input type="email" id="email" v-model="user.email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <div>
        <label for="password" class="block mb-1 font-medium text-gray-700">비밀번호:</label>
        <input type="password" id="password" v-model="user.password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">등록</button>
    </form>
    <p v-if="message" class="mt-4 text-center" :class="{'text-green-600': message.includes('성공'), 'text-red-600': message.includes('오류')}">{{ message }}</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UserRegistration',
  data() {
    return {
      user: {
        username: '',
        email: '',
        password: ''
      },
      message: ''
    }
  },
  methods: {
    async registerUser() {
      try {
        const response = await axios.post('/api/register', this.user);
        this.message = '사용자가 성공적으로 등록되었습니다.';
        this.resetForm();
      } catch (error) {
        this.message = '사용자 등록 중 오류가 발생했습니다: ' + error.message;
      }
    },
    resetForm() {
      this.user = {
        username: '',
        email: '',
        password: ''
      };
    }
  }
}
</script>
