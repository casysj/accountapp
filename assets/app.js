import { createApp } from 'vue'
import App from './components/App.vue'
import router from './router'
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
createApp(App).use(router).mount('#app')
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
