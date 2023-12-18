<template>
  <div class="home">
    <section class="bg-light py-5 py-xl-6">
      <div class="container my-5 mb-md-6">
        <div class="row justify-content-md-center">
          <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6 text-center">
            <template v-if="user">
              <h2 class="display-5">{{ user.name }}</h2>
              <p class="mb-4 text-primary">@{{ user.username }}</p>
            </template>
            <hr class="w-50 mx-auto mb-0 text-secondary">
          </div>
        </div>
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
        }
    },
    created() {
      this.getUser();
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
    },
}
</script>