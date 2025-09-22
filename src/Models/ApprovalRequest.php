<?php

namespace AuroraWebSoftware\AApproval\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApprovalRequest extends Model
{
    protected $table = 'approval_requests';

    protected $fillable = [
        'model_type',
        'model_id',
        'changes',
        'action_type',
        'requested_by',
        'status',
    ];

    public function getChangesAttribute($value)
    {
        if (empty($value)) return [];
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function setChangesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['changes'] = json_encode($value, JSON_UNESCAPED_UNICODE);
        } elseif (is_string($value)) {
            $decoded = json_decode($value, true);
            $this->attributes['changes'] = json_encode($decoded ?? [], JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['changes'] = json_encode([], JSON_UNESCAPED_UNICODE);
        }
    }

    public function steps()
    {
        return $this->hasMany(ApprovalStep::class, 'approval_request_id');
    }

    public function applyChanges()
    {
        $changesJson = DB::table($this->getTable())
            ->where('id', $this->id)
            ->value('changes');

        $changes = json_decode($changesJson, true);

        $modelClass = $this->model_type;
        $model = $this->model_id ? $modelClass::find($this->model_id) : new $modelClass();

        if ($this->action_type === 'delete') {
            if ($model) $model->delete();
        } else {
            foreach ($changes as $key => $value) {
                if (isset($model->getCasts()[$key]) && $model->getCasts()[$key] === 'array') {
                    $model->$key = is_array($value) ? $value : json_decode($value, true);
                } else {
                    $model->$key = $value;
                }
            }
            $model->save();
        }

        $this->status = 'approved';
        $this->save();
    }
}
