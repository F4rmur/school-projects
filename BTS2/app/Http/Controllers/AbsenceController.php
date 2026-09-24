<?php

namespace App\Http\Controllers;

use App\Models\absence;
use App\Models\Motif;
use App\Models\users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absences = absence::with(['user', 'motif'])
            ->orderByDesc('date_debut')
            ->get();

        return view('absences.index', compact('absences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Gate::authorize('create', absence::class);

        $users = Auth::user()->is_admin
            ? users::orderBy('nom')->get()
            : users::whereKey(Auth::id())->get();
        $motifs = Motif::orderBy('libelle')->get();
        $selectedUserId = $request->integer('user_id') ?: old('user_id');

        return view('absences.create', compact('users', 'motifs', 'selectedUserId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', absence::class);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::when(! Auth::user()->is_admin, Rule::in([Auth::id()])),
            ],
            'motif_id' => ['required', 'exists:motifs,id'],
            'type_conge' => [
                'nullable',
                'in:conges_payes,paternite,maternite',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if (! $value || ! in_array($value, ['paternite', 'maternite'], true)) {
                        return;
                    }

                    $user = users::find($request->input('user_id'));
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
                        $paidAbsences = absence::query()
                            ->where('user_id', $userId)
                            ->where(function ($query): void {
                                $query->where('conges_payes', true)
                                    ->orWhere('type_conge', 'conges_payes');
                            })
                            ->get(['date_debut', 'date_fin']);

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

                    $overlap = absence::query()
                        ->where('user_id', $userId)
                        ->whereDate('date_debut', '<=', $value)
                        ->whereDate('date_fin', '>=', $dateDebut)
                        ->exists();

                    if ($overlap) {
                        $fail('Cette période chevauche déjà une absence de cet utilisateur.');
                    }
                },
            ],
        ]);

        $validated['conges_payes'] = $validated['type_conge'] === 'conges_payes';
        $absence = absence::create($validated);

        return redirect()->route('user.show', $absence->user_id)
            ->with('success', 'Absence créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(absence $numeroAbsence)
    {
        $numeroAbsence->load(['user', 'motif']);

        return view('absences.show', ['absence' => $numeroAbsence]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(absence $absence)
    {
        Gate::authorize('update', $absence);

        $users = Auth::user()->is_admin
            ? users::orderBy('nom')->get()
            : users::whereKey(Auth::id())->get();
        $motifs = Motif::orderBy('libelle')->get();

        return view('absences.edit', compact('absence', 'users', 'motifs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, absence $absence)
    {
        Gate::authorize('update', $absence);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::when(! Auth::user()->is_admin, Rule::in([Auth::id()])),
            ],
            'motif_id' => ['required', 'exists:motifs,id'],
            'type_conge' => ['nullable', 'in:conges_payes,paternite,maternite'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
        ]);

        $validated['conges_payes'] = $validated['type_conge'] === 'conges_payes';
        $absence->update($validated);

        return redirect()->route('absence.show', $absence)
            ->with('success', 'Absence modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(absence $absence)
    {
        Gate::authorize('delete', $absence);
        $userId = $absence->user_id;
        $absence->delete();

        return redirect()->route('user.show', $userId)
            ->with('success', 'Absence supprimée avec succès.');
    }
}
