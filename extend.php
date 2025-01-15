<?php

namespace AyeCode\FlarumRestrictions;

use Flarum\Extend;
use AyeCode\FlarumRestrictions\Policy\LicensePolicy;
use Flarum\Discussion\Discussion;

return [
    (new Extend\Policy())
        ->modelPolicy(Discussion::class, LicensePolicy::class),
];
