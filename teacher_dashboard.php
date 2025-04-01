<?php
session_start();
include 'db.php';

// Ensure teacher is logged in
if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

// Handle file upload for notes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['note']) && !isset($_SESSION['upload_done'])) {
    $subject = $_POST['subject'];
    $file = $_FILES['note'];

    if ($file['error'] == 0) {
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['doc', 'docx', 'pdf', 'ppt', 'pptx', 'txt', 'mp4', 'mpeg', 'mov', 'url'];

        if (in_array($file_ext, $allowed_ext)) {
            $upload_dir = "uploads/notes/";

            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_path = $upload_dir . uniqid() . "_" . $file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $stmt = $conn->prepare("INSERT INTO notes (file_name, subject, file_path) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $file_name, $subject, $file_path);
                $stmt->execute();

                // ✅ Redirect after upload to prevent duplicate entry
                $_SESSION['upload_done'] = true;
                header("Location: teacher_dashboard.php");
                exit();
            } else {
                echo "Failed to upload note.";
            }
        } else {
            echo "Invalid file type. Allowed: DOC, DOCX, PDF, PPT, PPTX, TXT, MP4, MPEG, MOV, URL.";
        }
    } else {
        echo "Error uploading note.";
    }
}

// Handle file upload for question papers
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['question_paper']) && !isset($_SESSION['upload_done'])) {
    $subject = $_POST['subject'];
    $year = $_POST['year'];
    $file = $_FILES['question_paper'];

    if ($file['error'] == 0) {
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['doc', 'docx', 'pdf', 'ppt', 'pptx', 'txt', 'mp4', 'mpeg', 'mov'];

        if (in_array($file_ext, $allowed_ext)) {
            $upload_dir = "uploads/question_papers/";

            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_path = $upload_dir . uniqid() . "_" . $file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $stmt = $conn->prepare("INSERT INTO question_papers (file_name, subject, year, file_path) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $file_name, $subject, $year, $file_path);
                $stmt->execute();

                // ✅ Redirect after upload to prevent duplicate entry
                $_SESSION['upload_done'] = true;
                header("Location: teacher_dashboard.php");
                exit();
            } else {
                echo "Failed to upload question paper.";
            }
        } else {
            echo "Invalid file type. Allowed: DOC, DOCX, PDF, PPT, PPTX, TXT, MP4, MPEG, MOV.";
        }
    } else {
        echo "Error uploading question paper.";
    }
}

// Clear upload session flag after reload
unset($_SESSION['upload_done']);

// Fetch all notes and question papers
$notes = $conn->query("SELECT * FROM notes");
$papers = $conn->query("SELECT * FROM question_papers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="index.php" class="logout-btn">Logout</a>
</div>

<div class="container">
    <h1>👩‍🏫 Teacher Dashboard</h1>

    <!-- Notes Section -->
    <h2>📥 Upload Notes</h2>
    <form action="teacher_dashboard.php" method="post" enctype="multipart/form-data">
        <input type="text" name="subject" placeholder="Enter Subject" required>
        <input type="file" name="note" required>
        <input type="submit" value="Upload Note">
    </form>

    <!-- Display Notes -->
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>File Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $notes->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars($row['file_name']) ?></td>
                <td>
                    <a href="<?= $row['file_path'] ?>" download>Download</a> |
                    <a href="delete_file.php?id=<?= $row['id'] ?>&type=note" onclick="return confirm('Are you sure you want to delete this file?')" class="delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Question Papers Section -->
    <h2>📥 Upload Question Papers</h2>
    <form action="teacher_dashboard.php" method="post" enctype="multipart/form-data">
        <input type="text" name="subject" placeholder="Enter Subject" required>
        <input type="text" name="year" placeholder="Enter Year" required>
        <input type="file" name="question_paper" required>
        <input type="submit" value="Upload Question Paper">
    </form>

    <!-- Display Question Papers -->
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Year</th>
                <th>File Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $papers->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars($row['year']) ?></td>
                <td><?= htmlspecialchars($row['file_name']) ?></td>
                <td>
                    <a href="<?= $row['file_path'] ?>" download>Download</a> |
                    <a href="delete_file.php?id=<?= $row['id'] ?>&type=paper" onclick="return confirm('Are you sure you want to delete this file?')" class="delete-btn">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Footer -->
<div class="footer">
    &copy; 2025 Student Note Bank | Powered by PHP & MySQL
</div>

</body>
</html>
