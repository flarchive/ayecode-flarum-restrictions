<?php

namespace AyeCode\FlarumRestrictions\Access;

use Flarum\User\User;
use Flarum\Tags\Tag;

class LicenseChecker
{
    // Define product IDs
    private $product_mappings = array(
        'location-manager' => '65853',  // Single product
        'pricing-manager' => '65868',
        'advanced-search'   => '65056',
        'custom-post-types' => '65108',
        'claim-listings' => '65098',
        'buddypress-integration' => '65093',
        'marker-cluster' => '65859',
        'multiratings-and-reviews' => '65876',
        'wp-all-import' => '687375',
        'social-importer' => '65886',
        'custom-map-styles' => '65102',
        'franchise-manager' => '65845',
        'list-manager' => '69994',
        'ajax-duplicate-alert' => '65088',
        'embeddable-ratings-badge' => '696082',
        'events-tickets-marketplace' => '2195307',
        'advertising' => '1847560',
        'compare-listings' => '724713',
        'geomarketplace' => '2684822',
        'dynamic-user-emails' => '3943311',
        'booking-marketplace' => '2885909',
        'saved-search-notifications' => '3322593',
        '' => '',
        '' => '',
        '' => '',
        '' => '',
        '' => '',
        '' => '',
        'membership-products' => array(
            '111327', // 4 months
            '111330', // 6 months
            '66235', // 12 months
            '807546', // LTD
        ) // Membership products that give access to all
    );

    private function get_cookie_data()
    {
//        file_put_contents(
//            storage_path('logs/cookie-debug.log'),
//            "Checking cookies: " . print_r($_COOKIE, true) . "\n",
//            FILE_APPEND
//        );

        if (isset($_COOKIE['ayecode_licenses'])) {
            $license_data = json_decode($_COOKIE['ayecode_licenses'], true);

//            file_put_contents(
//                storage_path('logs/cookie-debug.log'),
//                "Found license data: " . print_r($license_data, true) . "\n",
//                FILE_APPEND
//            );

            return $license_data;
        }

        return null;
    }

    private function has_required_product($user_products, $tag_slug)
    {
        // Log what we're checking
//        file_put_contents(
//            storage_path('logs/access-debug.log'),
//            "Checking access for tag: {$tag_slug}\n" .
//            "User products: " . print_r($user_products, true) . "\n",
//            FILE_APPEND
//        );

        // First check if user has any membership products
        foreach ($this->product_mappings['membership-products'] as $membership_id) {
            if (in_array($membership_id, $user_products)) {
                return true; // User has membership access
            }
        }

        // If not, check for specific product access
        if (isset($this->product_mappings[$tag_slug])) {
            return in_array($this->product_mappings[$tag_slug], $user_products);
        }

        return false;
    }

    public function can_access(User $user = null, $tag_slug = null): bool
    {

        // Allow admins and mods
        if ($user && ($user->isAdmin() || $user->hasPermission('discussion.moderate'))) {
            return true;
        }

        // If no tag slug provided, return false
        if (!$tag_slug) {
            return false;
        }

        $cookie_data = $this->get_cookie_data();

        if ($cookie_data && isset($cookie_data['products'])) {
            return $this->has_required_product($cookie_data['products'], $tag_slug);
        }

        return false;
    }
}