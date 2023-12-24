<template>
  <div class="home">
    <section class="bg-light py-5 py-xl-6">
      <div class="container mt-5">
        <div class="row justify-content-md-center">
          <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6 text-center">
            <template v-if="user">
              <h2 class="display-5">{{ user.name }}</h2>
              <p class="mb-4 text-primary">@{{ user.username }}</p>
            </template>
          </div>
        </div>
      </div>

      <div class="container overflow-hidden">
        <form @submit.prevent="createCategory" class="mb-3">
          <div class="d-flex">
            <input type="text" class="form-control" id="exampleInputText1" placeholder="Add categories"
              style="border-radius: 10px 0px 0px 10px;" v-model="category_name">
            <button type="submit" class="btn btn-primary" style="border-radius: 0px 10px 10px 0px;">Submit</button>
          </div>
          <span class="text-danger" v-if="error && error.category_name">{{ error.category_name[0] }}</span>
        </form>
        <div class="row">
          <div class="col-6 col-lg-3 mb-2" v-for="data in categories">
            <router-link :to="'/category/' + data.category_name"
              class="btn w-100 px-5 py-4 text-white category rounded-top" v-bind:style="{
                backgroundColor: 'gray',
                background: 'linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url(' + photos_url + data.thumbnail + ')',
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                fontWeight: 'bold',
                fontSize: '1.3rem',
                borderRadius: '10px 10px 0px 0px',
              }">
              {{ data.category_name }}
              <br>
            </router-link>
            <button class="btn btn-danger w-100" style="border-radius: 0px 0px 10px 10px;"
              @click="deleteCategory(data.category_name)">Delete</button>
          </div>
        </div>
        <hr class="mx-auto mb-0 text-secondary">
      </div>
      <!-- Content -->
      <PhotosUserComponent />
    </section>
  </div>
</template>
<script>
import PhotosUserComponent from '@/components/PhotosUserComponent.vue'
import axios from "axios"
export default {
  components: {
    PhotosUserComponent,
  },
  data() {
    return {
      user: null,
      categories: [],
      photos_url: localStorage.getItem('photos_url'),
      category_name: "",
      error: null,
    }
  },
  created() {
    this.getUser();
    this.fetchDataCategories();
  },
  methods: {
    async getUser() {
      try {
        const response = await axios.get("auth/profile");
        this.user = response.data.user;
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: error.response.statusText,
          text: error.response.data.message,
        });
      }
    },
    async fetchDataCategories() {
      try {
        const response = await axios.get("user/category");
        this.categories = response.data.category;
      } catch (error) {
        console.log(error.response.data.message);
      }
    },
    async createCategory() {
      try {
        const response = await axios.post("category", { category_name: this.category_name });
        Swal.fire({
          title: response.statusText,
          text: response.data.message,
          icon: "success"
        }).then(() => {
          window.location.reload();
        });
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: error.response.statusText,
          text: error.response.data.message,
        });
        this.error = error.response.data.errors;
        console.log(error.response.data);
      }
    },
    async deleteCategory(name) {
      try {
        const response = await axios.delete("category/" + name);
        Swal.fire({
          title: response.statusText,
          text: response.data.message,
          icon: "success"
        }).then(() => {
          window.location.reload();
        });
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: error.response.statusText,
          text: error.response.data.message,
        });
        this.error = error.response.data.errors;
        console.log(error.response.data);
      }
    },
  },
}
</script>