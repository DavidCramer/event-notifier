<?php
/**
 * Main admin UI Template
 */
?>
<div class="notifier-status{{#if general/enable}}-active{{else}}-inactive{{/if}}">
	<label><?php echo esc_html__( 'Hook', 'event-notifier' ); ?></label>
	<div class="def-row" title="{{general/event}}">
		{{general/event}}&nbsp;
	</div>
	<div class="dis-row" title="{{general/description}}">{{general/description}}</div>

	<label><?php echo esc_html__( 'Email', 'event-notifier' ); ?></label>
	<div class="def-row">
		{{#if notice/enable}}
			<strong style="color:#8bc34a;"><span class="dashicons dashicons-yes"></span> <?php echo esc_html__( 'Enabled', 'event-notifier' ); ?>
			{{else}}
			<strong style="color:#b71c1c;"><span class="dashicons dashicons-no"></span> <?php echo esc_html__( 'Disabled', 'event-notifier' ); ?>
			{{/if}}
			</strong>
			<span class="evenote-mute" title="{{notice/email}}">{{notice/email}}</span>
	</div>

	<label><?php echo esc_html__( 'Slack', 'event-notifier' ); ?></label>
	<div class="def-row">
		{{#if slack/enable}}
		<strong style="color:#8bc34a;"><span class="dashicons dashicons-yes"></span> <?php echo esc_html__( 'Enabled', 'event-notifier' ); ?>
			{{else}}
			<strong style="color:#b71c1c;"><span class="dashicons dashicons-no"></span> <?php echo esc_html__( 'Disabled', 'event-notifier' ); ?>
				{{/if}}
			</strong>
			<span class="evenote-mute" title="{{#if slack/channel}}{{slack/channel}}{{else}}{{slack/url}}{{/if}}">{{#if slack/channel}}{{slack/channel}}{{else}}{{slack/url}}{{/if}}</span>
	</div>

	<label><?php echo esc_html__( 'Dashboard', 'event-notifier' ); ?></label>
	<div class="def-row">
		{{#if dashboard/enable}}
		<strong style="color:#8bc34a;"><span class="dashicons dashicons-yes"></span> <?php echo esc_html__( 'Enabled', 'event-notifier' ); ?>
			{{else}}
			<strong style="color:#b71c1c;"><span class="dashicons dashicons-no"></span> <?php echo esc_html__( 'Disabled', 'event-notifier' ); ?>
				{{/if}}
			</strong>
			<span class="evenote-mute" title="<?php echo esc_attr__( 'Dashboard', 'event-notifier' ); ?>">{{#if dashboard/enable}}<?php echo esc_attr__( 'Dashboard Log', 'event-notifier' ); ?>{{/if}}</span>
	</div>

</div>
<span class="evenote-item-remove" data-confirm="<?php echo esc_attr__( 'Are you sure you want to remove this notifier?', 'event-notifier' ); ?>"><?php esc_html_e( 'Delete Notifier', 'event-notifier' ); ?></span>
<button type="button" class="evenote-item-edit page-title-action"><?php esc_html_e( 'Edit Notifier', 'event-notifier' ); ?></button>
