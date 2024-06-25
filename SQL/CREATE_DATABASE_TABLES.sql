CREATE DATABASE IF NOT EXISTS SDAIC;

USE SDAIC;

CREATE TABLE IF NOT EXISTS tbl_Roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS tbl_Doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    contact VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS tbl_Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT DEFAULT 2,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    user_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    change_password INT DEFAULT 0,
    FOREIGN KEY (role_id) REFERENCES tbl_Roles(role_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tbl_Services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT,
    service_name VARCHAR(100) NOT NULL,
    description TEXT,
    duration INT NOT NULL,
    max INT NOT NULL,
    cost DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES tbl_Doctors(doctor_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tbl_ServiceSched (
    service_sched_id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    day_of_week ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (service_id) REFERENCES tbl_Services(service_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tbl_DoctorSched (
    doctor_sched_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    day_of_week ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (doctor_id) REFERENCES tbl_Doctors(doctor_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tbl_Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES tbl_Users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tbl_Logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category VARCHAR(100) NOT NULL,
    action VARCHAR(100) NOT NULL,
    affected_data TEXT NOT NULL, 
    device VARCHAR(100) NOT NULL,
    device_model VARCHAR(255) NOT NULL,
    browser VARCHAR(100) NOT NULL,
    latitude DECIMAL(10, 6) DEFAULT NULL,
    longitude DECIMAL(10, 6) DEFAULT NULL,
    location VARCHAR(255) DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    organization VARCHAR(50) DEFAULT NULL,
    time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tbl_Users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tbl_Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    request_image LONGBLOB,
    notes TEXT,
    status VARCHAR(50) DEFAULT 'PENDING',
    completed VARCHAR(50) DEFAULT 'NO',
    time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tbl_Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES tbl_Services(service_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tbl_AppointmentHistory (
    appointment_history_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    appointment_id INT NOT NULL,
    time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tbl_Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES tbl_Appointments(appointment_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tbl_Landing (
    landing_id INT AUTO_INCREMENT PRIMARY KEY,
    about_us TEXT,
    about_us_image LONGBLOB,
    main_image LONGBLOB
);

-- CREATE TABLE IF NOT EXISTS tbl_ClinicHours (
--     clinic_hours_id INT AUTO_INCREMENT PRIMARY KEY,
--     landing_id INT,
--     avail_day VARCHAR(20) NOT NULL,
--     avail_start_time TIME NOT NULL,
--     avail_end_time TIME NOT NULL,
--     FOREIGN KEY (landing_id) REFERENCES tbl_Landing(landing_id) ON DELETE CASCADE
-- );

-- -- Assuming landing_id of 1 was automatically assigned due to AUTO_INCREMENT

-- -- Insert dummy data into tbl_ClinicHours referencing tbl_Landing
-- INSERT INTO tbl_ClinicHours (landing_id, avail_day, avail_start_time, avail_end_time)
-- VALUES
-- (1, 'Monday', '08:00:00', '17:00:00'),
-- (1, 'Tuesday', '08:00:00', '17:00:00'),
-- (1, 'Wednesday', '08:00:00', '17:00:00'),
-- (1, 'Thursday', '08:00:00', '17:00:00'),
-- (1, 'Friday', '08:00:00', '17:00:00');

-- Insert dummy data into tbl_Landing
INSERT INTO tbl_Landing (about_us, about_us_image, main_image)
VALUES ('Welcome to our clinic!', 'https://wallpaperaccess.com/full/1282798.jpg', 'https://wallpaperaccess.com/full/1282798.jpg');

INSERT INTO `tbl_Roles` (`role_id`, `role_name`) VALUES ('1', 'admin'), ('2', 'user')