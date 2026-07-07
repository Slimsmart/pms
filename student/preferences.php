<?php
session_start();
if (!isset($_SESSION['stupass'])) {
    header("Location: ../login.php");
    exit;
}

include '../db.php';
$student_id = $_SESSION['stupass'];

// Fetch student details
$q_std = $db->prepare("select name from users where username = ?");
$q_std->bind_param('s', $student_id);
$q_std->execute();
$q_std->bind_result($student_name);
$q_std->fetch();
$q_std->close();

// Handle Form Submission
$message = "";
$msg_class = "";

if (isset($_POST['submit_prefs'])) {
    $choices = [
        1 => isset($_POST['pref_1']) ? $_POST['pref_1'] : '',
        2 => isset($_POST['pref_2']) ? $_POST['pref_2'] : '',
        3 => isset($_POST['pref_3']) ? $_POST['pref_3'] : ''
    ];

    // Validate that we don't have duplicate choices
    $valid_choices = array_filter($choices);
    if (count($valid_choices) !== count(array_unique($valid_choices))) {
        $message = "Error: You cannot select the same supervisor more than once.";
        $msg_class = "alert-danger";
    } else {
        // Clear existing preferences
        $q_del = $db->prepare("delete from student_preferences where student_username = ?");
        $q_del->bind_param('s', $student_id);
        $q_del->execute();
        $q_del->close();

        // Insert new preferences
        $q_ins = $db->prepare("insert into student_preferences (student_username, staff_username, preference_rank) values (?, ?, ?)");
        $success = true;
        foreach ($choices as $rank => $staff) {
            if (!empty($staff)) {
                $q_ins->bind_param('ssi', $student_id, $staff, $rank);
                if (!$q_ins->execute()) {
                    $success = false;
                }
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

// Fetch all staff/supervisors
$supervisors = [];
$q_sup = $db->query("select username, name from users where staff = 1 order by name asc");
while ($row = $q_sup->fetch_assoc()) {
    $supervisors[] = $row;
}
$q_sup->close();

// Fetch current preferences
$current_prefs = [];
$q_curr = $db->prepare("select staff_username, preference_rank from student_preferences where student_username = ? order by preference_rank asc");
$q_curr->bind_param('s', $student_id);
$q_curr->execute();
$q_curr->bind_result($staff_username, $rank);
while ($q_curr->fetch()) {
    $current_prefs[$rank] = $staff_username;
}
$q_curr->close();
$db->close();
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Preferences - PMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css -->
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
                <div class="col-md-8">
                    <div class="glass-card p-4">
                        <h3 class="mb-4 fw-bold text-center"><i class="fa fa-list-ol text-primary"></i> Rank Your Supervisor Preferences</h3>
                        <p class="text-muted text-center">Rank up to 3 preferred project supervisors. These rankings will be used in the automated Gale-Shapley Stable Matching process to match you with a supervisor.</p>
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert <?php echo $msg_class; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="preferences.php" class="mt-4">
                            <!-- Preference 1 -->
                            <div class="mb-4">
                                <label for="pref_1" class="form-label fw-semibold"><span class="badge bg-primary me-2">1</span> First Choice (Highest)</label>
                                <select class="form-select" name="pref_1" id="pref_1" required>
                                    <option value="">-- Select Supervisor --</option>
                                    <?php foreach ($supervisors as $sup): ?>
                                        <option value="<?php echo htmlspecialchars($sup['username']); ?>" <?php echo (isset($current_prefs[1]) && $current_prefs[1] == $sup['username']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sup['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Preference 2 -->
                            <div class="mb-4">
                                <label for="pref_2" class="form-label fw-semibold"><span class="badge bg-secondary me-2">2</span> Second Choice</label>
                                <select class="form-select" name="pref_2" id="pref_2">
                                    <option value="">-- Select Supervisor (Optional) --</option>
                                    <?php foreach ($supervisors as $sup): ?>
                                        <option value="<?php echo htmlspecialchars($sup['username']); ?>" <?php echo (isset($current_prefs[2]) && $current_prefs[2] == $sup['username']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sup['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Preference 3 -->
                            <div class="mb-4">
                                <label for="pref_3" class="form-label fw-semibold"><span class="badge bg-secondary me-2">3</span> Third Choice</label>
                                <select class="form-select" name="pref_3" id="pref_3">
                                    <option value="">-- Select Supervisor (Optional) --</option>
                                    <?php foreach ($supervisors as $sup): ?>
                                        <option value="<?php echo htmlspecialchars($sup['username']); ?>" <?php echo (isset($current_prefs[3]) && $current_prefs[3] == $sup['username']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sup['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="text-center mt-5">
                                <button type="submit" name="submit_prefs" class="btn btn-primary px-4 py-2"><i class="fa fa-save"></i> Save Preferences</button>
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
