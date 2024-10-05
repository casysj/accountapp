<template>
  <div class="expense-list max-w-4xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Expenses</h2>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
      <table v-if="expenses.length" class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="expense in expenses" :key="expense.id">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ expense.description }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ expense.amount }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ new Date(expense.date).toLocaleDateString('de-DE') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button @click="deleteExpense(expense.id)" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else class="px-6 py-4 text-center text-gray-500">No expenses found.</div>
    </div>

    <div class="mt-4 flex items-center justify-between">
      <button @click="fetchExpenses(currentPage - 1)" :disabled="currentPage === 1" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
        Previous
      </button>
      <span class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="fetchExpenses(currentPage + 1)" :disabled="currentPage === totalPages" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
        Next
      </button>
    </div>

    <div v-if="isLoading" class="mt-4 text-center text-gray-500">Loading...</div>
    <div v-if="error" class="mt-4 text-center text-red-600">{{ error }}</div>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  data() {
    return {
      expenses: [],
      currentPage: 1,
      totalPages: 1,
      isLoading: false,
      error: null
    }
  },
  mounted() {
    this.fetchExpenses();
  },
  methods: {
    async fetchExpenses(page = 1) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/expenses/', {
          params: { page, limit: 10 },
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        this.expenses = response.data.items;
        this.currentPage = response.data.currentPage;
        this.totalPages = response.data.totalPages;
      } catch (error) {
        console.error('Failed to fetch expenses', error);
        this.error = 'Failed to fetch expenses. Please try again.';
      } finally {
        this.isLoading = false;
      }
    },
    async deleteExpense(id) {
      try {
        await axios.delete(`/api/expenses/${id}`, {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        await this.fetchExpenses(this.currentPage); // 현재 페이지 새로고침
      } catch (error) {
        console.error('Failed to delete expense', error);
        this.error = 'Failed to delete expense. Please try again.';
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
