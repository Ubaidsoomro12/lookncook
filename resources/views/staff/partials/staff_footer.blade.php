<footer style="background:#ffffff; border-top:1px solid #e5e7eb; padding:20px 24px; margin-top:24px; border-radius:12px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div style="display:flex; flex-direction:column; align-items:center; gap:16px;" class="footer-inner">
        
        <!-- Left Side: Brand & Copyright -->
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:36px; height:36px; background:#fff0f6; border-radius:10px; display:flex; align-items:center; justify-content:center; border:1px solid #ffd6e5;">
                <i class="fa-solid fa-user-tie" style="color:#ff2d7a; font-size:16px;"></i>
            </div>
            <div style="text-align:left;">
                <p style="margin:0; font-size:14px; color:#111827; font-weight:700; line-height:1.2;">
                    Look n Cook <span style="font-size:10px; color:#ff2d7a; background:#fff0f6; padding:2px 8px; border-radius:6px; margin-left:4px; border:1px solid #ffd6e5; font-weight:600;">STAFF</span>
                </p>
                <p style="margin:0; font-size:12px; color:#6b7280; font-weight:500;">
                    &copy; {{ date('Y') }} All rights reserved.
                </p>
            </div>
        </div>

        <!-- Right Side: System Status & Version -->
        <div style="display:flex; align-items:center; gap:16px;">
            <div style="display:flex; align-items:center; gap:6px; background:#f0fdf4; padding:4px 10px; border-radius:20px; border:1px solid #bbf7d0;">
                <span style="display:inline-block; width:6px; height:6px; background:#10b981; border-radius:50%;"></span>
                <span style="font-size:11px; color:#047857; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">System Online</span>
            </div>
            
            <div style="width:1px; height:14px; background:#e5e7eb;"></div>
            
            <div style="display:flex; align-items:center; gap:4px;">
                <span style="font-size:11px; color:#9ca3af; font-weight:600;">Staff Panel v1.0</span>
            </div>
        </div>

    </div>
</footer>

<style>
    /* Desktop Layout: Row with space between */
    @media (min-width: 640px) {
        .footer-inner {
            flex-direction: row !important;
            justify-content: space-between !important;
        }
    }
</style>