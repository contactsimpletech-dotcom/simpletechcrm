<?php
/**
 * Base Module class.
 * Every module extends this and implements boot().
 */
abstract class Module {
    protected string $id      = '';
    protected string $name    = '';
    protected string $version = '1.0.0';

    /** Register routes, nav items, hooks. Called on every request if module is active. */
    abstract public function boot(): void;

    /** Run on install — executes install.sql and any PHP setup. */
    public function install(): void {
        $sql = BH_MODULES . '/' . $this->id . '/install.sql';
        if (file_exists($sql)) DB::runSql(file_get_contents($sql));
    }

    /** Run on uninstall — executes uninstall.sql. */
    public function uninstall(): void {
        $sql = BH_MODULES . '/' . $this->id . '/uninstall.sql';
        if (file_exists($sql)) DB::runSql(file_get_contents($sql));
    }

    public function getId(): string      { return $this->id; }
    public function getName(): string    { return $this->name; }
    public function getVersion(): string { return $this->version; }
}
