<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>looknCook | @yield('title')</title>
<link rel="shortcut icon" href="{{ asset('images/lock-logo.png') }}" type="image/jpeg">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-color: #ff2d7a;
        --sidebar-bg: #1e1e2d;
        --sidebar-text: #9ca3af;
        --sidebar-hover-bg: rgba(255, 45, 122, 0.1);
    }
    body {
        font-family: system-ui, -apple-system, sans-serif;
    }
    .sidebar-brand {
        padding: 20px 15px;
        border-bottom: 1px solid #2d2d3f;
        font-size: 20px;
        font-weight: 700;
        color: #ff2d7a;
        text-align: center;
    }
    .sidebar-brand i {
        margin-right: 8px;
    }
    .sidebar-nav .nav-link {
        color: var(--sidebar-text);
        padding: 12px 20px;
        border-radius: 8px;
        margin: 4px 10px;
        transition: all 0.2s;
    }
    .sidebar-nav .nav-link:hover {
        background: var(--sidebar-hover-bg);
        color: #fff;
    }
    .sidebar-nav .nav-link.active {
        background: var(--primary-color);
        color: #fff;
    }
    .sidebar-nav .nav-link i {
        width: 24px;
    }
</style>