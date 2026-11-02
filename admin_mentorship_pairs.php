<?php
session_start();
require_once 'db.php';
// (you can add admin auth guard here)

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$where = '';
$params = [];
$types = '';

if (in_array($status_filter, ['active','completed','terminated'])) {
    $where = "WHERE m.status = ?";
    $params[] = $status_filter; $types .= 's';
}

$sql = "
  SELECT m.mentorship_id, m.status, m.start_date, m.end_date, m.notes,
         ai.pi_register, ai.pi_name,
         si.st_register, si.st_name
  FROM mentorship m
  LEFT JOIN alumniinfo ai ON m.mentor_pi_register = ai.pi_register
  LEFT JOIN studentinfo si ON m.mentee_st_register = si.st_register
  $where
  ORDER BY m.start_date DESC
  LIMIT 200
";
$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mentorship Pairs (Admin)</title>
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
  max-width: 1000px;
  margin: 0 auto;
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.header {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  text-align: center;
  position: relative;
  overflow: hidden;
}

.header::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
  animation: shimmer 3s infinite;
}

@keyframes shimmer {
  0% { left: -100%; }
  100% { left: 100%; }
}

h2 {
  font-size: 2.5em;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: 700;
  position: relative;
  z-index: 1;
}

.controls {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 12px;
  animation: slideIn 0.5s ease-out 0.2s both;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.controls label {
  font-weight: 600;
  color: #333;
  font-size: 1.1em;
  display: flex;
  align-items: center;
  gap: 12px;
}

#filter {
  padding: 10px 16px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  background: white;
  font-size: 1em;
  cursor: pointer;
  transition: all 0.3s ease;
  outline: none;
  min-width: 150px;
}

#filter:hover {
  border-color: #667eea;
  box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
}

#filter:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.item {
  background: rgba(255, 255, 255, 0.95);
  border: 2px solid transparent;
  padding: 20px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
  animation: itemFadeIn 0.5s ease-out both;
}

.item:nth-child(1) { animation-delay: 0.1s; }
.item:nth-child(2) { animation-delay: 0.15s; }
.item:nth-child(3) { animation-delay: 0.2s; }
.item:nth-child(4) { animation-delay: 0.25s; }
.item:nth-child(5) { animation-delay: 0.3s; }

@keyframes itemFadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  transform: scaleY(0);
  transition: transform 0.3s ease;
}

.item:hover {
  transform: translateX(8px);
  border-color: #667eea;
  box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
}

.item:hover::before {
  transform: scaleY(1);
}

.item.expanded {
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
  border-color: #667eea;
}

.item strong {
  color: #667eea;
  font-size: 1.1em;
  font-weight: 600;
}

.arrow {
  display: inline-block;
  margin: 0 10px;
  color: #764ba2;
  font-weight: bold;
  font-size: 1.2em;
}

