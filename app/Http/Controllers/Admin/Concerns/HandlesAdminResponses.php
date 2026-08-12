<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\RedirectResponse;

trait HandlesAdminResponses
{
    protected function flashRedirect(
        string $route,
        string $message,
        string $type = 'success',
        array $parameters = []
    ): RedirectResponse {
        return redirect()
            ->route($route, $parameters)
            ->with($type, $message);
    }

    protected function flashBack(string $message, string $type = 'success'): RedirectResponse
    {
        return redirect()
            ->back()
            ->with($type, $message);
    }

    protected function flashSuccess(string $route, string $message, array $parameters = []): RedirectResponse
    {
        return $this->flashRedirect($route, $message, 'success', $parameters);
    }

    protected function flashError(string $route, string $message, array $parameters = []): RedirectResponse
    {
        return $this->flashRedirect($route, $message, 'error', $parameters);
    }
}
