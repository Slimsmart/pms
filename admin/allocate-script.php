<?php
session_start();
if (!isset($_SESSION['pass'])) {
    header("Location: logout.php");
    exit;
}

include '../db.php';

// 1. Manual Allocation
if (isset($_POST['allocate-form'])) {
    $staff = $_POST['staff'];
    $q = $db->prepare("insert into allocation (staff, Student) values (?, ?)");
    foreach ($_POST as $key => $value) {
        if (($key == "staff") || ($key == "allocate-form")) {
            continue;
        }
        $q->bind_param('ss', $staff, $value);
        $q->execute();
    }
    $q->close();
    $db->close();
    header("Location: allocate.php?msg=allocated");
    exit;
}

// 2. Update Capacities
if (isset($_POST['update-capacities'])) {
    $capacities = isset($_POST['capacities']) ? $_POST['capacities'] : [];
    $q = $db->prepare("update users set capacity = ? where username = ? and staff = 1");
    foreach ($capacities as $username => $capacity) {
        $cap_val = intval($capacity);
        $q->bind_param('is', $cap_val, $username);
        $q->execute();
    }
    $q->close();
    $db->close();
    header("Location: allocate.php?msg=capacities_updated");
    exit;
}

// 3. Run Gale-Shapley Auto-Allocation
if (isset($_POST['run-gale-shapley'])) {
    // Include the matching engine
    include '../allocation_engine.php';

    // Fetch all students (usernames)
    $students = [];
    $q_std = $db->query("select username from users where staff = 0");
    while ($row = $q_std->fetch_assoc()) {
        $students[] = $row['username'];
    }
    $q_std->close();

    // Fetch all staff and their capacities
    $capacities = [];
    $q_stf = $db->query("select username, capacity from users where staff = 1");
    while ($row = $q_stf->fetch_assoc()) {
        $capacities[$row['username']] = intval($row['capacity']);
    }
    $q_stf->close();

    // Fetch student preferences
    $studentPrefs = [];
    $q_sp = $db->query("select student_username, staff_username from student_preferences order by student_username, preference_rank asc");
    while ($row = $q_sp->fetch_assoc()) {
        $studentPrefs[$row['student_username']][] = $row['staff_username'];
    }
    $q_sp->close();

    // Fetch staff preferences
    $staffPrefs = [];
    $q_stp = $db->query("select staff_username, student_username from staff_preferences order by staff_username, preference_rank asc");
    while ($row = $q_stp->fetch_assoc()) {
        $staffPrefs[$row['staff_username']][] = $row['student_username'];
    }
    $q_stp->close();

    // Run Gale-Shapley algorithm
    $matching = GaleShapley::match($students, $studentPrefs, $staffPrefs, $capacities);

    // Clear existing allocations
    $db->query("delete from allocation");

    // Insert new matching results
    $q_ins = $db->prepare("insert into allocation (staff, Student) values (?, ?)");
    foreach ($matching as $student => $staff) {
        $q_ins->bind_param('ss', $staff, $student);
        $q_ins->execute();
    }
    $q_ins->close();
    $db->close();

    header("Location: allocate.php?msg=gale_shapley_run");
    exit;
}

header("Location: index.php");
exit;
?>
