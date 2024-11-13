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
                <div class="row">
                    @if ($galleries->count() > 0)
                        @foreach ($galleries as $gallery)
                            <div class="col-sm-2 mb-3">
                                <div>
                                    <a class="example-image-link"
                                        href="{{ asset('storage/posts_image/' . $gallery->picture) }}"
                                        data-lightbox="roadtrip" data-title="{{ $gallery->description }}">
                                        <img class="example-image img-fluid mb-2"
                                            src="{{ asset('storage/posts_image/' . $gallery->picture) }}"
                                            alt="image-1" />
                                    </a>
                                </div>
                                <a href="{{ route('gallery.edit', $gallery->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('gallery.destroy', $gallery->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <h3 class="text-center">Tidak ada data.</h3>
                    @endif
                </div>
                <div class="d-flex justify-content-center">
                    {{ $galleries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
