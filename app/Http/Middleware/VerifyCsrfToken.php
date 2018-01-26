<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //场馆请求账单相关接口，无需设防
        'bill/*',
        'placebill/*',
        '/referetoken',
        'wap/*',
    ];
}
