<?php

namespace App\Http\Controllers;

use App\Models\absence as AbsenceRecord;
use App\Repositories\Contracts\AbsenceRepository;
use App\Repositories\Contracts\MotifRepository;
use App\Repositories\Contracts\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

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
        $absences = $this->absences->allWithRelations();

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
    public function store(Request $request)
    {
        Gate::authorize('create', AbsenceRecord::class);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::when(! Gate::allows('manage-all-absences'), Rule::in([Auth::id()])),
            ],
            'motif_id' => ['required', 'exists:motifs,id'],
            'type_conge' => [
                'nullable',
                'in:conges_payes,paternite,maternite',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if (! $value || ! in_array($value, ['paternite', 'maternite'], true)) {
                        return;
                    }

                    $user = $this->users->find((int) $request->input('user_id'));
                    $requiredSexe = $value === 'paternite' ? 'homme' : 'femme';

                    if (! $user || $user->sexe !== $requiredSexe) {
                        $fail($value === 'paternite'
                            ? 'Le congé paternité est réservé aux utilisateurs enregistrés comme hommes.'
                            : 'Le congé maternité est réservé aux utilisatrices enregistrées comme femmes.');
                    }
                },
            ],
            'date_debut' => ['required', 'date'],
            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    $dateDebut = $request->input('date_debut');
                    $userId = $request->input('user_id');

                    if (! $dateDebut || ! $userId || ! strtotime($dateDebut) || ! strtotime($value)) {
                        return;
                    }

                    if ($request->input('type_conge') === 'conges_payes') {
                        $dateDebutCarbon = Carbon::parse($dateDebut);
                        $dateFinCarbon = Carbon::parse($value);
                        $paidAbsences = $this->absences->paidForUser((int) $userId);

                        for ($year = $dateDebutCarbon->year; $year <= $dateFinCarbon->year; $year++) {
                            $yearStart = Carbon::create($year, 1, 1);
                            $yearEnd = Carbon::create($year, 12, 31);
                            $newStart = $dateDebutCarbon->greaterThan($yearStart) ? $dateDebutCarbon : $yearStart;
                            $newEnd = $dateFinCarbon->lessThan($yearEnd) ? $dateFinCarbon : $yearEnd;
                            $paidDays = $newStart->diffInDays($newEnd) + 1;

                            foreach ($paidAbsences as $paidAbsence) {
                                $existingStart = Carbon::parse($paidAbsence->date_debut);
                                $existingEnd = Carbon::parse($paidAbsence->date_fin);
                                $existingStart = $existingStart->greaterThan($yearStart) ? $existingStart : $yearStart;
                                $existingEnd = $existingEnd->lessThan($yearEnd) ? $existingEnd : $yearEnd;

                                if ($existingStart->lessThanOrEqualTo($existingEnd)) {
                                    $paidDays += $existingStart->diffInDays($existingEnd) + 1;
                                }
                            }

                            if ($paidDays > 25) {
                                $fail('La limite de 25 jours de congés payés par an pour cet utilisateur serait dépassée.');

                                return;
                            }
                        }
                    }

                    $overlap = $this->absences->hasOverlap((int) $userId, $dateDebut, $value);

                    if ($overlap) {
                        $fail('Cette période chevauche déjà une absence de cet utilisateur.');
                    }
                },
            ],
        ]);

        $validated['conges_payes'] = $validated['type_conge'] === 'conges_payes';
        $absence = $this->absences->create($validated);

        return redirect()->route('user.show', $absence->user_id)
            ->with('success', 'Absence créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AbsenceRecord $numeroAbsence)
    {
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
    public function update(Request $request, AbsenceRecord $absence)
    {
        Gate::authorize('update', $absence);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::when(! Gate::allows('manage-all-absences'), Rule::in([Auth::id()])),
            ],
            'motif_id' => ['required', 'exists:motifs,id'],
            'type_conge' => ['nullable', 'in:conges_payes,paternite,maternite'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
        ]);

        $validated['conges_payes'] = $validated['type_conge'] === 'conges_payes';
        $this->absences->update($absence, $validated);

        return redirect()->route('absence.show', $absence)
            ->with('success', 'Absence modifiée avec succès.');
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
            ->with('success', 'Absence supprimée avec succès.');
    }
}
