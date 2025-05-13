<div class="wrap ems-admin">
    <h1 class="wp-heading-inline"><?php _e('Employee Management Dashboard', 'employee-management-system'); ?></h1>
    
    <!-- Dashboard Cards -->
    <div class="ems-dashboard-cards">
        <div class="ems-card">
            <div class="ems-card-icon">
                <span class="dashicons dashicons-groups"></span>
            </div>
            <div class="ems-card-content">
                <h3><?php _e('Total Employees', 'employee-management-system'); ?></h3>
                <p class="ems-card-number"><?php echo esc_html($employee_count); ?></p>
            </div>
        </div>
        
        <div class="ems-card">
            <div class="ems-card-icon ems-card-icon-present">
                <span class="dashicons dashicons-yes-alt"></span>
            </div>
            <div class="ems-card-content">
                <h3><?php _e('Present Today', 'employee-management-system'); ?></h3>
                <p class="ems-card-number"><?php echo esc_html($present_today); ?></p>
            </div>
        </div>
        
        <div class="ems-card">
            <div class="ems-card-icon ems-card-icon-absent">
                <span class="dashicons dashicons-no-alt"></span>
            </div>
            <div class="ems-card-content">
                <h3><?php _e('Absent Today', 'employee-management-system'); ?></h3>
                <p class="ems-card-number"><?php echo esc_html($absent_today); ?></p>
            </div>
        </div>
        
        <div class="ems-card">
            <div class="ems-card-icon ems-card-icon-salary">
                <span class="dashicons dashicons-money-alt"></span>
            </div>
            <div class="ems-card-content">
                <h3><?php _e('Pending Salary', 'employee-management-system'); ?></h3>
                <p class="ems-card-number"><?php echo esc_html($pending_salary); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="ems-quick-links">
        <h2><?php _e('Quick Links', 'employee-management-system'); ?></h2>
        <div class="ems-quick-links-container">
            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-employees')); ?>" class="ems-quick-link">
                <span class="dashicons dashicons-groups"></span>
                <span><?php _e('Manage Employees', 'employee-management-system'); ?></span>
            </a>
            
            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-attendance')); ?>" class="ems-quick-link">
                <span class="dashicons dashicons-calendar-alt"></span>
                <span><?php _e('Mark Attendance', 'employee-management-system'); ?></span>
            </a>
            
            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-salary')); ?>" class="ems-quick-link">
                <span class="dashicons dashicons-money-alt"></span>
                <span><?php _e('Generate Salary', 'employee-management-system'); ?></span>
            </a>
            
            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-settings')); ?>" class="ems-quick-link">
                <span class="dashicons dashicons-admin-settings"></span>
                <span><?php _e('Settings', 'employee-management-system'); ?></span>
            </a>
        </div>
    </div>
    
    <!-- Plugin Information -->
    <div class="ems-plugin-info">
        <h2><?php _e('About Employee Management System', 'employee-management-system'); ?></h2>
        <p><?php _e('Employee Management System is a comprehensive WordPress plugin for managing employees, attendance, and payroll.', 'employee-management-system'); ?></p>
        <p><?php _e('Key features include:', 'employee-management-system'); ?></p>
        <ul>
            <li><?php _e('Employee Management - Add, edit, and import employees', 'employee-management-system'); ?></li>
            <li><?php _e('Attendance Management - Track daily attendance', 'employee-management-system'); ?></li>
            <li><?php _e('Salary Management - Generate and manage monthly salary', 'employee-management-system'); ?></li>
            <li><?php _e('Role-based Access Control - Admin, HR Manager, and Viewer roles', 'employee-management-system'); ?></li>
        </ul>
    </div>
</div>