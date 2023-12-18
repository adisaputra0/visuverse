<template>
    <NavbarComponent />
    <div class="categories">
        <section class="bg-light py-5 py-xl-6">
            <div class="container my-5 mb-md-6">
                <div class="row justify-content-md-center">
                    <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6 text-center">
                        <h2 class="mb-4 display-5"><img alt="Vue logo" src="../assets/logo.png" width="50"> Categories</h2>
                        <hr class="w-50 mx-auto mb-0 text-secondary">
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="container overflow-hidden">
                <div class="row">
                    <div class="col-6 col-lg-3 mb-2" v-for="data in categories">
                        <router-link :to="'/category/' + data.category_name" class="btn w-100 px-5 py-4 text-white category"
                            v-bind:style="{
                                background: 'linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url(' + photos_url + data.thumbnail + ')',
                                backgroundSize: 'cover',
                                backgroundPosition: 'center',
                                fontWeight: 'bold',
                                fontSize: '1.3rem'
                            }">
                            {{ data.category_name }}
                        </router-link>

                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
  
<script>
import axios from "axios"
import NavbarComponent from '@/components/NavbarComponent.vue'
export default {
    components: {
        NavbarComponent,
    },
    data() {
        return {
            categories: [],
            photos_url: localStorage.getItem('photos_url'),
        }
    },
    created() {
        this.fetchDataCategories();
    },
    methods: {
        async fetchDataCategories() {
            try {
                const response = await axios.get("category");
                this.categories = response.data.category;
            } catch (error) {
                console.log(error.response.data.message);
            }
        }
    }
}
</script>
  