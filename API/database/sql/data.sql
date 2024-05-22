
-- ---------------------
-- App Setting Data
-- ---------------------
INSERT INTO `app_settings`(`id`, `key`, `label`, `value`, `description`) VALUES (1, 'logo', 'Logo', '/src/assets/images/logo.png', 'this logo for app');
INSERT INTO `app_settings`(`id`, `key`, `label`, `value`, `description`) VALUES (3, 'name_en', 'Intitute Name En', 'AVEC', NULL);
INSERT INTO `app_settings`(`id`, `key`, `label`, `value`, `description`) VALUES (2, 'name_kh', 'Intitute Name Kh', 'អង្គការ', NULL);

-- ---------------------
-- Setting Data
-- ---------------------
INSERT INTO `avec`.`academic_years` (`id`, `name`, `start_date`, `end_date`, `is_active`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES (1, 'term 1 2024', '2024-01-01', '2024-03-29', 1, NULL, NULL, NULL, NULL, NULL);

-- ---------------------
-- Student Data
-- ---------------------
INSERT INTO `avec`.`students` (`id`, `code`, `first_name`, `last_name`, `first_name_latin`, `last_name_latin`, `gender`, `dob`, `village_birth`, `commune_birth`, `district_birth`, `province_birth`, `d_first_name`, `d_last_name`, `d_job`, `d_phone_number`, `m_first_name`, `m_last_name`, `m_job`, `m_phone_number`, `village`, `commune`, `district`, `province`, `phone`, `student_status`, `from`, `photo_path`, `other`, `status`, `g_first_name`, `g_last_name`, `g_phone_number`, `g_job`, `g_gender`, `g_detail`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES ('1199c7e1-2ebe-4151-8121-07cd8c3b7c78', '001', 'សី', 'ចន', 'sey', 'john', 1, '2009-12-30', 'ហប់', 'ហប់', 'គាស់ក្រឡ', 'បាត់ដំបង', 'ចន', 'ហេង', 'Admin officer', '098080878', 'មារី', 'រឿន', 'Admin officer', '098080878', 'ហប់', 'ហប់', 'គាស់ក្រឡ', 'បាត់ដំបង', '09898974', 1, 'btb', NULL, 'good health', 1, 'ចន', 'ហេង', '098080878', 'Admin officer', NULL, 'father', '6a0652a1-c83f-4233-9bdb-85ce5851dca7', '6a0652a1-c83f-4233-9bdb-85ce5851dca7', NULL, '2024-05-20 04:21:34', '2024-05-20 04:21:34');
INSERT INTO `avec`.`students` (`id`, `code`, `first_name`, `last_name`, `first_name_latin`, `last_name_latin`, `gender`, `dob`, `village_birth`, `commune_birth`, `district_birth`, `province_birth`, `d_first_name`, `d_last_name`, `d_job`, `d_phone_number`, `m_first_name`, `m_last_name`, `m_job`, `m_phone_number`, `village`, `commune`, `district`, `province`, `phone`, `student_status`, `from`, `photo_path`, `other`, `status`, `g_first_name`, `g_last_name`, `g_phone_number`, `g_job`, `g_gender`, `g_detail`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES ('73f0df98-3935-4f48-bbd4-301f695296b6', '002', 'វិរៈ', 'ឆាយ', 'vireak', 'chay', 1, '2009-12-30', 'ហប់', 'ហប់', 'គាស់ក្រឡ', 'បាត់ដំបង', 'ឆាយ', 'ជា', 'Admin officer', '098080878', 'ធីតា', 'សឹម', 'Admin officer', '098080878', 'ហប់', 'ហប់', 'គាស់ក្រឡ', 'បាត់ដំបង', '09898974', 1, 'btb', NULL, 'good health', 1, 'ធីតា', 'សឹម', '098080878', 'Admin officer', NULL, 'father', '6a0652a1-c83f-4233-9bdb-85ce5851dca7', '6a0652a1-c83f-4233-9bdb-85ce5851dca7', NULL, '2024-05-20 04:21:34', '2024-05-20 04:21:34');
-- ---------------------
-- Teachers Data
-- ---------------------
INSERT INTO `avec`.`teachers` (`id`, `code`, `name`, `name_latin`, `sex`, `dob`, `photo_path`, `pob`, `address`, `position`, `phone`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES (uuid() , '0222', 'ដួង ដាលីន', 'Doung Dalin', 2, '1998-05-15', NULL, 'បាត់ដំបង', 'បាត់ដំបង', 3, '098 78 66 34', NULL, NULL, NULL, NULL, NULL);
