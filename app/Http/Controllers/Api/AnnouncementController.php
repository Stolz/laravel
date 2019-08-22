<?php

namespace App\Http\Controllers\Api;

use App\Models\Announcement;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{
    /**
     * Instance of the service used to interact with announcements.
     *
     * @var \App\Repositories\Contracts\AnnouncementRepository
     */
    protected $announcementRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\AnnouncementRepository $announcementRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\AnnouncementRepository $announcementRepository)
    {
        $this->announcementRepository = $announcementRepository;
    }

    /**
     * Display a list of announcements.
     *
     * @param  \App\Http\Requests\Announcement\Index $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(\App\Http\Requests\Announcement\Index $request): JsonResponse
    {
        // Get pagination options
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 15, 'id', 'desc');

        // Get announcements from repository
        $announcements = $this->announcementRepository->paginate($perPage, $page, $sortBy, $sortDirection);

        return $this->json($announcements);
    }

    /**
     * Display the specified announcement.
     *
     * @param  \App\Http\Requests\Announcement\View $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(\App\Http\Requests\Announcement\View $request, Announcement $announcement): JsonResponse
    {
        return $this->json($announcement);
    }

    /**
     *  Store a newly created announcement in storage.
     *
     * @param  \App\Http\Requests\Announcement\Create $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\App\Http\Requests\Announcement\Create $request): JsonResponse
    {
        // Get request input
        $attributes = $request->only('name', 'description', 'active');

        // Create a announcement with the provided input
        $announcement = Announcement::make($attributes);

        // Attempt to save announcement into the repository
        $created = $this->announcementRepository->create($announcement);

        // Success
        if ($created) {
            return $this->json(['created' => true, 'id' => $announcement->getId()], 201);
        }

        // Something went wrong
        return $this->json(['created' => false], 500);
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param  \App\Http\Requests\Announcement\Update $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(\App\Http\Requests\Announcement\Update $request, Announcement $announcement): JsonResponse
    {
        // Get request input
        $attributes = $request->only('name', 'description', 'active');

        // Apply changes to the announcement
        $announcement->set($attributes);

        // Attempt to update announcement
        $updated = $this->announcementRepository->update($announcement);

        return $this->json(['updated' => $updated], $updated ? 200 : 500);
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  \App\Http\Requests\Announcement\Delete $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(\App\Http\Requests\Announcement\Delete $request, Announcement $announcement): JsonResponse
    {
        // Delete announcement from repository
        $deleted = $this->announcementRepository->delete($announcement);

        return $this->json(['deleted' => $deleted], $deleted ? 200 : 500);
    }
}
