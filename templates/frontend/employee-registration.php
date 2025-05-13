<div class="ems-container ems-registration-container">
    <div class="ems-page-header">
        <h1><?php _e('Employee Registration', 'employee-management-system'); ?></h1>
        <?php if (EMS_Frontend_Admin::current_user_is_admin()) : ?>
            <div class="ems-user-info">
                <a href="<?php echo esc_url(get_permalink(get_option('ems_admin_dashboard_page_id'))); ?>" class="ems-btn ems-btn-sm ems-btn-outline">
                    <?php _e('Back to Dashboard', 'employee-management-system'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (EMS_Frontend_Admin::current_user_is_admin()) : ?>
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
                <li class="ems-nav-item active">
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
    <?php endif; ?>
    
    <div class="ems-registration-content">
        <?php if (!empty($message)) : ?>
            <?php echo $message; ?>
        <?php endif; ?>
        
        <div class="ems-card">
            <div class="ems-card-header">
                <h3><?php _e('Employee Information', 'employee-management-system'); ?></h3>
            </div>
            <div class="ems-card-body">
                <form method="post" class="ems-form" enctype="multipart/form-data" id="ems-registration-form">
                    <?php wp_nonce_field('ems_registration_action', 'ems_registration_nonce'); ?>
                    
                    <div class="ems-row">
                        <div class="ems-col-md-6">
                            <div class="ems-form-group">
                                <label for="name"><?php _e('Full Name', 'employee-management-system'); ?> <span class="ems-required">*</span></label>
                                <input type="text" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="ems-col-md-6">
                            <div class="ems-form-group">
                                <label for="email"><?php _e('Email', 'employee-management-system'); ?> <span class="ems-required">*</span></label>
                                <input type="email" name="email" id="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ems-row">
                        <div class="ems-col-md-6">
                            <div class="ems-form-group">
                                <label for="job_title"><?php _e('Job Title', 'employee-management-system'); ?> <span class="ems-required">*</span></label>
                                <input type="text" name="job_title" id="job_title" required>
                            </div>
                        </div>
                        <div class="ems-col-md-6">
                            <div class="ems-form-group">
                                <label for="company_name"><?php _e('Company Name', 'employee-management-system'); ?> <span class="ems-required">*</span></label>
                                <input type="text" name="company_name" id="company_name" value="<?php echo esc_attr(get_bloginfo('name')); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ems-row">
                        <div class="ems-col-md-6">
                            <div class="ems-form-group">
                                <label for="joining_date"><?php _e('Joining Date', 'employee-management-system'); ?> <span class="ems-required">*</span></label>
                                <input type="date" name="joining_date" id="joining_date" value="<?php echo esc_attr(date('Y-m-d')); ?>" required>
                            </div>
                        </div>
                        <div class="ems-col-md-6">
                            <div class="ems-form-group">
                                <label for="profile_picture"><?php _e('Profile Picture', 'employee-management-system'); ?></label>
                                <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                                <small class="ems-form-text"><?php _e('Upload a profile picture (optional).', 'employee-management-system'); ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="address"><?php _e('Address', 'employee-management-system'); ?></label>
                        <textarea name="address" id="address" rows="3"></textarea>
                    </div>
                    
                    <div class="ems-row">
                        <div class="ems-col-md-6">
                            <div class="ems-form-group">
                                <label for="salary_type"><?php _e('Salary Type', 'employee-management-system'); ?> <span class="ems-required">*</span></label>
                                <select name="salary_type" id="salary_type" required>
                                    <option value="monthly"><?php _e('Monthly', 'employee-management-system'); ?></option>
                                    <option value="hourly"><?php _e('Hourly', 'employee-management-system'); ?></option>
                                    <option value="fixed"><?php _e('Fixed', 'employee-management-system'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="ems-col-md-6">
                            <div class="ems-form-group">
                                <label for="salary_amount"><?php _e('Salary Amount', 'employee-management-system'); ?> <span class="ems-required">*</span></label>
                                <input type="number" name="salary_amount" id="salary_amount" step="0.01" min="0" required>
                                <small class="ems-form-text"><?php _e('Enter the base salary amount.', 'employee-management-system'); ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ems-form-group">
                        <button type="submit" class="ems-btn ems-btn-primary"><?php _e('Register Employee', 'employee-management-system'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // AJAX form submission
    $('#ems-registration-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'ems_register_employee');
        formData.append('nonce', ems_frontend.nonce);
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text(ems_frontend.i18n.loading);
        
        // Send AJAX request
        $.ajax({
            url: ems_frontend.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    var successMessage = $('<div class="ems-alert ems-alert-success"></div>').text(response.data.message);
                    $('#ems-registration-form').before(successMessage);
                    
                    // Reset form
                    $('#ems-registration-form')[0].reset();
                    
                    // Redirect to employee list if admin
                    <?php if (EMS_Frontend_Admin::current_user_is_admin()) : ?>
                        setTimeout(function() {
                            window.location.href = '<?php echo esc_url(get_permalink(get_option('ems_admin_employees_page_id'))); ?>';
                        }, 2000);
                    <?php endif; ?>
                } else {
                    // Show error message
                    var errorMessage = $('<div class="ems-alert ems-alert-danger"></div>').text(response.data);
                    $('#ems-registration-form').before(errorMessage);
                }
            },
            error: function() {
                // Show error message
                var errorMessage = $('<div class="ems-alert ems-alert-danger"></div>').text(ems_frontend.i18n.error);
                $('#ems-registration-form').before(errorMessage);
            },
            complete: function() {
                // Restore button state
                submitBtn.prop('disabled', false).text(originalText);
                
                // Scroll to top of form
                $('html, body').animate({
                    scrollTop: $('#ems-registration-form').offset().top - 100
                }, 500);
            }
        });
    });
});
</script>