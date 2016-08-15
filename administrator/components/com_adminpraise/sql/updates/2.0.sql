ALTER TABLE `#__adminpraise_menu`
  ADD `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'The SEF alias of the menu item.',
  ADD `path` varchar(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.',
  ADD `img` varchar(255) NOT NULL COMMENT 'The image of the menu item.',
  ADD `component_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__extensions.id',
  ADD `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The relative level in the tree.',
  ADD `template_style_id` int(10) unsigned NOT NULL DEFAULT '0',
  ADD `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  ADD `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  ADD `language` char(7) NOT NULL DEFAULT '',
  ADD `client_id` tinyint(4) NOT NULL DEFAULT '1',
  ADD `import_id` int(11) NOT NULL,
  ADD `updated_parent` int(11) NOT NULL DEFAULT '0';

ALTER TABLE `#__adminpraise_menu`
  CHANGE `access` `access` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The access level required to view the menu item.',
  CHANGE `menutype` `menutype` varchar(24) NOT NULL COMMENT 'The type of menu this item belongs to. FK to #__menu_types.menutype',
  CHANGE  `link` `link` varchar(1024) NOT NULL COMMENT 'The actually link the menu item refers to.',
  CHANGE `type` `type` varchar(16) NOT NULL COMMENT 'The type of link: Component, URL, Alias, Separator',
  CHANGE `home` `home` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicates if this menu item is the home or default page.';

ALTER TABLE `#__adminpraise_menu_types`
  ADD `menutype` varchar(24) NOT NULL;

