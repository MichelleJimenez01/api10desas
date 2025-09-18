<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'event_notification',
        'publication_id'
    ];

    // Listas blancas
    protected $allowIncluded = ['publication'];
    protected $allowFilter = ['notification_id', 'event_notification'];
    protected $allowSort = ['notification_id', 'created_at'];
    protected $allowPagination = 10;

    // Relaciones
    public function publication()
    {
        return $this->belongsTo(Publication::class, 'publication_id', 'publication_id');
    }

    // Scopes
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

    public function scopePaginateCustom(Builder $query)
    {
        $perPage = request('per_page') ?? $this->allowPagination;
        return $query->paginate($perPage);
    }
}
