<?php

namespace LisaFehr\Gallery\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UberGallery extends Model
{
    use HasFactory;

    protected $table = 'uber_gallery';
    public $timestamps = false;

    public $fillable = [
        'occurred',
        'img',
        'thumb',
        'type',
        'text',
    ];

    public function tag() : \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            UberTags::class,
            UberTagAssoc::class,
            'image_id', // Foreign key on the UberTagAssoc...
            'id', // Foreign key on the UberTags...
            'id', // Local key on the UberGallery...
            'tag_id' // Local key on the UberTagAssoc...
        );
    }

    public function getImageAttribute() : string
    {
        $url = $this->tag->directory . '/' . $this->img . '.' . $this->type;
        if (Storage::disk('gallery')->exists($url)) {
            return Storage::disk('gallery')->temporaryUrl($url, Carbon::now()->addDay());
        }
        $fallbackForSwf = $this->tag->directory . '/' . $this->img . '.jpg';
        if (Storage::disk('gallery')->exists($fallbackForSwf)) {
            return Storage::disk('gallery')->temporaryUrl($fallbackForSwf, Carbon::now()->addDay());
        }
        return Storage::disk('gallery')->url('/missing-image.gif');
    }

    public function getThumbnailAttribute() : ?string
    {
        if (! $this->tag) {
            return null;
        }
        $url = $this->tag->directory . '/t/' . $this->thumb;
        if (Storage::disk('gallery')->exists($url)) {
            return Storage::disk('gallery')->temporaryUrl($url, Carbon::now()->addDay());
        }
        return Storage::disk('gallery')->url('/missing-thumbnail.gif');
    }

    public function scopeTags(Builder $builder, ...$tags)
    {
        $builder
            ->select('uber_gallery.*')
            ->join('uber_tag_assoc', 'uber_gallery.id', '=', 'uber_tag_assoc.image_id')
            ->join('uber_tags', 'uber_tag_assoc.tag_id', '=', 'uber_tags.id')
            ->whereIn('uber_tags.name', $tags);
    }

    public function toArray() : array
    {
        return [
            'image' => $this->image,
            'thumbnail' => $this->thumbnail,
            'alt' => Str::replace('_', ' ', Str::title($this->img)),
        ];
    }
}
