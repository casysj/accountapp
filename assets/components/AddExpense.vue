<template>
  <div class="add-expense">
    <h2>Add Expense</h2>
    <form @submit.prevent="addExpense">
      <div>
        <label for="amount">Amount:</label>
        <input type="number" step="0.01" id="amount" v-model="amount" required>
      </div>
      <div>
        <label for="description">Description:</label>
        <input type="text" id="description" v-model="description" required>
      </div>
      <div>
        <label for="date">Date:</label>
        <input type="date" id="date" v-model="date" required>
      </div>
      <button type="submit">Add Expense</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      amount: 0,
      description: '',
      date: new Date().toISOString().substr(0, 10)
    }
  },
  methods: {
    async addExpense() {
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
      } catch (error) {
        console.error('Failed to add expense', error);
      }
    }
  },
  // beforeRouteEnter(to, from, next) {
  //   if (!localStorage.getItem('token')) {
  //     next('/login')
  //   } else {
  //     next()
  //   }
  // }
}
</script>