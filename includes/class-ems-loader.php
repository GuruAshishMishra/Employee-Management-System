<?php
/**
 * The main loader class for the plugin.
 */
class EMS_Loader {
    /**
     * Run the loader to execute all the hooks.
     */
    public function run() {
        // Load dependencies
        $this->load_dependencies();
        
        // Register admin menu
        add_action('admin_menu', array($this, 'register_admin_menu'));
        
        // Register scripts and styles for backend
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        // Register scripts and styles for frontend
        add_action('wp_enqueue_scripts', array($this, 'enqueue_admin_front_assets'));
    }   
    
    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        // Core classes
        require_once EMS_PLUGIN_DIR . 'includes/class-ems-employee.php';
        require_once EMS_PLUGIN_DIR . 'includes/class-ems-attendance.php';
        require_once EMS_PLUGIN_DIR . 'includes/class-ems-salary.php';
        require_once EMS_PLUGIN_DIR . 'includes/class-ems-roles.php';
        
        // Admin classes
        require_once EMS_PLUGIN_DIR . 'admin/class-ems-admin.php';
        require_once EMS_PLUGIN_DIR . 'admin/class-ems-employee-admin.php';
        require_once EMS_PLUGIN_DIR . 'admin/class-ems-attendance-admin.php';
        require_once EMS_PLUGIN_DIR . 'admin/class-ems-salary-admin.php';
        
        // Frontend classes
        require_once EMS_PLUGIN_DIR . 'includes/class-ems-frontend.php';
        require_once EMS_PLUGIN_DIR . 'includes/class-ems-frontend-admin.php';
        
