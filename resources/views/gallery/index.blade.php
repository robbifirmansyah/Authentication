@extends('auth.layouts')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Dashboard</span>
                <!-- Add Picture Button -->
                <a href="{{ route('gallery.create') }}" class="btn btn-primary">Add Picture</a>
            </div>
            <div class="card-body">
                <div class="row" id="gallery-container">
                    <!-- Data dari API akan dimuat di sini -->
                </div>
                <div class="d-flex justify-content-center" id="pagination-container">
                    <!-- Pagination akan dimuat di sini jika diperlukan -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // URL endpoint API
        const apiUrl = "/api/gallery";

        // Fungsi untuk mengambil data dari API
        function fetchGalleryData() {
            $.ajax({
                url: apiUrl,
                method: "GET",
                success: function (response) {
                    let galleryContainer = $('#gallery-container');
                    galleryContainer.empty(); // Kosongkan kontainer sebelum memuat data

                    if (response.length > 0) {
                        response.forEach(gallery => {
                            galleryContainer.append(`
                                <div class="col-sm-2 mb-3">
                                    <div>
                                        <a class="example-image-link"
                                            href="/storage/posts_image/${gallery.picture}"
                                            data-lightbox="roadtrip" data-title="${gallery.description}">
                                            <img class="example-image img-fluid mb-2"
                                                src="/storage/posts_image/${gallery.picture}" alt="${gallery.title}" />
                                        </a>
                                    </div>
                                    <a href="/gallery/${gallery.id}/edit" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="/gallery/${gallery.id}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            `);
                        });
                    } else {
                        galleryContainer.append(`<h3 class="text-center">Tidak ada data.</h3>`);
                    }
                },
                error: function () {
                    console.error("Gagal memuat data dari API.");
                }
            });
        }

        // Panggil fungsi untuk memuat data saat halaman pertama kali dimuat
        fetchGalleryData();
    });
</script>
@endsection
