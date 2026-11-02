<?php
session_start();
require_once 'db.php';
$st_register = isset($_SESSION['st_register']) ? $_SESSION['st_register'] : null;
if (!$st_register) { echo "Student not logged in."; exit; }

$sql = "
  SELECT m.mentorship_id, m.status, m.start_date, m.end_date, m.notes,
         ai.pi_register, ai.pi_name, ai.pi_email, ai.pi_mobile, ai.pi_company, ai.pi_designation
  FROM mentorship m
  JOIN alumniinfo ai ON m.mentor_pi_register = ai.pi_register
  WHERE m.mentee_st_register = ?
  ORDER BY m.start_date DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $st_register);
$stmt->execute();
$res = $stmt->get_result();
$mentors = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Mentors</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 20px;
    }
    
    .container {
      max-width: 900px;
      margin: 0 auto;
    }
    
    h2 {
      color: white;
      font-size: 2.2em;
      margin-bottom: 30px;
      text-align: center;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .card {
      background: white;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1), 0 1px 3px rgba(0,0,0,0.08);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.15), 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .card strong {
      font-size: 1.3em;
      color: #2d3748;
      display: block;
      margin-bottom: 8px;
    }
    
    .meta {
      font-size: 0.95em;
      color: #718096;
      margin: 6px 0;
      line-height: 1.6;
    }
    
    .status {
      display: inline-block;
      padding: 2px 10px;
      border-radius: 12px;
      font-size: 0.85em;
      font-weight: 600;
      text-transform: capitalize;
      background: #e2e8f0;
      color: #4a5568;
    }
    
    .card[data-status="active"] .status {
      background: #c6f6d5;
      color: #22543d;
    }
    
    .card[data-status="completed"] .status {
      background: #bee3f8;
      color: #2c5282;
    }
    
    .card[data-status="pending"] .status {
      background: #feebc8;
      color: #7c2d12;
    }
    
    .button-group {
      margin-top: 16px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    
    button, .button-group a button {
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 0.9em;
      font-weight: 600;
      transition: all 0.2s ease;
      font-family: inherit;
    }
    
    .btn-details {
      background: #667eea;
      color: white;
    }
    
    .btn-details:hover {
      background: #5568d3;
      transform: scale(1.05);
    }
    
    .button-group a button {
      background: #48bb78;
      color: white;
    }
    
    .button-group a button:hover {
      background: #38a169;
      transform: scale(1.05);
    }
    
    .button-group a:last-child button {
      background: #ed8936;
    }
    
    .button-group a:last-child button:hover {
      background: #dd6b20;
    }
    
    .details {
      margin-top: 16px;
      padding: 16px;
      background: #f7fafc;
      border-radius: 8px;
      border-left: 4px solid #667eea;
      animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .details p {
      line-height: 1.8;
      color: #4a5568;
    }
    
    .details strong {
      color: #2d3748;
      font-size: 0.95em;
    }
    
    .empty-state {
      background: white;
      border-radius: 12px;
      padding: 60px 40px;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .empty-state p {
      color: #718096;
      font-size: 1.1em;
    }
    
    @media (max-width: 600px) {
      body {
        padding: 10px;
      }
      
      h2 {
        font-size: 1.6em;
        margin-bottom: 20px;
      }
      
      .card {
        padding: 16px;
      }
      
      .button-group {
        flex-direction: column;
      }
      
      button, .button-group a button {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>My Mentors</h2>
    <div id="list">
      <?php if (count($mentors) === 0): ?>
        <div class="empty-state">
          <p>No mentors assigned yet.</p>
        </div>
      <?php else: ?>
        <?php foreach($mentors as $m): ?>
          <div class="card" data-id="<?=htmlspecialchars($m['mentorship_id'])?>" data-status="<?=htmlspecialchars(strtolower($m['status']))?>">
            <strong><?=htmlspecialchars($m['pi_name'])?></strong>
            <div class="meta"><?=htmlspecialchars($m['pi_designation'])?> at <?=htmlspecialchars($m['pi_company'])?></div>
            <div class="meta">
              Status: <span class="status"><?=htmlspecialchars($m['status'])?></span>
              | Since: <?=htmlspecialchars($m['start_date']) ?: 'N/A'?>
            </div>
            <div class="button-group">
              <button class="btn-details" data-id="<?=htmlspecialchars($m['mentorship_id'])?>">Details</button>
              <a href="mailto:<?=htmlspecialchars($m['pi_email'])?>"><button>Email</button></a>
              <?php if (!empty($m['pi_mobile'])): ?>
                <a href="tel:<?=htmlspecialchars($m['pi_mobile'])?>"><button>Call</button></a>
              <?php endif; ?>
            </div>
            <div class="details" style="display:none;">
              <p>
                <strong>Email:</strong> <?=htmlspecialchars($m['pi_email'])?><br>
                <strong>Phone:</strong> <?=htmlspecialchars($m['pi_mobile'])?><br>
                <strong>Notes:</strong> <?=nl2br(htmlspecialchars($m['notes']))?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

<script>
document.querySelectorAll('.btn-details').forEach(btn=>{
  btn.addEventListener('click', e=>{
    const card = e.target.closest('.card');
    const details = card.querySelector('.details');
    details.style.display = (details.style.display === 'none') ? 'block' : 'none';
  });
});
</script>
</body>
</html>