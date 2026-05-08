<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\IdeaStatus;
use App\Http\Requests\IdeaRequest;
use App\Http\Resources\IdeaResource;
use App\Models\Idea;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            ->when($requestedStatus !== 'all', fn ($query) => $query->where('status', $requestedStatus))->latest()
            ->paginate(10);

        $counts = Idea::countByStatus($user);
        $statuses = IdeaStatus::cases();

        return Inertia::render('Ideas/Index', ['items' => IdeaResource::collection($ideas), 'counts' => $counts, 'requestedStatus' => $requestedStatus, 'statuses' => $statuses]);
    }

    public function store(IdeaRequest $request): RedirectResponse
    {
        $this->authorize('create', Idea::class);
        $validated = $request->validated();
        $idea = [
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'links' => explode("\n", $validated['links']),
            'image_path' => $validated['image_path'],
        ];

        Idea::create($idea);

        return redirect()->route('ideas.index')->with('success', 'Idea created successfully.');
    }

    public function show(Idea $idea): Response
    {
        $this->authorize('view', $idea);
        $statuses = IdeaStatus::cases();

        return Inertia::render('Ideas/Show', ['idea' => new IdeaResource($idea), 'statuses' => $statuses]);
    }

    public function update(IdeaRequest $request, Idea $idea): RedirectResponse
    {
        $this->authorize('update', $idea);

        $data = $request->validated();

        $data['links'] = explode("\n", $data['links']);

        if ($request->hasFile('image')) {

            // delete old image if exists
            if ($idea->image_path) {
                Storage::disk('public')->delete($idea->image_path);
            }

            // store new image
            $data['image_path'] = $request
                ->file('image')
                ->store('ideas', 'public');
        }

        // remove non-db field
        unset($data['image']);

        $idea->update($data);

        return redirect()
            ->back()
            ->with('success', 'Idea updated successfully');
    }

    public function destroy(Idea $idea): RedirectResponse
    {
        $this->authorize('delete', $idea);

        $idea->delete();

        return redirect()->route('ideas.index')->with('error', 'Idea deleted successfully.');
    }
}
