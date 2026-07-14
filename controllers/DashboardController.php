<?php
class DashboardController {
    public function index(): void {
        Auth::requireLogin();
        if (Auth::isClient()) Router::redirect('/portal/dashboard');

        // Each module adds its own dashboard stats via bh_dashboard_stats filter
        $stats   = apply_bh_filter('dashboard_stats',   []);
        $widgets = apply_bh_filter('dashboard_widgets',  []);

        View::render('dashboard/index', compact('stats', 'widgets'));
    }
}

// ── Lightweight hook system (replaces WordPress add_action/apply_filters) ──────
function add_bh_filter(string $hook, callable $cb, int $priority = 10): void {
    global $bh_filters;
    $bh_filters[$hook][$priority][] = $cb;
}

function apply_bh_filter(string $hook, mixed $value): mixed {
    global $bh_filters;
    if (empty($bh_filters[$hook])) return $value;
    ksort($bh_filters[$hook]);
    foreach ($bh_filters[$hook] as $group) {
        foreach ($group as $cb) $value = $cb($value);
    }
    return $value;
}

function do_bh_action(string $hook, mixed ...$args): void {
    global $bh_filters;
    if (empty($bh_filters[$hook])) return;
    ksort($bh_filters[$hook]);
    foreach ($bh_filters[$hook] as $group) {
        foreach ($group as $cb) $cb(...$args);
    }
}
