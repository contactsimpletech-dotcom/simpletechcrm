<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $code; ?> — <?php echo View::e(BH_APP_NAME); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo BH_ASSETS_URL; ?>/css/app.css">
<style>body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f4f4f5;}</style>
</head>
<body>
<div style="text-align:center;max-width:480px;padding:40px 24px;">
    <div style="font-size:96px;font-weight:800;color:#e5e7eb;line-height:1;"><?php echo $code; ?></div>
    <h1 style="font-size:22px;color:#111827;margin:16px 0 8px;"><?php echo View::e($message); ?></h1>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:24px;">
        <a href="<?= BH_APP_URL ?>/dashboard" class="btn btn-primary"><i class="fa-solid fa-gauge-high" style="margin-right:6px;"></i>Dashboard</a>
        <button onclick="history.back()" class="btn btn-secondary"><i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i>Go Back</button>
    </div>
</div>
</body>
</html>
