<?php

namespace MiragePresent\Likeable;

/**
 * Trait Likable
 *
 * @property int $count_likes
 * @property bool $is_liked
 * @property-read \Illuminate\Database\Eloquent\Collection $likedBy
 */

trait Likeable
{

    /**
     *  Likes relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(
            $this->getLikesModel(),
            'likable',
            'likable_type',
            'likable_id'
        );
    }

    /**
     *  Add like
     *
     * @return bool
     */
    public function like()
    {
        if (!$this->isLiked()) {
            $this->likes()
                ->create(['user_id' => \Auth::id()]);
        }

        return true;
    }

    /**
     *  Remove like
     *
     * @return bool
     */
    public function dislike()
    {
        if ($this->isLiked()) {
            $this->likes()
                ->where('user_id', \Auth::id())
                ->delete();
        }

        return false;
    }

    /**
     * @return static Model instance
     * @throws \InvalidArgumentException
     */
    public function toggleLike()
    {
        if ($this->existsUserLike()) {
            $this->dislike();
        } else {
            $this->like();
        }

        return $this;
    }

    /**
     *  Checking whether item is liked or not
     *
     * @return bool
     */
    public function isLiked()
    {
        if (!\Auth::check()) {
            return false;
        }

        return !!$this
            ->likes()
            ->where('user_id', \Auth::id())
            ->count();
    }


    /**
     *  Users which liked this item
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function likedBy()
    {
        $usersClass = $this->getUserModel();

        return (new $usersClass)
            ->whereIn('id', $this->likes()->get(['user_id'])->pluck('user_id')->toArray())
            ->get();
    }

    /**
     *  Check whether user has liked this subject
     *
     * @return bool result
     * @throws \InvalidArgumentException
     */
    protected function existsUserLike()
    {
        try {
            return !!$this->likes()
                ->where('user_id', \Auth::id())
                ->count();

        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Model must has likes() relation');
        }
    }



    /**
     *  Is Liked
     * @return bool
     */
    public function getIsLikedAttribute()
    {
        return $this->isLiked();
    }

    /**
     *  Count likes
     * @return int
     */
    public function getCountLikesAttribute()
    {
        return $this->likes()->count();
    }

    /**
     *  Users collection
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLikedByAttribute()
    {
        return $this->likedBy()->get();
    }

    /**
     *  Get likes relation model
     *
     * @return string
     */
    protected function getLikesModel()
    {
        return \MiragePresent\Likeable\Like::class;
    }

    /**
     *  Get user relation model
     *
     * @return string
     */
    protected function getUserModel()
    {
        return \App\User::class;
    }

    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $name
     * @param  string  $type
     * @param  string  $id
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    abstract public function morphMany($related, $name, $type = null, $id = null, $localKey = null);



}
