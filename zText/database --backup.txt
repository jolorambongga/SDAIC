    CREATE DATABASE IF NOT EXISTS SDAIC3;

    USE SDAIC3;

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
        FOREIGN KEY (role_id) REFERENCES tbl_Roles(role_id)
    );

    CREATE TABLE IF NOT EXISTS tbl_Services (
        service_id INT AUTO_INCREMENT PRIMARY KEY,
        doctor_id INT NOT NULL,
        service_name VARCHAR(100) NOT NULL,
        description TEXT,
        cost DECIMAL(10, 2) NOT NULL,
        duration INT NOT NULL,
        FOREIGN KEY (doctor_id) REFERENCES tbl_Doctors(doctor_id)
    );

    CREATE TABLE IF NOT EXISTS tbl_ServiceAvailability (
        service_avail_id INT AUTO_INCREMENT PRIMARY KEY,
        service_id INT NOT NULL,
        avail_date VARCHAR(20) NOT NULL,
        avail_start_time TIME NOT NULL,
        avail_end_time TIME NOT NULL,
        FOREIGN KEY (service_id) REFERENCES tbl_Services(service_id)
    );

    CREATE TABLE IF NOT EXISTS tbl_DoctorAvailability (
        doctor_avail_id INT AUTO_INCREMENT PRIMARY KEY,
        doctor_id INT NOT NULL,
        avail_date VARCHAR(20) NOT NULL,
        avail_start_time TIME NOT NULL,
        avail_end_time TIME NOT NULL,
        FOREIGN KEY (doctor_id) REFERENCES tbl_Doctors(doctor_id)
    );

    CREATE TABLE IF NOT EXISTS tbl_Notifications (
        notification_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES tbl_Users(user_id)
    );

    CREATE TABLE IF NOT EXISTS tbl_Logs (
        log_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        category VARCHAR(100) NOT NULL,
        action VARCHAR(100) NOT NULL,
        details TEXT,
        device VARCHAR(100),
        browser VARCHAR(100),
        time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES tbl_Users(user_id)
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
        FOREIGN KEY (user_id) REFERENCES tbl_Users(user_id),
        FOREIGN KEY (service_id) REFERENCES tbl_Services(service_id)
    );

    CREATE TABLE IF NOT EXISTS tbl_AppointmentHistory (
        appointment_history_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        appointment_id INT NOT NULL,
        time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES tbl_Users(user_id),
        FOREIGN KEY (appointment_id) REFERENCES tbl_Appointments(appointment_id)
    );