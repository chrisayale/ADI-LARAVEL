<?php
// We'll edit the file directly using sed, but we need to be careful.
// Let's replace the has() method.
$file = file_get_contents('app/Support/TenantContext.php');
$old = '    /**
     * Vérifier si un SACCO courant est défini
     */
    public static function has(): bool
    {
        return self::get() !== null;
    }';
$new = '    /**
     * Vérifier si un SACCO courant est défini
     */
    public static function has(): bool
    {
        return app()->bound('currentSacco') && self::get() !== null;
    }';
if (strpos($file, $old) === false) {
    // Try alternative formatting
    $old = "    /**\n     * Vérifier si un SACCO courant est défini\n     */\n    public static function has(): bool\n    {\n        return self::get() !== null;\n    }";
    $new = "    /**\n     * Vérifier si un SACCO courant est défini\n     */\n    public static function has(): bool\n    {\n        return app()->bound('currentSacco') && self::get() !== null;\n    }";
}
$file = str_replace($old, $new, $file);
file_put_contents('app/Support/TenantContext.php', $file);
echo "Fixed TenantContext::has()\n";
