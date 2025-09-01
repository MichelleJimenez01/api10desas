<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'is_read',
        'sender_profile_id',
        'receiver_profile_id'
    ];

    // Listas blancas (mismo patrón que Profile)
    protected $allowIncluded = ['sender', 'receiver'];
    protected $allowFilter   = ['id', 'content', 'is_read', 'sender_profile_id', 'receiver_profile_id'];
    protected $allowSort     = ['id', 'content', 'is_read', 'sender_profile_id', 'receiver_profile_id', 'created_at', 'updated_at'];

    /* ---------- RELACIONES ---------- */
    public function sender()
    {
        return $this->belongsTo(Profile::class, 'sender_profile_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Profile::class, 'receiver_profile_id');
    }

    /* ---------- SCOPES (mismo patrón que Profile) ---------- */
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('include'))) return;

        $relations = explode(',', request('include'));
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $relation) {
            if (!$allowIncluded->contains($relation)) unset($relations[$key]);
        }

        $query->with($relations);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) return;

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $field => $value) {
            if ($allowFilter->contains($field)) {
                // Para campos booleanos y IDs usar igualdad exacta
                if (in_array($field, ['is_read', 'sender_profile_id', 'receiver_profile_id', 'id'])) {
                    $query->where($field, $value);
                } else {
                    $query->where($field, 'LIKE', "%$value%");
                }
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) return;

        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);

        foreach ($sortFields as $field) {
            $direction = 'asc';
            if (str_starts_with($field, '-')) {
                $direction = 'desc';
                $field = substr($field, 1);
            }
            if ($allowSort->contains($field)) {
                $query->orderBy($field, $direction);
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));
            if ($perPage > 0) return $query->paginate($perPage);
        }
        return $query->get();
    }
}