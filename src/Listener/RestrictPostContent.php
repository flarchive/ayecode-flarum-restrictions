<?php

namespace AyeCode\FlarumRestrictions\Listener;

use Flarum\Api\Serializer\PostSerializer;
use Flarum\Post\Post;

class RestrictPostContent
{
    public function __invoke(PostSerializer $serializer, Post $post, array $attributes)
    {
        $actor = $serializer->getActor();

        // Check if the post is restricted and the actor does not have permission
        if (!empty($post->is_restricted) && !$actor->hasPermission('viewRestrictedContent')) {
            $attributes['contentHtml'] = '<p>Restricted</p>';
        }

        return $attributes;
    }
}