.meta {
  margin-top: 12px;
  color: #666;
  font-size: 0.95em;
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.badge {
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.85em;
  font-weight: 600;
  text-transform: capitalize;
  display: inline-block;
  transition: all 0.3s ease;
}

.badge[data-status="active"] {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.badge[data-status="completed"] {
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: white;
  box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.badge[data-status="terminated"] {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.hidden-details {
  margin-top: 16px;
  padding-top: 16px;
  border-top: 2px solid #f0f0f0;
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    max-height: 0;
  }
  to {
    opacity: 1;
    max-height: 300px;
  }
}

.hidden-details p {
  color: #555;
  line-height: 1.6;
  margin-bottom: 12px;
}

.btn-view {
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  margin-top: 8px;
}

.btn-view:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.btn-view:active {
  transform: translateY(0);
}

.empty-state {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 12px;
  padding: 60px 20px;
  text-align: center;
  color: #666;
  font-size: 1.2em;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.empty-state::before {
  content: '🤝';
  display: block;
  font-size: 4em;
  margin-bottom: 16px;
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.expand-icon {
  float: right;
  color: #667eea;
  font-size: 1.2em;
  transition: transform 0.3s ease;
}

.item.expanded .expand-icon {
  transform: rotate(180deg);
}

@media (max-width: 768px) {
  body {
    padding: 12px;
  }
  
  h2 {
    font-size: 1.8em;
  }
  
  .header {
    padding: 24px 16px;
  }
  
  .controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .controls label {
    flex-direction: column;
    align-items: stretch;
    gap: 8px;
  }
  
  #filter {
    width: 100%;
  }
  
  .item {
    padding: 16px;
  }
  
  .meta {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
}

.stats {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 20px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  display: flex;
  justify-content: center;
  gap: 24px;
  flex-wrap: wrap;
  animation: slideIn 0.5s ease-out 0.1s both;
}

.stat-item {
  text-align: center;
  padding: 12px 20px;
}

.stat-number {
  font-size: 2em;
  font-weight: 700;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.stat-label {
  color: #666;
  font-size: 0.9em;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-top: 4px;
}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h2>All Mentorship Pairs</h2>
  </div>

  <div class="stats">
    <div class="stat-item">
      <div class="stat-number"><?= count($rows) ?></div>
      <div class="stat-label">Total Pairs</div>
    </div>
  </div>

  <div class="controls">
    <label>
      Filter by Status:
      <select id="filter">
        <option value="">All</option>
        <option value="active" <?= $status_filter==='active' ? 'selected' : '' ?>>Active</option>
        <option value="completed" <?= $status_filter==='completed' ? 'selected' : '' ?>>Completed</option>
        <option value="terminated" <?= $status_filter==='terminated' ? 'selected' : '' ?>>Terminated</option>
      </select>
    </label>
  </div>

  <div id="list" class="list">
    <?php if (count($rows) === 0): ?>
      <div class="empty-state">No pairs found.</div>
    <?php else: ?>
      <?php foreach($rows as $r): ?>
        <div class="item" data-id="<?=htmlspecialchars($r['mentorship_id'])?>">
          <span class="expand-icon">▼</span>
          <strong><?=htmlspecialchars($r['pi_name'] ?? '—')?></strong>
          <span class="arrow">→</span>
          <span style="color:#333;font-weight:500"><?=htmlspecialchars($r['st_name'] ?? '—')?></span>
          <div class="meta">
            <span>Status: <span class="badge" data-status="<?=htmlspecialchars($r['status'])?>"><?=htmlspecialchars($r['status'])?></span></span>
            <span>Since: <strong><?=htmlspecialchars($r['start_date']) ?: 'N/A'?></strong></span>
          </div>
          <div class="hidden-details" style="display:none">
            <p><strong>Mentor:</strong> <?=htmlspecialchars($r['pi_register'] ?? 'N/A')?></p>
            <p><strong>Mentee:</strong> <?=htmlspecialchars($r['st_register'] ?? 'N/A')?></p>
            <p><strong>End Date:</strong> <?=htmlspecialchars($r['end_date'] ?? 'Ongoing')?></p>
            <p><strong>Notes:</strong> <?=nl2br(htmlspecialchars($r['notes'])) ?: 'No notes available'?></p>
            <button class="btn-view" onclick="window.location.href='view_mentorship.php?id=<?=htmlspecialchars($r['mentorship_id'])?>'">View Details</button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
document.getElementById('filter').addEventListener('change', function(){
  const v = this.value;
  const url = new URL(window.location.href);
  if (v) url.searchParams.set('status', v); else url.searchParams.delete('status');
  
  // Add smooth transition
  document.querySelector('.list').style.opacity = '0';
  setTimeout(() => {
    window.location.href = url.toString();
  }, 200);
});

document.querySelectorAll('.item').forEach(it=>{
  it.addEventListener('click', (e)=>{
    // Don't toggle if clicking the button
    if (e.target.classList.contains('btn-view')) return;
    
    const det = it.querySelector('.hidden-details');
    const isExpanded = det.style.display !== 'none';
    
    // Close all other items
    document.querySelectorAll('.item').forEach(other => {
      if (other !== it) {
        other.querySelector('.hidden-details').style.display = 'none';
        other.classList.remove('expanded');
      }
    });
    
    // Toggle current item
    if (isExpanded) {
      det.style.display = 'none';
      it.classList.remove('expanded');
    } else {
      det.style.display = 'block';
      it.classList.add('expanded');
      
      // Smooth scroll to item
      setTimeout(() => {
        it.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }, 100);
    }
  });
});

// Add keyboard navigation
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.item').forEach(item => {
      item.querySelector('.hidden-details').style.display = 'none';
      item.classList.remove('expanded');
    });
  }
});

// Smooth page load
window.addEventListener('load', () => {
  document.body.style.opacity = '1';
});
</script>
</body>
</html>