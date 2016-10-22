<?php
namespace Trungtnm\Backend\Http\Middleware;

use Sentinel;

class VerifyCsrfToken extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
{
    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        if (
            $request->segment(1) == trim(config('trungtnm.backend.uri'), '/')
            && $request->isXmlHttpRequest()
            && Sentinel::check()
        ) {
            return true;
        }

        return false;

    }
}
