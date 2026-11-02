<?php
include_once 'db_config.php';
session_start();

if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit();
}

$search_name = isset($_GET['name']) ? mysqli_real_escape_string($conn, $_GET['name']) : '';
$search_branch = isset($_GET['branch']) ? mysqli_real_escape_string($conn, $_GET['branch']) : '';
$search_session = isset($_GET['session']) ? mysqli_real_escape_string($conn, $_GET['session']) : '';

$query = "SELECT ai.*, am.al_status 
          FROM alumniinfo ai 
          JOIN alumnimember am ON ai.pi_register = am.pi_register 
          WHERE 1=1";

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
    <title>Search Alumni - SGBIT Admin</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        table th {
            padding: 1rem;
            text-align: left;
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        table tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-approve {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Search Alumni</h1>
            <a href="admin_homepage.php" class="btn">← Back</a>
        </div>

        <form method="GET" class="search-form">
            <input type="text" name="name" placeholder="Search by name" value="<?php echo $search_name; ?>">
            <select name="branch">
                <option value="">All Branches</option>
                <option value="CSE" <?php if($search_branch=='CSE') echo 'selected'; ?>>CSE</option>
                <option value="ECE" <?php if($search_branch=='ECE') echo 'selected'; ?>>ECE</option>
                <option value="MECH" <?php if($search_branch=='MECH') echo 'selected'; ?>>MECH</option>
                <option value="IT" <?php if($search_branch=='IT') echo 'selected'; ?>>IT</option>
            </select>
            <input type="text" name="session" placeholder="Session year" value="<?php echo $search_session; ?>">
            <button type="submit" class="btn-search">Search</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Reg. No.</th>
                    <th>Name</th>
                    <th>Branch</th>
                    <th>Session</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Company</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($al = mysqli_fetch_assoc($alumni_result)): ?>
                    <tr>
                        <td><?php echo $al['pi_register']; ?></td>
                        <td><?php echo $al['pi_name']; ?></td>
                        <td><?php echo $al['pi_branch']; ?></td>
                        <td><?php echo $al['pi_session']; ?></td>
                        <td><?php echo $al['pi_email']; ?></td>
                        <td><?php echo $al['pi_mobile']; ?></td>
                        <td><?php echo $al['pi_company'] ?: '-'; ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($al['al_status']); ?>">
                                <?php echo $al['al_status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if (mysqli_num_rows($alumni_result) == 0): ?>
            <p style="text-align: center; color: #666; padding: 3rem;">No alumni found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

