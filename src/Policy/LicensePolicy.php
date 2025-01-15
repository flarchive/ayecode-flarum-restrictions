<?php

namespace AyeCode\FlarumRestrictions\Policy;

use Flarum\Discussion\Discussion;
use Flarum\Post\Post;
use Flarum\User\Access\AbstractPolicy;
use Flarum\User\User;

class LicensePolicy extends AbstractPolicy
{
    // Hardcoded mapping for POC - will be replaced with config/settings
    protected $restrictedTags = [
        'location-manager' => '123'
    ];

    /**
     * Mock function to check if user has access to product
     * This will be replaced with actual cookie check later
     */
    protected function hasProductAccess(User $user, string $productId): bool
    {
        // TODO: Replace with actual cookie check
        return false; // For testing - returns false to test restriction
    }

    /**
     * Check if discussion should be restricted based on its tags
     */
    protected function isRestricted(Discussion $discussion): bool
    {
        foreach ($discussion->tags as $tag) {
            if (isset($this->restrictedTags[$tag->slug])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get required product ID for a discussion
     */
    protected function getRequiredProduct(Discussion $discussion): ?string
    {
        foreach ($discussion->tags as $tag) {
            if (isset($this->restrictedTags[$tag->slug])) {
                return $this->restrictedTags[$tag->slug];
            }
        }
        return null;
    }

    /**
     * Policy to check discussion viewing permission
     */
    public function view(User $user, Discussion $discussion)
    {
        if (!$this->isRestricted($discussion)) {
            return true;
        }

        $requiredProduct = $this->getRequiredProduct($discussion);

        if ($requiredProduct && !$this->hasProductAccess($user, $requiredProduct)) {
            return false;
        }

        return true;
    }

    /**
     * Policy to check post viewing permission
     */
    public function viewPosts(User $user, Discussion $discussion)
    {
        // Reuse the same logic as discussion viewing for now
        return $this->view($user, $discussion);
    }

    /**
     * Policy to check reply permission
     */
    public function reply(User $user, Discussion $discussion)
    {
        // Reuse the same logic as viewing for now
        return $this->view($user, $discussion);
    }
}