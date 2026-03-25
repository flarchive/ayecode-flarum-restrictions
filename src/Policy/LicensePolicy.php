<?php

namespace AyeCode\FlarumRestrictions\Policy;

use Flarum\Post\Post;
use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;

class PostPolicy extends AbstractPolicy
{
    public function view(User $actor, Post $post)
    {
        // Allow viewing the first post always
        if ($post->number === 1) {
            return true;
        }

        // Check if post belongs to a discussion with location-manager tag
        foreach ($post->discussion->tags as $tag) {
            if ($tag->slug === 'location-manager') {
                // For testing, deny content to guests
                if ($actor->isGuest()) {
                    // We return true but will modify the content
                    $post->content = '<p>Restricted</p>';
                }
            }
        }

        return true;
    }
}