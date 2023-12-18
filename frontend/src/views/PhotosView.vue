<template>
    <div class="photos">

        <section class="bg-light py-5 py-xl-6">
            <!-- Detail -->
            <template v-if="photo">
                <div class="card my-5 mb-md-6 mx-auto container p-0">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img :src="photos_url + photo.photos.name_image" class="img-fluid rounded-start" alt="..." style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="row gap-3 gap-md-0">
                                    <h5 class="card-title fs-1 col-12 col-md-6 order-2 order-md-1">{{ photo.photos.title }}</h5>
                                    <div class="d-flex justify-content-md-end align-items-center col-12 col-md-6 gap-3 order-1 order-md-2">
                                        <i class="fa-solid fa-download fs-5" @click="downloadPhoto()"></i>
                                        <!-- <a :href="photos_url + photo.photos.name_image" download><i class="fa-solid fa-download fs-5"></i></a> -->
                                        <i class="fa-solid fa-eye fs-5" data-bs-toggle="modal" data-bs-target="#preview-image"></i>

                                        <template v-if="this.auth">
                                            <div class="d-flex align-items-center justify-content-center gap-1"><i :class="(isLike? 'fa-solid' : 'fa-regular') + ' fa-heart fs-5 text-danger'" @click="checkLikes()"></i><span class="fs-6 text-danger">{{ photo.total_likes }}</span></div>
                                        </template>
                                        <template v-else>
                                            <div class="d-flex align-items-center justify-content-center gap-1"><i class="fa-regular fa-heart fs-5 text-danger" @click="checkLikes()"></i><span class="fs-6 text-danger">{{ photo.total_likes }}</span></div>
                                        </template>
                                    </div>
                                </div>
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
                                        <p class="border-bottom pb-2" v-for="data in comments" :key="data.id">
                                            <b>{{ data.user.name }}</b>
                                            <br>{{ data.comment_text }} 
                                            <br>
                                            <span v-if="user && data.user_id === user.id">
                                                <span class="text-primary pointer" @click="editComment(slug, data.id)">edit</span> |
                                                <span class="text-danger pointer" @click="deleteComment(slug, data.id)">delete</span>    
                                            </span>
                                        </p>
                                    </template>
                                    <template v-else>
                                        <p class="text-body-secondary">No Comments</p>
                                    </template>
                                </div>
                                <form class="d-flex gap-1 mt-1" @submit.prevent="isEdit ? updateComment(slug, comment_id) : postComment(slug)">
                                    <input type="text" class="w-100 form-control" placeholder="Comment" v-model="comment_text">
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
                
                <!-- Modal Preview Image -->
                <div class="modal fade fade-custom" id="preview-image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-1">
                                <img :src="photos_url + photo.photos.name_image" alt="" width="100%" class="rounded">
                                <button type="button" class="close-preview-image" data-bs-dismiss="modal"><i class="fa-solid fa-x"></i></button>
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
            slug: this.$route.params.slug,
            auth: false,
            isLike: false,
            comments: null,
            comment_text: null,
            user: null,
            isEdit: false,
            comment_id: null,
        }
    },
    created() {
        this.fetchDataDetail(this.slug);
        this.getComments(this.slug);
        if(localStorage.getItem("token")){
            this.auth = true;
            this.getUser();
            this.getLike(this.slug);
        }
    },
    methods: {
        async getUser() {
            try {
                const response = await axios.get("auth/profile");
                this.user = response.data.user;
            } catch (error) {
                console.log(error.response.data.message);
            }
        },
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
        },
        async getLike(slug) {
            try {
                const response = await axios.get("photos/" + slug + "/like");
                this.isLike = response.data.like;
            } catch (error) {
                console.log(error.response.data.message);
            }
        },
        checkLikes(){
            if(this.isLike){
                this.unlike(this.slug);
            }else{
                this.like(this.slug);
            }
        },
        async like(slug) {
            try {
                const response = await axios.post("photos/" + slug + "/like");
                window.location.reload();
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: error.response.statusText,
                    text: error.response.data.message,
                });
            }
        },
        async unlike(slug) {
            try {
                const response = await axios.delete("photos/" + slug + "/like");
                window.location.reload();
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: error.response.statusText,
                    text: error.response.data.message,
                });
            }
        },
        async getComments(slug) {
            try {
                const response = await axios.get("photos/" + slug + "/comments");
                this.comments = response.data.comments;
            } catch (error) {
                console.log(error.response.data.message);
            }
        },
        async postComment(slug) {
            try {
                const response = await axios.post("photos/" + slug + "/comments", {"comment_text": this.comment_text});
                window.location.reload();
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: error.response.statusText,
                    text: error.response.data.message,
                });
            }
        },
        async editComment(slug, id_comment) {
            try {
                const response = await axios.get("photos/" + slug + "/comments/" + id_comment);
                this.isEdit = true;
                this.comment_id = id_comment;
                this.comment_text = response.data.comment;
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: error.response.statusText,
                    text: error.response.data.message,
                });
            }
        },
        async updateComment(slug, id_comment) {
            try {
                const response = await axios.post("photos/" + slug + "/comments/" + id_comment, {"comment_text": this.comment_text});
                window.location.reload();
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: error.response.statusText,
                    text: error.response.data.message,
                });
            }
        },
        async deleteComment(slug, id_comment) {
            try {
                const response = await axios.delete("photos/" + slug + "/comments/" + id_comment);
                window.location.reload();
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: error.response.statusText,
                    text: error.response.data.message,
                });
            }
        },
        async downloadPhoto() {
            try {
                const response = await axios({
                    url: this.photos_url + this.photo.photos.name_image + "/download",
                    method: 'GET',
                    withCredentials: false,
                    responseType: 'blob',
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', this.photo.photos.name_image);
                document.body.appendChild(link);
                link.click();

                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error(error);
            }
        },
    },
}
</script>