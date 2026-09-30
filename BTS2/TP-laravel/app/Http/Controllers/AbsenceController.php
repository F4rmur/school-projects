<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbsenceRequest;
use App\Http\Requests\UpdateAbsenceRequest;
use App\Models\absence as AbsenceRecord;
use App\Repositories\Contracts\AbsenceRepository;
use App\Repositories\Contracts\MotifRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AbsenceController extends Controller
{
    public function __construct(
        private AbsenceRepository $absences,
        private MotifRepository $motifs,
        private UserRepository $users,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserId = Gate::allows('manage-all-absences') ? null : (int) Auth::id();
        $absences = $this->absences->allWithRelations($currentUserId);

        return view('absences.index', compact('absences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Gate::authorize('create', AbsenceRecord::class);

        $users = $this->users->forAbsenceForm(Gate::allows('manage-all-absences'), (int) Auth::id());
        $motifs = $this->motifs->allOrderedByLibelle();
        $selectedUserId = $request->integer('user_id') ?: old('user_id');

        return view('absences.create', compact('users', 'motifs', 'selectedUserId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAbsenceRequest $request)
    {
        $validated = $request->validated();

        $validated['conges_payes'] = $validated['type_conge'] === 'conges_payes';
        $absence = $this->absences->create($validated);

        return redirect()->route('user.show', $absence->user_id)
            ->with('success', __('ui.flash.absence_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AbsenceRecord $numeroAbsence)
    {
        Gate::authorize('view', $numeroAbsence);
        $this->absences->loadRelations($numeroAbsence);

        return view('absences.show', ['absence' => $numeroAbsence]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AbsenceRecord $absence)
    {
        Gate::authorize('update', $absence);

        $users = $this->users->forAbsenceForm(Gate::allows('manage-all-absences'), (int) Auth::id());
        $motifs = $this->motifs->allOrderedByLibelle();

        return view('absences.edit', compact('absence', 'users', 'motifs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAbsenceRequest $request, AbsenceRecord $numeroAbsence)
    {
        $validated = $request->validated();

        $validated['conges_payes'] = $validated['type_conge'] === 'conges_payes';
        $this->absences->update($numeroAbsence, $validated);

        return redirect()->route('absence.show', $numeroAbsence)
            ->with('success', __('ui.flash.absence_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AbsenceRecord $absence)
    {
        Gate::authorize('delete', $absence);
        $userId = $absence->user_id;
        $this->absences->delete($absence);

        return redirect()->route('user.show', $userId)
            ->with('success', __('ui.flash.absence_deleted'));
    }
}
