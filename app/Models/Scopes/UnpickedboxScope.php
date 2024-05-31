<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;


class UnpickedboxScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        
            $builder->whereDate('booking_date' ,'<=',now())
            ->where(function (Builder $query){
                $query->where('is_pickup', false)
                ->orWhere('is_paid', false);});
        
    }
}
