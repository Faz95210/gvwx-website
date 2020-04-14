-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 27, 2019 at 07:30 PM
-- Server version: 5.6.23
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikarwebadvanced`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
                           `id` int(11) NOT NULL,
                           `user_id` int(11) NOT NULL,
                           `label` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
                           `address1` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
                           `address2` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
                           `city` varchar(250) CHARACTER SET utf32 DEFAULT NULL,
                           `postcode` int(11) DEFAULT NULL,
                           `country` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
                           `created_at` int(11) NOT NULL,
                           `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
                          `id` int(11) NOT NULL,
                          `user_id` int(11) DEFAULT NULL,
                          `default_vehicle_id` int(11) DEFAULT NULL,
                          `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                          `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                          `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                          `imei` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                          `status` smallint(6) NOT NULL DEFAULT '10',
                          `created_at` int(11) NOT NULL,
                          `updated_at` int(11) NOT NULL,
                          `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                          `awake` int(11) DEFAULT '0',
                          `api_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engine`
--

CREATE TABLE `engine` (
                          `id` int(11) NOT NULL,
                          `label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
                             `version` varchar(180) NOT NULL,
                             `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `power`
--

CREATE TABLE `power` (
                         `id` int(11) NOT NULL,
                         `label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `raw_coordinates`
--

CREATE TABLE `raw_coordinates` (
                                   `id` int(11) NOT NULL,
                                   `user_id` int(11) NOT NULL,
                                   `latitude` double NOT NULL,
                                   `longitude` double NOT NULL,
                                   `address_id` int(11) DEFAULT NULL,
                                   `created_at` int(11) DEFAULT NULL,
                                   `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rtTracking`
--

CREATE TABLE `rtTracking` (
                              `id` int(11) NOT NULL,
                              `device_id` int(11) NOT NULL,
                              `lng` double NOT NULL,
                              `lat` double NOT NULL,
                              `created_at` int(11) NOT NULL,
                              `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scaleIK`
--

CREATE TABLE `scaleIK` (
                           `id` int(11) NOT NULL,
                           `power_id` int(11) NOT NULL,
                           `year` int(11) NOT NULL,
                           `coeffBellow5k` double NOT NULL,
                           `extraBellow5k` double NOT NULL,
                           `coeffBellow20k` double NOT NULL,
                           `extraBellow20k` double NOT NULL,
                           `coeffAbove20k` double NOT NULL,
                           `extraAbove20k` double NOT NULL,
                           `status` smallint(6) NOT NULL DEFAULT '10'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
                       `id` int(11) NOT NULL,
                       `user_id` int(11) NOT NULL,
                       `label` varchar(255) NOT NULL,
                       `color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag2trip`
--

CREATE TABLE `tag2trip` (
                            `tag_id` int(11) NOT NULL,
                            `trip_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
                        `id` int(11) NOT NULL,
                        `start_coordinate_id` int(11) NOT NULL,
                        `stop_coordinate_id` int(11) NOT NULL,
                        `user_id` int(11) NOT NULL,
                        `start_date_time` int(11) NOT NULL,
                        `stop_date_time` int(11) DEFAULT NULL,
                        `duration` double DEFAULT NULL,
                        `distance` double DEFAULT NULL,
                        `kml_file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        `vehicle_id` int(11) DEFAULT NULL,
                        `device_id` int(11) DEFAULT NULL,
                        `comments` varchar(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                        `status` smallint(6) NOT NULL DEFAULT '10',
                        `created_at` int(11) DEFAULT NULL,
                        `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
                        `id` int(11) NOT NULL,
                        `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                        `givenname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                        `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                        `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                        `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                        `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                        `type` smallint(6) NOT NULL DEFAULT '1',
                        `status` smallint(6) NOT NULL DEFAULT '10',
                        `created_at` int(11) NOT NULL,
                        `updated_at` int(11) NOT NULL,
                        `verification_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user_settings` (
                                 `id` int(11) NOT NULL AUTO_INCREMENT,
                                 `user_id` int(11) NOT NULL,
                                 `mail_frequency` varchar(255) DEFAULT NULL,
                                 PRIMARY KEY (`id`),
                                 UNIQUE KEY `user_settings_id_uindex` (`id`),
                                 KEY `user_settings_user_id_fk` (`user_id`),
                                 CONSTRAINT `user_settings_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
                           `id` int(11) NOT NULL,
                           `user_id` int(11) NOT NULL,
                           `brand` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                           `model` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                           `year` int(11) NOT NULL,
                           `power_id` int(11) NOT NULL,
                           `engine_id` int(11) NOT NULL,
                           `status` smallint(6) NOT NULL DEFAULT '10',
                           `created_at` int(11) DEFAULT NULL,
                           `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `strip_discount`
--

CREATE TABLE `stripe_discount` (
                                   `id` int(11) NOT NULL AUTO_INCREMENT,
                                   `stripe_discount_token` varchar(255) DEFAULT NULL,
                                   `discount` varchar(255) DEFAULT NULL,
                                   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `stripe_discount_code`
--

CREATE TABLE `stripe_discount_code` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `discount_code` varchar(10) NOT NULL,
                                        `discount` varchar(255) DEFAULT NULL,
                                        `stripe_discount_id` int(11) DEFAULT NULL,
                                        `valid_from` timestamp NULL DEFAULT NULL,
                                        `valid_to` timestamp NULL DEFAULT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `stripe_discount_code_stripe_discount_id_fk` (`stripe_discount_id`),
                                        CONSTRAINT `stripe_discount_code_stripe_discount_id_fk` FOREIGN KEY (`stripe_discount_id`) REFERENCES `stripe_discount` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `stripe_plan`
--

CREATE TABLE `stripe_plan` (
                               `id` int(11) NOT NULL AUTO_INCREMENT,
                               `label` varchar(255) DEFAULT NULL,
                               `stripe_token` varchar(255) DEFAULT NULL,
                               `price` double DEFAULT NULL,
                               `currency` varchar(3) DEFAULT NULL,
                               `period` varchar(255) DEFAULT NULL,
                               `interval` int(11) DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `stripe_user`
--

CREATE TABLE `stripe_user` (
                               `id` int(11) NOT NULL AUTO_INCREMENT,
                               `user_id` int(11) DEFAULT NULL,
                               `stripe_user_token` varchar(255) DEFAULT NULL,
                               `stripe_card_token` varchar(255) DEFAULT NULL,
                               PRIMARY KEY (`id`),
                               UNIQUE KEY `user_to_stripe_id_uindex` (`id`),
                               KEY `user_to_stripe_user_id_fk` (`user_id`),
                               CONSTRAINT `user_to_stripe_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table structure for table `stripe_user_subscription`
--

CREATE TABLE `stripe_user_subscription` (
                                            `id` int(11) NOT NULL AUTO_INCREMENT,
                                            `stripe_discount_code_id` int(11) DEFAULT NULL,
                                            `stripe_plan_id` int(11) DEFAULT NULL,
                                            `stripe_user_id` int(11) DEFAULT NULL,
                                            `device_id` int(11) DEFAULT NULL,
                                            `stripe_subscription_token` varchar(255) DEFAULT NULL,
                                            PRIMARY KEY (`id`),
                                            KEY `stripe_user_subscription_stripe_plan_id_fk` (`stripe_plan_id`),
                                            KEY `stripe_user_subscription_stripe_user_id_fk` (`stripe_user_id`),
                                            KEY `stripe_user_subscription_device_id_fk` (`device_id`),
                                            KEY `stripe_user_subscription_stripe_discount_code_id_fk` (`stripe_discount_code_id`),
                                            CONSTRAINT `stripe_user_subscription_device_id_fk` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`),
                                            CONSTRAINT `stripe_user_subscription_stripe_discount_code_id_fk` FOREIGN KEY (`stripe_discount_code_id`) REFERENCES `stripe_discount_code` (`id`),
                                            CONSTRAINT `stripe_user_subscription_stripe_plan_id_fk` FOREIGN KEY (`stripe_plan_id`) REFERENCES `stripe_plan` (`id`),
                                            CONSTRAINT `stripe_user_subscription_stripe_user_id_fk` FOREIGN KEY (`stripe_user_id`) REFERENCES `stripe_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `label` varchar(255) DEFAULT NULL,
                          `time_before_warning` varchar(255) DEFAULT NULL,
                          `route_trigger` varchar(255) DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          UNIQUE KEY `events_id_uindex` (`id`),
                          UNIQUE KEY `events_label_uindex` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `user_events`
--

CREATE TABLE `user_event` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `user_id` int(11) DEFAULT NULL,
                              `event_id` int(11) DEFAULT NULL,
                              `event_time` int(11) DEFAULT NULL,
                              PRIMARY KEY (`id`),
                              UNIQUE KEY `user_event_id_uindex` (`id`),
                              KEY `user_event_events_id_fk` (`event_id`),
                              KEY `user_event_user_id_fk` (`user_id`),
                              CONSTRAINT `user_event_events_id_fk` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
                              CONSTRAINT `user_event_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Dumping data for table `stripe_plan`
--

INSERT INTO `stripe_plan` (`id`, `label`, `stripe_token`, `price`, `currency`, `period`, `interval`) VALUES
(1, 'Abonnement mensuel', 'plan_G5KeYIK2NktlQ3', 20, 'EUR', 'mois', 1),
(2, 'Abonnement annuel', 'plan_G5KeybXSQr68kn', 10, 'EUR', 'an', 1);

--
-- Dumping data for table `stripe_discount`
--

INSERT INTO `stripe_discount` (`id`, `stripe_discount_token`, `discount`) VALUES
(8, '4HCddeDX', '50%'),
(9, 'agn9EEhK', '20');

--
-- Dumping data for table `stripe_discount_code`
--

INSERT INTO `stripe_discount_code` (`id`, `discount_code`, `discount`, `stripe_discount_id`, `valid_from`, `valid_to`) VALUES
(1, 'twentyoff', '20', 9, '2019-01-18 20:05:11', '2021-11-01 06:25:27'),
(2, 'half', '50%', 8, '2019-01-18 20:05:11', '2021-11-01 06:25:27');


--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
    ADD PRIMARY KEY (`id`),
    ADD KEY `userId` (`user_id`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `imei` (`imei`) USING BTREE,
    ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
    ADD UNIQUE KEY `verification_token` (`verification_token`),
    ADD KEY `userId` (`user_id`) USING BTREE,
    ADD KEY `defaultVehicleId` (`default_vehicle_id`);

--
-- Indexes for table `engine`
--
ALTER TABLE `engine`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
    ADD PRIMARY KEY (`version`);

--
-- Indexes for table `power`
--
ALTER TABLE `power`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raw_coordinates`
--
ALTER TABLE `raw_coordinates`
    ADD PRIMARY KEY (`id`),
    ADD KEY `addressIdRawCoordinates` (`address_id`);

--
-- Indexes for table `rtTracking`
--
ALTER TABLE `rtTracking`
    ADD PRIMARY KEY (`id`),
    ADD KEY `device_id` (`device_id`) USING BTREE;

--
-- Indexes for table `scaleIK`
--
ALTER TABLE `scaleIK`
    ADD PRIMARY KEY (`id`),
    ADD KEY `power_id` (`power_id`) USING BTREE;

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
    ADD PRIMARY KEY (`id`),
    ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `tag2trip`
--
ALTER TABLE `tag2trip`
    ADD KEY `tag_id` (`tag_id`) USING BTREE,
    ADD KEY `trip_id` (`trip_id`) USING BTREE;

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
    ADD PRIMARY KEY (`id`),
    ADD KEY `user_id` (`user_id`) USING BTREE,
    ADD KEY `vehicle_id` (`vehicle_id`) USING BTREE,
    ADD KEY `device_id` (`device_id`) USING BTREE,
    ADD KEY `startCoordinateId` (`start_coordinate_id`),
    ADD KEY `stopCoordinateId` (`stop_coordinate_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `email` (`email`),
    ADD UNIQUE KEY `username` (`username`),
    ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
    ADD PRIMARY KEY (`id`),
    ADD KEY `userId` (`user_id`),
    ADD KEY `powerId` (`power_id`),
    ADD KEY `engineId` (`engine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `engine`
--
ALTER TABLE `engine`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `power`
--
ALTER TABLE `power`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `raw_coordinates`
--
ALTER TABLE `raw_coordinates`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rtTracking`
--
ALTER TABLE `rtTracking`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scaleIK`
--
ALTER TABLE `scaleIK`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
    ADD CONSTRAINT `userIdAddress` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `device`
--
ALTER TABLE `device`
    ADD CONSTRAINT `defaultVehicleId` FOREIGN KEY (`default_vehicle_id`) REFERENCES `vehicle` (`id`),
    ADD CONSTRAINT `userIdDevice` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `raw_coordinates`
--
ALTER TABLE `raw_coordinates`
    ADD CONSTRAINT `addressIdRawCoordinates` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

--
-- Constraints for table `rtTracking`
--
ALTER TABLE `rtTracking`
    ADD CONSTRAINT `deviceIdRtTracking` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`);

--
-- Constraints for table `scaleIK`
--
ALTER TABLE `scaleIK`
    ADD CONSTRAINT `powerIdScaleIk` FOREIGN KEY (`power_id`) REFERENCES `power` (`id`);

--
-- Constraints for table `tag`
--
ALTER TABLE `tag`
    ADD CONSTRAINT `userIdTag` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `tag2trip`
--
ALTER TABLE `tag2trip`
    ADD CONSTRAINT `tagIdTag2Trip` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`),
    ADD CONSTRAINT `tripId` FOREIGN KEY (`trip_id`) REFERENCES `trip` (`id`);

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
    ADD CONSTRAINT `deviceIdTrip` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`),
    ADD CONSTRAINT `startCoordinateId` FOREIGN KEY (`start_coordinate_id`) REFERENCES `raw_coordinates` (`id`),
    ADD CONSTRAINT `stopCoordinateId` FOREIGN KEY (`stop_coordinate_id`) REFERENCES `raw_coordinates` (`id`),
    ADD CONSTRAINT `userIdTrip` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
    ADD CONSTRAINT `vehicleIdTrip` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
    ADD CONSTRAINT `engineIdVehicle` FOREIGN KEY (`engine_id`) REFERENCES `engine` (`id`),
    ADD CONSTRAINT `powerIdVehicle` FOREIGN KEY (`power_id`) REFERENCES `power` (`id`),
    ADD CONSTRAINT `userIdVehicle` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