        // Include TCPDF library for PDF generation
        if (!class_exists('TCPDF')) {
            // Note: You'll need to download TCPDF library and place it in the includes/tcpdf directory
            // or use a WordPress PDF generation plugin
        }
    }
    
    /**
     * Register the admin menu.
     */
    public function register_admin_menu() {
        // Main menu
        add_menu_page(
            __('Employee Management', 'employee-management-system'),
            __('Employee Management', 'employee-management-system'),
            'manage_options',
            'employee-management',
            array('EMS_Admin', 'display_dashboard'),
            'dashicons-groups',
            30
        );
        
        // Submenus
        add_submenu_page(
            'employee-management',
            __('Dashboard', 'employee-management-system'),
            __('Dashboard', 'employee-management-system'),
            'manage_options',
            'employee-management',
            array('EMS_Admin', 'display_dashboard')
        );
        
        add_submenu_page(
            'employee-management',
            __('Employees', 'employee-management-system'),
            __('Employees', 'employee-management-system'),
            'manage_options',
            'employee-management-employees',
            array('EMS_Employee_Admin', 'display_employees')
        );
        
        add_submenu_page(
            'employee-management',
            __('Attendance', 'employee-management-system'),
            __('Attendance', 'employee-management-system'),
            'manage_options',
            'employee-management-attendance',
            array('EMS_Attendance_Admin', 'display_attendance')
        );
        
        add_submenu_page(
            'employee-management',
            __('Salary', 'employee-management-system'),
            __('Salary', 'employee-management-system'),
            'manage_options',
            'employee-management-salary',
            array('EMS_Salary_Admin', 'display_salary')
        );
        
        add_submenu_page(
            'employee-management',
            __('Settings', 'employee-management-system'),
            __('Settings', 'employee-management-system'),
            'manage_options',
            'employee-management-settings',
            array('EMS_Admin', 'display_settings')
        );
        
        // Add Frontend Pages submenu
        add_submenu_page(
            'employee-management',
            __('Frontend Pages', 'employee-management-system'),
            __('Frontend Pages', 'employee-management-system'),
            'manage_options',
            'employee-management-frontend',
            array($this, 'display_frontend_pages')
        );
    }
    
    /**
     * Display frontend pages information.
     */
    public function display_frontend_pages() {
        ?>
        <div class="wrap">
            <h1><?php _e('Frontend Pages', 'employee-management-system'); ?></h1>
            <p><?php _e('The following pages have been created for the frontend functionality of the Employee Management System.', 'employee-management-system'); ?></p>
            
            <h2><?php _e('Employee Pages', 'employee-management-system'); ?></h2>
            <table class="widefat fixed" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php _e('Page', 'employee-management-system'); ?></th>
                        <th><?php _e('Shortcode', 'employee-management-system'); ?></th>
                        <th><?php _e('URL', 'employee-management-system'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php _e('Employee Login', 'employee-management-system'); ?></td>
                        <td><code>[ems_login]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_login_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Employee Dashboard', 'employee-management-system'); ?></td>
                        <td><code>[ems_dashboard]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_dashboard_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('My Profile', 'employee-management-system'); ?></td>
                        <td><code>[ems_profile]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_profile_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('My Attendance', 'employee-management-system'); ?></td>
                        <td><code>[ems_attendance]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_attendance_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('My Salary', 'employee-management-system'); ?></td>
                        <td><code>[ems_salary]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_salary_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <h2 class="ems-mt-4"><?php _e('Admin Pages', 'employee-management-system'); ?></h2>
            <table class="widefat fixed" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php _e('Page', 'employee-management-system'); ?></th>
                        <th><?php _e('Shortcode', 'employee-management-system'); ?></th>
                        <th><?php _e('URL', 'employee-management-system'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php _e('Admin Dashboard', 'employee-management-system'); ?></td>
                        <td><code>[ems_admin_dashboard]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_admin_dashboard_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Manage Employees', 'employee-management-system'); ?></td>
                        <td><code>[ems_admin_employees]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_admin_employees_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Manage Attendance', 'employee-management-system'); ?></td>
                        <td><code>[ems_admin_attendance]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_admin_attendance_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Manage Salary', 'employee-management-system'); ?></td>
                        <td><code>[ems_admin_salary]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_admin_salary_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Employee Registration', 'employee-management-system'); ?></td>
                        <td><code>[ems_employee_registration]</code></td>
                        <td>
                            <?php 
                            $page_id = get_option('ems_employee_registration_page_id');
                            if ($page_id) {
                                echo '<a href="' . esc_url(get_permalink($page_id)) . '" target="_blank">' . esc_url(get_permalink($page_id)) . '</a>';
                            } else {
                                _e('Page not found', 'employee-management-system');
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="ems-mt-4">
                <h2><?php _e('Recreate Pages', 'employee-management-system'); ?></h2>
                <p><?php _e('If you need to recreate the frontend pages, click the button below.', 'employee-management-system'); ?></p>
                <form method="post" action="">
                    <?php wp_nonce_field('ems_recreate_pages', 'ems_recreate_pages_nonce'); ?>
                    <input type="hidden" name="ems_action" value="recreate_pages">
                    <p>
                        <input type="submit" class="button button-primary" value="<?php esc_attr_e('Recreate Pages', 'employee-management-system'); ?>">
                    </p>
                </form>
            </div>
        </div>
        <?php
        
        // Process form submission
        if (isset($_POST['ems_action']) && $_POST['ems_action'] === 'recreate_pages') {
            if (isset($_POST['ems_recreate_pages_nonce']) && wp_verify_nonce($_POST['ems_recreate_pages_nonce'], 'ems_recreate_pages')) {
                // Delete existing pages
                $page_ids = array(
                    'ems_login_page_id',
                    'ems_dashboard_page_id',
                    'ems_profile_page_id',
                    'ems_attendance_page_id',
                    'ems_salary_page_id',
                    'ems_admin_dashboard_page_id',
                    'ems_admin_employees_page_id',
                    'ems_admin_attendance_page_id',
                    'ems_admin_salary_page_id',
                    'ems_employee_registration_page_id'
                );
                
                foreach ($page_ids as $option_name) {
                    $page_id = get_option($option_name);
                    if ($page_id) {
                        wp_delete_post($page_id, true);
                    }
                }
                
                // Reset pages created flag
                delete_option('ems_pages_created');
                
                // Recreate pages
                ems_create_pages();
                
                // Show success message
                add_settings_error(
                    'ems_recreate_pages',
                    'ems_recreate_pages',
                    __('Frontend pages have been recreated successfully.', 'employee-management-system'),
                    'updated'
                );
            }
        }
    }
    
    /**
     * Register the stylesheets and scripts for the Frontend area.
     */
    public function enqueue_admin_front_assets($hook){
        wp_enqueue_style(
            'ems-admin-front-css',
            EMS_PLUGIN_URL . 'assets/css/admin-frontend.css',
            array(),
            EMS_PLUGIN_VERSION
        );

        // JavaScript
        wp_enqueue_script(
            'ems-frontend-js',
            EMS_PLUGIN_URL . 'assets/js/ems-frontend.js',
            array('jquery'),
            EMS_PLUGIN_VERSION,
            true
        );

        // Localize script for AJAX
        wp_localize_script(
            'ems-frontend-js',
            'ems_frontend',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ems_frontend_nonce'),
                'i18n' => array(
                    'add_employee' => __('Add New Employee', 'employee-management-system'),
                    'edit_employee' => __('Edit Employee', 'employee-management-system')
                )
            )
        );
    }

    /**
     * Register the stylesheets and scripts for the admin area.
     */
    public function enqueue_admin_assets($hook) {
        // Only load on our plugin pages
        if (strpos($hook, 'employee-management') === false) {
            return;
        }
        
        // CSS
        wp_enqueue_style(
            'ems-admin-css',
            EMS_PLUGIN_URL . 'admin/css/ems-admin.css',
            array(),
            EMS_PLUGIN_VERSION
        );
        
        // JavaScript
        wp_enqueue_script(
            'ems-admin-js',
            EMS_PLUGIN_URL . 'admin/js/ems-admin.js',
            array('jquery'),
            EMS_PLUGIN_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script(
            'ems-admin-js',
            'ems_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ems_ajax_nonce'),
                'i18n' => array(
                    'add_employee' => __('Add New Employee', 'employee-management-system'),
                    'edit_employee' => __('Edit Employee', 'employee-management-system')
                )
            )
        );
    }
}