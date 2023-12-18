<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow fixed-top navbar-user">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/"><img alt="Vue logo" src="../assets/logo.png" width="30">
          Visuverse</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
            <li class="nav-item">
              <router-link to="/" class="nav-link active">Explore</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/categories" class="nav-link active">Categories</router-link>
            </li>
            <li class="nav-item" v-if="is_login">
              <router-link to="/categories" class="nav-link active">Add</router-link>
            </li>
            <li class="nav-item w-100">
              <form class="d-flex" role="search">
                <input class="form-control me-2 w-100" type="search"
                  placeholder="Explore an endless collection of images..." aria-label="Search">
              </form>
            </li>
          </ul>
          <div class="d-flex gap-3" v-if="!is_login">
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
              data-bs-target="#register">Register</button>
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
              data-bs-target="#login">Login</button>
          </div>
          <div class="d-flex gap-3" v-if="is_login">
            <router-link to="/admin" class="btn btn-outline-primary">Profile</router-link>
            <button type="button" class="btn btn-outline-danger" v-on:click="logout()">Logout</button>
          </div>
        </div>
      </div>
    </nav>
  
    <!-- Register -->
    <div class="modal fade" id="register" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel"><img alt="Vue logo" src="../assets/logo.png" width="30">
              Register</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="name" class="col-form-label">Name:</label>
                <input type="text"
                  v-bind:class="'form-control ' + (error_register && error_register.name ? 'border-danger' : '')" id="name"
                  v-model="p_register.name">
                <span v-if="error_register && error_register.name">
                  <span class="text-danger">{{ error_register.name[0] }}</span>
                </span>
              </div>
              <div class="mb-3">
                <label for="username" class="col-form-label">Username:</label>
                <input type="text"
                  v-bind:class="'form-control ' + (error_register && error_register.username ? 'border-danger' : '')"
                  id="username" v-model="p_register.username">
                <span v-if="error_register && error_register.username">
                  <span class="text-danger">{{ error_register.username[0] }}</span>
                </span>
              </div>
              <div class="mb-3">
                <label for="password" class="col-form-label">Password:</label>
                <input type="password"
                  v-bind:class="'form-control ' + (error_register && error_register.password ? 'border-danger' : '')"
                  id="password" v-model="p_register.password">
                <span v-if="error_register && error_register.password">
                  <span class="text-danger">{{ error_register.password[0] }}</span>
                </span>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" v-on:click="register()">Register</button>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Login -->
    <div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel"><img alt="Vue logo" src="../assets/logo.png" width="30">
              Login</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label for="username_login" class="col-form-label">Username:</label>
                <input type="text"
                  v-bind:class="'form-control ' + (error_login && error_login.username ? 'border-danger' : '')"
                  id="username_login" v-model="p_login.username">
                <span v-if="error_login && error_login.username">
                  <span class="text-danger">{{ error_login.username[0] }}</span>
                </span>
              </div>
              <div class="mb-3">
                <label for="password_login" class="col-form-label">Password:</label>
                <input type="password"
                  v-bind:class="'form-control ' + (error_login && error_login.password ? 'border-danger' : '')"
                  id="password_login" v-model="p_login.password">
                <span v-if="error_login && error_login.password">
                  <span class="text-danger">{{ error_login.password[0] }}</span>
                </span>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" v-on:click="login()">Login</button>
          </div>
        </div>
      </div>
    </div>
</template>
  
<script>
import axios from 'axios';
import LoadingComponent from '@/components/LoadingComponent';

export default {
  components: {
    LoadingComponent,
  },
  data() {
    return {
      p_register: {
        name: null,
        username: null,
        password: null,
      },
      p_login: {
        username: null,
        password: null,
      },
      error_register: null,
      error_login: null,
      is_login: false,
    }
  },
  mounted() {
    this.checkLogin();
  },
  methods: {
    async register() {
      try {
        const response = await axios.post("auth/register", this.p_register);
        Swal.fire({
          title: response.statusText,
          text: response.data.message,
          icon: "success"
        });
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: error.response.statusText,
          text: error.response.data.message,
        });
        this.error_register = error.response.data.errors;
      }
    },
    async login() {
      try {
        const response = await axios.post("auth/login", this.p_login);
        localStorage.setItem("token", response.data.token);
        location.reload();
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: error.response.statusText,
          text: error.response.data.message,
        });
        this.error_login = error.response.data.errors;
      }
    },
    async logout() {
      try {
        const response = await axios.post("auth/logout", null);
        localStorage.removeItem("token");
        location.reload();
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: error.response.statusText,
          text: error.response.data.message,
        });
        this.error_login = error.response.data.errors;
      }
    },
    checkLogin() {
      if (localStorage.getItem("token")) {
        this.is_login = true;
      }
    },
  }
}

</script>

  