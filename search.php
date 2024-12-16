<?php

session_start();

require_once("db.php");

$limit = 4;

if(isset($_GET["page"])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start_from = ($page-1) * $limit;

$sql = "SELECT jp.*, c.*
        FROM job_post jp
        INNER JOIN company c ON jp.id_company = c.id_company";

$whereClause = "";
$parameters = array();



if(isset($_GET['filter']) && isset($_GET['search'])) {
    $filter = $_GET['filter'];
    $search = $_GET['search'];

    if($whereClause !== "") {
        $whereClause .= " AND ";
    }

    if($filter == 'city') {
        $whereClause .= "c.city = ?";
        $parameters[] = $search;
    } elseif($filter == 'experience') {
        $whereClause .= "jp.experience >= ?";
        $parameters[] = $search;
    } elseif($filter == 'jobtitle') {
        $whereClause .= " jp.jobtitle LIKE CONCAT('%', ?, '%')";
        $parameters[] = $search;
    }

    $_SESSION['filter'] = $filter;
    $_SESSION['search'] = $search;
}

if($whereClause !== "") {
    $sql .= " WHERE $whereClause";
}

$sql .= " LIMIT $start_from, $limit";

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($parameters)), ...$parameters);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        ?>
        <div class="attachment-block clearfix">
            <img class="attachment-img" src="uploads/logo/<?php echo $row['logo']; ?>" alt="Attachment Image">
            <div class="attachment-pushed">
                <h4 class="attachment-heading"><a href="view-job-post.php?id=<?php echo $row['id_jobpost']; ?>"><?php echo $row['jobtitle']; ?></a> <span class="attachment-heading pull-right">$<?php echo $row['maximumsalary']; ?>/Month</span></h4>
                <div class="attachment-text">
                    <div><strong><?php echo $row['companyname']; ?> | <?php echo $row['city']; ?> | Experience <?php echo $row['experience']; ?> Years</strong></div>
                </div>
            </div>
        </div>
        <?php
    }
}

$stmt->close();
$conn->close();
?>