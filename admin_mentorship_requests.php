<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch statistics
$statsQuery = "SELECT 
    COUNT(*) as total_requests,
    SUM(CASE WHEN status='Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status='Accepted' THEN 1 ELSE 0 END) as accepted,
    SUM(CASE WHEN status='Rejected' THEN 1 ELSE 0 END) as rejected
    FROM mentorship_requests";
$statsResult = mysqli_fetch_assoc(mysqli_query($conn, $statsQuery));

// Fetch all requests with details
$requestsQuery = "SELECT 
    r.request_id,
    r.mentor_pi_register,
    ai.pi_name as mentor_name,
    ai.pi_email as mentor_email,
    ai.pi_company,
    ai.pi_designation,
    ai.pi_branch as mentor_branch,
    r.st_register,
    si.st_name as student_name,
    si.st_email as student_email,
    si.st_branch as student_branch,
    si.st_year,
    r.status,
    r.request_date,
    r.response_date
    FROM mentorship_requests r
    JOIN alumniinfo ai ON r.mentor_pi_register = ai.pi_register
    JOIN studentinfo si ON r.st_register = si.st_register
    ORDER BY 
        CASE r.status 
            WHEN 'Pending' THEN 1 
            WHEN 'Accepted' THEN 2 
            WHEN 'Rejected' THEN 3 
        END,
        r.request_date DESC";
$requestsResult = mysqli_query($conn, $requestsQuery);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mentorship Requests - Admin</title>
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
    position: relative;
    overflow-x: hidden;
}

/* Animated background particles */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    animation: bgPulse 8s ease-in-out infinite;
    pointer-events: none;
    z-index: 0;
}

@keyframes bgPulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
}

.container {
    max-width: 1400px;
    margin: 40px auto;
    background: rgba(255, 255, 255, 0.98);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 1;
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.header-section {
    text-align: center;
    margin-bottom: 40px;
    position: relative;
}

h2 {
    font-size: 2.8rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    margin-bottom: 10px;
    animation: fadeIn 0.8s ease-out;
}

.subtitle {
    color: #666;
    font-size: 1.1rem;
    animation: fadeIn 1s ease-out 0.2s both;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: cardFadeIn 0.6s ease-out both;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

@keyframes cardFadeIn {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    transition: transform 0.6s ease;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.4);
}

.stat-card:hover::before {
    transform: translate(-25%, -25%);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 10px;
    display: block;
    animation: iconBounce 2s ease-in-out infinite;
}

@keyframes iconBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 8px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    position: relative;
    z-index: 1;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.95;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    position: relative;
    z-index: 1;
}

.filter-section {
    margin-bottom: 30px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    animation: slideIn 0.6s ease-out 0.4s both;
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

.filter-btn {
    padding: 12px 24px;
    border: 2px solid #667eea;
    background: white;
    color: #667eea;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.filter-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
    z-index: 0;
}

.filter-btn:hover::before,
.filter-btn.active::before {
    width: 300px;
    height: 300px;
}

.filter-btn span {
    position: relative;
    z-index: 1;
}

.filter-btn:hover,
.filter-btn.active {
    color: white;
    border-color: #764ba2;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
}

.table-container {
    overflow-x: auto;
    margin-bottom: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.8s ease-out 0.6s both;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
}

th {
    padding: 18px 15px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

td {
    padding: 16px 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.3s ease;
}

tbody tr {
    transition: all 0.3s ease;
    animation: rowFadeIn 0.5s ease-out both;
}

tbody tr:nth-child(1) { animation-delay: 0.1s; }
tbody tr:nth-child(2) { animation-delay: 0.15s; }
tbody tr:nth-child(3) { animation-delay: 0.2s; }
tbody tr:nth-child(4) { animation-delay: 0.25s; }
tbody tr:nth-child(5) { animation-delay: 0.3s; }

@keyframes rowFadeIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

tbody tr:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
}

.status-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}

.badge-pending {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
}

.badge-accepted {
    background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
    color: white;
}

.badge-rejected {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.user-name {
    font-weight: 600;
    color: #333;
}

.user-email {
    font-size: 0.85rem;
    color: #666;
}

.company-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.company-name {
    font-weight: 600;
    color: #333;
}

.company-designation {
    font-size: 0.85rem;
    color: #666;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    animation: fadeIn 1s ease-out 0.8s both;
}

.back-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
}

.back-btn:active {
    transform: translateY(-1px);
}

.no-data {
    text-align: center;
    padding: 60px 20px;
    color: #666;
    font-size: 1.2rem;
}

