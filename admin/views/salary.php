<div class="wrap ems-admin">
    <h1 class="wp-heading-inline"><?php _e('Salary Management', 'employee-management-system'); ?></h1>
    <a href="#" class="page-title-action generate-salary"><?php _e('Generate Salary', 'employee-management-system'); ?></a>
    <a href="#" class="page-title-action export-salary"><?php _e('Export Salary', 'employee-management-system'); ?></a>
    
    <!-- Month/Year Navigation -->
    <div class="ems-month-navigation">
        <a href="<?php echo esc_url(add_query_arg(array(
            'month' => $current_month == 1 ? 12 : $current_month - 1,
            'year' => $current_month == 1 ? $current_year - 1 : $current_year
        ))); ?>" class="button">
            &laquo; <?php _e('Previous Month', 'employee-management-system'); ?>
        </a>
        
        <form method="get" class="ems-month-form">
            <input type="hidden" name="page" value="employee-management-salary">
            
            <select name="month" onchange="this.form.submit()">
                <?php for ($m = 1; $m <= 12; $m++) : ?>
                    <option value="<?php echo esc_attr($m); ?>" <?php selected($current_month, $m); ?>>
                        <?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $m, 1))); ?>
                    </option>
                <?php endfor; ?>
            </select>
            
            <select name="year" onchange="this.form.submit()">
                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) : ?>
                    <option value="<?php echo esc_attr($y); ?>" <?php selected($current_year, $y); ?>>
                        <?php echo esc_html($y); ?>
                    </option>
                <?php endfor; ?>
            </select>
        </form>
        
        <a href="<?php echo esc_url(add_query_arg(array(
            'month' => $current_month == 12 ? 1 : $current_month + 1,
            'year' => $current_month == 12 ? $current_year + 1 : $current_year
        ))); ?>" class="button">
            <?php _e('Next Month', 'employee-management-system'); ?> &raquo;
        </a>
    </div>
    
    <!-- Salary Table -->
    <div class="ems-table-container">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Employee', 'employee-management-system'); ?></th>
                    <th><?php _e('Base Salary', 'employee-management-system'); ?></th>
                    <th><?php _e('Bonus', 'employee-management-system'); ?></th>
                    <th><?php _e('Deduction', 'employee-management-system'); ?></th>
                    <th><?php _e('Total', 'employee-management-system'); ?></th>
                    <th><?php _e('Status', 'employee-management-system'); ?></th>
                    <th><?php _e('Payment Date', 'employee-management-system'); ?></th>
                    <th><?php _e('Actions', 'employee-management-system'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($salary_records)) : ?>
                    <tr>
                        <td colspan="8"><?php _e('No salary records found for this month. Click "Generate Salary" to create them.', 'employee-management-system'); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($salary_records as $salary) : ?>
                        <tr>
                            <td><?php echo esc_html($salary->employee_name); ?></td>
                            <td><?php echo esc_html(number_format_i18n($salary->base_salary, 2)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($salary->bonus, 2)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($salary->deduction, 2)); ?></td>
                            <td><?php echo esc_html(number_format_i18n($salary->total_salary, 2)); ?></td>
                            <td>
                                <span class="ems-status ems-status-<?php echo esc_attr($salary->payment_status); ?>">
                                    <?php echo $salary->payment_status === 'paid' ? __('Paid', 'employee-management-system') : __('Pending', 'employee-management-system'); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($salary->payment_date)) : ?>
                                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($salary->payment_date))); ?>
                                <?php else : ?>
                                    &mdash;
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="#" class="edit-salary" data-id="<?php echo esc_attr($salary->id); ?>"><?php _e('Edit', 'employee-management-system'); ?></a> | 
                                <a href="#" class="view-payslip" data-id="<?php echo esc_attr($salary->id); ?>"><?php _e('Payslip', 'employee-management-system'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Generate Salary Modal -->
    <div id="ems-generate-modal" class="ems-modal">
        <div class="ems-modal-content">
            <span class="ems-close">&times;</span>
            <h2><?php _e('Generate Salary', 'employee-management-system'); ?></h2>
            
            <form id="ems-generate-form" method="post">
                <?php wp_nonce_field('ems_salary_action', 'ems_salary_nonce'); ?>
                <input type="hidden" name="ems_action" value="generate_salary">
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="month"><?php _e('Month', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <select name="month" id="month" required>
                            <?php for ($m = 1; $m <= 12; $m++) : ?>
                                <option value="<?php echo esc_attr($m); ?>" <?php selected($current_month, $m); ?>>
                                    <?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $m, 1))); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="year"><?php _e('Year', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <select name="year" id="year" required>
                            <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) : ?>
                                <option value="<?php echo esc_attr($y); ?>" <?php selected($current_year, $y); ?>>
                                    <?php echo esc_html($y); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group ems-full-width">
                        <label for="employee_id"><?php _e('Employee (Optional)', 'employee-management-system'); ?></label>
                        <select name="employee_id" id="employee_id">
                            <option value=""><?php _e('All Employees', 'employee-management-system'); ?></option>
                            <?php foreach ($employees as $employee) : ?>
                                <option value="<?php echo esc_attr($employee->id); ?>"><?php echo esc_html($employee->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e('Leave empty to generate salary for all employees.', 'employee-management-system'); ?></p>
                    </div>
                </div>
                
                <div class="ems-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Generate', 'employee-management-system'); ?></button>
                    <button type="button" class="button ems-cancel"><?php _e('Cancel', 'employee-management-system'); ?></button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Salary Modal -->
    <div id="ems-edit-salary-modal" class="ems-modal">
        <div class="ems-modal-content">
            <span class="ems-close">&times;</span>
            <h2><?php _e('Edit Salary', 'employee-management-system'); ?></h2>
            
            <form id="ems-edit-salary-form" method="post">
                <?php wp_nonce_field('ems_salary_action', 'ems_salary_nonce'); ?>
                <input type="hidden" name="ems_action" value="update_salary">
                <input type="hidden" name="salary_id" id="salary_id" value="">
                <input type="hidden" name="month" value="<?php echo esc_attr($current_month); ?>">
                <input type="hidden" name="year" value="<?php echo esc_attr($current_year); ?>">
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="employee_name"><?php _e('Employee', 'employee-management-system'); ?></label>
                        <input type="text" id="employee_name" readonly>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="base_salary"><?php _e('Base Salary', 'employee-management-system'); ?></label>
                        <input type="text" id="base_salary" readonly>
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="bonus"><?php _e('Bonus', 'employee-management-system'); ?></label>
                        <input type="number" name="bonus" id="bonus" step="0.01" min="0">
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="deduction"><?php _e('Deduction', 'employee-management-system'); ?></label>
                        <input type="number" name="deduction" id="deduction" step="0.01" min="0">
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="payment_status"><?php _e('Payment Status', 'employee-management-system'); ?></label>
                        <select name="payment_status" id="payment_status">
                            <option value="pending"><?php _e('Pending', 'employee-management-system'); ?></option>
                            <option value="paid"><?php _e('Paid', 'employee-management-system'); ?></option>
                        </select>
                    </div>
                    
                    <div class="ems-form-group payment-date-group">
                        <label for="payment_date"><?php _e('Payment Date', 'employee-management-system'); ?></label>
                        <input type="date" name="payment_date" id="payment_date">
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group ems-full-width">
                        <label for="notes"><?php _e('Notes', 'employee-management-system'); ?></label>
                        <textarea name="notes" id="notes" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="ems-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Update Salary', 'employee-management-system'); ?></button>
                    <button type="button" class="button ems-cancel"><?php _e('Cancel', 'employee-management-system'); ?></button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Export Salary Modal -->
    <div id="ems-export-salary-modal" class="ems-modal">
        <div class="ems-modal-content">
            <span class="ems-close">&times;</span>
            <h2><?php _e('Export Salary', 'employee-management-system'); ?></h2>
            
            <form id="ems-export-salary-form" method="post">
                <?php wp_nonce_field('ems_salary_action', 'ems_salary_nonce'); ?>
                <input type="hidden" name="ems_action" value="export_salary">
                
                <div class="ems-form-row">
                    <div class="ems-form-group">
                        <label for="export_month"><?php _e('Month', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <select name="month" id="export_month" required>
                            <?php for ($m = 1; $m <= 12; $m++) : ?>
                                <option value="<?php echo esc_attr($m); ?>" <?php selected($current_month, $m); ?>>
                                    <?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $m, 1))); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="ems-form-group">
                        <label for="export_year"><?php _e('Year', 'employee-management-system'); ?> <span class="required">*</span></label>
                        <select name="year" id="export_year" required>
                            <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) : ?>
                                <option value="<?php echo esc_attr($y); ?>" <?php selected($current_year, $y); ?>>
                                    <?php echo esc_html($y); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <div class="ems-form-row">
                    <div class="ems-form-group ems-full-width">
                        <label for="export_employee_id"><?php _e('Employee (Optional)', 'employee-management-system'); ?></label>
                        <select name="employee_id" id="export_employee_id">
                            <option value=""><?php _e('All Employees', 'employee-management-system'); ?></option>
                            <?php foreach ($employees as $employee) : ?>
                                <option value="<?php echo esc_attr($employee->id); ?>"><?php echo esc_html($employee->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="ems-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Export', 'employee-management-system'); ?></button>
                    <button type="button" class="button ems-cancel"><?php _e('Cancel', 'employee-management-system'); ?></button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Payslip Modal -->
    <div id="ems-payslip-modal" class="ems-modal">
        <div class="ems-modal-content ems-payslip-content">
            <span class="ems-close">&times;</span>
            <h2><?php _e('Employee Payslip', 'employee-management-system'); ?></h2>
            
            <div id="ems-payslip">
                <!-- Payslip content will be loaded here via AJAX -->
                <div class="ems-loading"><?php _e('Loading...', 'employee-management-system'); ?></div>
            </div>
            
            <div class="ems-form-actions">
                <button type="button" class="button button-primary" id="print-payslip"><?php _e('Print', 'employee-management-system'); ?></button>
                <button type="button" class="button ems-cancel"><?php _e('Close', 'employee-management-system'); ?></button>
            </div>
        </div>
    </div>
</div>