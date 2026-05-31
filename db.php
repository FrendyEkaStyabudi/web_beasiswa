<?php
function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:2rem;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;max-width:500px;margin:2rem auto;">
                <h3 style="color:#991b1b;">Koneksi Database Gagal</h3>
                <p>Pastikan XAMPP aktif, MySQL jalan, dan database <strong>db_beasiswa</strong> sudah dibuat.</p>
                <p style="color:#9ca3af;font-size:0.8rem;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>
            </div>');
        }
    }

    return $pdo;
}