<?php

namespace {
    if (!defined('VENDOR_PARTNER_NAME')) {
        /* @const Aspro partner name */
        define('VENDOR_PARTNER_NAME', 'aspro');
    }

    if (!defined('VENDOR_SOLUTION_NAME')) {
        /* @const Aspro solution name */
        define('VENDOR_SOLUTION_NAME', 'next');
    }

    if (!defined('VENDOR_MODULE_ID')) {
        /* @const Aspro module id */
        define('VENDOR_MODULE_ID', 'aspro.next');
    }

    foreach ([
        'CNext' => 'TSolution',
        'CNextCache' => 'TSolution\Cache',
        'CNextCondition' => 'TSolution\Condition',
        'CNextEvents' => 'TSolution\Events',
        'CNextRegionality' => 'TSolution\Regionality',

        'Aspro\Functions\CAsproNext' => 'TSolution\Functions',
        'Aspro\Next\Captcha' => 'TSolution\Captcha',
        'Aspro\Next\Captcha\Service' => 'TSolution\Captcha\Service',
        'Aspro\Next\Functions\Extensions' => 'TSolution\Extensions',
        'Aspro\Next\Grupper' => 'TSolution\Grupper',
        'Aspro\Next\PhoneAuth' => 'TSolution\PhoneAuth',
        'Aspro\Next\Product\Sku' => 'TSolution\Product\Sku',
        'Aspro\Next\Product\SkuTools' => 'TSolution\Product\SkuTools',
        'Aspro\Next\Product\MetaInfo' => 'TSolution\Product\MetaInfo',
        'Aspro\Next\Product\DetailProps' => 'TSolution\Product\DetailProps',
        'Aspro\Functions\CAsproItem' => 'TSolution\Item',
        'Aspro\Functions\CAsproSku' => 'TSolution\Sku',
        'Aspro\Next\CrossSales' => 'TSolution\CrossSales',
        'Aspro\Next\CacheableUrl' => 'TSolution\CacheableUrl',
        'Aspro\Next\Social\Factory' => 'TSolution\Social\Factory',
        'Aspro\Next\Traits\Consent' => 'TSolution\Consent',
        'Aspro\Next\Url' => 'TSolution\Url',
        'Aspro\Next\Utils' => 'TSolution\Utils',
        'Aspro\Next\Validation' => 'TSolution\Validation',
    ] as $original => $alias) {
        if (!class_exists($alias)) {
            class_alias($original, $alias);
        }
    }

    // these alias declarations for IDE only
    if (false) {
        class TSolution extends CNext
        {
        }
    }
}

namespace TSolution {
    if (false) {
        class Extensions extends \Aspro\Next\Functions\Extensions
        {
        }

        class Grupper extends \Aspro\Next\Grupper
        {
        }

        class PhoneAuth extends \Aspro\Next\PhoneAuth
        {
        }

        class Validation extends \Aspro\Next\Validation
        {
        }

        class Consent extends \Aspro\Next\Consent
        {
        }

        class Functions extends \Aspro\Functions\CAsproNext
        {
        }
    }
}

namespace TSolution\Captcha {
    if (false) {
        class Service extends \Aspro\Next\Captcha\Service
        {
        }
    }
}
