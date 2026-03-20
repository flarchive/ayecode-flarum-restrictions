<?php

namespace AyeCode\FlarumRestrictions\Listener;

use Flarum\Post\Event\Saving;

class ModifyPostContent
{
    public function handle(Saving $event)
    {
        $actor = $event->actor;

        // Set a custom attribute for restriction
        if (!$actor->hasPermission('viewRestrictedContent')) {
            $event->post->is_restricted = true; // Add a custom property
        }
    }
}
