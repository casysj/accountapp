<template>
  <div class="settlement">
    <h2>Settlement</h2>
    
    <div class="date-selector">
      <select v-model="selectedYear">
        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
      </select>
      <select v-model="selectedMonth">
        <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
      </select>
      <button @click="calculateSettlement" :disabled="isLoading">Calculate Settlement</button>
    </div>

    <div v-if="isLoading" class="loading">Loading...</div>

    <div v-if="currentSettlement" class="current-settlement">
      <h3>Current Settlement</h3>
      <p>Total Expense: {{ currentSettlement.totalExpense }}</p>
      <p>Your Balance: {{ currentSettlement.balance }}</p>
    </div>

    <div v-if="error" class="error">{{ error }}</div>

    <div class="settlement-list" v-if="settlementHistory.length">
      <h3>Settlement History</h3>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Total Expense</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in settlementHistory" :key="item.id">
            <td>{{ item.year }}/{{ item.month }}</td>
            <td>{{ item.totalExpense }}</td>
            <td>{{ item.balance }}</td>
          </tr>
        </tbody>
      </table>
      <div class="pagination">
        <button @click="fetchSettlementHistory(currentPage - 1)" :disabled="currentPage === 1">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="fetchSettlementHistory(currentPage + 1)" :disabled="currentPage === totalPages">Next</button>
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
        console.log(response.data);
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
        console.log(response.data.items);
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
<style scoped>
.settlement {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.date-selector {
  margin-bottom: 20px;
}

select, button {
  margin-right: 10px;
  padding: 5px 10px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
}

.pagination {
  margin-top: 20px;
  text-align: center;
}

.pagination button {
  margin: 0 10px;
}

.loading, .error {
  margin-top: 20px;
  text-align: center;
}

.error {
  color: red;
}

.current-settlement {
  margin-top: 20px;
  padding: 15px;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 5px;
}
</style>