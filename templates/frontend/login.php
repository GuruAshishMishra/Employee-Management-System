<div class="ems-container ems-login-container">
    <div class="ems-card">
        <div class="ems-card-header">
            <h2><?php _e('Employee Login', 'employee-management-system'); ?></h2>
        </div>
        <div class="ems-card-body">
            <?php if (!empty($error)) : ?>
                <div class="ems-alert ems-alert-danger">
                    <?php echo esc_html($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="ems-form">
                <?php wp_nonce_field('ems_login_action', 'ems_login_nonce'); ?>
                
                <div class="ems-form-group">
                    <label for="username"><?php _e('Username or Email', 'employee-management-system'); ?></label>
                    <input type="text" name="username" id="username" required>
                </div>
                
                <div class="ems-form-group">
                    <label for="password"><?php _e('Password', 'employee-management-system'); ?></label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <div class="ems-form-group">
                    <button type="submit" class="ems-btn ems-btn-primary"><?php _e('Login', 'employee-management-system'); ?></button>
                </div>
                
                <div class="ems-form-links">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php _e('Forgot Password?', 'employee-management-system'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>