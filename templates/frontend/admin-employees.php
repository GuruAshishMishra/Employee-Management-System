<div class="ems-container ems-admin-employees-container">
    <div class="ems-page-header">
        <h1><?php _e('Employees', 'employee-management-system'); ?></h1>
        <div class="ems-user-info">
            <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_dashboard_page_id'))); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                <?php _e('Back to Dashboard', 'employee-management-system'); ?>
            </a>
        </div>
    </div>
    
    <div class="ems-admin-nav">
        <ul class="ems-nav">
            <li class="ems-nav-item">
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
            <li class="ems-nav-item active">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_employees_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-user-plus"></i>
                    <?php _e('All Employees', 'employee-management-system'); ?>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="ems-admin-content">
        <div class="ems-card">
            <div class="ems-card-header">
                <div class="ems-card-header-actions">
                    <h3><?php _e('Employee List', 'employee-management-system'); ?></h3>
                    <a href="<?php echo esc_url(get_permalink(get_option('ems_employee_registration_page_id'))); ?>" class="ems-btn ems-btn-primary">
                        <i class="ems-icon ems-icon-plus"></i> <?php _e('Add Employee', 'employee-management-system'); ?>
                    </a>
                </div>
                <div class="ems-search-box">
                    <input type="text" id="ems-employee-search" placeholder="<?php esc_attr_e('Search employees...', 'employee-management-system'); ?>">
                    <button type="button" class="ems-search-btn">
                        <i class="ems-icon ems-icon-search"></i>
                    </button>
                </div>
            </div>
            <div class="ems-card-body">
                <?php if (empty($employees)) : ?>
                    <div class="ems-alert ems-alert-info">
                        <?php _e('No employees found.', 'employee-management-system'); ?>
                    </div>
                <?php else : ?>
                    <div class="ems-table-responsive">
                        <table class="ems-table" id="ems-employees-table">
                            <thead>
                                <tr>
                                    <th><?php _e('ID', 'employee-management-system'); ?></th>
                                    <th><?php _e('Name', 'employee-management-system'); ?></th>
                                    <th><?php _e('Email', 'employee-management-system'); ?></th>
                                    <th><?php _e('Position', 'employee-management-system'); ?></th>
                                    <th><?php _e('Company', 'employee-management-system'); ?></th>
                                    <th><?php _e('Joined', 'employee-management-system'); ?></th>
                                    <th><?php _e('Salary', 'employee-management-system'); ?></th>
                                    <th><?php _e('Actions', 'employee-management-system'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee) : ?>
                                    <tr>
                                        <td><?php echo esc_html($employee->id); ?></td>
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
                                        <td><?php echo esc_html($employee->email); ?></td>
                                        <td><?php echo esc_html($employee->job_title); ?></td>
                                        <td><?php echo esc_html($employee->company_name); ?></td>
                                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($employee->joining_date))); ?></td>
                                        <td>
                                            <?php 
                                            $settings = get_option('ems_settings', array('currency_symbol' => '$'));
                                            echo esc_html($settings['currency_symbol'] . number_format_i18n($employee->salary_amount, 2)); 
                                            ?>
                                            <span class="ems-text-muted">(<?php echo esc_html(ucfirst($employee->salary_type)); ?>)</span>
                                        </td>
                                        <td>
                                            <div class="ems-action-buttons">
                                                <a href="<?php echo esc_url(admin_url('admin.php?page=employee-management-employees&action=edit&id=' . $employee->id)); ?>" class="ems-btn ems-btn-sm ems-btn-outline ems-btn-icon" title="<?php esc_attr_e('Edit', 'employee-management-system'); ?>">
                                                    <i class="ems-icon ems-icon-edit"></i>
                                                </a>
                                                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_attendance_page_id')) . '?employee_id=' . $employee->id); ?>" class="ems-btn ems-btn-sm ems-btn-outline ems-btn-icon" title="<?php esc_attr_e('Attendance', 'employee-management-system'); ?>">
                                                    <i class="ems-icon ems-icon-calendar"></i>
                                                </a>
                                                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_salary_page_id')) . '?employee_id=' . $employee->id); ?>" class="ems-btn ems-btn-sm ems-btn-outline ems-btn-icon" title="<?php esc_attr_e('Salary', 'employee-management-system'); ?>">
                                                    <i class="ems-icon ems-icon-dollar-sign"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Employee search functionality
    $('#ems-employee-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#ems-employees-table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>