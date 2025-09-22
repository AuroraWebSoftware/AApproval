<?php

namespace Aurorawebsoftware\AApproval\Traits;

use Aurorawebsoftware\AApproval\Models\ApprovalRequest;
use Aurorawebsoftware\AApproval\Models\ApprovalStep;
use Illuminate\Support\Facades\Auth;

trait HasApprovals
{
    public static function bootHasApprovals()
    {
        static::creating(function ($model) {
            return self::handleApproval($model, 'create');
        });

        static::updating(function ($model) {
            return self::handleApproval($model, 'update');
        });

        static::deleting(function ($model) {
            return self::handleApproval($model, 'delete');
        });
    }

    protected static function handleApproval($model, $actionType)
    {
        $user = Auth::user();

        if ($user->can('create_payment::link', $model)) {
            return true;
        }

        $request = ApprovalRequest::create([
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'changes' => $model->getAttributes(),
            'status' => 'pending',
            'requested_by' => $user->id,
            'action_type' => $actionType,
        ]);

        $flow = config('approvals.flows.'.get_class($model), []);
        foreach ($flow as $index => $step) {
            ApprovalStep::create([
                'approval_request_id' => $request->id,
                'step' => $index + 1,
                'name' => $step['name'],
                'type' => $step['type'],
                'identifier' => $step['identifier'],
                'status' => 'pending',
            ]);
        }

        return false;
    }
}
