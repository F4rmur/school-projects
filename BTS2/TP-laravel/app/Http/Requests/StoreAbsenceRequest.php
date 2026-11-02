<?php

namespace App\Http\Requests;

use App\Models\absence as AbsenceRecord;
use App\Repositories\Contracts\AbsenceRepository;
use App\Repositories\Contracts\UserRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Gate;

class StoreAbsenceRequest extends BaseAbsenceRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', AbsenceRecord::class);
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return $this->sharedRules();
    }

    /** @return array<int, \Closure(Validator): void> */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $userId = $this->integer('user_id');
                $typeConge = $this->string('type_conge')->toString();

                if (! $validator->errors()->hasAny(['user_id', 'type_conge'])
                    && in_array($typeConge, ['paternite', 'maternite'], true)) {
                    $user = app(UserRepository::class)->find($userId);
                    $requiredSexe = $typeConge === 'paternite' ? 'homme' : 'femme';

                    if (! $user || $user->sexe !== $requiredSexe) {
                        $validator->errors()->add('type_conge', $typeConge === 'paternite'
                            ? __('ui.validation.paternity_for_men')
                            : __('ui.validation.maternity_for_women'));
                    }
                }

                if ($validator->errors()->hasAny(['user_id', 'date_debut', 'date_fin'])) {
                    return;
                }

                $dateDebut = $this->string('date_debut')->toString();
                $dateFin = $this->string('date_fin')->toString();

                if ($typeConge === 'conges_payes') {
                    $dateDebutCarbon = Carbon::parse($dateDebut);
                    $dateFinCarbon = Carbon::parse($dateFin);
                    $paidAbsences = app(AbsenceRepository::class)->paidForUser($userId);

                    for ($year = $dateDebutCarbon->year; $year <= $dateFinCarbon->year; $year++) {
                        $yearStart = Carbon::parse($year.'-01-01');
                        $yearEnd = Carbon::parse($year.'-12-31');
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
                            $validator->errors()->add('date_fin', __('ui.validation.paid_leave_limit'));

                            return;
                        }
                    }
                }

                if (app(AbsenceRepository::class)->hasOverlap($userId, $dateDebut, $dateFin)) {
                    $validator->errors()->add('date_fin', __('ui.validation.absence_overlap'));
                }
            },
        ];
    }
}
