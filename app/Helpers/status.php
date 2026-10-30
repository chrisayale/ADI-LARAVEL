<?php

if (!function_exists('translate_status')) {
    /**
     * Translate a status value using Laravel's translation system.
     * Falls back to ucfirst if translation not found.
     */
    function translate_status(string $status, string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $key = strtolower(str_replace(' ', '_', $status));

        $translation = __($key, [], $locale);

        // If translation returns the key itself (not found), use ucfirst
        if ($translation === $key) {
            return ucfirst(str_replace('_', ' ', $status));
        }

        return $translation;
    }
}

if (!function_exists('status_badge_class')) {
    /**
     * Get Bootstrap badge class for a status.
     */
    function status_badge_class(string $status): string
    {
        $status = strtolower($status);

        return match ($status) {
            'active', 'approved', 'paid', 'verified', 'completed', 'disbursed', 'fully_paid' => 'bg-success',
            'inactive', 'rejected', 'cancelled', 'defaulted' => 'bg-danger',
            'pending', 'processing', 'under_review', 'awaiting_approval', 'awaiting_disbursement', 'partially_paid' => 'bg-warning text-dark',
            'overdue' => 'bg-danger',
            'unverified', 'submitted' => 'bg-info',
            default => 'bg-secondary',
        };
    }
}

if (!function_exists('status_badge')) {
    /**
     * Render a status badge with translation.
     */
    function status_badge(string $status, array $attributes = []): string
    {
        $class = status_badge_class($status);
        $label = translate_status($status);

        $attrs = '';
        foreach ($attributes as $key => $value) {
            $attrs .= " $key=\"$value\"";
        }

        return "<span class=\"badge $class\"$attrs>$label</span>";
    }
}