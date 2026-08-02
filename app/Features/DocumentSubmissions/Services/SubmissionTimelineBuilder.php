<?php

namespace App\Features\DocumentSubmissions\Services;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use Illuminate\Support\Collection;

class SubmissionTimelineBuilder
{
    /**
     * Builds an ordered timeline of entries for a submission by merging
     * the full stage pipeline with actual approval history. Each entry
     * represents one (stage, cycle) pair with its state and any recorded
     * approval data.
     *
     * Entry shape:
     * [
     *   'stage'    => DocumentProcessStage,
     *   'cycle'    => int,
     *   'state'    => 'approved' | 'rejected' | 'current' | 'pending',
     *   'approval' => DocumentApproval|null,
     * ]
     *
     * Returns empty collection if the submission has no process or stages.
     */
    public function build(DocumentSubmission $submission): Collection
    {
        // Guard: process or stages missing
        if (! $submission->documentProcess) {
            return collect();
        }

        $stages = $submission->documentProcess->stages;

        if ($stages->isEmpty()) {
            return collect();
        }

        $approvalsByCycle = $submission->approvals
            ->sortBy('acted_at')
            ->groupBy('cycle');

        $entries = collect();
        $currentCycle = $submission->current_cycle;

        // Replay past completed cycles first
        foreach ($approvalsByCycle as $cycle => $cycleApprovals) {
            if ((int) $cycle === $currentCycle) {
                continue;
            }

            $approvalsByStage = $cycleApprovals->keyBy('document_process_stage_id');

            foreach ($stages as $stage) {
                $approval = $approvalsByStage->get($stage->id);

                if ($approval) {
                    $entries->push([
                        'stage'    => $stage,
                        'cycle'    => (int) $cycle,
                        'state'    => $approval->status,
                        'approval' => $approval,
                    ]);
                }
            }
        }

        // Current cycle: past approvals + current + future
        $currentCycleApprovals = $approvalsByCycle->get($currentCycle, collect());
        $approvalsByStage = $currentCycleApprovals->keyBy('document_process_stage_id');

        foreach ($stages as $stage) {
            $approval = $approvalsByStage->get($stage->id);

            $state = match (true) {
                $approval !== null                                    => $approval->status,
                $stage->id === $submission->current_process_stage_id => 'current',
                default                                               => 'pending',
            };

            $entries->push([
                'stage'    => $stage,
                'cycle'    => $currentCycle,
                'state'    => $state,
                'approval' => $approval,
            ]);
        }

        return $entries;
    }
}