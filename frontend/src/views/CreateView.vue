<template>
    <section class="bg-light py-5 py-xl-6">
        <div class="card my-5 mb-md-6 mx-auto container p-0">
            <form class="row g-0" @submit.prevent="createPhoto" enctype="multipart/form-data">
                <div class="col-md-4">
                    <div class="bg-secondary d-flex align-items-center justify-content-center position-relative h-100 h-md-30" id="preview-image">
                        <i class="fa-solid fa-image position-absolute w-100 left-0 d-flex justify-content-center fs-md-5 fs-10"></i>
                        <input class="form-control w-100 h-100 opacity-0 position-absolute left-0" id="formFileLg" type="file" accept="image/*" @input="handleImageUpload">
                        <img ref="image" class="img-fluid rounded-start d-none w-100 h-100" alt="..." style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Title</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" v-model="data_post.title">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="8" v-model="data_post.description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="categories" class="form-label">Categories</label>
                            <select class="js-example-basic-multiple" name="states[]" multiple="multiple" id="categories">
                                <template v-for="category in categories" :key="category.id">
                                    <option :value="category.id">{{ category.category_name }}</option>
                                </template>
                            </select>
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
            }
        },
        created() {
            this.fetchDataCategories();
        },
        mounted() {
            this.initializeSelect2();
        },
        methods: {
            async fetchDataCategories() {
                try {
                    const response = await axios.get("category");
                    this.categories = response.data.category;
                } catch (error) {
                    console.log(error.response.data.message);
                }
            },
            async createPhoto() {
                try {
                    this.data_post.categories_id = this.data_post.categories_id.join(",");
                    const response = await axios.post("user/photos", this.data_post);
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
                    console.log(error.response.data);
                }
            },
            handleImageUpload(event) {
                const imageElement = this.$refs.image;
                if (event.target.files && event.target.files[0]) {
                    const file = event.target.files[0];
                    imageElement.src = URL.createObjectURL(event.target.files[0]);
                    this.data_post.image = file;
                    console.log(this.data_post.image);
                    imageElement.classList.remove("d-none");
                    document.querySelector(".fa-image").classList.add("d-none");
                    document.querySelector("#preview-image").classList.remove("bg-secondary");
                    document.querySelector("#preview-image").classList.remove("h-md-30");
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