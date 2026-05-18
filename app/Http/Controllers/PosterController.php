<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use App\Http\Resources\PosterResource;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PosterController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Poster::query()
            ->with(['user', 'categories']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        $query->orderBy(
            $request->get('sort', 'created_at'),
            $request->get('direction', 'desc')
        );

        return PosterResource::collection($query->paginate());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'content' => ['required'],
        ]);

        $poster = Poster::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'draft', // important for your test
        ]);

        return new PosterResource($poster->load(['user', 'categories']));
    }

    public function show(Poster $poster)
    {
        $this->authorize('view', $poster);

        return new PosterResource(
            $poster->load(['user', 'categories'])
        );
    }

    public function update(Request $request, Poster $poster)
    {
        $this->authorize('update', $poster);

        $validated = $request->validate([
            'title' => ['sometimes', 'string'],
            'content' => ['sometimes'],
            'status' => ['sometimes'],
        ]);

        $poster->update($validated);

        return new PosterResource(
            $poster->fresh()->load(['user', 'categories'])
        );
    }

    public function destroy(Poster $poster)
    {
        $this->authorize('delete', $poster);

        $poster->delete();

        return response()->json([
            'message' => 'Poster deleted successfully',
        ]);
    }
}