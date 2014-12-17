<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `is_globally_shared` TINYINT NOT NULL DEFAULT  '0' COMMENT  'if true then the image uses the global default images container' AFTER  `id` ;";

$sqls[] = "
	INSERT INTO `photos` (`id`, `is_globally_shared`, `date_taken`, `cdn-filename`, `cdn-filename-forcache`, `cdn-filename-smaller-forcache`, `display_title`, `display_subtitle`, `description`, `alt_text`, `enabled`, `photo_format_id`, `pixel_width`, `pixel_height`, `forcache_pixel_width`, `forcache_pixel_height`, `smaller_forcache_pixel_width`, `smaller_forcache_pixel_height`, `tag_attributes`, `created`, `modified`) VALUES
	(null, 1, '2014-07-25', 'Bow-Tie.jpg', 'Bow-Tie-forcache.jpg', 'Bow-Tie-smaller-forcache.jpg', '(Example Image) Bow Tie', '', '', '', 1, 1, 3130, 2075, 1500, 994, 250, 166, 'width=\"3130\" height=\"2075\"', '2014-07-25 17:30:33', '2014-07-25 17:30:33'),
	(null, 1, '2014-07-25', 'Wreath.jpg', 'Wreath-forcache.jpg', 'Wreath-smaller-forcache.jpg', '(Example Image) Wreath', '', '', '', 1, 1, 3072, 2048, 1500, 1000, 250, 167, 'width=\"3072\" height=\"2048\"', '2014-07-25 17:30:41', '2014-07-25 17:30:41'),
	(null, 1, '2014-07-29', 'Junkyard.jpg', 'Junkyard-forcache.jpg', 'Junkyard-smaller-forcache.jpg', '(Example Image) Junkyard Kid', '', '', '', 1, 2, 2670, 4000, 1001, 1500, 167, 250, 'width=\"2670\" height=\"4000\"', '2014-07-29 17:24:58', '2014-07-29 17:24:58'),
	(null, 1, '2014-07-29', 'Jenn.jpg', 'Jenn-forcache.jpg', 'Jenn-smaller-forcache.jpg', '(Example Image) Jenn', '', '', '', 1, 1, 4000, 3556, 1500, 1334, 250, 222, 'width=\"4000\" height=\"3556\"', '2014-07-29 17:25:08', '2014-07-29 17:25:08'),
	(null, 1, '2014-07-25', 'A-Degree-Coyote.jpg', 'A-Degree-Coyote-forcache.jpg', 'A-Degree-Coyote-smaller-forcache.jpg', '(Example Image) 22 Degree Coyote', '', '', '', 1, 2, 3539, 4000, 1327, 1500, 221, 250, 'width=\"3539\" height=\"4000\"', '2014-07-25 17:19:28', '2014-07-25 17:19:28'),
	(null, 1, '2014-07-28', 'A-Tangerine-Blue.jpg', 'A-Tangerine-Blue-forcache.jpg', 'A-Tangerine-Blue-smaller-forcache.jpg', '(Example Image) A Tangerine Blue', '', '', '', 1, 1, 4000, 2836, 1500, 1064, 250, 177, 'width=\"4000\" height=\"2836\"', '2014-07-28 14:45:09', '2014-07-28 14:45:09'),
	(null, 1, '2014-07-25', 'Harvest.jpg', 'Harvest-forcache.jpg', 'Harvest-smaller-forcache.jpg', '(Example Image) Harvest', '', '', '', 1, 3, 4000, 4000, 1500, 1500, 250, 250, 'width=\"4000\" height=\"4000\"', '2014-07-25 17:27:29', '2014-07-25 17:27:29'),
	(null, 1, '2014-07-25', 'Provo-River-Stars.jpg', 'Provo-River-Stars-forcache.jpg', 'Provo-River-Stars-smaller-forcache.jpg', '(Example Image) Provo River Stars', '', '', '', 1, 2, 2772, 4000, 1040, 1500, 173, 250, 'width=\"2772\" height=\"4000\"', '2014-07-25 17:28:01', '2014-07-25 17:28:01'),
	(null, 1, '2014-07-28', 'Southwest-Passage.jpg', 'Southwest-Passage-forcache.jpg', 'Southwest-Passage-smaller-forcache.jpg', '(Example Image) Southwest Passage', '', '', '', 1, 1, 4000, 3548, 1500, 1331, 250, 222, 'width=\"4000\" height=\"3548\"', '2014-07-28 14:30:35', '2014-07-28 14:30:35');
