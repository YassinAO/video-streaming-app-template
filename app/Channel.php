<?php

namespace App;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Channel extends Model implements HasMedia
{

    use HasMediaTrait;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        if ($this->media->first()) {
            return $this->media->first()->getFullUrl('thumb');
        }
        return null;
    }

    // We have to make sure if the current user is allowed to make changes to the currently visited channel.
    public function editable(){
        if (! auth()->check()) return false;
        return $this->user_id === auth()->user()->id;
    }
    
    // To save storage we convert the file to a smaller size.
    public function registerMediaConversions(?Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
    }

    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }
}
