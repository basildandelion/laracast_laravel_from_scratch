<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\IdeaStatus;
use App\Http\Requests\IdeaRequest;
use App\Http\Resources\IdeaResource;
use App\Models\Idea;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class IdeaController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Idea::class);
        $user = Auth::user();
        $status = $request->query('status');
        $requestedStatus = is_string($status) && in_array($status, IdeaStatus::values(), true)
            ? $status
            : 'all';

        $ideas = $user->ideas()
            ->when($requestedStatus !== 'all', fn ($query) => $query->where('status', $requestedStatus))
            ->paginate(10);

        $counts = Idea::countByStatus($user);

        return Inertia::render('Ideas/Index', ['items' => IdeaResource::collection($ideas), 'counts' => $counts, 'requestedStatus' => $requestedStatus]);
    }

    public function store(IdeaRequest $request)
    {
        $this->authorize('create', Idea::class);

        return Idea::create($request->validated());
    }

    public function edit(Idea $idea): Response
    {
        $this->authorize('update', $idea);

        return Inertia::render('Ideas/Edit', ['idea' => new IdeaResource($idea)]);
    }

    public function show(Idea $idea): Response
    {
        $this->authorize('view', $idea);

        return Inertia::render('Ideas/Show', ['idea' => new IdeaResource($idea)]);
    }

    public function update(IdeaRequest $request, Idea $idea): Idea
    {
        $this->authorize('update', $idea);

        $idea->update($request->validated());

        return $idea;
    }

    public function destroy(Idea $idea)
    {
        $this->authorize('delete', $idea);

        $idea->delete();

        return redirect()->route('ideas.index')->with('success', 'Idea deleted successfully.');
    }
}
