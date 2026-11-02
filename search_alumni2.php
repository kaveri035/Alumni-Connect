<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['pi_register'])) {
    header("Location: login.php");
    exit();
}

$search_name = isset($_GET['name']) ? mysqli_real_escape_string($conn, $_GET['name']) : '';
$search_branch = isset($_GET['branch']) ? mysqli_real_escape_string($conn, $_GET['branch']) : '';
$search_session = isset($_GET['session']) ? mysqli_real_escape_string($conn, $_GET['session']) : '';

$query = "SELECT ai.* 
          FROM alumniinfo ai 
          JOIN alumnimember am ON ai.pi_register = am.pi_register 
          WHERE am.al_status = 'Approve'";

if (!empty($search_name)) {
    $query .= " AND ai.pi_name LIKE '%$search_name%'";
}
if (!empty($search_branch)) {
    $query .= " AND ai.pi_branch = '$search_branch'";
}
if (!empty($search_session)) {
    $query .= " AND ai.pi_session LIKE '%$search_session%'";
}

$query .= " ORDER BY ai.pi_name ASC";
$alumni_result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Alumni - SGBIT Alumni</title>
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
            padding: 2rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        h1 {
            color: #667eea;
        }

        .btn {
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            background: #6c757d;
            color: white;
        }

        .search-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .search-form input,
        .search-form select {
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
        }

        .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .alumni-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .alumni-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s;
        }

        .alumni-card:hover {
            transform: translateY(-10px);
        }

        .alumni-card h3 {
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .alumni-card p {
            margin: 0.5rem 0;
            opacity: 0.95;
        }

        .alumni-card strong {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Search Alumni</h1>
            <a href="alumni_home.php" class="btn">← Back</a>
        </div>

        <form method="GET" class="search-form">
            <input type="text" name="name" placeholder="Search by name" value="<?php echo $search_name; ?>">
            <select name="branch">
                <option value="">All Branches</option>
                <option value="CSE" <?php if($search_branch=='CSE') echo 'selected'; ?>>CSE</option>
                <option value="ECE" <?php if($search_branch=='ECE') echo 'selected'; ?>>ECE</option>
                <option value="MECH" <?php if($search_branch=='MECH') echo 'selected'; ?>>MECH</option>
                <option value="IT" <?php if($search_branch=='IT') echo 'selected'; ?>>IT</option>
                <option value="EE" <?php if($search_branch=='EE') echo 'selected'; ?>>EE</option>
                <option value="CIVIL" <?php if($search_branch=='CIVIL') echo 'selected'; ?>>CIVIL</option>
            </select>
            <input type="text" name="session" placeholder="Session (e.g., 2020)" value="<?php echo $search_session; ?>">
            <button type="submit" class="btn-search">Search</button>
        </form>

        <div class="alumni-grid">
            <?php while ($al = mysqli_fetch_assoc($alumni_result)): ?>
                <div class="alumni-card">
                    <h3><?php echo $al['pi_name']; ?></h3>
                    <p><strong>Branch:</strong> <?php echo $al['pi_branch']; ?></p>
                    <p><strong>Session:</strong> <?php echo $al['pi_session']; ?></p>
                    <p><strong>Company:</strong> <?php echo $al['pi_company'] ? $al['pi_company'] : 'Not specified'; ?></p>
                    <p><strong>Email:</strong> <?php echo $al['pi_email']; ?></p>
                    <p><strong>Mobile:</strong> <?php echo $al['pi_mobile']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if (mysqli_num_rows($alumni_result) == 0): ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No alumni found matching your search criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>

