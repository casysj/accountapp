<template>
  <div class="expense-list">
    <h2>Expenses</h2>
    <ul>
      <li v-for="expense in expenses" :key="expense.id">
        {{ expense.description }} - {{ expense.amount }}
        <button @click="deleteExpense(expense.id)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      expenses: []
    }
  },
  mounted() {
    this.fetchExpenses();
  },
  methods: {
    async fetchExpenses() {
      try {
        const response = await axios.get('/api/expenses/user/2023/10', {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        this.expenses = response.data;
      } catch (error) {
        console.error('Failed to fetch expenses', error);
      }
    },
    async deleteExpense(id) {
      try {
        await axios.delete(`/api/expenses/${id}`, {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        this.fetchExpenses(); // 목록 새로고침
      } catch (error) {
        console.error('Failed to delete expense', error);
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