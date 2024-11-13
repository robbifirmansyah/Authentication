@extends('auth.layouts')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Edit Picture
                </div>
                <div class="card-body">
                    <!-- Display success or error messages -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Edit Form -->
                    <form action="{{ route('gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $gallery->title }}" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required>{{ $gallery->description }}</textarea>
                        </div>

                        <div class="form-group mt-3">
                            <label for="picture">Picture</label>
                            <input type="file" name="picture" id="picture" class="form-control-file">
                            @if ($gallery->picture)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/posts_image/' . $gallery->picture) }}" alt="Current Image" class="img-fluid" style="max-width: 200px;">
                                    <p class="text-muted">Current Image</p>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
                        <a href="{{ route('gallery.index') }}" class="btn btn-secondary mt-4">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
