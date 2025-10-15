<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';

    protected $fillable = [
        'content',
        'is_read',
        'sender_profile_id',
        'receiver_profile_id',
    ];

    // Listas blancas
    protected $allowIncluded = ['sender', 'receiver'];
    protected $allowFilter = ['message_id', 'content', 'is_read'];
    protected $allowSort = ['message_id', 'created_at'];
    protected $allowPagination = 10;

    // Relaciones
    public function sender()
    {
        return $this->belongsTo(Profile::class, 'sender_profile_id', 'id');
    }


    public function receiver()
    {
        return $this->belongsTo(Profile::class, 'receiver_profile_id', 'id');
    }


    // 📌 Scope: incluir relaciones
    public function scopeIncluded(Builder $query)
    {
        if (empty(request('include'))) {
            return;
        }

        $relations = explode(',', request('include'));

        $allowRelations = collect($relations)->filter(function ($relation) {
            return in_array($relation, $this->allowIncluded);
        })->all();

        return $query->with($allowRelations);
    }

    // 📌 Scope: filtros
    public function scopeFilter(Builder $query)
    {
        if (empty(request('filter'))) {
            return;
        }

        foreach (request('filter') as $field => $value) {
            if (in_array($field, $this->allowFilter)) {
                $query->where($field, 'LIKE', "%$value%");
            }
        }
    }

    // 📌 Scope: ordenamiento
    public function scopeSort(Builder $query)
    {
        if (empty(request('sort'))) {
            return;
        }

        $sortFields = explode(',', request('sort'));

        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            if (substr($sortField, 0, 1) === '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if (in_array($sortField, $this->allowSort)) {
                $query->orderBy($sortField, $direction);
            }
        }
    }

    // 📌 Scope: paginación
    public function scopePaginateCustom(Builder $query)
    {
        $perPage = request('per_page') ?? $this->allowPagination;
        return $query->paginate($perPage);
    }
}
