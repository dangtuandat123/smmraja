<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang bảo trì - SMM Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
        }
        .maintenance-container {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            max-width: 550px;
            margin: 1rem;
        }
        .maintenance-icon {
            font-size: 5rem;
            color: #f59e0b;
            margin-bottom: 1.5rem;
            animation: spin 4s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .maintenance-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem;
        }
        .maintenance-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .progress-bar-inner {
            height: 100%;
            width: 60%;
            background: linear-gradient(90deg, #f59e0b 0%, #ef4444 100%);
            border-radius: 4px;
            animation: progress 2s ease-in-out infinite;
        }
        @keyframes progress {
            0%, 100% { width: 30%; }
            50% { width: 70%; }
        }
        .contact-info {
            background: #fef3c7;
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1.5rem;
        }
        .contact-info p {
            margin: 0;
            color: #92400e;
            font-size: 0.9rem;
        }
        .contact-info a {
            color: #d97706;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="fas fa-cog"></i>
        </div>
        <h1 class="maintenance-title">Đang bảo trì hệ thống</h1>
        <p class="maintenance-subtitle">
            Chúng tôi đang nâng cấp hệ thống để phục vụ bạn tốt hơn.<br>
            Vui lòng quay lại sau ít phút.
        </p>
        <div class="progress-bar">
            <div class="progress-bar-inner"></div>
        </div>
        <p class="has-text-grey is-size-7">
            <i class="fas fa-clock mr-1"></i>
            Dự kiến hoàn thành trong thời gian ngắn
        </p>
        <div class="contact-info">
            <p>
                <i class="fas fa-envelope mr-1"></i>
                Liên hệ hỗ trợ: <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'support@tiktos.me') }}">{{ \App\Models\Setting::get('contact_email', 'support@tiktos.me') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
