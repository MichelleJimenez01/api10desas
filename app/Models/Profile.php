<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Profile extends Model
{
    use HasFactory;
   
    // Campos que pueden ser llenados en masa

    protected $fillable = [

        'photo',
        'phone',
        'vereda',
        'user_id',
        'role_id',
    ];

    
    // Listas blancas — definen lo que se puede incluir, filtrar u ordenar
    protected $allowIncluded = ['user', 'role', 'publications', 'sentMessages', 'receivedMessages'];
    protected $allowFilter   = ['id','photo','phone','vereda','user_id','role_id'];
    protected $allowSort     = ['id','photo','phone','vereda','user_id','role_id','created_at','updated_at'];

    /* ---------- RELACIONES ---------- */

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

    /* ---------- SCOPES ---------- */
    
    // Incluir relaciones dinámicamente con ?included=role,user
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

    // Permitir filtros dinámicos ?filter[vereda]=Centro
    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) return;

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $field => $value) {
            if ($allowFilter->contains($field)) {
                if (in_array($field, ['id', 'phone', 'user_id', 'role_id'])) {
                    $query->where($field, $value);
                } else {
                    $query->where($field, 'LIKE', "%$value%");
                }
            }
        }
    }

    // Permitir orden dinámico ?sort=-created_at
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

    // Obtener todo o paginar dinámicamente ?perPage=10
    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));
            if ($perPage > 0) return $query->paginate($perPage);
        }
        return $query->get();
    }
}
