<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) { echo json_encode(['success'=>false,'error'=>'Invalid input']); exit; }

$mentorship_id = $body['mentorship_id'] ?? null;
$status = $body['status'] ?? null;

// simple validation
$allowed = ['active','completed','terminated'];
if (!$mentorship_id || !in_array($status, $allowed)) {
    echo json_encode(['success'=>false,'error'=>'Invalid data']); exit;
}

// optional: ensure current user is the mentor for this mentorship
$pi_register = $_SESSION['pi_register'] ?? null;
if (!$pi_register) { echo json_encode(['success'=>false,'error'=>'Not logged in']); exit; }

// verify the mentorship belongs to this mentor
$check = $conn->prepare("SELECT mentor_pi_register FROM mentorship WHERE mentorship_id = ?");
$check->bind_param('i', $mentorship_id);
$check->execute();
$res = $check->get_result();
$row = $res->fetch_assoc();
if (!$row) { echo json_encode(['success'=>false,'error'=>'Not found']); exit; }
if ($row['mentor_pi_register'] !== $pi_register) {
    echo json_encode(['success'=>false,'error'=>'Permission denied']); exit;
}

// update
$upd = $conn->prepare("UPDATE mentorship SET status = ? WHERE mentorship_id = ?");
$upd->bind_param('si', $status, $mentorship_id);
if ($upd->execute()) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false,'error'=>'DB error: '.$conn->error]);
}
