import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from "axios"
import "../public/assets/css/bootstrap.css"
import "../public/assets/css/style.css"
import "../public/assets/css/masonry-1.css"
import "../public/assets/fontawesome/css/all.min.css"
import "../public/assets/fontawesome/css/fontawesome.min.css"
import "../public/assets/select2/select2.min.css"

localStorage.setItem("photos_url", "http://127.0.0.1:8000/photos/");
axios.defaults.baseURL = 'http://127.0.0.1:8000/api/';
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
createApp(App).use(router).mount('#app')
