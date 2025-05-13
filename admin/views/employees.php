<div class="wrap ems-admin">
    <h1 class="wp-heading-inline"><?php _e('Employee Management', 'employee-management-system'); ?></h1>
    <a href="#" class="page-title-action add-employee"><?php _e('Add New Employee', 'employee-management-system'); ?></a>
    <a href="#" class="page-title-action import-employees"><?php _e('Import Employees', 'employee-management-system'); ?></a>
    
    <!-- Employee List Table -->
    <div class="ems-table-container">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('ID', 'employee-management-system'); ?></th>
                    <th><?php _e('Profile', 'employee-management-system'); ?></th>
                    <th><?php _e('Name', 'employee-management-system'); ?></th>
                    <th><?php _e('Email', 'employee-management-system'); ?></th>
                    <th><?php _e('Job Title', 'employee-management-system'); ?></th>
                    <th><?php _e('Company', 'employee-management-system'); ?></th>
                    <th><?php _e('Joining Date', 'employee-management-system'); ?></th>
                    <th><?php _e('Salary', 'employee-management-system'); ?></th>
                    <th><?php _e('Actions', 'employee-management-system'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($employees)) : ?>
                    <tr>
                        <td colspan="9"><?php _e('No employees found.', 'employee-management-system'); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($employees as $employee) : ?>
                        <tr>
                            <td><?php echo esc_html($employee->id); ?></td>
                            <td>
                                <?php if (!empty($employee->profile_picture)) : ?>
                                    <img src="<?php echo esc_url($employee->profile_picture); ?>" alt="<?php echo esc_attr($employee->name); ?>" width="50" height="50" />
                                <?php else : ?>
                                    <div class="ems-no-profile"><?php echo esc_html(substr($employee->name, 0, 1)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($employee->name); ?></td>
                            <td><?php echo esc_html($employee->email); ?></td>
                            <td><?php echo esc_html($employee->job_title); ?></td>
                            <td><?php echo esc_html($employee->company_name); ?></td>
                            <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($employee->joining_date))); ?></td>
                            <td>
                                <?php 
                                    $salary_type_labels = array(
                                        'fixed' => __('Fixed', 'employee-management-system'),
                                        'hourly' => __('Hourly', 'employee-management-system'),
                                        'monthly' => __('Monthly', 'employee-management-system')
                                    );
                                    echo esc_html($salary_type_labels[$employee->salary_type] . ': ' . number_format_i18n($employee->salary_amount, 2));
                                ?>
                            </td>
                            <td>
                                <a href="#" class="edit-employee" data-id="<?php echo esc_attr($employee->id); ?>"><?php _e('Edit', 'employee-management-system'); ?></a> | 
                                <a href="#" class="delete-employee" data-id="<?php echo esc_attr($employee->id); ?>"><?php _e('Delete', 'employee-management-system'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Add/Edit Employee Modal -->
    <div id="ems-employee-modal" class="ems-modal">
        <div class="ems-modal-content">
            <span class="ems-close">&times;</span>
            <h2 id="ems-modal-title"><?php _e('Add New Employee', 'employee-management-system'); ?></h2>
            
            <form id="ems-employee-form" method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('ems_employee_action', 'ems_employee_nonce'); ?>
                <input type="hidden" name="ems_action" id="ems_action" value="add">
                <input type="hidden" name="employee_id" id="employee_id" value="">
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="name"><?php _e('Name', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="email"><?php _e('Email', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="email" name="email" id="email" required>
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="profile_picture"><?php _e('Profile Picture', 'employee-management-system'); ?></label>
                        <input type="file" name="profile_picture" id="profile_picture">
                        <div id="profile_preview"></div>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="joining_date"><?php _e('Joining Date', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="date" name="joining_date" id="joining_date" required>
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="job_title"><?php _e('Job Title', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="text" name="job_title" id="job_title" required>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="company_name"><?php _e('Company Name', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="text" name="company_name" id="company_name" required>
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group ems-full-width">
                        <label for="address"><?php _e('Address', 'employee-management-system'); ?></label>
                        <textarea name="address" id="address" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="salary_type"><?php _e('Salary Type', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <select name="salary_type" id="salary_type" required>
                            <option value="fixed"><?php _e('Fixed', 'employee-management-system'); ?></option>
                            <option value="hourly"><?php _e('Hourly', 'employee-management-system'); ?></option>
                            <option value="monthly"><?php _e('Monthly', 'employee-management-system'); ?></option>
                        </select>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="salary_amount"><?php _e('Salary Amount', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="number" name="salary_amount" id="salary_amount" step="0.01" min="0" required>
                    </div>
                </div>
                
                <div class="ems-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Save Employee', 'employee-management-system'); ?></button>
                    <button type="button" class="button ems-cancel"><?php _e('Cancel', 'employee-management-system'); ?></button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Import Employees Modal -->
    <div id="ems-import-modal" class="ems-modal">
        <div class="ems-modal-content">
            <span class="ems-close">&times;</span>
            <h2><?php _e('Import Employees', 'employee-management-system'); ?></h2>
            
            <form id="ems-import-form" method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('ems_employee_action', 'ems_employee_nonce'); ?>
                <input type="hidden" name="ems_action" value="import">
                
                <div class="ems-form-row">
                    <div class="ems-form-group ems-full-width">
                        <label for="csv_file"><?php _e('CSV File', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                    </div>
                </div>
                
                <div class="ems-form-group">
                    <p><?php _e('CSV file should have the following columns:', 'employee-management-system'); ?></p>
                    <code>Name, Email, Profile Picture URL, Joining Date (YYYY-MM-DD), Job Title, Company Name, Address, Salary Type (fixed/hourly/monthly), Salary Amount</code>
                    <p><a href="<?php echo esc_url(EMS_PLUGIN_URL . 'admin/templates/employee_template.csv'); ?>" download><?php _e('Download Sample CSV', 'employee-management-system'); ?></a></p>
                </div>
                
                <div class="ems-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Import', 'employee-management-system'); ?></button>
                    <button type="button" class="button ems-cancel"><?php _e('Cancel', 'employee-management-system'); ?></button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="ems-delete-modal" class="ems-modal">
        <div class="ems-modal-content">
            <span class="ems-close">&times;</span>
            <h2><?php _e('Delete Employee', 'employee-management-system'); ?></h2>
            
            <p><?php _e('Are you sure you want to delete this employee? This action cannot be undone.', 'employee-management-system'); ?></p>
            
            <form id="ems-delete-form" method="post">
                <?php wp_nonce_field('ems_employee_action', 'ems_employee_nonce'); ?>
                <input type="hidden" name="ems_action" value="delete">
                <input type="hidden" name="employee_id" id="delete_employee_id" value="">
                
                <div class="ems-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Delete', 'employee-management-system'); ?></button>
                    <button type="button" class="button ems-cancel"><?php _e('Cancel', 'employee-management-system'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>