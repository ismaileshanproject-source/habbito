<?php
// ============================================================
// HABBITO — config.php (Bulletproof Railway Version)
// ============================================================
declare(strict_types=1);

define('APP_NAME',    'Habbito');
define('APP_ENV',     getenv('RAILWAY_ENVIRONMENT_NAME') ? 'production' : 'development'); 

// ── Database — Auto-Detecting Railway Variable Names ────────
// This checks for MYSQLHOST or MYSQL_HOST, etc.
define('DB_HOST', getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: 'localhost');
define('DB_PORT', getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: 3306);
define('DB_NAME', getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'habbito');
define('DB_USER', getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: ''); 

// ── XP constants ──────────────────────────────────────────────
define('XP_BASE',        10);
define('XP_STREAK_3',     5);
define('XP_STREAK_7',    15);
define('XP_STREAK_30',   50);
define('XP_FIRST_HABIT', 20);
define('XP_PERFECT_DAY', 75);
define('XP_JOURNAL',     15);
define('XP_LUCKY_MAX',   10);

date_default_timezone_set('UTC');

if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// ── Database connection ───────────────────────────────────────
function getDB(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => true, 
        ]);
    } catch (PDOException $e) {
        // If it fails, show the specific error only in development mode
        $msg = (APP_ENV === 'development') 
            ? 'DB Error: ' . $e->getMessage() 
            : 'Database connection failed. Please check your Railway Variables.';
        
        if (!headers_sent()) header('Content-Type: application/json');
        die(json_encode(['success' => false, 'error' => $msg]));
    }
    return $pdo;
}

// ... rest of your helper functions (xpToLevel, jsonResponse, etc.) stay the same ...
