<?php
/**
 * Cron  system-wide task registry.
 * Modules call Cron::register() in their boot() method.
 * The /cron/run endpoint iterates all registered tasks.
 */
class Cron {
    private static array $tasks = [];

    /**
     * Register a cron task.
     * @param string   $id   Unique task ID (e.g. 'invoices.due_warnings')
     * @param callable $fn   fn(): array  returns array of log lines
     */
public static function register(string $id, string|callable $frequency_or_fn, callable $fn = null): void {
    if ($fn === null) {
        // Old 2-arg style: register($id, $fn)
        $fn = $frequency_or_fn;
        $frequency = 'always';
    } else {
        // New 3-arg style: register($id, $frequency, $fn)
        $frequency = $frequency_or_fn;
    }
    self::$tasks[$id] = ['fn' => $fn, 'frequency' => $frequency];
}
    /** Run all registered tasks. Returns array ['id' => [...log lines]]. */
    public static function runAll(): array {
        $results = [];
foreach (self::$tasks as $id => $task) {
    $fn = is_array($task) ? $task['fn'] : $task;
    try {
        $log = $fn();
                $results[$id] = is_array($log) ? $log : [];
            } catch (\Throwable $e) {
                $results[$id] = ['ERROR: ' . $e->getMessage()];
            }
        }
        return $results;
    }

    public static function tasks(): array { return array_keys(self::$tasks); }
}

