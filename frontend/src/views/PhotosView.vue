<template>
    <div class="photos">

        <section class="bg-light py-5 py-xl-6">
            <!-- Detail -->
            <template v-if="photo">
                <div class="card my-5 mb-md-6 mx-auto container p-0">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img :src="photos_url + photo.photos.name_image" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title fs-1">{{ photo.photos.title }}</h5>
                                <p class="card-text">{{ photo.photos.description }}</p>
                                <div class="d-flex mb-3">
                                    <div class="d-flex gap-2 pb-2" style="width: 100%; overflow: auto; height: 50px;"
                                        v-for="data in photo.photos.category_name">
                                        <router-link :to="'/category/' + data" class="btn text-white btn-primary pt-2">
                                            {{ data }}
                                        </router-link>
                                    </div>
                                </div>
                                <div class="comentars mx-auto border w-100 p-3 rounded" style="height: 150px;">
                                    <template v-if="photo.total_comment != 0">
                                        <p class="border-bottom pb-2">
                                            <b>Adi</b>
                                            <br>keren
                                        </p>
                                    </template>
                                    <template v-else>
                                        <p class="text-body-secondary">No Comments</p>
                                    </template>
                                </div>
                                <form class="d-flex gap-1 mt-1">
                                    <input type="text" class="w-100 form-control" placeholder="Comment">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                                <p class="card-text mt-2 ms-1"><small class="text-body-secondary">{{
                                    photo.photos.updated_at
                                }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <!-- Content -->
            <PhotosComponent />
        </section>
    </div>
</template>
  
<script>
import router from "@/router";
import PhotosComponent from '@/components/PhotosComponent.vue'
import axios from "axios"
export default {
    components: {
        PhotosComponent,
    },
    data() {
        return {
            photo: null,
            photos_url: localStorage.getItem('photos_url'),
        }
    },
    created() {
        this.fetchDataDetail(this.$route.params.slug);
    },
    methods: {
        async fetchData() {
            try {
                const response = await axios.get("photos");
                this.photos = response.data.photos;
            } catch (error) {
                console.log(error.response.data.message);
            }
        },
        async fetchDataDetail(slug) {
            try {
                const response = await axios.get("photos/" + slug);
                this.photo = response.data;
            } catch (error) {
                console.log(error.response.data.message);
            }
        }
    }
}
</script>
  