.no-data::before {
    content: '📋';
    display: block;
    font-size: 4rem;
    margin-bottom: 16px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@media (max-width: 768px) {
    body {
        padding: 12px;
    }
    
    .container {
        padding: 20px;
        margin: 20px auto;
    }
    
    h2 {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .filter-section {
        gap: 8px;
    }
    
    .filter-btn {
        padding: 10px 18px;
        font-size: 0.9rem;
    }
    
    table {
        font-size: 0.85rem;
    }
    
    th, td {
        padding: 10px 8px;
    }
}

/* Loading animation */
.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(102, 126, 234, 0.3);
    border-radius: 50%;
    border-top-color: #667eea;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Tooltip styles */
[data-tooltip] {
    position: relative;
    cursor: help;
}

[data-tooltip]:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 8px 12px;
    background: rgba(0, 0, 0, 0.9);
    color: white;
    border-radius: 6px;
    font-size: 0.85rem;
    white-space: nowrap;
    z-index: 1000;
    animation: tooltipFadeIn 0.3s ease;
}

@keyframes tooltipFadeIn {
    from { opacity: 0; transform: translateX(-50%) translateY(5px); }
    to { opacity: 1; transform: translateX(-50%) translateY(0); }
}
</style>
</head>
<body>
<div class="container">
    <div class="header-section">
        <h2>📊 Mentorship Requests Dashboard</h2>
        <p class="subtitle">Monitor and manage all mentorship requests in real-time</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon">📝</span>
            <div class="stat-number"><?php echo $statsResult['total_requests']; ?></div>
            <div class="stat-label">Total Requests</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">
            <span class="stat-icon">⏳</span>
            <div class="stat-number"><?php echo $statsResult['pending']; ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);">
            <span class="stat-icon">✅</span>
            <div class="stat-number"><?php echo $statsResult['accepted']; ?></div>
            <div class="stat-label">Accepted</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);">
            <span class="stat-icon">❌</span>
            <div class="stat-number"><?php echo $statsResult['rejected']; ?></div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="filter-section">
        <button class="filter-btn active" onclick="filterTable('all')"><span>All Requests</span></button>
        <button class="filter-btn" onclick="filterTable('Pending')"><span>⏳ Pending Only</span></button>
        <button class="filter-btn" onclick="filterTable('Accepted')"><span>✅ Accepted Only</span></button>
        <button class="filter-btn" onclick="filterTable('Rejected')"><span>❌ Rejected Only</span></button>
    </div>

    <!-- Requests Table -->
    <div class="table-container">
        <table id="requestsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Branch</th>
                    <th>Year</th>
                    <th>Mentor Name</th>
                    <th>Company</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Response Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (mysqli_num_rows($requestsResult) > 0) {
                    while ($row = mysqli_fetch_assoc($requestsResult)) { 
                ?>
                <tr data-status="<?php echo $row['status']; ?>">
                    <td><strong>#<?php echo $row['request_id']; ?></strong></td>
                    <td>
                        <div class="user-info">
                            <span class="user-name"><?php echo htmlspecialchars($row['student_name']); ?></span>
                            <span class="user-email"><?php echo htmlspecialchars($row['student_email']); ?></span>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($row['student_branch']); ?></td>
                    <td><strong>Year <?php echo $row['st_year']; ?></strong></td>
                    <td>
                        <div class="user-info">
                            <span class="user-name"><?php echo htmlspecialchars($row['mentor_name']); ?></span>
                            <span class="user-email"><?php echo htmlspecialchars($row['mentor_email']); ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="company-info">
                            <span class="company-name"><?php echo htmlspecialchars($row['pi_company']); ?></span>
                            <span class="company-designation"><?php echo htmlspecialchars($row['pi_designation']); ?></span>
                        </div>
                    </td>
                    <td><?php echo date('d M Y', strtotime($row['request_date'])); ?></td>
                    <td>
                        <span class="status-badge badge-<?php echo strtolower($row['status']); ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        echo $row['response_date'] 
                            ? '<strong>' . date('d M Y', strtotime($row['response_date'])) . '</strong>'
                            : '<span style="color: #999;">—</span>'; 
                        ?>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                ?>
                <tr>
                    <td colspan="9">
                        <div class="no-data">No mentorship requests found</div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="adminpage.php" class="back-btn">
        <span>⬅</span>
        <span>Back to Admin Dashboard</span>
    </a>
</div>

<script>
function filterTable(status) {
    const table = document.getElementById('requestsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button states
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.closest('.filter-btn').classList.add('active');
    
    // Filter rows with animation
    let delay = 0;
    for (let row of rows) {
        const rowStatus = row.getAttribute('data-status');
        
        if (status === 'all' || rowStatus === status) {
            setTimeout(() => {
                row.style.display = '';
                row.style.animation = 'rowFadeIn 0.4s ease-out';
            }, delay);
            delay += 50;
        } else {
            row.style.display = 'none';
        }
    }
}

// Add smooth scroll behavior
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Add loading state simulation for filter changes
const filterButtons = document.querySelectorAll('.filter-btn');
filterButtons.forEach(button => {
    button.addEventListener('click', function() {
        const originalContent = this.innerHTML;
        this.style.pointerEvents = 'none';
        
        setTimeout(() => {
            this.style.pointerEvents = 'auto';
        }, 300);
    });
});

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all animated elements
document.querySelectorAll('.stat-card, .filter-section, .table-container').forEach(el => {
    observer.observe(el);
});
</script>
</body>
</html>