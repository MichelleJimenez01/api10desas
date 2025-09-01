<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'password',
        'photo',
        'user_id',
        'role_id',
    ];

    // Listas blancas (mismo patrón que Category)
    protected $allowIncluded = ['user', 'role', 'publications', 'sentMessages', 'receivedMessages', 'messages'];
    protected $allowFilter   = ['id', 'user_id', 'role_id'];
    protected $allowSort     = ['id', 'user_id', 'role_id', 'created_at', 'updated_at'];

    /* ---------- RELACIONES (según migraciones) ---------- */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function publications()
    {
        return $this->hasMany(Publication::class, 'profile_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_profile_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_profile_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'profile_id');
    }

    /* ---------- SCOPES  ---------- */
    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) return;

        $relations = explode(',', request('included'));
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
                $query->where($field, 'LIKE', "%$value%");
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