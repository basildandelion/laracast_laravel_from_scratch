<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\IdeaStatus;
use App\Http\Requests\IdeaRequest;
use App\Http\Resources\IdeaResource;
use App\Models\Idea;
use App\Services\IdeaService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class IdeaController extends Controller
{
    use AuthorizesRequests;

    private $ideaService;

    public function __construct(IdeaService $ideaService)
    {
        $this->ideaService = $ideaService;
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Idea::class);

        [$ideas, $counts, $requestedStatus, $statuses] = $this->ideaService->getIdeas($request);

        return Inertia::render('Ideas/Index', ['items' => IdeaResource::collection($ideas), 'counts' => $counts, 'requestedStatus' => $requestedStatus, 'statuses' => $statuses]);
    }

    public function store(IdeaRequest $request): RedirectResponse
    {
        $this->authorize('create', Idea::class);
        $validated = $request->validated();

        $this->ideaService->createOrUpdateIdea($validated);

        return redirect()->route('ideas.index')->with('success', 'Idea created successfully.');
    }

    public function show(Idea $idea): Response
    {
        $this->authorize('view', $idea);
        $statuses = IdeaStatus::cases();
        $steps = $idea->steps()->get();

        return Inertia::render('Ideas/Show', ['idea' => new IdeaResource($idea), 'statuses' => $statuses, 'steps' => $steps]);
    }

    public function update(IdeaRequest $request, Idea $idea): RedirectResponse
    {
        $this->authorize('update', $idea);

        $data = $request->validated();

        $this->ideaService->createOrUpdateIdea($data, $idea);

        return redirect()
            ->back()
            ->with('success', 'Idea updated successfully');
    }

    public function destroy(Idea $idea): RedirectResponse
    {

        $this->authorize('delete', $idea);
        // delete an image if exists
        if ($idea->image_path) {
            Storage::disk('public')->delete($idea->image_path);
        }
        $idea->steps()->delete();
        $idea->delete();

        return redirect()->route('ideas.index')->with('error', 'Idea deleted successfully.');
    }

    public function updateStatus(Idea $idea, Request $request): RedirectResponse
    {
        if ($idea->status !== $request->status) {
            $idea->update(['status' => $request->status]);
        }

        return redirect()
            ->back()
            ->with('success', 'Idea status updated successfully');
    }

    public function updateStepCompletion(Idea $idea, int $stepId): RedirectResponse
    {
        $this->authorize('update', $idea);
        $step = $idea->steps()->find($stepId);
        if (! $step) {
            abort(404);
        }
        $step->update(['completed' => ! $step->completed]);

        return redirect()
            ->back()
            ->with('success', 'Step completion updated successfully');
    }
}