";		

$sqls[] = "INSERT INTO `photo_galleries` (`id`, `weight`, `type`, `display_name`, `description`, `smart_settings`, `created`, `modified`) VALUES
(null, 1, 'standard', 'Landscape', '', '', '2014-07-29 18:01:07', '2014-07-29 18:01:20'),
(null, 2, 'standard', 'Portrait', '', '', '2014-07-29 18:01:28', '2014-07-29 18:01:36');";

$sqls[] = "INSERT INTO `photo_galleries_photos` (`id`, `photo_id`, `photo_gallery_id`, `photo_order`) VALUES
(null, (select id from photos where `cdn-filename`='A-Degree-Coyote.jpg'), (select id from photo_galleries where display_name='Landscape'), 1),
(null, (select id from photos where `cdn-filename`='A-Tangerine-Blue.jpg'), (select id from photo_galleries where display_name='Landscape'), 2),
(null, (select id from photos where `cdn-filename`='Provo-River-Stars.jpg'), (select id from photo_galleries where display_name='Landscape'), 3),
(null, (select id from photos where `cdn-filename`='Harvest.jpg'), (select id from photo_galleries where display_name='Landscape'), 4),
(null, (select id from photos where `cdn-filename`='Southwest-Passage.jpg'), (select id from photo_galleries where display_name='Landscape'), 5),
(null, (select id from photos where `cdn-filename`='Junkyard.jpg'), (select id from photo_galleries where display_name='Portrait'), 1),
(null, (select id from photos where `cdn-filename`='Jenn.jpg'), (select id from photo_galleries where display_name='Portrait'), 2),
(null, (select id from photos where `cdn-filename`='Bow-Tie.jpg'), (select id from photo_galleries where display_name='Portrait'), 3),
(null, (select id from photos where `cdn-filename`='Wreath.jpg'), (select id from photo_galleries where display_name='Portrait'), 4);";

$sqls[] = "INSERT INTO `tags` (`id`, `name`, `created`) VALUES
(null, 'Southwest', '2014-07-30 13:42:45'),
(null, 'Kids', '2014-07-30 13:42:45'),
(null, 'Portrait', '2014-07-30 13:42:45'),
(null, 'Fall Color', '2014-07-30 13:42:45'),
(null, 'Stars', '2014-07-30 13:42:45');";

$sqls[] = "INSERT INTO `photos_tags` (`id`, `tag_id`, `photo_id`) VALUES
(null, (select id from tags where name='Kids'), (select id from photos where `cdn-filename`='Bow-Tie.jpg')),
(null, (select id from tags where name='Portrait'), (select id from photos where `cdn-filename`='Bow-Tie.jpg')),
(null, (select id from tags where name='Kids'), (select id from photos where `cdn-filename`='Wreath.jpg')),
(null, (select id from tags where name='Portrait'), (select id from photos where `cdn-filename`='Wreath.jpg')),
(null, (select id from tags where name='Kids'), (select id from photos where `cdn-filename`='Junkyard.jpg')),
(null, (select id from tags where name='Portrait'), (select id from photos where `cdn-filename`='Junkyard.jpg')),
(null, (select id from tags where name='Portrait'), (select id from photos where `cdn-filename`='Jenn.jpg')),
(null, (select id from tags where name='Southwest'), (select id from photos where `cdn-filename`='A-Degree-Coyote.jpg')),
(null, (select id from tags where name='Fall Color'), (select id from photos where `cdn-filename`='A-Degree-Coyote.jpg')),
(null, (select id from tags where name='Southwest'), (select id from photos where `cdn-filename`='A-Tangerine-Blue.jpg')),
(null, (select id from tags where name='Fall Color'), (select id from photos where `cdn-filename`='Harvest.jpg')),
(null, (select id from tags where name='Stars'), (select id from photos where `cdn-filename`='Provo-River-Stars.jpg')),
(null, (select id from tags where name='Southwest'), (select id from photos where `cdn-filename`='Southwest-Passage.jpg')),
(null, (select id from tags where name='Fall Color'), (select id from photos where `cdn-filename`='Southwest-Passage.jpg'));";

