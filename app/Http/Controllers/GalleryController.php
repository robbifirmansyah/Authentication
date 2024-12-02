<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'id' => "posts",
            'menu' => "Gallery",
            'galleries' => Post::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(30)
        ];
        return view('gallery.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);

        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $filenameSimpan = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);
        } else {
            $filenameSimpan = 'noimage.png';
        }

        $post = new Post;
        $post->picture = $filenameSimpan;
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();

        return redirect('gallery')->with('success', 'Berhasil menambahkan data baru');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Post::findOrFail($id);
        return view('gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);

        $gallery = Post::findOrFail($id);

        if ($request->hasFile('picture')) {
            // Delete old image if exists
            if ($gallery->picture != 'noimage.png') {
                Storage::delete('posts_image/' . $gallery->picture);
            }
            // Store new image
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $filenameSimpan = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);
            $gallery->picture = $filenameSimpan;
        }

        $gallery->title = $request->input('title');
        $gallery->description = $request->input('description');
        $gallery->save();

        return redirect('gallery')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Post::findOrFail($id);

        if ($gallery->picture != 'noimage.png') {
            Storage::delete('posts_image/' . $gallery->picture);
        }

        $gallery->delete();

        return redirect('gallery')->with('success', 'Data berhasil dihapus');
    }

    /**
     * API: Retrieve a JSON list of galleries with images.
     * 
     * @OA\Get(
     *     path="/api/gallery",
     *     summary="Get all galleries with pictures",
     *     description="Retrieve a JSON list of galleries with images.",
     *     @OA\Response(
     *         response=200,
     *         description="List of galleries",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Gallery 1"),
     *                 @OA\Property(property="description", type="string", example="A beautiful image"),
     *                 @OA\Property(property="picture", type="string", example="posts_image/image1.jpg")
     *             )
     *         )
     *     )
     * )
     */
    public function apiGallery()
    {
        $galleries = Post::where('picture', '!=', '')->whereNotNull('picture')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'description', 'picture']);

        return response()->json($galleries, 200);
    }
}
