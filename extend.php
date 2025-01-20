<?php

namespace AyeCode\FlarumRestrictions;

use Flarum\Extend;
use Illuminate\Contracts\View\Factory;
use Flarum\Post\Post;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Api\Serializer\TagSerializer;
use Flarum\Discussion\Discussion;
use Flarum\Tags\Tag;
use AyeCode\FlarumRestrictions\Access\LicenseChecker;
use AyeCode\FlarumRestrictions\Policy\TagPolicy;

return [


    // register our new templates
    (new Extend\View)->namespace("flarum-restrictions", __DIR__."/resources/views"),


    // Add this frontend JS
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->content(function (\Flarum\Frontend\Document $document) { // set new template
        $document->layoutView = "flarum-restrictions::frontend.forum";
    }),


    // Content restriction
//    (new Extend\ApiSerializer(PostSerializer::class))
//        ->attributes(function(PostSerializer $serializer, Post $post, array $attributes) {
//            if ($post->number === 1) {
//                return $attributes;
//            }
//
//            if ($post->discussion && $post->discussion->tags) {
//                foreach ($post->discussion->tags as $tag) {
//                    if ($tag->slug !== 'general') {
//                        $checker = new LicenseChecker();
//                        if (!$checker->can_access($serializer->getActor())) {
//                            $attributes['contentHtml'] = '<div class="Alert"><p>This content is restricted. Please purchase a Location Manager license to view replies.</p><p><a href="https://wpgeodirectory.com/downloads/location-manager/" class="Button">Purchase License</a></p></div>';
//                            $attributes['content'] = 'This content is restricted. Please purchase a Location Manager license to view replies.';
//                        }
//                    }
//                }
//            }
//
//            return $attributes;
//        }),


    // Discussion reply restriction
    (new Extend\ApiSerializer(DiscussionSerializer::class))
        ->attributes(function(DiscussionSerializer $serializer, Discussion $discussion, array $attributes) {
            if ($discussion->tags) {
                foreach ($discussion->tags as $tag) {
                    if ($tag->slug !== 'general') {
                        $checker = new LicenseChecker();
                        if (!$checker->can_access($serializer->getActor(), $tag->slug)) {
                            $attributes['canReply'] = false;
                           // $attributes['replyPlaceholder'] = 'You must have a valid license to reply to this discussion. <a href="https://wpgeodirectory.com/downloads/location-manager/" class="Button Button--link">Purchase License</a>';
                        }
                    }
                }
            }
            return $attributes;
        }),


    // Tag permissions
    (new Extend\ApiSerializer(TagSerializer::class))
        ->attributes(function(TagSerializer $serializer, Tag $tag, array $attributes) {
            if ($tag->slug !== 'general') {
                $checker = new LicenseChecker();
                if (!$checker->can_access($serializer->getActor(), $tag->slug)) {
                    $attributes['canStartDiscussion'] = false;
                    $attributes['canAddToDiscussion'] = false;
                 //   $attributes['description'] = ($attributes['description'] ?? '') . ' <div class="Alert Alert--warning"><p>You need a valid license to start discussions in this section. <a href="https://wpgeodirectory.com/downloads/location-manager/" class="Button Button--link">Purchase License</a></p></div>';
                }
            }
            return $attributes;
        }),


    // Register the Tag Policy
    (new Extend\Policy())
        ->modelPolicy(Tag::class, TagPolicy::class)
];