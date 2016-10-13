<?php
/**
 * Main admin UI Template
 */
?>
<div class="notifier-status{{#if general/enable}}-active{{else}}-inactive{{/if}}">
	<label><?php echo esc_html__( 'Hook', 'event-notifier' ); ?></label>
	<div class="def-row">
		{{general/event}}&nbsp;
	</div>

	<label><?php echo esc_html__( 'Email', 'event-notifier' ); ?></label>
	<div class="def-row">
		{{general/email}}&nbsp;
	</div>

	<label><?php echo esc_html__( 'Subject', 'event-notifier' ); ?></label>
	<div class="def-row">
		{{notice/subject}}&nbsp;
	</div>

	<label><?php echo esc_html__( 'Status', 'event-notifier' ); ?></label>
	<div class="def-row">
		{{#if general/enable}}<strong style="color:#8bc34a;"><?php echo esc_html__( 'Active', 'event-notifier' ); ?></strong>{{else}}<strong style="color:#b71c1c;"><?php echo esc_html__( 'Inactive', 'event-notifier' ); ?></strong>{{/if}}
	</div>
</div>
<span class="evenote-item-remove" data-confirm="<?php echo esc_attr__( 'Are you sure you want to remove this notifier?', 'event-notifier' ); ?>"><?php esc_html_e( 'Delete Notifier', 'event-notifier' ); ?></span>
<button type="button" class="evenote-item-edit page-title-action"><?php esc_html_e( 'Edit Notifier', 'event-notifier' ); ?></button>
