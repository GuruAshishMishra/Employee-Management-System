<div class="ems-container ems-profile-container">
    <div class="ems-page-header">
        <h1><?php _e('My Profile', 'employee-management-system'); ?></h1>
        <div class="ems-user-info">
            <a href="<?php echo esc_url(get_permalink(get_option('ems_dashboard_page_id'))); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                <?php _e('Back to Dashboard', 'employee-management-system'); ?>
            </a>
        </div>
    </div>
    
    <div class="ems-dashboard-nav">
        <ul class="ems-nav">
            <li class="ems-nav-item ">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_dashboard_page_id'))); ?>" class="ems-nav-link">
                    <i class="ems-icon ems-icon-dashboard"></i>
                    <?php _e('Dashboard', 'employee-management-system'); ?>
                </a>
            </li>
            <li class="ems-nav-item active">
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
    
    <div class="ems-profile-content">
        <?php if (!empty($message)) : ?>
            <?php echo $message; ?>
        <?php endif; ?>
        
        <div class="ems-row">
            <div class="ems-col-md-4">
                <div class="ems-card">
                    <div class="ems-card-body ems-text-center">
                        <div class="ems-profile-avatar">
                            <?php if (!empty($employee->profile_picture)) : ?>
                                <img src="<?php echo esc_url($employee->profile_picture); ?>" alt="<?php echo esc_attr($employee->name); ?>">
                            <?php else : ?>
                                <div class="ems-profile-avatar-placeholder">
                                    <?php echo esc_html(substr($employee->name, 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="ems-profile-name"><?php echo esc_html($employee->name); ?></h3>
                        <p class="ems-profile-position"><?php echo esc_html($employee->job_title); ?></p>
                        <p class="ems-profile-company"><?php echo esc_html($employee->company_name); ?></p>
                    </div>
                </div>
                
                <div class="ems-card ems-mt-4">
                    <div class="ems-card-header">
                        <h3><?php _e('Employment Details', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <div class="ems-profile-detail">
                            <span class="ems-profile-detail-label"><?php _e('Employee ID:', 'employee-management-system'); ?></span>
                            <span class="ems-profile-detail-value"><?php echo esc_html($employee->id); ?></span>
                        </div>
                        <div class="ems-profile-detail">
                            <span class="ems-profile-detail-label"><?php _e('Joining Date:', 'employee-management-system'); ?></span>
                            <span class="ems-profile-detail-value"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($employee->joining_date))); ?></span>
                        </div>
                        <div class="ems-profile-detail">
                            <span class="ems-profile-detail-label"><?php _e('Salary Type:', 'employee-management-system'); ?></span>
                            <span class="ems-profile-detail-value">
                                <?php 
                                $salary_types = array(
                                    'fixed' => __('Fixed', 'employee-management-system'),
                                    'hourly' => __('Hourly', 'employee-management-system'),
                                    'monthly' => __('Monthly', 'employee-management-system')
                                );
                                echo esc_html($salary_types[$employee->salary_type]);
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ems-col-md-8">
                <div class="ems-card">
                    <div class="ems-card-header">
                        <h3><?php _e('Edit Profile', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <form method="post" class="ems-form" id="ems-profile-form">
                            <?php wp_nonce_field('ems_profile_action', 'ems_profile_nonce'); ?>
                            
                            <div class="ems-row">
                                <div class="ems-col-md-6">
                                    <div class="ems-form-group">
                                        <label for="first_name"><?php _e('First Name', 'employee-management-system'); ?></label>
                                        <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($user->first_name); ?>" required>
                                    </div>
                                </div>
                                <div class="ems-col-md-6">
                                    <div class="ems-form-group">
                                        <label for="last_name"><?php _e('Last Name', 'employee-management-system'); ?></label>
                                        <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($user->last_name); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ems-form-group">
                                <label for="email"><?php _e('Email', 'employee-management-system'); ?></label>
                                <input type="email" name="email" id="email" value="<?php echo esc_attr($user->user_email); ?>" required>
                            </div>
                            
                            <div class="ems-form-group">
                                <label for="address"><?php _e('Address', 'employee-management-system'); ?></label>
                                <textarea name="address" id="address" rows="3"><?php echo esc_textarea($employee->address); ?></textarea>
                            </div>
                            
                            <div class="ems-form-group">
                                <button type="submit" class="ems-btn ems-btn-primary"><?php _e('Update Profile', 'employee-management-system'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="ems-card ems-mt-4">
                    <div class="ems-card-header">
                        <h3><?php _e('Change Password', 'employee-management-system'); ?></h3>
                    </div>
                    <div class="ems-card-body">
                        <p class="ems-text-muted"><?php _e('To change your password, please use the WordPress password reset functionality.', 'employee-management-system'); ?></p>
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="ems-btn ems-btn-outline">
                            <?php _e('Reset Password', 'employee-management-system'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>