<div class="wrap">

	<h2><?php $rule->exists() ? _e( 'Edit Notification Rule', 'stream-notifications' ) : _e( 'Add New Notification Rule', 'stream-notifications' ); ?>
		<?php if ( $rule->exists() ) : ?>
			<?php
			$new_link = add_query_arg(
				array(
					'page' => WP_Stream_Notifications::NOTIFICATIONS_PAGE_SLUG,
					'view' => 'rule',
				),
				admin_url( WP_Stream_Admin::ADMIN_PARENT_PAGE )
			);
			?>
			<a href="<?php echo esc_url( $new_link ) ?>" class="add-new-h2"><?php _e( 'Add New', 'stream-notifications' ) ?></a>
		<?php endif; ?>
	</h2>

	<form action="" method="post">

		<?php wp_nonce_field( 'stream-notifications-form' ); ?>

		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">

					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="summary" size="30" value="<?php echo esc_attr( $rule->summary ) ?>" id="title" autocomplete="off" keyev="true" placeholder="<?php _e( 'Rule title', 'stream-notifications' ) ?>">
						</div>
					</div><!-- /titlediv -->
				</div><!-- /post-body-content -->

				<div id="postbox-container-1" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div id="submitdiv" class="postbox ">
							<h3 class="hndle">
								<span>
									<?php _e( 'Status', 'stream-notifications' ) ?>
								</span>
							</h3>
							<div class="inside">
								<div class="submitbox" id="submitpost">
									<div id="minor-publishing">
										<div id="misc-publishing-actions">
											<div class="misc-pub-section misc-pub-post-status">
												<label for="post_status">
													<?php _e( 'Active', 'stream-notifications' ) ?>
												</label>
												<span id="post-status-display">
													<input type="checkbox" name="visibility" id="post_status" value="active" <?php checked( $rule->visibility, 'active' ) ?>>
												</span>
											</div>
										</div>
									</div>

									<div id="major-publishing-actions">
										<?php if ( $rule->exists() ) : ?>
										<div id="delete-action">
											<?php
											$delete_link = add_query_arg(
												array(
													'page'            => WP_Stream_Notifications::NOTIFICATIONS_PAGE_SLUG,
													'action'          => 'delete',
													'id'              => absint( $rule->ID ),
													'wp_stream_nonce' => wp_create_nonce( 'delete-record_' . absint( $rule->ID ) ),
												),
												admin_url( WP_Stream_Admin::ADMIN_PARENT_PAGE )
											);
											?>
											<a class="submitdelete deletion" href="<?php echo esc_url( $delete_link ) ?>">
												<?php _e( 'Delete', 'stream-notifications' ) ?>
											</a>
										</div>
										<?php endif ?>

										<div id="publishing-action">
											<span class="spinner"></span>
											<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="<?php _e( 'Save', 'stream-notifications' ) ?>" accesskey="p">
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!-- postbox-container-1 -->

				<div id="postbox-container-2" class="postbox-container">

					<div id="normal-sortables" class="meta-box-sortables ui-sortable">

						<div id="triggers" class="postbox">
							<h3 class="hndle">
								<span><?php _e( 'Triggers', 'stream-notifications' ) ?></span>
							</h3>
							<div class="inside">

								<a class="add-trigger button button-secondary" href="#add-trigger" data-group="0"><?php _e( 'Add Trigger', 'stream-notifications' ) ?></a>
								<a class="add-trigger-group button button-secondary" href="#add-trigger-group" data-group="0"><?php _e( 'Add Group', 'stream-notifications' ) ?></a>

								<div class="group" rel="0">

								</div>

							</div>
						</div>

						<div id="alerts" class="postbox">
							<h3 class="hndle"><span><?php _e( 'Alerts', 'stream-notifications' ) ?></span></h3>
							<div class="inside">
								<a class="add-alert button button-secondary" href="#add-alert"><?php _e( 'Add Alert', 'stream-notifications' ) ?></a>
							</div>
						</div>

					</div>

				</div><!-- postbox-container-2 -->
			</div><!-- /postbody -->
		</div><!-- /poststuff -->
	</form>
