<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdeaRequest;
use App\Http\Resources\IdeaResource;
use App\Models\Idea;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class IdeaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Idea::class);

        $ideas = Auth::user()->ideas()->paginate(10);

        return Inertia::render('Ideas/Index', ['ideas' => IdeaResource::collection($ideas)]);
    }

    public function store(IdeaRequest $request)
    {
        $this->authorize('create', Idea::class);

        return Idea::create($request->validated());
    }

    public function show(Idea $idea)
    {
        $this->authorize('view', $idea);

        return $idea;
    }

    public function update(IdeaRequest $request, Idea $idea)
    {
        $this->authorize('update', $idea);

        $idea->update($request->validated());

        return $idea;
    }

    public function destroy(Idea $idea)
    {
        $this->authorize('delete', $idea);

        $idea->delete();

        return response()->json();
    }
}
