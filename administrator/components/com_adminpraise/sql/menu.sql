INSERT INTO `#__adminpraise_menu` (`id`, `menutype`, `title`, `alias`, `note`, `path`, `link`, `type`, `published`, `parent_id`, `level`, `component_id`, `ordering`, `checked_out`, `checked_out_time`, `browserNav`, `access`, `img`, `template_style_id`, `params`, `lft`, `rgt`, `home`, `language`, `client_id`, `import_id`, `updated_parent`) VALUES
(1, '', 'Menu_Item_Root', 'root', '', '', '', '', 1, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, '', 0, '', 0, 149, 0, '*', 1, 0, 1),
(2, 'main', 'MOD_MENU_SITE', 'mod-menu-site', '', 'mod-menu-site', 'index.php', 'url', 1, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:home-item ', 0, '{"menu_image":-1,"menu_class":"home-item"}', 1, 6, 1, '*', 1, 0, 1),
(3, 'main', 'MOD_MENU_MENUS', 'mod-menu-menus', '', 'mod-menu-menus', 'index.php?option=com_menus', 'component', 1, 1, 1, 14, 1, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 53, 62, 0, '*', 1, 0, 1),
(4, 'main', 'MOD_MENU_COM_CONTENT', 'mod-menu-com-content', '', 'mod-menu-com-content', 'index.php?option=com_content', 'component', 1, 1, 1, 22, 2, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 73, 88, 0, '*', 1, 0, 1),
(5, 'main', 'MOD_MENU_COMPONENTS', 'mod_menu_components', '', 'mod_menu_components', 'index.php?ap_task=list_components', 'url', 1, 1, 1, 0, 3, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 89, 90, 0, '*', 1, 0, 1),
(6, 'main', 'MOD_MENU_EXTENSIONS_MODULE_MANAGER', 'mod-menu-extensions-module-manager', '', 'mod-menu-extensions-extensions/mod-menu-extensions-module-manager', 'index.php?option=com_modules', 'component', 1, 54, 2, 16, 4, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 98, 103, 0, '*', 1, 0, 1),
(7, 'main', 'MOD_MENU_EXTENSIONS_TEMPLATE_MANAGER', 'mod-menu-extensions-template-manager', '', 'mod-menu-extensions-extensions/mod-menu-extensions-template-manager', 'index.php?option=com_templates', 'component', 1, 54, 2, 20, 5, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 104, 109, 0, '*', 1, 0, 1),
(8, 'main', 'MOD_MENU_COM_USERS', 'mod-menu-com-users', '', 'mod-menu-com-users', 'index.php?option=com_users&view=users', 'component', 1, 1, 1, 25, 6, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 117, 136, 0, '*', 1, 0, 1),
(9, 'main', 'MOD_MENU_CONTROL_PANEL', 'mod_menu_control_panel', '', 'mod-menu-site/mod_menu_control_panel', '##CONTROL_PANEL##', 'url', 1, 2, 2, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 2, 3, 0, '*', 1, 0, 1),
(10, 'main', 'MOD_ADMINPRAISE_PREVIEW_SITE', 'preview-site', '', 'mod-menu-site/preview-site', '##PREVIEW_SITE##', 'url', 1, 2, 2, 0, 1, 0, '0000-00-00 00:00:00', 1, 3, '', 0, '{"menu_image":-1}', 4, 5, 0, '*', 1, 0, 1),
(11, 'main', 'MOD_MENU_MENU_MANAGER', 'mod-menu-menu-manager', '', 'mod-menu-menus/mod-menu-menu-manager', 'index.php?option=com_menus', 'component', 1, 3, 2, 14, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 54, 57, 0, '*', 1, 0, 1),
(12, 'main', 'Menu Trash', 'menu trash', '', 'mod-menu-menus/menu trash', 'index.php?option=com_menus&view=items&menutype=&filter_published=-2', 'url', 1, 3, 2, 0, 1, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 58, 59, 0, '*', 1, 0, 1),
(16, 'main', 'MOD_MENU_MENU_MANAGER_NEW_MENU', 'mod-menu-menu-manager-new-menu', '', 'mod-menu-menus/mod-menu-menu-manager/mod-menu-menu-manager-new-menu', 'index.php?option=com_menus&view=menu&layout=edit', 'url', 1, 11, 3, 14, 5, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 55, 56, 0, '*', 1, 0, 1),
(17, 'main', 'MOD_MENU_COM_CONTENT_ARTICLE_MANAGER', 'mod_menu_com_content_article_manager', '', 'mod-menu-com-content/mod_menu_com_content_article_manager', 'index.php?option=com_content', 'url', 1, 4, 2, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 74, 77, 0, '*', 1, 0, 1),
(18, 'main', 'MOD_MENU_COM_CONTENT_NEW_ARTICLE', 'mod_menu_com_content_new_article', '', 'mod-menu-com-content/mod_menu_com_content_article_manager/mod_menu_com_content_new_article', 'index.php?option=com_content&view=article&layout=edit', 'url', 1, 17, 3, 0, 1, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 75, 76, 0, '*', 1, 0, 1),
(19, 'main', 'MOD_MENU_COM_CONTENT_CATEGORY_MANAGER', 'mod_menu_com_content_category_manager', '', 'mod-menu-com-content/mod_menu_com_content_category_manager', 'index.php?option=com_categories&scope=content', 'url', 1, 4, 2, 0, 2, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 78, 81, 0, '*', 1, 0, 1),
(20, 'main', 'MOD_MENU_COM_CONTENT_NEW_CATEGORY', 'mod_menu_com_content_new_category', '', 'mod-menu-com-content/mod_menu_com_content_category_manager/mod_menu_com_content_new_category', 'index.php?option=com_categories&view=category&layout=edit&extension=com_content', 'url', 1, 19, 3, 0, 3, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 79, 80, 0, '*', 1, 0, 1),
(21, 'main', 'Archived Articles', 'archived articles', '', 'mod-menu-com-content/archived articles', 'index.php?option=com_content&filter_published=2', 'url', 1, 4, 2, 0, 4, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 82, 83, 0, '*', 1, 0, 1),
(22, 'main', 'MOD_MENU_COM_CONTENT_FEATURED', 'mod_menu_com_content_featured', '', 'mod-menu-com-content/mod_menu_com_content_featured', 'index.php?option=com_content&view=featured', 'url', 1, 4, 2, 0, 5, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 84, 85, 0, '*', 1, 0, 1),
(23, 'main', 'Article Trash', 'article trash', '', 'mod-menu-com-content/article trash', 'index.php?option=com_content&filter_published=-2', 'url', 1, 4, 2, 0, 6, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 86, 87, 0, '*', 1, 0, 1),
(25, 'main', 'COM_ADMINPRAISE_SITE_MODULES', 'com_adminpraise_site_modules', '', 'mod-menu-extensions-extensions/mod-menu-extensions-module-manager/com_adminpraise_site_modules', 'index.php?option=com_modules&filter_client_id=0', 'url', 1, 6, 3, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 99, 100, 0, '*', 1, 0, 1),
(28, 'main', 'COM_ADMINPRAISE_ADMIN_MODULES', 'com_adminpraise_admin_modules', '', 'mod-menu-extensions-extensions/mod-menu-extensions-module-manager/com_adminpraise_admin_modules', 'index.php?option=com_modules&filter_client_id=1', 'url', 1, 6, 3, 0, 3, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 101, 102, 0, '*', 1, 0, 1),
(30, 'main', 'MOD_ADMINPRAISE_SITE_TEMPLATES', 'mod_adminpraise_site_templates', '', 'mod-menu-extensions-extensions/mod-menu-extensions-template-manager/mod_adminpraise_site_templates', 'index.php?option=com_templates&filter_client_id=0', 'url', 1, 7, 3, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 105, 106, 0, '*', 1, 0, 1),
(31, 'main', 'MOD_ADMINPRAISE_ADMIN_TEMPLATES', 'com_adminpraise_admin_modules', '', 'mod-menu-extensions-extensions/mod-menu-extensions-template-manager/com_adminpraise_admin_modules', 'index.php?option=com_templates&filter_client_id=1', 'url', 1, 7, 3, 0, 1, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 107, 108, 0, '*', 1, 0, 1),
(32, 'tools', 'MOD_MENU_CONFIGURATION', 'mod_menu_configuration', '', 'mod_menu_configuration', 'index.php?option=com_config', 'url', 1, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:admin-item', 0, '{"menu_image":-1,"menu_class":"admin-item"}', 17, 24, 0, '*', 1, 0, 1),
(33, 'tools', 'MOD_MENU_CONFIGURATION', 'mod_menu_configuration', '', 'mod_menu_configuration/mod_menu_configuration', 'index.php?option=com_config', 'url', 1, 32, 2, 0, 1, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 18, 19, 0, '*', 1, 0, 1),
(34, 'tools', 'MOD_MENU_SYSTEM_INFORMATION', 'mod_menu_system_information', '', 'mod_menu_configuration/mod_menu_system_information', 'index.php?option=com_admin&view=sysinfo', 'url', 1, 32, 2, 0, 2, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 20, 21, 0, '*', 1, 0, 1),
(35, 'tools', 'Adminpraise Settings', 'adminpraise settings', '', 'mod_menu_configuration/adminpraise settings', 'index.php?option=com_adminpraise&view=settings', 'url', 1, 32, 2, 0, 3, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 22, 23, 0, '*', 1, 0, 1),
(37, 'tools', 'Tools', 'tools', '', 'tools', 'index.php?option=com_installer', 'url', 1, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:tools-item', 0, '{"menu_image":-1,"menu_class":"tools-item"}', 25, 40, 0, '*', 1, 0, 1),
(38, 'tools', 'MOD_MENU_EXTENSIONS_EXTENSION_MANAGER', 'mod_menu_extensions_extension_manager', '', 'tools/mod_menu_extensions_extension_manager', 'index.php?option=com_installer', 'url', 1, 37, 2, 0, 1, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 26, 27, 0, '*', 1, 0, 1),
(39, 'tools', 'MOD_MENU_EXTENSIONS_PLUGIN_MANAGER', 'mod_menu_extensions_plugin_manager', '', 'tools/mod_menu_extensions_plugin_manager', 'index.php?option=com_plugins', 'url', 1, 37, 2, 0, 2, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 28, 29, 0, '*', 1, 0, 1),
(40, 'tools', 'MOD_MENU_MASS_MAIL_USERS', 'mod_menu_mass_mail_users', '', 'tools/mod_menu_mass_mail_users', 'index.php?option=com_users&view=mail', 'url', 1, 37, 2, 0, 3, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 30, 31, 0, '*', 1, 0, 1),
(41, 'tools', 'MOD_MENU_CLEAR_CACHE', 'mod_menu_clear_cache', '', 'tools/mod_menu_clear_cache', 'index.php?option=com_cache', 'url', 1, 37, 2, 0, 4, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 32, 33, 0, '*', 1, 0, 1),
(42, 'tools', 'MOD_MENU_MEDIA_MANAGER', 'mod_menu_media_manager', '', 'tools/mod_menu_media_manager', 'index.php?option=com_media', 'url', 1, 37, 2, 0, 5, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 34, 35, 0, '*', 1, 0, 1),
(43, 'tools', 'MOD_ADMINPRAISE_PREVIEW_SITE', 'preview', '', 'tools/preview', '##PREVIEW_SITE##', 'url', 1, 37, 2, 0, 6, 0, '0000-00-00 00:00:00', 1, 3, '', 0, '{"menu_image":-1}', 36, 37, 0, '*', 1, 0, 1),
(44, 'tools', 'MOD_MENU_GLOBAL_CHECKIN', 'mod_menu_global_checkin', '', 'tools/mod_menu_global_checkin', 'index.php?option=com_checkin', 'url', 1, 37, 2, 0, 7, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '{"menu_image":-1}', 38, 39, 0, '*', 1, 0, 1),
(45, 'main', 'MOD_MENU_COM_USERS_USER_MANAGER', 'mod_menu_com_users_user_manager', '', 'mod-menu-com-users/mod_menu_com_users_user_manager', 'index.php?option=com_users&view=users', 'component', 1, 8, 2, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 118, 121, 0, '*', 1, 0, 1),
(46, 'main', 'MOD_MENU_COM_USERS_ADD_USER', 'mod_menu_com_users_add_user', '', 'mod-menu-com-users/mod_menu_com_users_user_manager/mod_menu_com_users_add_user', 'index.php?option=com_users&task=user.add', 'component', 1, 45, 3, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 119, 120, 0, '*', 1, 0, 1),
(47, 'main', 'MOD_MENU_COM_USERS_GROUPS', 'mod_menu_com_users_groups', '', 'mod-menu-com-users/mod_menu_com_users_groups', 'index.php?option=com_users&view=groups', 'component', 1, 8, 2, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 122, 125, 0, '*', 1, 0, 1),
(48, 'main', 'MOD_MENU_COM_USERS_ADD_GROUP', 'mod_menu_com_users_add_group', '', 'mod-menu-com-users/mod_menu_com_users_groups/mod_menu_com_users_add_group', 'index.php?option=com_users&task=group.add', 'component', 1, 47, 3, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 123, 124, 0, '*', 1, 0, 1),
(49, 'main', 'MOD_MENU_COM_USERS_LEVELS', 'mod_menu_com_users_levels', '', 'mod-menu-com-users/mod_menu_com_users_levels', 'index.php?option=com_users&view=levels', 'component', 1, 8, 2, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 126, 129, 0, '*', 1, 0, 1),
(50, 'main', 'MOD_MENU_COM_USERS_ADD_LEVEL', 'mod_menu_com_users_add_level', '', 'mod-menu-com-users/mod_menu_com_users_levels/mod_menu_com_users_add_level', 'index.php?option=com_users&view=levels', 'component', 1, 49, 3, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 127, 128, 0, '*', 1, 0, 1),
(51, 'main', 'MOD_MENU_COM_USERS_NOTES', 'mod_menu_com_users_notes', '', 'mod-menu-com-users/mod_menu_com_users_notes', 'index.php?option=com_users&view=notes', 'component', 1, 8, 2, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 130, 133, 0, '*', 1, 0, 1),
(52, 'main', 'MOD_MENU_COM_USERS_ADD_NOTE', 'mod_menu_com_users_add_note', '', 'mod-menu-com-users/mod_menu_com_users_notes/mod_menu_com_users_add_note', 'index.php?option=com_users&task=note.add', 'component', 1, 51, 3, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 131, 132, 0, '*', 1, 0, 1),
(53, 'main', 'MOD_MENU_COM_USERS_NOTE_CATEGORIES', 'user_note_categories', '', 'mod-menu-com-users/user_note_categories', 'index.php?option=com_categories&view=categories&extension=com_users.notes', 'component', 1, 8, 2, 25, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 134, 135, 0, '*', 1, 0, 1),
(54, 'main', 'MOD_MENU_EXTENSIONS_EXTENSIONS', 'mod-menu-extensions-extensions', '', 'mod-menu-extensions-extensions', 'index.php?option=com_installer', 'component', 1, 1, 1, 10, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 97, 116, 0, '*', 1, 0, 1),
(55, 'main', 'COM_ADMINPRAISE_MENU', 'com-adminpraise-menu', '', 'mod-menu-menus/com-adminpraise-menu', 'index.php?option=com_adminpraise&view=adminitems', 'url', 1, 3, 2, 10010, 0, 0, '0000-00-00 00:00:00', 0, 1, '', 0, '', 60, 61, 0, '', 1, 0, 1),
(56, 'main', 'MOD_MENU_EXTENSIONS_EXTENSION_MANAGER', 'mod-menu-extensions-extension-manager', '', 'mod-menu-extensions-extensions/mod-menu-extensions-extension-manager', 'index.php?option=com_installer', 'component', 1, 54, 2, 10, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:install', 0, '', 110, 111, 0, '', 1, 0, 1),
(57, 'main', 'MOD_MENU_EXTENSIONS_PLUGIN_MANAGER', 'mod-menu-extensions-plugin-manager', '', 'mod-menu-extensions-extensions/mod-menu-extensions-plugin-manager', 'index.php?option=com_plugins', 'component', 1, 54, 2, 10, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:plugin', 0, '', 112, 113, 0, '', 1, 0, 1),
(58, 'main', 'MOD_MENU_EXTENSIONS_LANGUAGE_MANAGER', 'mod-menu-extensions-language-manager', '', 'mod-menu-extensions-extensions/mod-menu-extensions-language-manager', 'index.php?option=com_languages', 'component', 1, 54, 2, 11, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:language', 0, '', 114, 115, 0, '', 1, 0, 1),
(59, 'main', 'MOD_MENU_HELP', 'mod-menu-help', '', 'mod-menu-help', '#', 'url', 1, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, '', 0, '', 141, 148, 0, '', 1, 0, 1),
(60, 'main', 'MOD_MENU_HELP_JOOMLA', 'mod-menu-help-joomla', '', 'mod-menu-help/mod-menu-help-joomla', 'index.php?option=com_admin&view=help', 'url', 1, 59, 2, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:help', 0, '', 142, 143, 0, '', 1, 0, 1),
(61, 'main', 'MOD_MENU_HELP_SUPPORT_OFFICIAL_FORUM', 'mod-menu-help-support-official-forum', '', 'mod-menu-help/mod-menu-help-support-official-forum', 'http://forum.joomla.org', 'url', 1, 59, 2, 0, 0, 0, '0000-00-00 00:00:00', 1, 3, '', 0, '', 144, 145, 0, '', 1, 0, 1),
(62, 'main', 'MOD_MENU_HELP_DOCUMENTATION', 'mod-menu-help-documentation', '', 'mod-menu-help/mod-menu-help-documentation', 'http://docs.joomla.org', 'url', 1, 59, 2, 0, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:help-docs', 0, '', 146, 147, 0, '', 1, 0, 1),
(63, 'panel', 'com_banners', 'banners', '', 'banners', 'index.php?option=com_banners', 'component', 1, 1, 1, 4, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_banners', 0, '', 7, 16, 0, '*', 1, 0, 1),
(64, 'panel', 'com_banners', 'Banners', '', 'banners/Banners', 'index.php?option=com_banners', 'component', 1, 63, 2, 4, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:banners', 0, '', 8, 9, 0, '*', 1, 0, 1),
(65, 'panel', 'com_banners_categories', 'Categories', '', 'banners/Categories', 'index.php?option=com_categories&extension=com_banners', 'component', 1, 63, 2, 6, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:banners-cat', 0, '', 10, 11, 0, '*', 1, 0, 1),
(66, 'panel', 'com_banners_clients', 'Clients', '', 'banners/Clients', 'index.php?option=com_banners&view=clients', 'component', 1, 63, 2, 4, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:banners-clients', 0, '', 12, 13, 0, '*', 1, 0, 1),
(67, 'panel', 'com_banners_tracks', 'Tracks', '', 'banners/Tracks', 'index.php?option=com_banners&view=tracks', 'component', 1, 63, 2, 4, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:banners-tracks', 0, '', 14, 15, 0, '*', 1, 0, 1),
(68, 'panel', 'com_contact', 'Contacts', '', 'Contacts', 'index.php?option=com_contact', 'component', 1, 1, 1, 8, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_contact', 0, '', 41, 46, 0, '*', 1, 0, 1),
(69, 'panel', 'com_contact', 'Contacts', '', 'Contacts/Contacts', 'index.php?option=com_contact', 'component', 1, 68, 2, 8, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:contact', 0, '', 42, 43, 0, '*', 1, 0, 1),
(70, 'panel', 'com_contact_categories', 'Categories', '', 'Contacts/Categories', 'index.php?option=com_categories&extension=com_contact', 'component', 1, 68, 2, 6, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:contact-cat', 0, '', 44, 45, 0, '*', 1, 0, 1),
(71, 'panel', 'com_messages', 'Messaging', '', 'Messaging', 'index.php?option=com_messages', 'component', 1, 1, 1, 15, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_messages', 0, '', 47, 52, 0, '*', 1, 0, 1),
(72, 'panel', 'com_messages_add', 'New Private Message', '', 'Messaging/New Private Message', 'index.php?option=com_messages&task=message.add', 'component', 1, 71, 2, 15, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:messages-add', 0, '', 48, 49, 0, '*', 1, 0, 1),
(73, 'panel', 'com_messages_read', 'Read Private Message', '', 'Messaging/Read Private Message', 'index.php?option=com_messages', 'component', 1, 71, 2, 15, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:messages-read', 0, '', 50, 51, 0, '*', 1, 0, 1),
(74, 'panel', 'com_newsfeeds', 'News Feeds', '', 'News Feeds', 'index.php?option=com_newsfeeds', 'component', 1, 1, 1, 17, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_newsfeeds', 0, '', 63, 68, 0, '*', 1, 0, 1),
(75, 'panel', 'com_newsfeeds_feeds', 'Feeds', '', 'News Feeds/Feeds', 'index.php?option=com_newsfeeds', 'component', 1, 74, 2, 17, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:newsfeeds', 0, '', 64, 65, 0, '*', 1, 0, 1),
(76, 'panel', 'com_newsfeeds_categories', 'Categories', '', 'News Feeds/Categories', 'index.php?option=com_categories&extension=com_newsfeeds', 'component', 1, 74, 2, 6, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:newsfeeds-cat', 0, '', 66, 67, 0, '*', 1, 0, 1),
(77, 'panel', 'com_redirect', 'Redirect', '', 'Redirect', 'index.php?option=com_redirect', 'component', 1, 1, 1, 24, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_redirect', 0, '', 137, 138, 0, '*', 1, 0, 1),
(78, 'panel', 'com_search', 'Basic Search', '', 'Basic Search', 'index.php?option=com_search', 'component', 1, 1, 1, 19, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_search', 0, '', 71, 72, 0, '*', 1, 0, 1),
(79, 'panel', 'com_weblinks', 'Weblinks', '', 'Weblinks', 'index.php?option=com_weblinks', 'component', 1, 1, 1, 21, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_weblinks', 0, '', 91, 96, 0, '*', 1, 0, 1),
(80, 'panel', 'com_weblinks_links', 'Links', '', 'Weblinks/Links', 'index.php?option=com_weblinks', 'component', 1, 79, 2, 21, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:weblinks', 0, '', 92, 93, 0, '*', 1, 0, 1),
(81, 'panel', 'com_weblinks_categories', 'Categories', '', 'Weblinks/Categories', 'index.php?option=com_categories&extension=com_weblinks', 'component', 1, 79, 2, 6, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:weblinks-cat', 0, '', 94, 95, 0, '*', 1, 0, 1),
(82, 'panel', 'com_finder', 'Smart Search', '', 'Smart Search', 'index.php?option=com_finder', 'component', 1, 1, 1, 27, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_finder', 0, '', 69, 70, 0, '*', 1, 0, 1),
(83, 'panel', 'com_joomlaupdate', 'Joomla! Update', '', 'Joomla! Update', 'index.php?option=com_joomlaupdate', 'component', 0, 1, 1, 28, 0, 0, '0000-00-00 00:00:00', 0, 3, 'class:com_joomlaupdate', 0, '', 139, 140, 0, '*', 1, 0, 1);

INSERT INTO `#__adminpraise_menu_types` (`id`, `menutype`, `title`, `description`) VALUES
(1, 'main', 'AdminPraiseTopMenu', 'AdminPraise Menu'),
(2, 'tools', 'AdminpraiseToolsMenu', 'System tools menu'),
(3, 'panel', 'AdminpraisePanelMenu', 'The Components Panel on the left');

