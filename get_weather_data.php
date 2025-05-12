<?php
include 'includes/db.php';

$weatherType = $_GET['weatherType'] ?? 'all';
$minTemp = $_GET['minTemp'] ?? '';
$seasons = isset($_GET['seasons']) ? explode(',', $_GET['seasons']) : [];

$sql = "SELECT date AS Date, temperature AS Temperature, humidity AS Humidity, season AS Season, weatherType AS 'Weather Type' FROM weather WHERE 1=1";

if ($weatherType !== 'all') {
    $sql .= " AND weatherType = '" . $conn->real_escape_string($weatherType) . "'";
}

if ($minTemp !== '') {
    $sql .= " AND temperature >= " . intval($minTemp);
}

if (!empty($seasons)) {
    $seasonList = implode("','", array_map([$conn, 'real_escape_string'], $seasons));
    $sql .= " AND season IN ('$seasonList')";
}

$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
