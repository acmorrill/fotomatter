<?php

$sqls = array();

$functions = array();

$sqls[] = "TRUNCATE TABLE  `tags`;";
$sqls[] = "INSERT INTO `tags` (`id`, `name`, `created`) VALUES
(null, 'Southwest', '2014-07-30 13:42:45'),
(null, 'Kids', '2014-07-30 13:42:45'),
(null, 'Portrait', '2014-07-30 13:42:45'),
(null, 'Fall Color', '2014-07-30 13:42:45'),
(null, 'Stars', '2014-07-30 13:42:45');";

$sqls[] = "TRUNCATE TABLE  `photos_tags`;";
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

$sqls[] = "TRUNCATE TABLE  `site_pages`;";
$sqls[] = "INSERT INTO `site_pages` (`id`, `title`, `external_link`, `weight`, `type`, `contact_header`, `contact_message`, `created`, `modified`) VALUES
(null, 'About Me', '', 1, 'custom', NULL, NULL, '2014-07-30 14:12:18', '2014-07-30 14:12:30'),
(null, 'Contact', '', 2, 'contact_us', 'Contact Andrew Morrill', 'Please fill out the form below to contact me.', '2014-07-30 14:57:08', '2014-07-30 14:57:16');";


$sqls[] = "TRUNCATE TABLE  `site_pages_site_page_elements`;";
$sqls[] = "INSERT INTO `site_pages_site_page_elements` (`id`, `site_page_id`, `site_page_element_id`, `config`, `page_element_order`, `created`, `modified`) VALUES
(2, 3, 1, '" 
. 'a:5:{s:22:"para_image_header_text";s:9:"Biography";s:26:"para_header_image_photo_id";s:2:"-1";s:25:"para_image_paragraph_text";s:33:"<p>This is your about me page</p>";s:27:"para_image_header_image_pos";s:4:"left";s:28:"para_image_header_image_size";s:6:"medium";}' 
. "', 1, '2015-02-26 20:25:17', '2015-02-26 20:26:33');";

$sqls[] = "TRUNCATE TABLE  `site_two_level_menu_containers`;";
$sqls[] = "INSERT INTO `site_two_level_menu_containers` (`id`, `display_name`, `modified`, `created`) VALUES
(null, 'Galleries', '2014-07-30 15:04:56', '2014-07-30 15:04:56');";

$sqls[] = "TRUNCATE TABLE  `site_two_level_menu_container_items`;";
$sqls[] = "INSERT INTO `site_two_level_menu_container_items` (`id`, `site_two_level_menu_container_id`, `ref_name`, `external_id`, `external_model`, `weight`, `created`, `modified`) VALUES
(null, (select id from site_two_level_menu_containers where display_name='Galleries'), 'custom', (select id from photo_galleries where display_name='Landscape'), 'PhotoGallery', 1, '2014-07-30 15:05:09', '2014-07-30 15:05:09'),
(null, (select id from site_two_level_menu_containers where display_name='Galleries'), 'custom', (select id from photo_galleries where display_name='Portrait'), 'PhotoGallery', 2, '2014-07-30 15:06:43', '2014-07-30 15:06:43');";

$sqls[] = "TRUNCATE TABLE  `site_two_level_menus`;";
$sqls[] = "INSERT INTO `site_two_level_menus` (`id`, `is_system`, `ref_name`, `external_id`, `external_model`, `weight`, `created`, `modified`) VALUES
(null, 0, 'custom', (select id from site_two_level_menu_containers where display_name='Galleries'), 'SiteTwoLevelMenuContainer', 1, '2014-07-30 15:20:41', '2014-07-30 15:20:41');";


