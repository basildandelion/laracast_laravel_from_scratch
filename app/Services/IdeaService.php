<?php

namespace App\Services;

use App\Enums\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IdeaService
{
    public function getIdeas(Request $request): array
    {
        $user = Auth::user();
        $status = $request->query('status');
        $requestedStatus = is_string($status) && in_array($status, IdeaStatus::values(), true)
            ? $status
            : 'all';

        $ideas = $user->ideas()
            ->when($requestedStatus !== 'all', fn ($query) => $query->where('status', $requestedStatus))
            ->latest()
            ->paginate(10);

        $counts = Idea::countByStatus($user);
        $statuses = IdeaStatus::cases();

        return [$ideas, $counts, $requestedStatus, $statuses];
    }

    public function createOrUpdateIdea(array $data, ?Idea $idea = null): Idea
    {
        if (!$idea) {
            return $this->createIdea($data);
        }

        if (! empty($data['image']) && ! empty($idea['image_path'])) {
            $this->deleteImage($idea['image_path']);
        }
        if (! empty($data['image'])) {
            $idea['image_path'] = $this->storeImage($data['image']);
        }
        $idea->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
            'links' => ! empty($data['links']) ? $data['links'] : [],
        ]);
        $this->createOrUpdateIdeaSteps($idea, $data['steps'] ?? []);

        return $idea;
    }

    private function createIdea(array $data): Idea
    {
        $idea = [
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
        ];
        $idea['links'] = ! empty($data['links']) ? $data['links'] : [];

        if ($image = $data['image']) {
            $idea['image_path'] = $this->storeImage($image);
        }

        $idea = Idea::create($idea);

        $this->createOrUpdateIdeaSteps($idea, $data['steps']);

        return $idea;
    }

    private function storeImage($image)
    {
        return $image ? $image->store('ideas', 'public') : null;
    }

    private function deleteImage($image)
    {
        if ($image) {
            Storage::disk('public')->delete($image);
        }
    }

    private function createOrUpdateIdeaSteps(Idea $idea, array $steps = []): void
    {
        $idea->steps()->delete();
        foreach ($steps as $step) {
            $idea->steps()->create(
                [
                    'description' => $step['description'],
                    'completed' => $step['completed'],
                ]
            );
        }
    }
}
