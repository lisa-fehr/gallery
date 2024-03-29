<?php

namespace LisaFehr\Gallery\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UberTags extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'display_name',
        'parent',
        'children',
        'directory',
        'count',
        'details',
    ];

    protected $visible = [
        'name',
        'display_name',
        'count',
        'details',
        'parent',
    ];

    public function scopeAllChildren(Builder $builder) : Builder
    {
        $children = $builder->select('children', 'id')->having('children', '=', 1);
        $parents = $builder->pluck('id');
        $collect = collect([]);

        while ($children) {
            $current = self::select('id', 'children')->whereIn('parent', $parents)->get();
            $parents = $current->pluck('id');
            $collect = $collect->merge($parents);
            $children = $current->contains('children', '1');
        }
        return self::query()->whereIn('id', $collect);
    }

    public function scopeChildren(Builder $builder) : Builder
    {
        $parents = $builder->pluck('id');
        $collect = collect([]);

        $current = self::select('id', 'children')->whereIn('parent', $parents)->get();
        $parents = $current->pluck('id');
        $collect = $collect->merge($parents);

        return self::query()->whereIn('id', $collect);
    }

    public function parent() : \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent');
    }
}
