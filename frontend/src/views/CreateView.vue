<template>
    <section class="bg-light py-5 py-xl-6">
        <div class="card my-5 mb-md-6 mx-auto container p-0">
            <div class="row g-0">
                <div class="col-md-4">
                    <div class="bg-secondary d-flex align-items-center justify-content-center position-relative h-100 h-md-30">
                        <i class="fa-solid fa-image position-absolute w-100 left-0 d-flex justify-content-center fs-md-5 fs-10"></i>
                        <input class="form-control w-100 h-100 opacity-0" id="formFileLg" type="file" accept="Image/*">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Title</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="8"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="categories" class="form-label">Categories</label>
                            <select class="js-example-basic-multiple" name="states[]" multiple="multiple" id="categories">
                                <template v-for="category in categories" :key="category.id">
                                    <option :value="category.category_name">{{ category.category_name }}</option>
                                </template>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import axios from "axios"
    export default {
        data() {
            return {
                categories: [],
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
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            width: '100%'
        });
    });
</script>