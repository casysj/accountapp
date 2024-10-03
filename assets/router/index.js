import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/Login.vue'
import ExpenseList from '../components/ExpenseList.vue'
import AddExpense from '../components/AddExpense.vue'
import Settlement from '../components/Settlement.vue'
import UserRegistration from '../components/UserRegistration.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: ExpenseList
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/add-expense',
    name: 'AddExpense',
    component: AddExpense
  },
  {
    path: '/settlement',
    name: 'Settlement',
    component: Settlement
  },
  {
    path: '/register',
    name: 'UserRegistration',
    component: UserRegistration
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router