-- Add capacity column to users table for staff (supervisors)
ALTER TABLE users ADD COLUMN capacity INT DEFAULT 5;

-- Create student_preferences table
CREATE TABLE student_preferences (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_username VARCHAR(100) NOT NULL,
  staff_username VARCHAR(100) NOT NULL,
  preference_rank INT NOT NULL,
  UNIQUE KEY unique_student_staff (student_username, staff_username),
  FOREIGN KEY (student_username) REFERENCES users(username) ON DELETE CASCADE,
  FOREIGN KEY (staff_username) REFERENCES users(username) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create staff_preferences table
CREATE TABLE staff_preferences (
  id INT AUTO_INCREMENT PRIMARY KEY,
  staff_username VARCHAR(100) NOT NULL,
  student_username VARCHAR(100) NOT NULL,
  preference_rank INT NOT NULL,
  UNIQUE KEY unique_staff_student (staff_username, student_username),
  FOREIGN KEY (staff_username) REFERENCES users(username) ON DELETE CASCADE,
  FOREIGN KEY (student_username) REFERENCES users(username) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
