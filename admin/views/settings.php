<div class="wrap ems-admin">
    <h1 class="wp-heading-inline"><?php _e('Employee Management Settings', 'employee-management-system'); ?></h1>
    
    <form method="post" id="ems-settings-form">
        <?php wp_nonce_field('ems_settings_action', 'ems_settings_nonce'); ?>
        <input type="hidden" name="ems_action" value="save_settings">
        
        <div class="ems-settings-section">
            <h2><?php _e('Company Information', 'employee-management-system'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="company_name"><?php _e('Company Name', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="company_name" id="company_name" value="<?php echo esc_attr($settings['company_name']); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="company_address"><?php _e('Company Address', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <textarea name="company_address" id="company_address" rows="3" class="large-text"><?php echo esc_textarea($settings['company_address']); ?></textarea>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="company_email"><?php _e('Company Email', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <input type="email" name="company_email" id="company_email" value="<?php echo esc_attr($settings['company_email']); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="company_phone"><?php _e('Company Phone', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="company_phone" id="company_phone" value="<?php echo esc_attr($settings['company_phone']); ?>" class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="company_logo"><?php _e('Company Logo URL', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="company_logo" id="company_logo" value="<?php echo esc_attr($settings['company_logo']); ?>" class="regular-text">
                        <button type="button" class="button" id="upload_logo_button"><?php _e('Upload Logo', 'employee-management-system'); ?></button>
                        <p class="description"><?php _e('Enter a URL or upload an image for the company logo.', 'employee-management-system'); ?></p>
                        <?php if (!empty($settings['company_logo'])) : ?>
                            <div id="logo_preview">
                                <img src="<?php echo esc_url($settings['company_logo']); ?>" alt="<?php echo esc_attr($settings['company_name']); ?>" style="max-width: 200px; margin-top: 10px;">
                            </div>
                        <?php else : ?>
                            <div id="logo_preview"></div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="ems-settings-section">
            <h2><?php _e('Work Settings', 'employee-management-system'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label><?php _e('Working Days', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><?php _e('Working Days', 'employee-management-system'); ?></legend>
                            <label>
                                <input type="checkbox" name="working_days[]" value="1" <?php checked(in_array('1', $settings['working_days'])); ?>>
                                <?php _e('Monday', 'employee-management-system'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="working_days[]" value="2" <?php checked(in_array('2', $settings['working_days'])); ?>>
                                <?php _e('Tuesday', 'employee-management-system'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="working_days[]" value="3" <?php checked(in_array('3', $settings['working_days'])); ?>>
                                <?php _e('Wednesday', 'employee-management-system'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="working_days[]" value="4" <?php checked(in_array('4', $settings['working_days'])); ?>>
                                <?php _e('Thursday', 'employee-management-system'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="working_days[]" value="5" <?php checked(in_array('5', $settings['working_days'])); ?>>
                                <?php _e('Friday', 'employee-management-system'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="working_days[]" value="6" <?php checked(in_array('6', $settings['working_days'])); ?>>
                                <?php _e('Saturday', 'employee-management-system'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="working_days[]" value="7" <?php checked(in_array('7', $settings['working_days'])); ?>>
                                <?php _e('Sunday', 'employee-management-system'); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="working_hours"><?php _e('Working Hours Per Day', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <input type="number" name="working_hours" id="working_hours" value="<?php echo esc_attr($settings['working_hours']); ?>" min="1" max="24" step="0.5" class="small-text">
                        <p class="description"><?php _e('Used for calculating hourly wages.', 'employee-management-system'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="ems-settings-section">
            <h2><?php _e('Display Settings', 'employee-management-system'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="currency_symbol"><?php _e('Currency Symbol', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="currency_symbol" id="currency_symbol" value="<?php echo esc_attr($settings['currency_symbol']); ?>" class="small-text">
                        <p class="description"><?php _e('Symbol to display before salary amounts.', 'employee-management-system'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="date_format"><?php _e('Date Format', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <select name="date_format" id="date_format">
                            <option value="Y-m-d" <?php selected($settings['date_format'], 'Y-m-d'); ?>><?php echo date('Y-m-d'); ?> (YYYY-MM-DD)</option>
                            <option value="m/d/Y" <?php selected($settings['date_format'], 'm/d/Y'); ?>><?php echo date('m/d/Y'); ?> (MM/DD/YYYY)</option>
                            <option value="d/m/Y" <?php selected($settings['date_format'], 'd/m/Y'); ?>><?php echo date('d/m/Y'); ?> (DD/MM/YYYY)</option>
                            <option value="d.m.Y" <?php selected($settings['date_format'], 'd.m.Y'); ?>><?php echo date('d.m.Y'); ?> (DD.MM.YYYY)</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="ems-settings-section">
            <h2><?php _e('Notification Settings', 'employee-management-system'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="enable_email_notifications"><?php _e('Email Notifications', 'employee-management-system'); ?></label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" name="enable_email_notifications" id="enable_email_notifications" value="1" <?php checked($settings['enable_email_notifications'], '1'); ?>>
                            <?php _e('Enable email notifications', 'employee-management-system'); ?>
                        </label>
                        <p class="description"><?php _e('Send email notifications for salary payments, etc.', 'employee-management-system'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <p class="submit">
            <button type="submit" class="button button-primary"><?php _e('Save Settings', 'employee-management-system'); ?></button>
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Media uploader for company logo
    $('#upload_logo_button').on('click', function(e) {
        e.preventDefault();
        
        var image_frame;
        
        if (image_frame) {
            image_frame.open();
            return;
        }
        
        image_frame = wp.media({
            title: '<?php _e('Select or Upload Company Logo', 'employee-management-system'); ?>',
            button: {
                text: '<?php _e('Use this image', 'employee-management-system'); ?>'
            },
            multiple: false
        });
        
        image_frame.on('select', function() {
            var attachment = image_frame.state().get('selection').first().toJSON();
            $('#company_logo').val(attachment.url);
            $('#logo_preview').html('<img src="' + attachment.url + '" alt="" style="max-width: 200px; margin-top: 10px;">');
        });
        
        image_frame.open();
    });
});
</script>