</div><!-- /wrap -->

	<?php if ( $rule->triggers ) { ?>
		<script>
			var notification_rule = {
				triggers : <?php echo json_encode( $rule->triggers ) ?>,
				groups   : <?php echo json_encode( $rule->groups ) ?>,
				alerts   : <?php echo json_encode( $rule->alerts ) ?>,
			}
		</script>
	<?php } ?>

<script type="text/template" id="trigger-template-row">
<div class="trigger" rel="<%- vars.index %>">
	<div class="form-row">
		<input type="hidden" name="triggers[<%- vars.index %>][group]" value="<%- vars.group %>" />
		<div class="field relation">
			<select name="triggers[<%- vars.index %>][relation]" class="trigger-relation">
				<option value="and"><?php _e( 'AND', 'stream-notifications' ) ?></option>
				<option value="or"><?php _e( 'OR', 'stream-notifications' ) ?></option>
			</select>
		</div>
		<div class="field type">
			<select name="triggers[<%- vars.index %>][type]" class="trigger-type" rel="<%- vars.index %>" placeholder="Choose Rule">
				<option></option>
				<% _.each( vars.types, function( type, name ){ %>
				<option value="<%- name %>"><%- type.title %></option>
				<% }); %>
			</select>
		</div>
		<a href="#" class="delete-trigger">Remove</a>
	</div>
</div>
</script>

<script type="text/template" id="trigger-template-group">
<div class="group" rel="<%- vars.index %>">
	<div class="group-meta">
		<input type="hidden" name="groups[<%- vars.index %>][group]" value="<%- vars.parent %>" />
		<div class="field relation">
			<select name="groups[<%- vars.index %>][relation]" class="group-relation">
				<option value="and"><?php _e( 'AND', 'stream-notifications' ) ?></option>
				<option value="or"><?php _e( 'OR', 'stream-notifications' ) ?></option>
			</select>
		</div>
		<a href="#add-trigger" class="add-trigger button button-secondary" data-group="<%- vars.index %>">Add Trigger</a>
		<a href="#add-trigger-group" class="add-trigger-group button button-secondary" data-group="<%- vars.index %>">Add Group</a>
		<a href="#" class="delete-group">Remove</a>
	</div>
</div>
</script>

<script type="text/template" id="trigger-template-options">
<div class="trigger-options">
	<div class="field operator">
		<select name="triggers[<%- vars.index %>][operator]" class="trigger-operator">
			<% _.each( vars.operators, function( list, name ){ %>
			<option value="<%- name %>"><%- list %></option>
			<% }); %>
		</select>
	</div>
	<div class="field value">
		<% if ( ['select', 'ajax'].indexOf( vars.type ) != -1 ){ %>
		<select name="triggers[<%- vars.index %>][value]" class="trigger-value" data-ajax="<% ( vars.ajax ) %>" <% if ( vars.multiple ){ %>multiple="multiple"<% } %>>
			<option></option>
			<% if ( vars.options ) { %>
				<% _.each( vars.options, function( list, name ){ %>
				<option value="<%- name %>"><%- list %></option>
				<% }); %>
			<% } %>
		</select>
		<% } else { %>
		<input type="text" name="triggers[<%- vars.index %>][value]" class="trigger-value <% if ( vars.tags ){ %>tags<% } %> <% if ( vars.ajax ){ %>ajax<% } %>">
		<% } // endif%>
	</div>
</div>
</script>

<script type="text/template" id="alert-template-row">
<div class="alert" rel="<%- vars.index %>">
	<div class="form-row">
		<div class="field type">
			<select name="alerts[<%- vars.index %>][type]" class="alert-type" rel="<%- vars.index %>" placeholder="Choose Type">
				<option></option>
				<% _.each( vars.adapters, function( type, name ){ %>
				<option value="<%- name %>"><%- type.title %></option>
				<% }); %>
			</select>
		</div>
		<a href="#" class="delete-alert">Remove</a>
	</div>
