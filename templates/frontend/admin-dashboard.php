<div class="ems-container ems-admin-dashboard-container">
    <div class="ems-page-header">
        <h1><?php _e('Admin Dashboard', 'employee-management-system'); ?></h1>
        <div class="ems-user-info">
            <?php
            $user = wp_get_current_user();
            $display_name = $user->display_name;
            ?>
            <span class="ems-welcome-text"><?php printf(__('Welcome, %s', 'employee-management-system'), esc_html($display_name)); ?></span>
            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                <?php _e('Logout', 'employee-management-system'); ?>
            </a>
        </div>
    </div>
    
    <div class="ems-admin-nav">
        <ul class="ems-nav">
            <li class="ems-nav-item active">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_dashboard_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-dashboard"></i>
                    <?php _e('Dashboard', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_profile_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-user"></i>
                    <?php _e('My Profile', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_attendance_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-calendar"></i>
                    <?php _e('Attendance', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_salary_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-money"></i>
                    <?php _e('Salary', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_employee_registration_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-user-plus"></i>
                    <?php _e('Add Employee', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_employees_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-user-plus"></i>
                    <?php _e('All Employees', 'employee-management-system'); ?>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="ems-admin-content">
        <div class="ems-row">
            <div class="ems-col-md-3">
                <div class="ems-stat-card ems-stat-employees">
                    <div class="ems-stat-icon">
                        <i class="ems-icon ems-icon-users"></i>
                    </div>
                    <div class="ems-stat-content">
                        <div class="ems-stat-value"><?php echo esc_html($employee_count); ?></div>
                        <div class="ems-stat-label"><?php _e('Total Employees', 'employee-management-system'); ?></div>
                    </div>
                </div>
            </div>
            <div class="ems-col-md-3">
                <div class="ems-stat-card ems-stat-present">
                    <div class="ems-stat-icon">
                        <i class="ems-icon ems-icon-check-circle"></i>
                    </div>
                    <div class="ems-stat-content">
                        <div class="ems-stat-value"><?php echo esc_html($present_today); ?></div>
                        <div class="ems-stat-label"><?php _e('Present Today', 'employee-management-system'); ?></div>
                    </div>
                </div>
            </div>
            <div class="ems-col-md-3">
                <div class="ems-stat-card ems-stat-absent">
                    <div class="ems-stat-icon">
                        <i class="ems-icon ems-icon-x-circle"></i>
                    </div>
                    <div class="ems-stat-content">
                        <div class="ems-stat-value"><?php echo esc_html($absent_today); ?></div>
                        <div class="ems-stat-label"><?php _e('Absent Today', 'employee-management-system'); ?></div>
                    </div>
                </div>
            </div>
            <div class="ems-col-md-3">
                <div class="ems-stat-card ems-stat-pending">
                    <div class="ems-stat-icon">
                        <i class="ems-icon ems-icon-clock"></i>
                    </div>
                    <div class="ems-stat-content">
                        <div class="ems-stat-value"><?php echo esc_html($pending_salary); ?></div>
                        <div class="ems-stat-label"><?php _e('Pending Salaries', 'employee-management-system'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="ems-row ems-mt-4">
            <div class="ems-col-md-6">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Recent Employees', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <?php if (empty($recent_employees)) : ?>
                            <div class="ems-alert ems-alert-info">
                                <?php _e('No employees found.', 'employee-management-system'); ?>
                            </div>
                        <?php else : ?>
                            <div class="ems-table-responsive">
                                <table class="ems-table">
                                    <thead>
                                        <tr>
                                            <th><?php _e('Name', 'employee-management-system'); ?></th>
                                            <th><?php _e('Position', 'employee-management-system'); ?></th>
                                            <th><?php _e('Joined', 'employee-management-system'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_employees as $employee) : ?>
                                            <tr>
                                                <td>
                                                    <div class="ems-employee-list-item">
                                                        <?php if (!empty($employee->profile_picture)) : ?>
                                                            <div class="ems-employee-list-avatar">
                                                                <img src="<?php echo esc_url($employee->profile_picture); ?>" alt="<?php echo esc_attr($employee->name); ?>">
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="ems-employee-list-avatar ems-employee-list-avatar-placeholder">
                                                                <?php echo esc_html(substr($employee->name, 0, 1)); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="ems-employee-list-name">
                                                            <?php echo esc_html($employee->name); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo esc_html($employee->job_title); ?></td>
                                                <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($employee->joining_date))); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="ems-text-center ems-mt-3">
                                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_employees_page_id'))); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                                    <?php _e('View All Employees', 'employee-management-system'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="ems-col-md-6">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Quick Actions', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-quick-actions">
                            <a href="<?php echo esc_url(get_permalink(get_option('ems_employee_registration_page_id'))); ?>" class="ems-quick-action">
                                <i class="ems-icon ems-icon-user-plus"></i>
                                <span><?php _e('Add Employee', 'employee-management-system'); ?></span>
                            </a>
                            <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_attendance_page_id'))); ?>" class="ems-quick-action">
                                <i class="ems-icon ems-icon-calendar-plus"></i>
                                <span><?php _e('Mark Attendance', 'employee-management-system'); ?></span>
                            </a>
                            <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_salary_page_id'))); ?>" class="ems-quick-action">
                                <i class="ems-icon ems-icon-dollar-sign"></i>
                                <span><?php _e('Process Salary', 'employee-management-system'); ?></span>
                            </a>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-settings')); ?>" class="ems-quick-action">
                                <i class="ems-icon ems-icon-settings"></i>
                                <span><?php _e('Settings', 'employee-management-system'); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="ems-card ems-mt-4">
                    <div class="ems-card-header">
                        <h3><?php _e('System Information', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-system-info">
                            <div class="ems-system-info-item">
                                <span class="ems-system-info-label"><?php _e('Plugin Version:', 'employee-management-system'); ?></span>
                                <span class="ems-system-info-value"><?php echo esc_html(EMS_PLUGIN_VERSION); ?></span>
                            </div>
                            <div class="ems-system-info-item">
                                <span class="ems-system-info-label"><?php _e('WordPress Version:', 'employee-management-system'); ?></span>
                                <span class="ems-system-info-value"><?php echo esc_html(get_bloginfo('version')); ?></span>
                            </div>
                            <div class="ems-system-info-item">
                                <span class="ems-system-info-label"><?php _e('PHP Version:', 'employee-management-system'); ?></span>
                                <span class="ems-system-info-value"><?php echo esc_html(phpversion()); ?></span>
                            </div>
                            <div class="ems-system-info-item">
                                <span class="ems-system-info-label"><?php _e('Database Version:', 'employee-management-system'); ?></span>
                                <span class="ems-system-info-value"><?php echo esc_html($wpdb->db_version()); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>