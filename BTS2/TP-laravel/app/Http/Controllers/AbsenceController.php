<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbsenceRequest;
use App\Http\Requests\UpdateAbsenceRequest;
use App\Mail\AbsenceRequestApproved;
use App\Mail\AbsenceRequestCreated;
use App\Models\absence as AbsenceRecord;
use App\Models\User;
use App\Repositories\Contracts\AbsenceRepository;
use App\Repositories\Contracts\MotifRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

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
        $attributes = $this->withPaidLeaveFlag($request->validated());
        $attributes['status'] = AbsenceRecord::STATUS_PENDING;
        $attributes['approved_by'] = null;
        $attributes['approved_at'] = null;
        $absence = $this->absences->create($attributes);
        $this->notifyAbsenceRequestRecipients($absence);

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
        $attributes = $this->withPaidLeaveFlag($request->validated());
        $attributes['status'] = AbsenceRecord::STATUS_PENDING;
        $attributes['approved_by'] = null;
        $attributes['approved_at'] = null;

        $this->absences->update($numeroAbsence, $attributes);

        return redirect()->route('absence.show', $numeroAbsence)
            ->with('success', __('ui.flash.absence_updated'));
    }

    public function approve(AbsenceRecord $numeroAbsence): RedirectResponse
    {
        abort_unless($numeroAbsence->status === AbsenceRecord::STATUS_PENDING, 409);

        $this->absences->update($numeroAbsence, [
            'status' => AbsenceRecord::STATUS_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        $numeroAbsence->loadMissing(['user', 'motif', 'approvedBy']);
        $recipient = User::query()->findOrFail($numeroAbsence->user_id);

        Mail::to($recipient->email)->send(
            (new AbsenceRequestApproved($numeroAbsence))->locale(app()->getLocale())
        );

        return redirect()->route('absence.show', $numeroAbsence)
            ->with('success', __('ui.flash.absence_approved'));
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

    private function notifyAbsenceRequestRecipients(AbsenceRecord $absence): void
    {
        $absence->loadMissing(['user', 'motif']);

        $recipients = User::query()
            ->get()
            ->filter(fn (User $user): bool => $user->can('manage-all-absences'))
            ->push(User::query()->findOrFail($absence->user_id))
            ->unique('id');

        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)->send(
                (new AbsenceRequestCreated($absence))->locale(app()->getLocale())
            );
        }
    }
}
