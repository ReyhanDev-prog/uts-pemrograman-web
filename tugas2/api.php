<?php
// api.php - REST API sederhana untuk menyimpan data CV ke file JSON

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$dataFile = __DIR__ . '/data.json';

// Data default jika file belum ada
$defaultData = [
    'profile' => [
        'name' => 'Reyhan Aditya Kusumah',
        'title' => 'Backend Engineer | Machine Learning Enthusiast',
        'location' => 'Bandung, Indonesia',
        'email' => 'reyhanak02@gmail.com',
        'linkedin' => 'https://linkedin.com/in/reyhan-ak',
        'github' => 'https://github.com/SFZIBO',
        'image' => 'profil.jpg'
    ],
    'about' => 'Mahasiswa Teknik Informatika yang berfokus pada pengembangan sistem <strong>Backend</strong> yang scalable dan implementasi <strong>Machine Learning</strong>. Memiliki ketertarikan kuat pada arsitektur Microservices, optimasi sistem Linux, dan otomasi industri.',
    'education' => [
        'institution' => 'Universitas Bale Bandung',
        'degree' => 'S1 Teknik Informatika (Semester 6)',
        'semester' => 'Semester 6',
        'focus' => 'Machine Learning & Software Architecture'
    ],
    'skills' => [
        ['category' => 'Bahasa & Framework', 'items' => ['Go (Golang)', 'Python', 'Node.js']],
        ['category' => 'Machine Learning', 'items' => ['Random Forest', 'XGBoost', 'Scikit-learn']],
        ['category' => 'Sistem & Tooling', 'items' => ['Docker', 'KVM (WinBoat)', 'CachyOS (Arch Linux)', 'PLC (LDmicro, OpenPLC)']]
    ],
    'projects' => [
        [
            'title' => 'E-Commerce Microservices System',
            'role' => 'Backend Developer',
            'description' => 'Membangun arsitektur microservices untuk platform e-commerce menggunakan Go. Mengimplementasikan RESTful API, database PostgreSQL, dan manajemen state yang efisien.',
            'technologies' => ['Golang', 'Microservices', 'PostgreSQL']
        ],
        [
            'title' => 'Monitoring Beban Kognitif (Skripsi)',
            'role' => 'Peneliti',
            'description' => 'Penelitian mengenai implementasi Cognitive Load Theory (CLT) dalam lingkungan digital menggunakan komparasi algoritma Random Forest dan XGBoost untuk mendeteksi overload kognitif pada pengguna.',
            'technologies' => ['Python', 'XGBoost', 'Data Science']
        ],
        [
            'title' => 'WinBoat Project',
            'role' => 'Contributor',
            'description' => 'Alat open-source untuk menjalankan aplikasi Windows di lingkungan Linux menggunakan Docker dan KVM untuk performa yang lebih terisolasi dan stabil.',
            'technologies' => ['Docker', 'KVM', 'CachyOS']
        ]
    ],
    'interests' => [
        ['title' => 'Industrial Automation', 'description' => 'Pemrograman PLC menggunakan Ladder Logic (LDmicro & OpenPLC).'],
        ['title' => 'System Administration', 'description' => 'Kustomisasi Arch-based Linux (CachyOS) dan optimasi X11.']
    ]
];

// Baca atau buat file data
function loadData() {
    global $dataFile, $defaultData;
    if (!file_exists($dataFile)) {
        file_put_contents($dataFile, json_encode($defaultData, JSON_PRETTY_PRINT));
        return $defaultData;
    }
    $content = file_get_contents($dataFile);
    $data = json_decode($content, true);
    return $data ?: $defaultData;
}

function saveData($data) {
    global $dataFile;
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle request
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $data = loadData();
    echo json_encode($data);
    exit;
}

if ($method === 'POST' || $method === 'PUT') {
    $input = file_get_contents('php://input');
    $newData = json_decode($input, true);
    if ($newData === null) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }
    // Simpan data baru (overwrite)
    saveData($newData);
    echo json_encode(['status' => 'success', 'data' => $newData]);
    exit;
}

http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);