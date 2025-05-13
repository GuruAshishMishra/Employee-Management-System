<?php
/**
 * Fired during plugin deactivation.
 */
class EMS_Deactivator {
    /**
     * Deactivate the plugin.
     */
    public static function deactivate() {
        // Clean up if needed
        // For now, we'll just leave the database tables intact
    }
}