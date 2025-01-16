<?php

namespace AyeCode\FlarumRestrictions\Policy;

use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;
use Flarum\Tags\Tag;
use AyeCode\FlarumRestrictions\Access\LicenseChecker;

class TagPolicy extends AbstractPolicy
{
    public function startDiscussion(User $actor, Tag $tag)
    {
        if ($tag->slug !== 'general') {
            $checker = new LicenseChecker();
            if (!$checker->can_access($actor)) {
                return false;
            }
        }
    }
}