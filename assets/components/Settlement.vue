<template>
  <div class="settlement max-w-4xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Settlement</h2>
    
    <div class="date-selector mb-6 flex items-center space-x-4">
      <select v-model="selectedYear" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
      </select>
      <select v-model="selectedMonth" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
      </select>
      <button @click="calculateSettlement" :disabled="isLoading" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
        Calculate Settlement
      </button>
    </div>

    <div v-if="isLoading" class="text-center text-gray-500">Loading...</div>

    <div v-if="currentSettlement" class="current-settlement bg-white p-6 rounded-lg shadow-md mb-6">
      <h3 class="text-xl font-semibold mb-4">Current Settlement</h3>
      <p class="mb-2">총 지출: <span class="font-bold">{{ currentSettlement.totalExpense }}</span></p>
      <p v-if="currentSettlement.balance > 0" class="text-red-600">줘야할 돈: <span class="font-bold">{{ Math.abs(currentSettlement.balance) }}</span></p>
      <p v-else class="text-green-600">받아야할 돈: <span class="font-bold">{{ Math.abs(currentSettlement.balance) }}</span></p>
    </div>

    <div v-if="error" class="mb-4 text-center text-red-600">{{ error }}</div>

    <div class="settlement-list" v-if="settlementHistory.length">
      <h3 class="text-xl font-semibold mb-4">Settlement History</h3>
      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Expense</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="item in settlementHistory" :key="item.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.year }}/{{ item.month }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.totalExpense }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.balance }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.user.name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-4 flex items-center justify-between">
        <button @click="fetchSettlementHistory(currentPage - 1)" :disabled="currentPage === 1" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
          Previous
        </button>
        <span class="text-sm text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="fetchSettlementHistory(currentPage + 1)" :disabled="currentPage === totalPages" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      currentSettlement: null,
      settlementHistory: [],
      selectedYear: new Date().getFullYear(),
      selectedMonth: new Date().getMonth() + 1,
      years: [],
      months: Array.from({length: 12}, (_, i) => i + 1),
      currentPage: 1,
      totalPages: 1,
      isLoading: false,
      error: null
    }
  },
  methods: {
    async calculateSettlement() {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.post(`/api/settlements/${this.selectedYear}/${this.selectedMonth}`, {}, {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        this.currentSettlement = response.data[0];
        await this.fetchSettlementHistory();
      } catch (error) {
        console.error('Failed to calculate settlement', error);
        this.error = 'Failed to calculate settlement. Please try again.';
      } finally {
        this.isLoading = false;
      }
    },
    async fetchSettlementHistory(page = 1) {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/settlements', {
          params: { page, limit: 10 },
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        this.settlementHistory = response.data.items;
        this.currentPage = response.data.currentPage;
        this.totalPages = response.data.totalPages;
      } catch (error) {
        console.error('Failed to fetch settlement history', error);
        this.error = 'Failed to fetch settlement history. Please try again.';
      } finally {
        this.isLoading = false;
      }
    },
    generateYearList() {
      const currentYear = new Date().getFullYear();
      this.years = Array.from({length: 5}, (_, i) => currentYear - i);
    }
  },
  created() {
    this.generateYearList();
    this.fetchSettlementHistory();
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