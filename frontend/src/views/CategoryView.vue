
<template>
  <div class="home">
    <section class="bg-light py-5 py-xl-6">
      <div class="container my-5 mb-md-6">
        <div class="row justify-content-md-center">
          <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6 text-center">
            <h2 class="mb-4 display-5"><img alt="Vue logo" src="../assets/logo.png" width="50"> {{ category_name }}</h2>
            <hr class="w-50 mx-auto mb-0 text-secondary">
          </div>
        </div>
      </div>

      <!-- Content -->
      <div class="container overflow-hidden">
        <div class="row gy-3 g-md-3 hcf-isotope-grid mt-3">
          <div class="col-6 col-lg-3 hcf-isotope-item" v-for="data in photos">
            <router-link :to="'/photos/' + data.slug" class="hcf-masonry-card rounded rounded-4">
              <img class="card-img img-fluid" loading="lazy" :src="photos_url + data.name_image" alt="">
              <div class="card-overlay d-flex flex-column justify-content-center bg-dark p-4"
                style="--bs-bg-opacity: .5;">
                <h3 class="card-title text-white text-center mb-1">{{ data.title }}</h3>
                <div class="card-category text-white text-center">{{ data.category_name.join(",") }}</div>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>

import axios from "axios"
export default {
  data() {
    return {
      photos: [],
      category_name: this.$route.params.category_name,
      photos_url: localStorage.getItem('photos_url'),
    }
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get("category/" + this.category_name);
        this.photos = response.data.photos;
      } catch (error) {
        console.log(error.response.data.message);
      }
    }
  }
}
</script>