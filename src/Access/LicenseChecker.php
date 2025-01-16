<?php

namespace AyeCode\FlarumRestrictions\Access;

use Flarum\User\User;

class LicenseChecker
{
    private function get_cookie_data()
    {
        // Debug log all cookies
        file_put_contents(
            storage_path('logs/cookie-debug.log'),
            "Checking cookies: " . print_r($_COOKIE, true) . "\n",
            FILE_APPEND
        );

        // Look for our specific cookie
        if (isset($_COOKIE['ayecode_licenses'])) {
            $license_data = json_decode($_COOKIE['ayecode_licenses'], true);

            file_put_contents(
                storage_path('logs/cookie-debug.log'),
                "Found license data: " . print_r($license_data, true) . "\n",
                FILE_APPEND
            );

            return $license_data;
        }

        return null;
    }

    public function can_access(User $user = null): bool
    {
        $cookie_data = $this->get_cookie_data();

        // If we have cookie data, check for location manager license
        if ($cookie_data && isset($cookie_data['products'])) {
            return in_array('123', $cookie_data['products']); // 123 is location manager product ID
        }

        return false;
    }
}