<template>
  <div class="home">
    <!-- Content -->
    <template v-if="photos.photos">
      <div class="container overflow-hidden">
        <div class="row gy-3 g-md-3 hcf-isotope-grid mt-3">
          <div class="col-6 col-lg-3 hcf-isotope-item" v-for="data in photos.photos">
            <div @click.prevent="move(data.slug)" class="hcf-masonry-card rounded rounded-4" style="cursor: pointer;">
              <img class="card-img img-fluid" loading="lazy" :src="photos_url + data.name_image" alt="">
              <div class="card-overlay d-flex flex-column justify-content-center bg-dark p-4"
                style="--bs-bg-opacity: .5;">
                <h3 class="card-title text-white text-center mb-1">{{ data.title }}</h3>
                <div class="card-category text-white text-center">{{ data.category_name.join(",") }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import axios from "axios"
import router from "@/router"
export default {
  data() {
    return {
      photos: [],
      photos_url: localStorage.getItem('photos_url'),
    }
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get("user/photos");
        this.photos = response.data;
      } catch (error) {
        console.log(error.response.data.message);
      }
    },
    move(slug) {
      router.push("/photos/" + slug).then(() => {
        router.go();
      });
    }
  }
}
</script>
