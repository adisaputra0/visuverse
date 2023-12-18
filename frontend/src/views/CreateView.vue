<template>
    <section class="bg-light py-5 py-xl-6">
        <div class="card my-5 mb-md-6 mx-auto container p-0">
            <div class="row g-0">
                <div class="col-md-4">
                    <div class="bg-secondary p-5 d-flex align-items-center justify-content-center" style="height: 100%;">
                        <input class="form-control" id="formFileLg" type="file" accept="Image/*">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Title</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="title photo">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="8"></textarea>
                        </div>
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Categories</option>
                            <option value="1">Nature</option>
                            <option value="2">Anime</option>
                            <option value="3">Design</option>
                        </select>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
  