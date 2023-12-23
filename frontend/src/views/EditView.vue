<template>
    <section class="bg-light py-5 py-xl-6">
        <div class="card my-5 mb-md-6 mx-auto container p-0" v-if="photo">
            <form class="row g-0" @submit.prevent="updatePhoto" enctype="multipart/form-data">
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-center position-relative h-100" id="preview-image">
                        <input class="form-control w-100 h-100 opacity-0 position-absolute left-0" id="formFileLg" type="file" accept="image/*" @input="handleImageUpload">
                        <img ref="image" class="img-fluid rounded-start w-100 h-100" alt="..." style="object-fit: cover;" :src="photos_url + photo.photos.name_image">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Title</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" v-model="data_post.title">
                            <span class="text-danger" v-if="error && error.title">{{ error.title[0] }}</span>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="8" v-model="data_post.description"></textarea>
                            <span class="text-danger" v-if="error && error.description">{{ error.description[0] }}</span>
                        </div>
                        <div class="mb-3">
                            <label for="categories" class="form-label">Categories</label>
                            <select class="js-example-basic-multiple" name="states[]" multiple="multiple" id="categories">
                                <template v-for="category in categories" :key="category.id">
                                    <option :value="category.id" :selected="data_post.categories_id.includes(category.id.toString())">{{ category.category_name }}</option>
                                </template>
                            </select>
                            <span class="text-danger" v-if="error && error.categories_id">{{ error.categories_id[0] }}</span>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</template>

<script>
    import axios from "axios"
    import router from "@/router"
    export default {
        data() {
            return {
                categories: [],
                data_post: {
                    title: "",
                    image: null,
                    description: "",
                    categories_id: [],
                },
                photo: null,
                photos_url: localStorage.getItem('photos_url'),
                slug: this.$route.params.slug,
                error: null,
            }
        },
        mounted(){
            this.fetchDataDetail();
            this.fetchDataCategories();
        },
        methods: {
            async fetchDataCategories() {
                try {
                    const response = await axios.get("category");
                    this.categories = response.data.category;
                    this.initializeSelect2();
                } catch (error) {
                    console.log(error.response.data.message);
                }
            },
            async fetchDataDetail() {
                try {
                    const response = await axios.get("photos/" + this.slug);
                    this.photo = response.data;          
                    this.data_post.title = this.photo.photos.title;
                    this.data_post.description = this.photo.photos.description;
                    
                    let categories = this.photo.photos.categories_id.split(",");
                    categories.forEach((e) => {
                        this.data_post.categories_id.push(e);
                    });
                    console.log(this.data_post.categories_id);
                } catch (error) {
                    console.log(error.response.data.message);
                }
            },
            async updatePhoto() {
                try {
                    if(Array.isArray(this.data_post.categories_id)){
                        this.data_post.categories_id = this.data_post.categories_id.join(",");
                    }
                    const response = await axios.post("user/photos/"+this.photo.photos.id, this.data_post);
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
                    this.error = error.response.data.errors;
                    console.log(error.response.data);
                }
            },
            handleImageUpload(event) {
                const imageElement = this.$refs.image;
                if (event.target.files && event.target.files[0]) {
                    const file = event.target.files[0];
                    imageElement.src = URL.createObjectURL(event.target.files[0]);
                    this.data_post.image = file;
                }
            },
            initializeSelect2() {
                const vm = this;
                $('.js-example-basic-multiple').select2({
                    width: '100%'
                }).on('change', function() {
                    var selectedValues = $(this).val();
                    vm.data_post.categories_id = selectedValues;
                });
            },
        }
    }
</script>