<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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

        $ideas = $user->ideas()
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->paginate(10);

        $counts = Idea::countByStatus($user);

        $requestedStatus = $request->query('status', 'all');

        return Inertia::render('Ideas/Index', ['items' => IdeaResource::collection($ideas), 'counts' => $counts, 'requestedStatus' => $requestedStatus]);
    }

    public function store(IdeaRequest $request)
    {
        $this->authorize('create', Idea::class);

        return Idea::create($request->validated());
    }

    public function show(Idea $idea): Idea
    {
        $this->authorize('view', $idea);

        return $idea;
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

        return response()->json();
    }
}
