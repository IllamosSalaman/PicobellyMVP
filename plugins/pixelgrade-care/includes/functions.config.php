<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function get_pixelgrade_care_default_config( $original_theme_slug ){
	// General strings ready to be translated
	$return['l10n'] = array(
		'myAccountBtn'                  => esc_html__( 'My Account', 'pixelgrade_care' ),
		'needHelpBtn'                   => esc_html__( 'Need Help?', 'pixelgrade_care' ),
		'returnToDashboard'             => esc_html__( 'Continue to Your WordPress Dashboard', 'pixelgrade_care' ),
		'nextButton'                    => esc_html__( 'Continue', 'pixelgrade_care' ),
		'skipButton'                    => esc_html__( 'Skip this step', 'pixelgrade_care' ),
		'notRightNow'                   => esc_html__( 'Not right now', 'pixelgrade_care' ),
		'validationErrorTitle'          => esc_html__( 'Something went wrong', 'pixelgrade_care' ),
		'themeValidationNoticeFail'     => esc_html__( 'Not Activated.', 'pixelgrade_care' ),
		'themeValidationNoticeOk'       => esc_html__( 'Verified & Up-to-date!', 'pixelgrade_care' ),
		'themeValidationNoticeOutdated' => esc_html__( 'Your theme is outdated!', 'pixelgrade_care' ),
		'themeValidationNoticeExpired'  => esc_html__( 'Expired License.', 'pixelgrade_care' ),
		'themeUpdateAvailableTitle'     => esc_html__( 'New Theme Update is Available!', 'pixelgrade_care' ),
		'themeUpdateAvailableContent'   => esc_html__( 'Great news! A brand new theme update is waiting.', 'pixelgrade_care' ),
		'hashidNotFoundNotice'          => esc_html__( 'Sorry but we could not recognize your theme. This might have happened because you have made changes to the functions.php file. If that is the case - please try to revert to the original contents of that file and retry to validate your theme license.', 'pixelgrade_care' ),
		'themeUpdateButton'             => esc_html__( 'Update', 'pixelgrade_care' ),
		'kbButton'                      => esc_html__( 'Theme Help', 'pixelgrade_care' ),
		'Error500Text'                  => esc_html__( 'Oh, snap! Something went wrong but we are unable to make sense of the actual problem.', 'pixelgrade_care' ),
		'Error400Text'                  => esc_html__( 'There is something wrong with the current setup of this WordPress installation.', 'pixelgrade_care' ),
		'Error500Link'                  => trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'docs/guides-and-resources/server-errors-handling',
		'Error400Link'                  => trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'docs/guides-and-resources/server-errors-handling',
		'missingWupdatesCode'           => __( 'It seems that the theme you are using is not a self-hosted, premium theme of ours. Maybe it\'s a free version or a WordPress.com theme?' , 'pixelgrade_care' ),
		'tamperedWupdatesCode'          => __( 'This will give you all kinds of trouble when installing updates for the theme. To be able to successfully install updates please use the original theme files. Use a child theme if you wish to modify things.', 'pixelgrade_care' ),
		'themeDirectoryChanged'         => __( 'This will give you all kinds of trouble when installing updates for the theme. To be able to successfully install updates please change the theme directory name to "' . $original_theme_slug . '".', 'pixelgrade_care' ),
		'themeNameChanged'              => __( 'The next time you update your theme this name will be changed back to "' . $original_theme_slug . '"', 'pixelgrade_care' ),
		'childThemeNameChanged'         => __( 'On your next update, your parent theme name will be changed back to its original one: "' . $original_theme_slug . '". To avoid issues with your child theme, you will need to update the style.css file of both your parent and child theme with the original name: "' . $original_theme_slug . '".', 'pixelgrade_care' ),
		'forceDisconnected'             => __( 'Unfortunately, we\'ve lost your connection with pixelgrade.com. Just reconnect and all will be back to normal.', 'pixelgrade_care' ),
	);

	$return['setupWizard'] = array(

		'activation' => array(
			'stepName' => 'Connect',
			'blocks'   => array(
				'authenticator' => array(
					'class'  => 'full white',
					'fields' => array(
						'authenticator_component' => array(
							'title' => 'Activate {{theme_name}}!',
							'type'  => 'component',
							'value' => 'authenticator',
						),
					),
				),
			),
		),

		'theme' => array(
			'stepName' => 'Theme',
			'blocks'   => array(
				'plugins' => array(
					'class'  => 'full white',
					'fields' => array(
						'theme-selector' => array(
							'title' => 'Choose a Theme',
							'type'  => 'component',
							'value' => 'theme-selector',
						),
					),
				),
			),
		),

		'plugins' => array(
			'stepName' => 'Plugins',
			'blocks'   => array(
				'plugins' => array(
					'class'  => 'full white',
					'fields' => array(
						'title'             => array(
							'type'  => 'h2',
							'value' => 'Install Plugins',
							'value_installing' => 'Installing Plugins..',
							'value_installed' => '<span class="c-icon  c-icon--large  c-icon--success-auth"></span> Plugins Installed!',
							'class' => 'section__title'
						),
						'head_content'   => array(
							'type'             => 'text',
							'value'            => 'Install and activate the plugins that provide the needed functionality for your site. You can add or remove plugins later on from within WordPress.',
							'value_installing' => 'Why not take a peek at our <a href="https://twitter.com/pixelgrade" target="_blank">Twitter page</a> while you wait? (opens in a new tab and the plugins aren\'t going anywhere)',
							'value_installed'  => 'You made it! 🙌 You’ve correctly installed and activated the plugins. You are good to jump to the next step.',
						),
						'plugins_component' => array(
							'title' => 'Install Plugins',
							'type'  => 'component',
							'value' => 'plugin-manager',
						),
					),
				),
			),
		),

		'support'   =>  array(
			'stepName'  =>  'Starter Content',
			'nextText'  =>  'Next Step',
			'blocks'    =>  array(
				'support'   =>  array(
					'class'  => 'full white',
					'fields' => array(
						'title'          => array(
							'type'  => 'h2',
							'value' => 'Starter Content',
							'value_installing' => 'Installing Starter Content..',
							'value_installed' => '<span class="c-icon  c-icon--large  c-icon--success-auth"></span> Starter Content Installed!',
							'class' => 'section__title',
						),
						'head_content'   => array(
							'type'             => 'text',
							'value'            => 'Use the starter content to make your site look as eye-candy as the theme’s demo. The importer helps you have a strong starting point for your content and speed up the entire process.',
							'value_installing' => 'Why not join our <a href="https://www.facebook.com/groups/PixelGradeUsersGroup/" target="_blank">Facebook Group</a> while you wait? (opens in a new tab)',
							'value_installed'  => 'Mission accomplished! 👍 You\'ve successfully imported the starter content, so you’re good to move forward. Have fun!',
						),
						'starterContent' => array(
							'type'     => 'component',
							'value'    => 'starter-content',
							'inactive' => 'notice',
						),
						'content'        => '',
						'links'          => '',
						'footer_content' => '',
					),
				),
			),
		),

		'ready' => array(
			'stepName' => 'Ready',
			'blocks'   => array(
				'ready' => array(
					'class'  => 'full white',
					'fields' => array(
						'title'   => array(
							'type'  => 'h2',
							'value' => 'Your Theme is Ready!',
							'class' => 'section__title'
						),
						'content' => array(
							'type'  => 'text',
							'value' => 'Big congrats, mate! 👏 Your theme has been activated, and your website is ready to get some traction. Login to your WordPress dashboard to make changes, and feel free to change the default content to match your needs.'
						),
					),
				),

				'redirect_area' => array(
					'class'  => 'half',
					'fields' => array(
						'title' => array(
							'type'  => 'h4',
							'value' => 'Next Steps'
						),
						'cta'   => array(
							'type'  => 'button',
							'class' => 'btn--large',
							'label' => 'Customize your site!',
							'url'   => '{{customizer_url}}?return=' . urlencode( admin_url( 'admin.php?page=pixelgrade_care' ) )
						),
					),
				),

				'help_links' => array(
					'class'  => 'half',
					'fields' => array(
						'title' => array(
							'type'  => 'h4',
							'value' => 'Learn More'
						),
						'links' => array(
							'type'  => 'links',
							'value' => array(
								array(
									'label' => 'Browse the Theme Documentation',
									'url'   => trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'docs/'
								),
								array(
									'label' => 'Learn How to Use WordPress',
									'url'   => 'https://easywpguide.com'
								),
								array(
									'label' => 'Get Help and Support',
									'url'   => trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'get-support/'
								),
								array(
									'label' => 'Join our Facebook group',
									'url'   => 'https://www.facebook.com/groups/PixelGradeUsersGroup/'
								),
							),
						),
					),
				),
			),
		),
	);

	$return['dashboard'] = array(
		'general' => array(
			'name'   => 'General',
			'blocks' => array(
				'authenticator' => array(
					'class'  => 'full white',
					'fields' => array(
						'authenticator' => array(
							'type'  => 'component',
							'value' => 'authenticator'
						),
					),
				),
                'starterContent' => array(
                    'inactive' =>  'hidden',
                    'fields' => array(
	                    'title'          => array(
		                    'type'  => 'h2',
		                    'value' => 'Starter Content',
		                    'value_installing' => 'Starter Content Installing..',
		                    'value_installed' => '<span class="c-icon  c-icon--large  c-icon--success-auth"></span> Starter Content Installed!',
		                    'class' => 'section__title',
	                    ),
	                    'head_content'   => array(
		                    'type'             => 'text',
		                    'value'            => 'Use the starter content to make your site look as eye-candy as the theme’s demo. The importer helps you have a strong starting point for your content and speed up the entire process.',
		                    'value_installing' => 'Why not join our <a href="https://www.facebook.com/groups/PixelGradeUsersGroup/" target="_blank">Facebook Group</a> while you wait? (opens in a new tab)',
		                    'value_installed'  => 'Mission accomplished! 👍 You\'ve successfully imported the starter content, so you’re good to move forward. Have fun!',
	                    ),
	                    'starterContent' => array(
		                    'type'     => 'component',
		                    'value'    => 'starter-content',
	                    ),
                    ),
                ),
			),
		),

		'customizations' => array(
			'name'   => 'Customizations',
			'class'  => 'sections-grid__item',
			'blocks' => array(
				'featured'  => array(
					'class'  => 'u-text-center',
					'fields' => array(
						'title'   => array(
							'type'  => 'h2',
							'value' => 'Customizations',
							'class' => 'section__title'
						),
						'content' => array(
							'type'  => 'text',
							'value' => 'We know that each website needs to have an unique voice in tune with your charisma. That\'s why we created a smart options system to easily make handy color changes, spacing adjustments and balancing fonts, each step bringing you closer to a striking result.',
							'class' => 'section__content'
						),
						'cta'     => array(
							'type'  => 'button',
							'class' => 'btn--action  btn--green',
							'label' => 'Access the Customizer',
							'url'   => '{{customizer_url}}',
							'target' => '', // we don't want the default _blank target
						),
					),
				),
				'subheader' => array(
					'class'  => 'section--airy  u-text-center',
					'fields' => array(
						'subtitle' => array(
							'type'  => 'h3',
							'value' => 'Learn more',
							'class' => 'section__subtitle'
						),
						'title'    => array(
							'type'  => 'h2',
							'value' => 'Design & Style',
							'class' => 'section__title'
						),
					),
				),
				'colors'    => array(
					'class'  => 'half sections-grid__item',
					'fields' => array(
						'title'   => array(
							'type'  => 'h4',
							'value' => '<img class="emoji" alt="🎨" src="https://s.w.org/images/core/emoji/2.2.1/svg/1f3a8.svg"> Tweaking Colors Schemes',
							'class' => 'section__title'
						),
						'content' => array(
							'type'  => 'text',
							'value' => 'Choose colors that resonate with the statement you want to portray. For example, blue inspires safety and peace, while yellow is translated into energy and joyfulness.'
						),
						'cta'     => array(
							'type'  => 'button',
							'label' => 'Changing Colors',
							'class' => 'btn--action btn--small  btn--blue',
							'url'   => trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'docs/design-and-style/style-changes/changing-colors/'
						),
					),
				),

				'fonts' => array(
					'class'  => 'half sections-grid__item',
					'fields' => array(
						'title'   => array(
							'type'  => 'h4',
							'value' => '<img class="emoji" alt="🎨" src="https://s.w.org/images/core/emoji/2.2.1/svg/1f3a8.svg"> Managing Fonts',
							'class' => 'section__title'
						),
						'content' => array(
							'type'  => 'text',
							'value' => 'We recommend you settle on only a few fonts: it\'s best to stick with two fonts but if you\'re feeling ambitious, three is tops.'
						),
						'cta'     => array(
							'type'  => 'button',
							'label' => 'Changing Fonts',
							'class' => 'btn--action btn--small  btn--blue',
							'url'   => trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'docs/design-and-style/style-changes/changing-fonts/'
						),
					),
				),

				'custom_css' => array(
					'class'  => 'half sections-grid__item',
					'fields' => array(
						'title'   => array(
							'type'  => 'h4',
							'value' => '<img class="emoji" alt="🎨" src="https://s.w.org/images/core/emoji/2.2.1/svg/1f3a8.svg"> Custom CSS',
							'class' => 'section__title'
						),
						'content' => array(
							'type'  => 'text',
							'value' => 'If you\'re looking for changes that are not possible through the current set of options, swing some Custom CSS code to override the default CSS of your theme.'
						),
						'cta'     => array(
							'type'  => 'button',
							'label' => 'Using the Custom CSS Editor',
							'class' => 'btn--action btn--small  btn--blue',
							'url'   => trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'docs/design-and-style/custom-code/using-custom-css-editor'
						),
					),
				),

				'advanced' => array(
					'class'  => 'half sections-grid__item',
					'fields' => array(
						'title'   => array(
							'type'  => 'h4',
							'value' => '<img class="emoji" alt="🎨" src="https://s.w.org/images/core/emoji/2.2.1/svg/1f3a8.svg"> Advanced Customizations',
							'class' => 'section__title'
						),
						'content' => array(
							'type'  => 'text',
							'value' => 'If you want to change HTML or PHP code, and keep your changes from being overwritten on the next theme update, the best way is to make them in a child theme.'
						),
						'cta'     => array(
							'type'  => 'button',
							'label' => 'Using a Child Theme',
							'class' => 'btn--action btn--small  btn--blue',
							'url'   => trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'docs/getting-started/using-child-theme'
						),
					),
				),
			),
		),

		'system-status' => array(
			'name'   => 'System Status',
			'blocks' => array(
				'system-status' => array(
					'class'  => 'u-text-center',
					'fields' => array(
						'title'        => array(
							'type'  => 'h2',
							'class' => 'section__title',
							'value' => 'System Status'
						),
						'systemStatus' => array(
							'type'  => 'component',
							'value' => 'system-status'
						),
						'tools'        => array(
							'type'  => 'component',
							'value' => 'pixcare-tools'
						),
					),
				),
			),
		),
	);

	$return['systemStatus'] = array(
		'phpRecommendedVersion'     => 5.6,
		'title'                     => 'System Status',
		'description'               => 'Allow Pixelgrade to collect non-sensitive diagnostic data and usage information. This will allow us to provide better assistance when you reach us through our support system. Thanks!',
		'phpOutdatedNotice'         => 'This version is a little old. We recommend you update to PHP ',
		'wordpress_outdated_notice' => 'We recommend you update to the latest and greatest WordPress version.',
	);

	$return['pluginManager'] = array(
		'updateButton' => esc_html__( 'Update', 'pixelgrade_care' )
	);

	$return['knowledgeBase'] = array(
		'selfHelp'   => array(
			'name'   => 'Self Help',
			'blocks' => array(
				'search' => array(
					'class'  => 'support-autocomplete-search',
					'fields' => array(
						'placeholder' => 'Search through the Knowledge Base'
					),
				),
				'info'   => array(
					'class'  => '',
					'fields' => array(
						'title'     => array(
							'type'  => 'h1',
							'value' => 'Theme Help & Support',
						),
						'content'   => array(
							'type'  => 'text',
							'value' => 'You have an <strong>active theme license</strong> for {{theme_name}}. This means you\'re able to get <strong>front-of-the-line support service.</strong> Check out our documentation in order to get quick answers in no time. Chances are it\'s <strong>already been answered!</strong>'
						),
						'subheader' => array(
							'type'  => 'h2',
							'value' => 'How can we help?'
						),
					),
				),
			),
		),
		'openTicket' => array(
			'name'   => 'Open Ticket',
			'blocks' => array(
				'topics'        => array(
					'class'  => '',
					'fields' => array(
						'title'  => array(
							'type'  => 'h1',
							'value' => 'What can we help with?'
						),
						'topics' => array(
							'class'  => 'topics-list',
							'fields' => array(
								'start'          => array(
									'type'  => 'text',
									'value' => 'I have a question about how to start'
								),
								'feature'        => array(
									'type'  => 'text',
									'value' => 'I have a question about how a distinct feature works'
								),
								'plugins'        => array(
									'type'  => 'text',
									'value' => 'I have a question about plugins'
								),
								'productUpdates' => array(
									'type'  => 'text',
									'value' => 'I have a question about product updates'
								),
								'billing'        => array(
									'type'  => 'text',
									'value' => 'I have a question about payments'
								),
							),
						),
					),
				),
				'ticket'        => array(
					'class'  => '',
					'fields' => array(
						'title'             => array(
							'type'  => 'h1',
							'value' => 'Give us more details'
						),
						'changeTopic'       => array(
							'type'  => 'button',
							'label' => 'Change Topic',
							'class' => 'btn__dark',
							'url'   => '#'
						),
						'descriptionHeader' => array(
							'type'  => 'text',
							'value' => 'How can we help?'
						),
						'descriptionInfo'   => array(
							'type'  => 'text',
							'class' => 'label__more-info',
							'value' => 'Briefly describe how we can help.'
						),
						'detailsHeader'     => array(
							'type'  => 'text',
							'value' => 'Tell Us More'
						),
						'detailsInfo'       => array(
							'type'  => 'text',
							'class' => 'label__more-info',
							'value' => 'Share all the details. Be specific and include some steps to recreate things and help us get to the bottom of things more quickly! Use a free service like <a href="http://imgur.com/" target="_blank">Imgur</a> or <a href="http://tinypic.com/" target="_blank">Tinypic</a> to upload files and include the link.'
						),
						'nextButton'        => array(
							'type'  => 'button',
							'label' => 'Next Step',
							'class' => 'form-row submit-wrapper',
						),
					),
				),
				'searchResults' => array(
					'class'  => '',
					'fields' => array(
						'title'       => array(
							'type'  => 'h1',
							'value' => 'Try these solutions first'
						),
						'description' => array(
							'type'  => 'text',
							'value' => 'Based on the details you provided, we found a set of articles that could help you instantly. Before you submit a ticket, please check these resources first:'
						),
						'noResults'   => array(
							'type'  => 'text',
							'value' => 'Sorry, we couldn\'t find any articles suitable for your question. Submit your ticket using the button below.'
						),
					),
				),
				'sticky'        => array(
					'class'  => 'notification__blue clear sticky',
					'fields' => array(
						'noLicense'       => array(
							'type'  => 'text',
							'value' => 'Please activate your theme in order to be able to submit tickets.'
						),
						'initialQuestion' => array(
							'type'  => 'text',
							'value' => 'Did any of the above resources answer your question?'
						),
						'success'         => array(
							'type'  => 'text',
							'value' => '😊 Yaaay! You did it by yourself!'
						),
						'noSuccess'       => array(
							'type'  => 'text',
							'value' => '😕 Sorry we couldn\'t find an instant answer.'
						),
						'submitTicket'    => array(
							'type'  => 'button',
							'label' => 'Submit a ticket',
							'class' => 'btn__dark'
						),
					),
				),
				'success'       => array( // !!! This is not used !!!
					'class'  => 'success',
					'fields' => array(
						'title'       => array(
							'type'  => 'h1',
							'value' => '👍 Thanks!'
						),
						'description' => array(
							'type'  => 'text',
							'value' => 'Your ticket was submitted successfully! As soon as a member of our crew has had a chance to review it they will be in touch with you at dev-email@pressmatic.dev (the email you used to purchase this theme).'
						),
						'footer'      => array(
							'type'  => 'text',
							'value' => 'Cheers'
						),
						'links'       => array(
							'type'  => 'links',
							'value' => array(
								array(
									'label' => 'Back to Self Help',
									'url'   => '#'
								),
							),
						),
					),
				),
			),
		),
	);

	// the authenticator config is based on the component status which can be: not_validated, loading, validated
	$return['authentication'] = array(
		//general strings
		'title'               => 'You are almost finished!',
		// validated string
		'validatedTitle'      => 'Well done, <strong>{{username}}</strong>!',
		'validatedContent'    => 'Congratulations, {{username}}! Your site is successfully connected, and you can move forward and set up your website.',
		'validatedButton'     => '{{theme_name}} Activated!',
		//  not validated strings
		'notValidatedContent' => 'In order to get access to support, demo content and automatic updates you need to validate the theme by simply linking this site to your Pixelgrade shop account. <a href="https://pixelgrade.com/docs/getting-started/updating-the-theme/" target="_blank">Learn more</a> about product validation.',
		'notValidatedButton'  => 'Activate the Theme License!',
		// no themes from shop
		'noThemeContent'      => 'Ups! You are logged in, but it seems you haven\'t purchased this theme yet.',
		'noThemeRetryButton'  => 'Retry to activate',
		'noThemeLicense'      => 'You don\'t seem to have any licenses for this theme',
		// Theme of ours but broken
		'oursBrokenTitle'      => 'Huston, we have a problem..',
		'oursBrokenContent'    => 'You seem to be using a Pixelgrade theme, but something is wrong with it. Are you sure you are <strong>using the theme code</strong> downloaded from <a href="https://pixelgrade.com">pixelgrade.com</a> or maybe the marketplace you\'ve purchased from?<br/><strong>We can\'t activate this theme</strong> in it\'s current state.<br/><br/>Reach us at <a href="mailto:help@pixelgrade.com?Subject=Help%20with%20broken%20theme" target="_top">help@pixelgrade.com</a> if you need further help.',
		// Not our theme or broken beyond recognition
		'brokenTitle'      => 'Huston, we have a problem.. Really!',
		'brokenContent'    => 'This doesn\'t seem to be <strong>a Pixelgrade theme.</strong> Are you sure you are <strong>using the theme code</strong> downloaded from <a href="https://pixelgrade.com">pixelgrade.com</a> or maybe the marketplace you\'ve purchased from?<br/><strong>We can\'t activate this theme</strong> in it\'s current state.<br/><br/>Reach us at <a href="mailto:help@pixelgrade.com?Subject=Help%20with%20broken%20theme" target="_top">help@pixelgrade.com</a> if you need further help.',
		// loading strings
		'loadingContent'      => 'Getting a couple of details ...',
		'loadingLicensesTitle' => 'Licenses on the way',
		'loadingLicenses'     => 'Take a deep breath. We are looking carefully through your licenses ...',
		'loadingPrepare'      => 'Prepare ...',
		'loadingError'        => 'Sorry .. I can\'t do this right now!',
		// license urls
		'buyThemeUrl'         => esc_url( trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'pricing' ),
		'renewLicenseUrl'     => esc_url( trailingslashit( PIXELGRADE_CARE__SHOP_BASE ) . 'my-account' )
	);

	$update_core = get_site_transient( 'update_core' );

	if ( ! empty( $update_core->updates ) && ! empty( $update_core->updates[0] ) ) {
		$new_update                                     = $update_core->updates[0];
		$return['systemStatus']['wpRecommendedVersion'] = $new_update->current;
	}

	$return = apply_filters( 'pixcare_default_config', $return );

	return $return;
}
