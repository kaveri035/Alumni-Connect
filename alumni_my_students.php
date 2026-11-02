<?php
session_start();
require_once 'db.php';
$pi_register = isset($_SESSION['pi_register']) ? $_SESSION['pi_register'] : null;
if (!$pi_register) { echo "Alumni not logged in."; exit; }
$sql = "
  SELECT m.mentorship_id, m.status, m.start_date, m.end_date, m.notes,
         si.st_register, si.st_name, si.st_email, si.st_mobile, si.st_branch
  FROM mentorship m
  JOIN studentinfo si ON m.mentee_st_register = si.st_register
  WHERE m.mentor_pi_register = ?
  ORDER BY m.start_date DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $pi_register);
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Mentees</title>
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
  max-width: 1200px;
  margin: 0 auto;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  overflow: hidden;
  animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 32px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.header::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: pulse 4s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

h2 {
  font-size: 2.5em;
  font-weight: 700;
  position: relative;
  z-index: 1;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.content {
  padding: 32px;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 1.2em;
}

.empty-state::before {
  content: '📚';
  display: block;
  font-size: 4em;
  margin-bottom: 16px;
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.table-wrapper {
  overflow-x: auto;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.table {
  width: 100%;
  border-collapse: collapse;
  background: white;
}

.table thead {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.table th {
  padding: 16px;
  text-align: left;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.85em;
  letter-spacing: 0.5px;
}

.table td {
  padding: 16px;
  border-bottom: 1px solid #f0f0f0;
  transition: background-color 0.3s ease;
}

.table tbody tr {
  transition: all 0.3s ease;
}

.table tbody tr:hover {
  background-color: #f8f9ff;
  transform: scale(1.01);
  box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}

.meta {
  font-size: 0.85em;
  color: #888;
  margin-top: 4px;
}

.status-select {
  padding: 8px 12px;
  border: 2px solid #e0e0e0;
  border-radius: 6px;
  background: white;
  font-size: 0.9em;
  cursor: pointer;
  transition: all 0.3s ease;
  outline: none;
}

.status-select:hover {
  border-color: #667eea;
}

.status-select:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

button {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

button:active {
  transform: translateY(0);
}

.msg {
  display: inline-block;
  margin-left: 8px;
  font-size: 0.9em;
  font-weight: 600;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.msg.show {
  opacity: 1;
}

.success {
  color: #10b981;
  animation: slideInRight 0.3s ease;
}

.error {
  color: #ef4444;
  animation: shake 0.5s ease;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(-10px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

.loading {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid #f3f3f3;
  border-top: 2px solid #667eea;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .container {
    border-radius: 0;
  }
  
  h2 {
    font-size: 1.8em;
  }
  
  .table th, .table td {
    padding: 12px 8px;
    font-size: 0.9em;
  }
  
  button {
    padding: 8px 16px;
    font-size: 0.9em;
  }
}

.fade-in {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h2>My Mentees</h2>
  </div>
  <div class="content">
    <?php if (count($rows) === 0): ?>
      <div class="empty-state">No mentees assigned yet.</div>
    <?php else: ?>
      <div class="table-wrapper">
        <table class="table">
          <thead><tr><th>Mentee</th><th>Branch</th><th>Contact</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
          <?php foreach($rows as $r): ?>
            <tr data-id="<?=htmlspecialchars($r['mentorship_id'])?>" class="fade-in">
              <td><strong><?=htmlspecialchars($r['st_name'])?></strong><div class="meta"><?=htmlspecialchars($r['st_register'])?></div></td>
              <td><?=htmlspecialchars($r['st_branch'])?></td>
              <td><?=htmlspecialchars($r['st_email'])?><br><?=htmlspecialchars($r['st_mobile'])?></td>
              <td>
                <select class="status-select">
                  <?php $s = $r['status']; ?>
                  <option value="active"  <?= $s==='active' ? 'selected' : '' ?>>Active</option>
                  <option value="completed" <?= $s==='completed' ? 'selected' : '' ?>>Completed</option>
                  <option value="terminated" <?= $s==='terminated' ? 'selected' : '' ?>>Terminated</option>
                </select>
              </td>
              <td>
                <button class="btn-update">Update</button>
                <span class="msg"></span>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
document.querySelectorAll('.btn-update').forEach(btn=>{
  btn.addEventListener('click', async e=>{
    const row = e.target.closest('tr');
    const id = row.dataset.id;
    const select = row.querySelector('.status-select');
    const status = select.value;
    const msgSpan = row.querySelector('.msg');
    const button = e.target;
    
    // Show loading state
    const originalText = button.textContent;
    button.disabled = true;
    button.innerHTML = '<span class="loading"></span>';
    msgSpan.textContent = '';
    msgSpan.className = 'msg';
    
    try{
      const resp = await fetch('update_status.php', {
        method:'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({mentorship_id: id, status: status})
      });
      const j = await resp.json();
      
      button.textContent = originalText;
      button.disabled = false;
      
      if (j.success) {
        msgSpan.textContent = '✓ Saved';
        msgSpan.className = 'msg success show';
        
        // Add success animation to row
        row.style.backgroundColor = '#ecfdf5';
        setTimeout(() => {
          row.style.backgroundColor = '';
        }, 500);
      } else {
        msgSpan.textContent = '✗ ' + (j.error || 'Failed');
        msgSpan.className = 'msg error show';
      }
    } catch(err) {
      button.textContent = originalText;
      button.disabled = false;
      msgSpan.textContent = '✗ Network error';
      msgSpan.className = 'msg error show';
    }
    
    setTimeout(()=>{ 
      msgSpan.classList.remove('show');
      setTimeout(() => {
        msgSpan.textContent=''; 
        msgSpan.className='msg';
      }, 300);
    }, 2500);
  });
});

// Add smooth scroll reveal animation
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '0';
      entry.target.style.transform = 'translateY(20px)';
      setTimeout(() => {
        entry.target.style.transition = 'all 0.5s ease';
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }, 50);
      observer.unobserve(entry.target);
    }
  });
}, observerOptions);

document.querySelectorAll('tbody tr').forEach(row => {
  observer.observe(row);
});
</script>
</body>
</html>