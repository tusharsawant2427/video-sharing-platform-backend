<?php

namespace App\Features\Users\Domains\Query;

use App\Features\Users\Domains\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait UserScopes
{
    /**
     * @param Builder<User> $query
     * @param int|null $id
     *
     * @return Builder<User>
     */
    public function scopeSearchById(Builder $query, ?int $id): Builder
    {
        if (!empty($id)) {
            return $query->where('id', $id);
        }
        return $query;
    }

    /**
     * @param Builder<User> $query
     * @param int $start
     * @param int $length
     *
     * @return QueryBuilder|Builder<User>
     */
    public function scopeLimitBy(Builder $query, int $start, int $length): QueryBuilder|Builder
    {
        if($length != -1)
        {
            return $query->offset($start)->limit($length);
        }
        return $query;
    }

    /**
     * @param Builder<User> $query
     * @param array<int,array<string,string>> $order
     *
     * @return QueryBuilder|Builder<User>
     */
    public function scopeOrder(Builder $query, array $order): Builder|QueryBuilder
    {
        if ($order) {
            $columns = [
                0 => 'id',
                2 => 'name',
                3 => 'email',
                4 => 'created_at',
            ];
            if(! in_array($order[0]['column'], array_keys($columns))) {
                return $query;
            }

            return $query->orderBy($columns[$order[0]['column']], $order[0]['dir']);
        }
        return $query;
    }

    /**
     * @param Builder<User> $query
     * @param array<string,int|string|null>|null $search
     *
     * @return Builder<User>
     */
    public function scopeSearch(Builder $query, ?array $search): Builder
    {
        if(! $search) {
            return $query;
        }

        return $query->searchById(! empty($search['id']) ? (int)$search['id'] : null)
            ->searchByName(! empty($search['name']) ? (string)$search['name'] : null)
            ->searchByEmail(! empty($search['email']) ? (string)$search['email'] : null);
    }

    /**
     * @param Builder<User> $query
     * @param string|null $name
     *
     * @return Builder<User>
     */
    public function scopeSearchByName(Builder $query, ?string $name): Builder
    {
        if($name) {
            $query->where('name', 'like', "%$name%");
        }
        return $query;
    }

    /**
     * @param Builder<User> $query
     * @param string|null $email
     *
     * @return Builder<User>
     */
    public function scopeSearchByEmail(Builder $query, ?string $email): Builder
    {
        if($email) {
            $query->where('email', 'like', "%$email%");
        }
        return $query;
    }

}
