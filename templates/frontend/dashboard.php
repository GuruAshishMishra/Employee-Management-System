<div class="ems-container ems-dashboard-container">
    <div class="ems-page-header">
        <h1><?php _e('Employee Dashboard', 'employee-management-system'); ?></h1>
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
    
    <div class="ems-dashboard-nav">
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
    
    <div class="ems-dashboard-content">
        <div class="ems-row">
            <div class="ems-col-md-6">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Employee Information', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-employee-info">
                            <?php if (!empty($employee->profile_picture)) : ?>
                                <div class="ems-employee-avatar">
                                    <img src="<?php echo esc_url($employee->profile_picture); ?>" alt="<?php echo esc_attr($employee->name); ?>">
                                </div>
                            <?php else : ?>
                                <div class="ems-employee-avatar ems-employee-avatar-placeholder">
                                    <?php echo esc_html(substr($employee->name, 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="ems-employee-details">
                                <h4><?php echo esc_html($employee->name); ?></h4>
                                <p class="ems-employee-position"><?php echo esc_html($employee->job_title); ?></p>
                                <p class="ems-employee-company"><?php echo esc_html($employee->company_name); ?></p>
                                <p class="ems-employee-joined">
                                    <strong><?php _e('Joined:', 'employee-management-system'); ?></strong>
                                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($employee->joining_date))); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ems-col-md-6">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('This Month\'s Attendance', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-attendance-summary">
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value"><?php echo esc_html($attendance_summary ? $attendance_summary->present_days : 0); ?></div>
                                <div class="ems-attendance-stat-label"><?php _e('Present', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value"><?php echo esc_html($attendance_summary ? $attendance_summary->absent_days : 0); ?></div>
                                <div class="ems-attendance-stat-label"><?php _e('Absent', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value"><?php echo esc_html($attendance_summary ? $attendance_summary->half_days : 0); ?></div>
                                <div class="ems-attendance-stat-label"><?php _e('Half Day', 'employee-management-system'); ?></div>
                            </div>
                            <div class="ems-attendance-stat">
                                <div class="ems-attendance-stat-value"><?php echo esc_html($attendance_summary ? $attendance_summary->leave_days : 0); ?></div>
                                <div class="ems-attendance-stat-label"><?php _e('Leave', 'employee-management-system'); ?></div>
                            </div>
                        </div>
                        <div class="ems-text-center ems-mt-3">
                            <a href="<?php echo esc_url(get_permalink(get_option('ems_attendance_page_id'))); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                                <?php _e('View Full Attendance', 'employee-management-system'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="ems-row ems-mt-4">
            <div class="ems-col-md-6">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Current Month Salary', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <?php if ($current_month_salary) : ?>
                            <div class="ems-salary-info">
                                <div class="ems-salary-amount">
                                    <?php 
                                    $settings = get_option('ems_settings', array('currency_symbol' => '$'));
                                    echo esc_html($settings['currency_symbol'] . number_format_i18n($current_month_salary->total_salary, 2)); 
                                    ?>
                                </div>
                                <div class="ems-salary-status">
                                    <span class="ems-badge <?php echo $current_month_salary->payment_status === 'paid' ? 'ems-badge-success' : 'ems-badge-warning'; ?>">
                                        <?php echo $current_month_salary->payment_status === 'paid' ? __('Paid', 'employee-management-system') : __('Pending', 'employee-management-system'); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="ems-salary-details">
                                <div class="ems-salary-detail">
                                    <span class="ems-salary-detail-label"><?php _e('Base Salary:', 'employee-management-system'); ?></span>
                                    <span class="ems-salary-detail-value"><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($current_month_salary->base_salary, 2)); ?></span>
                                </div>
                                <?php if ($current_month_salary->bonus > 0) : ?>
                                    <div class="ems-salary-detail">
                                        <span class="ems-salary-detail-label"><?php _e('Bonus:', 'employee-management-system'); ?></span>
                                        <span class="ems-salary-detail-value"><?php echo esc_html($settings['currency_symbol'] . number_format_i18n($current_month_salary->bonus, 2)); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($current_month_salary->deduction > 0) : ?>
                                    <div class="ems-salary-detail">
                                        <span class="ems-salary-detail-label"><?php _e('Deduction:', 'employee-management-system'); ?></span>
                                        <span class="ems-salary-detail-value">-<?php echo esc_html($settings['currency_symbol'] . number_format_i18n($current_month_salary->deduction, 2)); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ems-text-center ems-mt-3">
                                <a href="<?php echo esc_url(get_permalink(get_option('ems_salary_page_id'))); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                                    <?php _e('View All Salary Records', 'employee-management-system'); ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="ems-alert ems-alert-info">
                                <?php _e('No salary record found for the current month.', 'employee-management-system'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="ems-col-md-6">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Quick Links', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-quick-links">
                            <a href="<?php echo esc_url(get_permalink(get_option('ems_profile_page_id'))); ?>" class="ems-quick-link">
                                <i class="ems-icon ems-icon-user"></i>
                                <span><?php _e('Update Profile', 'employee-management-system'); ?></span>
                            </a>
                            <a href="<?php echo esc_url(get_permalink(get_option('ems_attendance_page_id'))); ?>" class="ems-quick-link">
                                <i class="ems-icon ems-icon-calendar"></i>
                                <span><?php _e('View Attendance', 'employee-management-system'); ?></span>
                            </a>
                            <a href="<?php echo esc_url(get_permalink(get_option('ems_salary_page_id'))); ?>" class="ems-quick-link">
                                <i class="ems-icon ems-icon-download"></i>
                                <span><?php _e('Download Payslip', 'employee-management-system'); ?></span>
                            </a>
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="ems-quick-link">
                                <i class="ems-icon ems-icon-logout"></i>
                                <span><?php _e('Logout', 'employee-management-system'); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>