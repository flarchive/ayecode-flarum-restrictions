<?php

namespace AyeCode\FlarumRestrictions\Policy;

use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;
use Flarum\Tags\Tag;
use AyeCode\FlarumRestrictions\Access\LicenseChecker;

class TagPolicy extends AbstractPolicy
{
    // Define array of allowed forums
    protected $allowedForums = ['general', 'geodirectory-core']; // Add your forums here

    public function startDiscussion(User $actor, Tag $tag)
    {
        if (!in_array($tag->slug, $this->allowedForums)) {
            $checker = new LicenseChecker();
            if (!$checker->can_access($actor, $tag->slug)) {
                return false;
            }
        }
    }
}