</div>
</script>

<script type="text/template" id="alert-template-options">
<table class="alert-options form-table">
	<% for ( field_name in vars.fields ) { var field = vars.fields[field_name]; %>
		<tr>
			<th>
				<label><%- field.title %></label>
			</th>
			<td>
				<div class="field value">
					<% if ( ['select'].indexOf( field.type ) != -1 ){ %>
					<select name="alerts[<%- vars.index %>][<%- field_name %>]" class="alert-value widefat" data-ajax="<% ( field.ajax ) %>" <% if ( field.multiple ){ %>multiple="multiple"<% } %>>
						<option></option>
						<% if ( vars.fields[field] ) { %>
							<% _.each( vars.fields[field], function( list, name ){ %>
							<option value="<%- name %>"><%- list %></option>
							<% }); %>
						<% } %>
					</select>
					<% } else if ( ['textarea'].indexOf( field.type ) != -1 ) { %>
						<textarea name="alerts[<%- vars.index %>][<%- field_name %>]" class="alert-value large-text code" rows="10" cols="80"></textarea>
					<% } else { %>
					<input type="text" name="alerts[<%- vars.index %>][<%- field_name %>]" class="alert-value widefat <% if ( field.tags ){ %>tags<% } %> <% if ( field.ajax ){ %>ajax<% } %>" <% if ( field.ajax && field.key ){ %>data-ajax-key="<%- field.key %>"<% } %> >
					<% } %>
					<% if ( field.hint ) { %>
						<p class="description"><%- field.hint %></p>
					<% } %>
				</div>
			</td>
		</div>
	<% } %>
</div>
</script>

<style>
	.field, .trigger-type, .trigger-options, .trigger-value { float: left; }
	.form-row {
		clear: both;
		overflow: hidden;
		margin-bottom: 10px;
		background: #eee;
		padding: 10px;
	}
	#triggers .inside,
	#alerts .inside {
		margin-top: 12px;
	}
	.inside > .group,
	.inside > .alert {
		margin: 10px 0 0;
		background: none;
		padding: 0;

		-webkit-box-shadow: none;
			    box-shadow: none;
	}
	.group,
	.alert {
		background: rgba(0, 0, 0, 0.08);
		padding: 20px 20px 12px;
		margin-bottom: 10px;
		min-height: 16px;
		clear: both;
	}
	.group .form-row,
	.alert .form-row {
		background: rgba(0, 0, 0, 0.03);
	}
	.group,
	.group .form-row,
	.alert,
	.alert .form-row {
		margin-left: 90px;

		-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
			    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
	}
	.group .form-row .delete-trigger,
	.alert .form-row .delete-alert {
		line-height: 29px;
	}
	.inside > .group,
	.inside > .group > .group,
	.inside > .alert {
		margin-left: 0;
	}
	.inside > .group > .trigger > .form-row,
	.inside > .alert > .form-row {
		margin-left: 0;
	}
	.alert-options th {
		width: auto;
	}
	.group-meta {
		float: left;
		margin-top: -10px;
		margin-left: -10px;
		padding: 0 0 12px;
	}
	.group-meta a {
		font-size: 10px;
		padding-left: 5px;
	}
	.group-meta a.delete-group {
		line-height: 28px;
	}
	.group .trigger:first-of-type .field.relation,
	.trigger.first .field.relation {
		display: none;
	}
	.trigger.first .field.type {
		margin-left: 99px;
	}
	.delete-trigger,
	.delete-alert {
		float: right;
	}
	.select2-container {
		margin-right: 6px;
	}
	.select2-results li {
		margin-bottom: 2px;
	}
	.select2-container .select2-choice > .select2-chosen {
		font-size: 13px;
	}
	.select2-container.trigger-type {
		width: 180px !important;
	}
	.select2-container.trigger-operator {
		width: 140px !important;
	}
	.select2-choices img.avatar,
	.select2-results img.avatar {
		vertical-align: middle;
		padding-right: 5px;
	}
</style>
