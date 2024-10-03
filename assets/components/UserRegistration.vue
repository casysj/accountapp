<template>
  <div class="user-registration">
    <h2>사용자 등록</h2>
    <form @submit.prevent="registerUser">
      <div>
        <label for="username">사용자명:</label>
        <input type="text" id="username" v-model="user.name" required>
      </div>
      <div>
        <label for="email">이메일:</label>
        <input type="email" id="email" v-model="user.email" required>
      </div>
      <div>
        <label for="password">비밀번호:</label>
        <input type="password" id="password" v-model="user.password" required>
      </div>
      <button type="submit">등록</button>
    </form>
    <p v-if="message">{{ message }}</p>
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

<style scoped>
.user-registration {
  max-width: 400px;
  margin: 0 auto;
}
form div {
  margin-bottom: 1rem;
}
label {
  display: block;
  margin-bottom: 0.5rem;
}
input {
  width: 100%;
  padding: 0.5rem;
}
button {
  background-color: #4CAF50;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  cursor: pointer;
}
button:hover {
  background-color: #45a049;
}
</style>