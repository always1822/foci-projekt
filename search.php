<?php
header('Content-Type: application/json');

$apiKey = "afe5ad7292f9123ea67ea762877dc783";
$sport = "soccer";
$region = "eu";
$markets = "h2h";
$query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : "";

if (!$query || strlen($query) < 3) {
    echo json_encode([]);
    exit;
}

$url = "https://api.the-odds-api.com/v4/sports/$sport/odds/?apiKey=$apiKey&regions=$region&markets=$markets";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode != 200 || !$response) {
    echo json_encode(["error" => "Nem sikerült API-t elérni", "http_code" => $httpcode]);
    exit;
}

$data = json_decode($response, true);
if (!is_array($data)) {
    echo json_encode(["error" => "Hibás JSON válasz", "raw" => $response]);
    exit;
}

$matches = [];
foreach ($data as $match) {
    $home = strtolower($match['home_team']);
    $away = strtolower($match['away_team']);
    if (strpos($home, $query) !== false || strpos($away, $query) !== false) {
        $odds = $match['bookmakers'][0]['markets'][0]['outcomes'][0]['price'] ?? null;
        $matches[] = [
            "home_team" => $match['home_team'],
            "away_team" => $match['away_team'],
            "odds" => $odds
        ];
    }
}

echo json_encode($matches);
