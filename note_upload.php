<?php
include 'db.php';

// Handle form submission for uploading notes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $file = $_FILES['note'];

    if ($file['error'] == 0) {
        $upload_dir = "uploads/notes/";

        // Create the folder if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . "_" . $file['name'];
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO notes (file_name, subject, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $file['name'], $subject, $file_path);
            $stmt->execute();
            echo "<p class='success'>✅ Note uploaded successfully!</p>";
        } else {
            echo "<p class='error'>❌ Failed to upload the file.</p>";
        }
    } else {
        echo "<p class='error'>❌ Error uploading file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Upload Student Notes</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px #ccc;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #3498db;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input, select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            background: #28a745;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #218838;
        }

        .success {
            color: #28a745;
            font-weight: bold;
            text-align: center;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        .back-link:hover {
            color: #2980b9;
        }

    </style>
</head>
<body>

<div class="container">
    <h1>📄 Upload Student Notes</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>Subject:</label>
        <input type="text" name="subject" placeholder="Enter Subject Name" required>

        <label>Upload Note (DOC/DOCX/PDF/PPT/PPTX/TXT/MP4/MPEG/MOV):</label>
        <input type="file" name="note" accept=".doc, .docx, .pdf, .ppt, .pptx, .txt, .mp4, .mpeg, .mov" required>

        <input type="submit" value="Upload Note">
    </form>

    <a href="index.php" class="back-link">⬅️ Back to Home</a>
</div>

</body>
<div class="footer">
    &copy; 2025 Student Note Bank | Powered by PHP & MySQL
</div>
</html>
