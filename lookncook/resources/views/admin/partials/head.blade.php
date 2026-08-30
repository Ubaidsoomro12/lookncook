<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin | @yield('title')</title>

<link rel="shortcut icon" href="{{ asset('images/lock-logo.png') }}" type="image/jpeg">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

<!-- FontAwesome for Dashboard & Management Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Custom Scrollbar for modern look */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #1a1a1a; }
    ::-webkit-scrollbar-thumb { background: #ff2d7a; border-radius: 3px; }
    
    /* Smooth transition for responsive sidebar overlay toggle */
    .sidebar-transition { transition: all 0.3s ease-in-out; }
</style>