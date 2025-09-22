<?php

namespace Aurorawebsoftware\AApproval\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStep extends Model
{
    protected $fillable = ['approval_request_id', 'step', 'name', 'type', 'identifier', 'status'];
    protected $casts = ['identifier' => 'array'];

    public function canUserApprove($user)
    {

        return match($this->type) {
            'role' => $user->hasRole($this->identifier),
            'permission' => $user->hasAnyPermission($this->identifier),
            'user' => in_array($user->id, $this->identifier),
            default => false,
        };
    }

    public function approve($user)
    {
        if (!$this->canUserApprove($user)) {
            throw new \Exception("User cannot approve this step.");
        }

        $this->status = 'approved';
        $this->save();

        $request = $this->approvalRequest;
        if ($request->steps()->where('status', 'pending')->count() === 0) {
            $request->applyChanges();
        }
    }

    public function approvalRequest()
    {
        return $this->belongsTo(ApprovalRequest::class);
    }
}
