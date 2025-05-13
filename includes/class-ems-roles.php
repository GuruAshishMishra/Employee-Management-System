<?php
/**
 * Role management functionality.
 */
class EMS_Roles {
    /**
     * Initialize roles.
     */
    public static function initialize_roles() {
        // Add custom capabilities to administrator
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap('ems_manage_employees');
            $admin->add_cap('ems_manage_attendance');
            $admin->add_cap('ems_manage_salary');
            $admin->add_cap('ems_view_reports');
            $admin->add_cap('ems_manage_settings');
        }
        
        // Create HR Manager role if it doesn't exist
        if (!get_role('ems_hr_manager')) {
            add_role(
                'ems_hr_manager',
                __('HR Manager', 'employee-management-system'),
                array(
                    'read' => true,
                    'ems_manage_employees' => true,
                    'ems_manage_attendance' => true,
                    'ems_manage_salary' => true,
                    'ems_view_reports' => true
                )
            );
        }
        
        // Create Viewer role if it doesn't exist
        if (!get_role('ems_viewer')) {
            add_role(
                'ems_viewer',
                __('EMS Viewer', 'employee-management-system'),
                array(
                    'read' => true,
                    'ems_view_reports' => true
                )
            );
        }
    }
    
    /**
     * Check if user has a specific EMS role.
     */
    public static function has_role($user_id, $role) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_user_roles';
        
        $user_role = $wpdb->get_var($wpdb->prepare(
            "SELECT role FROM $table_name WHERE user_id = %d",
            $user_id
        ));
        
        return $user_role === $role;
    }
    
    /**
     * Assign a role to a user.
     */
    public static function assign_role($user_id, $role) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_user_roles';
        
        // Check if user already has a role
        $existing_role = $wpdb->get_var($wpdb->prepare(
            "SELECT role FROM $table_name WHERE user_id = %d",
            $user_id
        ));
        
        if ($existing_role) {
            // Update existing role
            return $wpdb->update(
                $table_name,
                array('role' => $role),
                array('user_id' => $user_id)
            );
        } else {
            // Insert new role
            return $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'role' => $role
                )
            );
        }
    }
    
    /**
     * Remove a role from a user.
     */
    public static function remove_role($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_user_roles';
        
        return $wpdb->delete($table_name, array('user_id' => $user_id));
    }
    
    /**
     * Check if current user can perform an action.
     */
    public static function current_user_can($capability) {
        // WordPress administrators always have all capabilities
        if (current_user_can('administrator')) {
            return true;
        }
        
        // Check if user has the WordPress capability
        if (current_user_can($capability)) {
            return true;
        }
        
        // Check custom role capabilities
        $user_id = get_current_user_id();
        $role = self::get_user_role($user_id);
        
        if ($role === 'admin') {
            return true;
        }
        
        if ($role === 'hr_manager') {
            $hr_capabilities = array(
                'ems_manage_employees',
                'ems_manage_attendance',
                'ems_manage_salary',
                'ems_view_reports'
            );
            
            return in_array($capability, $hr_capabilities);
        }
        
        if ($role === 'viewer') {
            return $capability === 'ems_view_reports';
        }
        
        return false;
    }
    
    /**
     * Get a user's EMS role.
     */
    public static function get_user_role($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_user_roles';
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT role FROM $table_name WHERE user_id = %d",
            $user_id
        ));
    }
    
    /**
     * Get all users with EMS roles.
     */
    public static function get_users_with_roles() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ems_user_roles';
        
        $query = "SELECT r.*, u.display_name, u.user_email 
                 FROM $table_name r
                 JOIN {$wpdb->users} u ON r.user_id = u.ID
                 ORDER BY u.display_name ASC";
        
        return $wpdb->get_results($query);
    }
}