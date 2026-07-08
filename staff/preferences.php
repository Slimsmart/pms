<?php
session_start();
if (!isset($_SESSION['stapass'])) {
    header("Location: logout.php");
    exit;
}

include '../db.php';
$staff_id = $_SESSION['stapass'];

// Fetch staff details
$q_staff = $db->prepare("select name from users where username = ?");
$q_staff->bind_param('s', $staff_id);
$q_staff->execute();
$q_staff->bind_result($staff_name);
$q_staff->fetch();
$q_staff->close();

$message = "";
$msg_class = "";

// Handle Form Submission
if (isset($_POST['submit_prefs'])) {
    $ranks = isset($_POST['ranks']) ? $_POST['ranks'] : []; // [student_username => rank]
    
    // Filter out unranked (empty) ones
    $active_ranks = [];
    foreach ($ranks as $student_id => $rank_val) {
        if (!empty($rank_val)) {
            $active_ranks[$student_id] = intval($rank_val);
        }
    }

    // Validate that we don't have duplicate ranks
    $rank_values = array_values($active_ranks);
    if (count($rank_values) !== count(array_unique($rank_values))) {
        $message = "Error: You cannot assign the same rank to multiple students.";
        $msg_class = "alert-danger";
    } else {
        // Clear existing preferences
        $q_del = $db->prepare("delete from staff_preferences where staff_username = ?");
        $q_del->bind_param('s', $staff_id);
        $q_del->execute();
        $q_del->close();

        // Insert new preferences
        $q_ins = $db->prepare("insert into staff_preferences (staff_username, student_username, preference_rank) values (?, ?, ?)");
        $success = true;
        foreach ($active_ranks as $student_id => $rank_val) {
            $q_ins->bind_param('ssi', $staff_id, $student_id, $rank_val);
            if (!$q_ins->execute()) {
                $success = false;
            }
        }
        $q_ins->close();

        if ($success) {
            $message = "Preferences updated successfully!";
            $msg_class = "alert-success";
        } else {
            $message = "An error occurred while saving your preferences.";
            $msg_class = "alert-danger";
        }
    }
}

// Fetch all students and join with their projects if they have any
$students = [];
$q_std = $db->query("
    SELECT u.username, u.name, p.topic, 
           (SELECT sp.preference_rank FROM student_preferences sp WHERE sp.student_username = u.username AND sp.staff_username = '$staff_id') as student_rank
    FROM users u
    LEFT JOIN projects p ON u.username = p.username
    WHERE u.staff = '0'
    ORDER BY u.name ASC
");
while ($row = $q_std->fetch_assoc()) {
    $students[] = $row;
}
$q_std->close();

// Fetch current preferences
$current_prefs = [];
$q_curr = $db->prepare("select student_username, preference_rank from staff_preferences where staff_username = ?");
$q_curr->bind_param('s', $staff_id);
$q_curr->execute();
$q_curr->bind_result($student_username, $rank);
while ($q_curr->fetch()) {
    $current_prefs[$student_username] = $rank;
}
$q_curr->close();
$db->close();
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Supervisor Preferences - PMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/font-awesome.css">
    <link href="../color/default.css" rel="stylesheet">
    <link rel="stylesheet" href="/pms/css/modern-theme.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .spacer {
            padding-top: 80px;
            padding-bottom: 40px;
        }
    </style>
</head>
<body>
    <!-- navbar -->
    <div class="navbar-wrapper">
        <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <div class="navbar-header">
                    <h3 class="navbar-brand fw-bold"><a href="index.php">Project Management System</a></h3>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul id="menu-main" class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php"><span class="fa fa-home"></span> Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="preferences.php"><span class="fa fa-list"></span> Preferences</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><span class="fa fa-lock"></span> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="spacer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="glass-card p-4">
                        <h3 class="mb-3 fw-bold"><i class="fa fa-users text-primary"></i> Student Allocation Preference Rankings</h3>
                        <p class="text-muted">Rank students who you want to supervise. Students who ranked you are highlighted with their choice rank. Unranked students will be matches of lower priority during stable allocation.</p>

                        <?php if (!empty($message)): ?>
                            <div class="alert <?php echo $msg_class; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="preferences.php">
                            <div class="table-responsive mt-4">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Project Topic / Research Area</th>
                                            <th>Student's Rank For You</th>
                                            <th style="width: 200px;">Your Preference Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $std): ?>
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold"><?php echo htmlspecialchars($std['name']); ?></span><br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($std['username']); ?></small>
                                                </td>
                                                <td>
                                                    <span style="font-size: 0.9rem;"><?php echo !empty($std['topic']) ? htmlspecialchars($std['topic']) : '<span class="text-muted italic">No topic submitted</span>'; ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($std['student_rank']): ?>
                                                        <span class="badge bg-info text-dark">Choice #<?php echo $std['student_rank']; ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" name="ranks[<?php echo htmlspecialchars($std['username']); ?>]">
                                                        <option value="">-- Unranked --</option>
                                                        <?php for ($r = 1; $r <= 10; $r++): ?>
                                                            <option value="<?php echo $r; ?>" <?php echo (isset($current_prefs[$std['username']]) && $current_prefs[$std['username']] == $r) ? 'selected' : ''; ?>>
                                                                Rank <?php echo $r; ?>
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" name="submit_prefs" class="btn btn-primary px-4 py-2"><i class="fa fa-save"></i> Save Rankings</button>
                                <a href="index.php" class="btn btn-light px-4 py-2 ms-2">Back to Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container text-center">
            <p class="copyright">&copy; <?php echo date('Y'); ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
