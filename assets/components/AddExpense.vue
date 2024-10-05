<template>
  <div class="add-expense max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Add Expense</h2>
    <form @submit.prevent="addExpense" class="space-y-4">
      <div>
        <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
        <input type="number" step="0.01" id="amount" v-model="amount" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
               :class="{'opacity-50 cursor-not-allowed': isSaving}"
               :disabled="isSaving">
      </div>
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
        <input type="text" id="description" v-model="description" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
               :class="{'opacity-50 cursor-not-allowed': isSaving}"
               :disabled="isSaving">
      </div>
      <div>
        <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
        <input type="date" id="date" v-model="date" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
               :class="{'opacity-50 cursor-not-allowed': isSaving}"
               :disabled="isSaving">
      </div>
      <button type="submit" 
              class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              :class="{'opacity-50 cursor-not-allowed': isSaving}"
              :disabled="isSaving">
        {{ isSaving ? 'Saving...' : 'Add Expense' }}
      </button>
    </form>
    <div v-if="showSuccessMessage" 
         class="mt-4 p-4 rounded-md bg-green-50 border border-green-400">
      <p class="text-sm text-green-700">Expense added successfully!</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      amount: 0,
      description: '',
      date: new Date().toISOString().substr(0, 10),
      isSaving: false,
      showSuccessMessage: false
    }
  },
  methods: {
    async addExpense() {
      this.isSaving = true;
      try {
        await axios.post('/api/expenses', {
          amount: this.amount,
          description: this.description,
          date: this.date
        }, {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        // 성공 후 폼 초기화
        this.amount = 0;
        this.description = '';
        this.date = new Date().toISOString().substr(0, 10);
        this.$emit('expense-added');
        
        // 성공 메시지 표시
        this.showSuccessMessage = true;
        // 3초 후 메시지 숨김
        setTimeout(() => {
          this.showSuccessMessage = false;
        }, 3000);
      } catch (error) {
        console.error('Failed to add expense', error);
        // 에러 처리 (예: 에러 메시지 표시)
      } finally {
        this.isSaving = false;
      }
    }
  },
  beforeRouteEnter(to, from, next) {
    if (!localStorage.getItem('token')) {
      next('/login')
    } else {
      next()
    }
  }
}
</script>