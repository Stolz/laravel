<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

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
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\Announcement\Index $request)
    {
        // Get pagination options
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 15, 'id', 'desc');

        // Get announcements from repository
        $announcements = $this->announcementRepository->paginate($perPage, $page, $sortBy, $sortDirection);

        // Load view
        return view('modules.master.announcement.index')->withAnnouncements($announcements);
    }

    /**
     * Display the specified announcement.
     *
     * @param  \App\Http\Requests\Announcement\View $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Http\Requests\Announcement\View $request, Announcement $announcement)
    {
        // Load view
        return view('modules.master.announcement.show')->withAnnouncement($announcement);
    }

    /**
     * Show the form for creating a new announcement.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Create an empty announcement
        $announcement = Announcement::make();

        // Load view
        return view('modules.master.announcement.create')->withAnnouncement($announcement);
    }

    /**
     * Show the form for editing the specified announcement.
     *
     * @param  \App\Http\Requests\Announcement\View $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Http\Requests\Announcement\View $request, Announcement $announcement)
    {
        // Load view
        return view('modules.master.announcement.update')->withAnnouncement($announcement);
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @param  \App\Http\Requests\Announcement\Create $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\Announcement\Create $request)
    {
        // Get request input
        $attributes = $request->only('name', 'description', 'active');

        // Create a announcement with the provided input
        $announcement = Announcement::make($attributes);

        // Attempt to save announcement into the repository
        $created = $this->announcementRepository->create($announcement);

        // Success
        if ($created) {
            return redirect()->route('master.announcement.index')->with('success', sprintf(_("Announcement '%s' successfully created"), $announcement));
        }

        // Something went wrong
        return redirect()->back()->withInput()->with('error', sprintf(_("Unable to create announcement '%s'"), $announcement));
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param  \App\Http\Requests\Announcement\Update $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\Announcement\Update $request, Announcement $announcement)
    {
        // Get request input
        $attributes = $request->only('name', 'description', 'active');

        // Apply changes to the announcement
        $announcement->set($attributes);

        // Attempt to update announcement
        $updated = $this->announcementRepository->update($announcement);

        // Success
        if ($updated) {
            return redirect()->route('master.announcement.show', $announcement->getId())->with('success', sprintf(_("Announcement '%s' successfully updated"), $announcement));
        }

        // Something went wrong
        return redirect()->back()->withInput()->with('error', sprintf(_("Unable to update announcement '%s'"), $announcement));
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  \App\Http\Requests\Announcement\Delete $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Http\Requests\Announcement\Delete $request, Announcement $announcement)
    {
        // Attempt to delete announcement
        $deleted = $this->announcementRepository->delete($announcement);

        // Something went wrong
        if (! $deleted) {
            return redirect()->back()->with('error', sprintf(_("Unable to delete announcement '%s'"), $announcement));
        }

        // Success
        $redirectBack = ($request->input('_from') === 'master.announcement.show') ? redirect()->route('master.announcement.index') : redirect()->back();

        return $redirectBack->with('success', sprintf(_("Announcement '%s' successfully deleted"), $announcement));
    }
}
