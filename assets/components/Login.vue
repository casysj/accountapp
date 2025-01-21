<template>
  <div class="login max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
    <!-- 에러 메시지 표시 영역 추가 -->
    <div v-if="errorMessage" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
      {{ errorMessage }}
    </div>
    <form @submit.prevent="login" class="space-y-4">
      <div>
        <label for="email" class="block mb-1 font-medium text-gray-700">Email:</label>
        <input 
          type="email" 
          id="email" 
          v-model="email" 
          required 
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          @input="errorMessage = ''"
        >
      </div>
      <div>
        <label for="password" class="block mb-1 font-medium text-gray-700">Password:</label>
        <input 
          type="password" 
          id="password" 
          v-model="password" 
          required 
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          @input="errorMessage = ''"
        >
      </div>
      <button 
        type="submit" 
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        :disabled="isLoading"
      >
        {{ isLoading ? '로그인 중...' : '로그인' }}
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: '',  // 에러 메시지 상태 추가
      isLoading: false  // 로딩 상태 추가
    }
  },
  methods: {
    async login() {
      this.errorMessage = '';  // 로그인 시도 시 에러 메시지 초기화
      this.isLoading = true;   // 로딩 상태 시작

      try {
        const response = await axios.post('/api/login_check', {
          username: this.email,
          password: this.password
        });
        
        localStorage.setItem('token', response.data.token);
        this.$emit('login-success');
        this.$router.push('/');
      } catch (error) {
        // 에러 응답에 따른 적절한 메시지 설정
        if (error.response) {
          switch (error.response.status) {
            case 401:
              this.errorMessage = '이메일 또는 비밀번호가 올바르지 않습니다.';
              break;
            case 422:
              this.errorMessage = '입력하신 정보를 확인해주세요.';
              break;
            default:
              this.errorMessage = '로그인 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
          }
        } else {
          this.errorMessage = '서버와 통신 중 오류가 발생했습니다. 네트워크 연결을 확인해주세요.';
        }
      } finally {
        this.isLoading = false;  // 로딩 상태 종료
      }
    }
  }
}
</script>