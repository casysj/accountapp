<template>
  <div class="expense-list">
    <h2>Expenses</h2>
    <table v-if="expenses.length">
      <thead>
        <tr>
          <th>Description</th>
          <th>Amount</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="expense in expenses" :key="expense.id">
          <td>{{ expense.description }}</td>
          <td>{{ expense.amount }}</td>
          <td>{{ new Date(expense.date).toLocaleDateString('de-DE') }}</td>
          <td>
            <button @click="deleteExpense(expense.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else>No expenses found.</div>

    <div class="pagination">
      <button @click="fetchExpenses(currentPage - 1)" :disabled="currentPage === 1">Previous</button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="fetchExpenses(currentPage + 1)" :disabled="currentPage === totalPages">Next</button>
    </div>

    <div v-if="isLoading" class="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>
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

<style scoped>
.expense-list {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
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
  padding: 5px 10px;
}

.loading, .error {
  margin-top: 20px;
  text-align: center;
}

.error {
  color: red;
}
</style>