<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbsenceRequest;
use App\Http\Requests\UpdateAbsenceRequest;
use App\Models\absence as AbsenceRecord;
use App\Repositories\Contracts\AbsenceRepository;
use App\Repositories\Contracts\MotifRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
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

    public function index(): View
    {
        $currentUserId = Gate::allows('manage-all-absences') ? null : (int) Auth::id();
        $absences = $this->absences->allWithRelations($currentUserId);

        return view('absences.index', compact('absences'));
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', AbsenceRecord::class);

        $selectedUserId = $request->integer('user_id') ?: old('user_id');

        return view('absences.create', [
            ...$this->absenceFormOptions(),
            'selectedUserId' => $selectedUserId,
        ]);
    }

    public function store(StoreAbsenceRequest $request): RedirectResponse
    {
        $absence = $this->absences->create($this->withPaidLeaveFlag($request->validated()));

        return redirect()->route('user.show', $absence->user_id)
            ->with('success', __('ui.flash.absence_created'));
    }

    public function show(AbsenceRecord $numeroAbsence): View
    {
        Gate::authorize('view', $numeroAbsence);
        $this->absences->loadRelations($numeroAbsence);

        return view('absences.show', ['absence' => $numeroAbsence]);
    }

    public function edit(AbsenceRecord $numeroAbsence): View
    {
        Gate::authorize('update', $numeroAbsence);

        return view('absences.edit', [
            'absence' => $numeroAbsence,
            ...$this->absenceFormOptions(),
        ]);
    }

    public function update(UpdateAbsenceRequest $request, AbsenceRecord $numeroAbsence): RedirectResponse
    {
        $this->absences->update($numeroAbsence, $this->withPaidLeaveFlag($request->validated()));

        return redirect()->route('absence.show', $numeroAbsence)
            ->with('success', __('ui.flash.absence_updated'));
    }

    public function destroy(AbsenceRecord $numeroAbsence): RedirectResponse
    {
        Gate::authorize('delete', $numeroAbsence);
        $userId = $numeroAbsence->user_id;
        $this->absences->delete($numeroAbsence);

        return redirect()->route('user.show', $userId)
            ->with('success', __('ui.flash.absence_deleted'));
    }

    /**
     * @return array{users: Collection, motifs: Collection}
     */
    private function absenceFormOptions(): array
    {
        return [
            'users' => $this->users->forAbsenceForm(Gate::allows('manage-all-absences'), (int) Auth::id()),
            'motifs' => $this->motifs->allOrderedByLibelle(),
        ];
    }

    /**
     * Keep the legacy paid-leave flag synchronized with the selected leave type.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function withPaidLeaveFlag(array $attributes): array
    {
        $attributes['conges_payes'] = ($attributes['type_conge'] ?? null) === 'conges_payes';

        return $attributes;
    }
}