$sqls[] = "TRUNCATE TABLE  `photo_avail_sizes`;";
$sqls[] = "INSERT INTO `photo_avail_sizes` (`id`, `short_side_length`, `photo_format_ids`) VALUES
(null, 2.5, '1,2,3'),
(null, 3.5, '1,2,3'),
(null, 4, '1,2,3'),
(null, 5, '1,2,3'),
(null, 8, '1,2,3'),
(null, 10, '1,2,3,4,5'),
(null, 11, '1,2,3'),
(null, 16, '1,2,3,4,5'),
(null, 20, '1,2,3'),
(null, 22, '1,2,3,4,5'),
(null, 24, '1,2,3'),
(null, 26, '1,2,3,4,5'),
(null, 29, '1,2,3,4,5'),
(null, 30, '1,2,3'),
(null, 40, '1,2,3'),
(null, 44, '1,2,3'),
(null, 48, '1,2,3');";

$sqls[] = "INSERT INTO `site_pages` (`id`, `title`, `external_link`, `weight`, `type`, `contact_header`, `contact_message`, `created`, `modified`) VALUES
(null, 'About Me', '', 1, 'custom', NULL, NULL, '2014-07-30 14:12:18', '2014-07-30 14:12:30'),
(null, 'Contact', '', 2, 'contact_us', 'Contact Andrew Morrill', 'Please fill out the form below to contact me.', '2014-07-30 14:57:08', '2014-07-30 14:57:16');";

$sqls[] = "INSERT INTO `site_pages_site_page_elements` (`id`, `site_page_id`, `site_page_element_id`, `config`, `page_element_order`, `created`, `modified`) VALUES
(null, (select id from site_pages where title='About Me'), 1, 'a:5:{s:22:\"para_image_header_text\";s:9:\"Biography\";s:26:\"para_header_image_photo_id\";s:2:\"-1\";s:25:\"para_image_paragraph_text\";s:273:\"<p>I am a really amazing photographer that decided to try fotomatter as my online presence.</p>\r\n<p>I''m continually improving and someday I'll be the photographer I set out to be. </p>\";s:27:\"para_image_header_image_pos\";s:4:\"left\";s:28:\"para_image_header_image_size\";s:6:\"medium\";}', 1, '2014-07-30 14:18:16', '2014-07-30 14:21:28');";

$sqls[] = "INSERT INTO `site_two_level_menu_containers` (`id`, `display_name`, `modified`, `created`) VALUES
(null, 'Galleries', '2014-07-30 15:04:56', '2014-07-30 15:04:56');";

$sqls[] = "INSERT INTO `site_two_level_menu_container_items` (`id`, `site_two_level_menu_container_id`, `ref_name`, `external_id`, `external_model`, `weight`, `created`, `modified`) VALUES
(null, (select id from site_two_level_menu_containers where display_name='Galleries'), 'custom', (select id from photo_galleries where display_name='Landscape'), 'PhotoGallery', 1, '2014-07-30 15:05:09', '2014-07-30 15:05:09'),
(null, (select id from site_two_level_menu_containers where display_name='Galleries'), 'custom', (select id from photo_galleries where display_name='Portrait'), 'PhotoGallery', 2, '2014-07-30 15:06:43', '2014-07-30 15:06:43');";

$sqls[] = "INSERT INTO `site_two_level_menus` (`id`, `is_system`, `ref_name`, `external_id`, `external_model`, `weight`, `created`, `modified`) VALUES
(null, 0, 'custom', (select id from site_two_level_menu_containers where display_name='Galleries'), 'SiteTwoLevelMenuContainer', 1, '2014-07-30 15:20:41', '2014-07-30 15:20:41');";


