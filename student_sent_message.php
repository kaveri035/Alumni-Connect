<?php
include_once 'db_config.php';
session_start();

// ✅ Check if student is logged in
if (!isset($_SESSION['st_register'])) {
    header("Location: login.php");
    exit();
}

$st_register = $_SESSION['st_register'];

// ✅ Get sent messages by this student
$query = "
    SELECT 
        m.message_id,
        m.subject,
        m.message_body,
        m.sent_at,
        m.receiver_id,
        ai.pi_name AS alumni_name,
        am.ad_fullname AS admin_name,
        sm2.st_register AS student_receiver
    FROM messages m
    LEFT JOIN alumniinfo ai ON m.receiver_id = ai.pi_register
    LEFT JOIN adminmember am ON m.receiver_id = am.ad_id
    LEFT JOIN studentmember sm2 ON m.receiver_id = sm2.st_register
    WHERE m.sender_id = '$st_register'
    ORDER BY m.sent_at DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("❌ SQL Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sent Messages - Student</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #667eea;
            color: white;
        }
        tr:hover {
            background: #f1f3ff;
        }
        .no-msg {
            text-align: center;
            color: #888;
            font-size: 18px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <h1>📤 Sent Messages</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Receiver</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Sent At</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <?php 
                            if (!empty($row['alumni_name'])) echo "Alumni - " . htmlspecialchars($row['alumni_name']);
                            elseif (!empty($row['admin_name'])) echo "Admin - " . htmlspecialchars($row['admin_name']);
                            elseif (!empty($row['student_receiver'])) echo "Student - " . htmlspecialchars($row['student_receiver']);
                            else echo "Unknown Receiver";
                        ?>
                    </td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['message_body'])) ?></td>
                    <td><?= htmlspecialchars($row['sent_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-msg">You haven’t sent any messages yet.</p>
    <?php endif; ?>
</body>
</html>
