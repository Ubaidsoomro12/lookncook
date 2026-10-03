{{-- ============================================================
   RECEIPT MODAL
   Path: resources/views/pos/partials/receipt_modal.blade.php
============================================================ --}}
<style>
#receiptModal .modal-content{
    border:none;border-radius:18px;overflow:hidden;
    box-shadow:0 30px 60px -15px rgba(0,0,0,.35);
}
#receiptModal .modal-header{
    background:linear-gradient(135deg,#ff2d7a,#ff4b91);
    color:#fff;border:none;padding:16px 22px;
}
#receiptModal .modal-header .btn-close{filter:invert(1) brightness(2);}
#receiptModal .modal-body{
    padding:0;background:#f3f4f6;max-height:70vh;overflow-y:auto;
}
#receiptModal .modal-footer{
    background:#fff;border-top:1px solid #e5e7eb;padding:14px 22px;
}

.rm-paper{
    max-width:640px;margin:20px auto;background:#fff;
    border-radius:14px;overflow:hidden;
    box-shadow:0 8px 30px -10px rgba(0,0,0,.15);
    position:relative;
}
.rm-paper::before{
    content:'';position:absolute;top:0;left:0;right:0;height:5px;
    background:linear-gradient(135deg,#ff2d7a,#ff4b91);
}
.rm-header{text-align:center;padding:28px 24px 18px;border-bottom:2px solid #ff2d7a;}
.rm-logo{width:60px;height:60px;border-radius:50%;border:2px solid #ff2d7a;object-fit:cover;margin-bottom:10px;}
.rm-brand{font-size:24px;font-weight:800;color:#ff2d7a;letter-spacing:1px;margin:0 0 4px;}
.rm-tagline{font-size:10px;color:#6b7280;letter-spacing:3px;text-transform:uppercase;margin:0 0 6px;font-weight:600;}
.rm-contact{font-size:11px;color:#9ca3af;margin:0;}
.rm-titleband{
    text-align:center;font-size:11px;font-weight:800;color:#111827;
    letter-spacing:5px;padding:12px;background:#fff5f8;
    border-bottom:1px dashed #ffd6e5;text-transform:uppercase;
}
.rm-info{
    display:grid;grid-template-columns:1fr 1fr;gap:14px 18px;
    padding:20px 22px;border-bottom:1px dashed #e5e7eb;
}
.rm-info-row .rm-label{
    font-size:9px;color:#9ca3af;text-transform:uppercase;
    letter-spacing:1px;font-weight:700;margin-bottom:3px;
}
.rm-info-row .rm-value{
    font-size:12px;color:#111827;font-weight:700;word-break:break-word;
}
.rm-info-full{grid-column:1 / -1;}
.rm-items{padding:18px 22px;border-bottom:1px dashed #e5e7eb;}
.rm-items-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;}
.rm-items-title{font-size:10px;font-weight:800;color:#6b7280;text-transform:uppercase;letter-spacing:1.5px;}
.rm-items-title i{color:#ff2d7a;font-size:12px;margin-right:5px;}
.rm-items-count{font-size:10px;color:#9ca3af;font-weight:600;}
.rm-item{
    display:flex;justify-content:space-between;align-items:flex-start;
    gap:10px;padding:9px 0;border-bottom:1px dashed #f3f4f6;
}
.rm-item:last-child{border-bottom:none;}
.rm-item-left{flex:1;min-width:0;}
.rm-item-name{font-size:12px;font-weight:700;color:#111827;word-break:break-word;margin-bottom:2px;}
.rm-item-cat{font-size:10px;color:#9ca3af;font-style:italic;}
.rm-item-right{text-align:right;flex-shrink:0;}
.rm-item-qty{font-size:10px;color:#6b7280;margin-bottom:3px;}
.rm-item-total{font-size:12px;font-weight:800;color:#111827;}
.rm-bill{background:linear-gradient(135deg,#111827,#1f2937);color:#f3f4f6;padding:18px 22px;}
.rm-bill-row{display:flex;justify-content:space-between;font-size:12px;padding:4px 0;opacity:.85;}
.rm-bill-total{
    display:flex;justify-content:space-between;align-items:center;
    font-size:20px;font-weight:800;color:#ff2d7a;
    border-top:1px dashed #374151;padding-top:12px;margin-top:8px;
}
.rm-notes{margin:16px 22px;padding:10px 14px;background:#fffbeb;border-left:3px solid #f59e0b;border-radius:6px;font-size:11px;color:#78350f;line-height:1.5;}
.rm-notes-title{font-weight:800;text-transform:uppercase;font-size:9px;letter-spacing:1.5px;margin-bottom:4px;color:#92400e;}
.rm-footer{text-align:center;padding:20px 22px 24px;border-top:2px dashed #ffd6e5;}
.rm-thanks{font-size:14px;font-weight:800;color:#ff2d7a;letter-spacing:1px;margin-bottom:6px;}
.rm-thanks-msg{font-size:10px;color:#6b7280;margin-bottom:10px;}
.rm-footer-line{font-size:9px;color:#9ca3af;letter-spacing:2px;}

/* Info banner showing both receipts will download */
.rm-info-banner{
    background:#f0f9ff;border-left:3px solid #0ea5e9;
    padding:10px 14px;margin:16px 22px;border-radius:6px;
    font-size:11px;color:#075985;line-height:1.5;
}
.rm-info-banner strong{color:#0369a1;}
</style>

<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-receipt me-2"></i>Receipt Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="receiptModalBody">
                <div style="padding:60px 20px;text-align:center;color:#9ca3af;">
                    <i class="fa-solid fa-spinner fa-spin fa-2x mb-3 d-block" style="color:#ff2d7a;"></i>
                    Preparing receipt...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="receiptCloseBtn">
                    <i class="fa-solid fa-xmark me-1"></i>Close
                </button>
                <button type="button" class="btn" id="receiptPrintBtn"
                        style="background:#111827;color:#fff;border:none;font-weight:600;">
                    <i class="fa-solid fa-print me-1"></i>Print (Windows Dialog)
                </button>
                <button type="button" class="btn" id="receiptDownloadBtn"
                        style="background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;border:none;font-weight:600;">
                    <i class="fa-solid fa-download me-1"></i>Download PDF (Both)
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.POSReceipt = (function(){
    'use strict';

    let modalInstance = null;
    let currentOrder = null;

    const money = n => 'Rs ' + (Number(n)||0).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
    const esc = s => !s ? '' : String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    function buildHTML(order){
        const items = order.items || [];

        let itemsHTML;
        if (!items.length) {
            itemsHTML = '<div style="text-align:center;padding:16px;color:#9ca3af;font-size:12px;">No items</div>';
        } else {
            itemsHTML = items.map(it => `
                <div class="rm-item">
                    <div class="rm-item-left">
                        <div class="rm-item-name">${esc(it.product_name)}</div>
                        ${it.category ? `<div class="rm-item-cat">${esc(it.category)}</div>` : ''}
                    </div>
                    <div class="rm-item-right">
                        <div class="rm-item-qty">${it.quantity} × ${money(it.unit_price)}</div>
                        <div class="rm-item-total">${money(it.total_price)}</div>
                    </div>
                </div>`).join('');
        }

        const tax = parseFloat(order.tax) || 0;
        const discount = parseFloat(order.discount) || 0;

        let billHTML = `<div class="rm-bill-row"><span>Subtotal</span><span>${money(order.subtotal)}</span></div>`;
        if (tax > 0) billHTML += `<div class="rm-bill-row"><span>Tax</span><span>${money(tax)}</span></div>`;
        if (discount > 0) billHTML += `<div class="rm-bill-row"><span>Discount</span><span>- ${money(discount)}</span></div>`;
        billHTML += `<div class="rm-bill-total"><span>GRAND TOTAL</span><span>${money(order.total_amount)}</span></div>`;

        const notesHTML = order.notes ? `
            <div class="rm-notes">
                <div class="rm-notes-title"><i class="fa-solid fa-note-sticky me-1"></i>Special Instructions</div>
                ${esc(order.notes)}
            </div>` : '';

        return `
            <div class="rm-paper">
                <div class="rm-header">
                    <img src="/images/lock-logo.png" alt="Logo" class="rm-logo" onerror="this.style.display='none';">
                    <h1 class="rm-brand">Look n Cook</h1>
                    <p class="rm-tagline">Fine Dining Restaurant</p>
                    <p class="rm-contact">Main Branch &bull; 0300-0000000 &bull; info@lookncook.com</p>
                </div>

                <div class="rm-titleband">Customer Receipt</div>

                <div class="rm-info">
                    <div class="rm-info-row">
                        <div class="rm-label">Order No.</div>
                        <div class="rm-value">#${esc(order.order_number)}</div>
                    </div>
                    <div class="rm-info-row">
                        <div class="rm-label">Date &amp; Time</div>
                        <div class="rm-value">${esc(order.created_at || '')}</div>
                    </div>
                    <div class="rm-info-row">
                        <div class="rm-label">Table</div>
                        <div class="rm-value">${esc(order.table_number || '—')}${order.table_name ? ' — ' + esc(order.table_name) : ''}</div>
                    </div>
                    <div class="rm-info-row">
                        <div class="rm-label">Waiter</div>
                        <div class="rm-value">${esc(order.waiter_name || 'Unassigned')}</div>
                    </div>
                    <div class="rm-info-row rm-info-full">
                        <div class="rm-label">Customer</div>
                        <div class="rm-value">${esc(order.customer_name || 'Walk-in Customer')}</div>
                    </div>
                </div>

                <div class="rm-items">
                    <div class="rm-items-head">
                        <div class="rm-items-title"><i class="fa-solid fa-bowl-food"></i>Ordered Items</div>
                        <div class="rm-items-count">${items.length} item${items.length === 1 ? '' : 's'}</div>
                    </div>
                    ${itemsHTML}
                </div>

                <div class="rm-bill">${billHTML}</div>
                ${notesHTML}

                <div class="rm-info-banner">
                    <strong>📄 Both copies will download:</strong> This PDF contains the <strong>Customer Receipt</strong> on Page 1 and the <strong>Department (Kitchen) Receipt</strong> on Page 2.
                </div>

                <div class="rm-footer">
                    <div class="rm-thanks">THANK YOU FOR DINING WITH US!</div>
                    <p class="rm-thanks-msg">We hope you enjoyed your meal. Please visit us again.</p>
                    <div class="rm-footer-line">&mdash;&mdash;&mdash; Powered by Look n Cook POS &mdash;&mdash;&mdash;</div>
                </div>
            </div>
        `;
    }

    function ensureModal(){
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(document.getElementById('receiptModal'), {
                backdrop: 'static',
                keyboard: false
            });
        }
        return modalInstance;
    }

    function show(order){
        currentOrder = order;
        const body = document.getElementById('receiptModalBody');
        body.innerHTML = buildHTML(order);
        body.scrollTop = 0;

        const btn = document.getElementById('receiptDownloadBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-download me-1"></i>Download PDF (Both)';

        ensureModal().show();
    }

    /* ============================================================
       PRINT — Opens the browser's native print dialog
       The print view renders 2 receipts (Customer + Department)
    ============================================================ */
    function printReceipt(){
        if (!currentOrder) return;
        const url = `/pos/orders/${currentOrder.id}/print-receipt`;
        window.open(url, '_blank', 'width=900,height=800');
    }

    /* ============================================================
       DOWNLOAD — Downloads a SINGLE PDF with BOTH receipts inside
       (Page 1: Customer, Page 2: Department)
    ============================================================ */
    async function download(){
        if (!currentOrder) return;
        const btn = document.getElementById('receiptDownloadBtn');
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Preparing...';

        try {
            const url = `/pos/orders/${currentOrder.id}/receipt-data`;
            const res = await fetch(url + '?t=' + Date.now(), {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: { 'Accept': 'application/json' }
            });

            if (!res.ok) throw new Error('Server returned ' + res.status);

            const json = await res.json();
            if (!json.success || !json.base64) throw new Error(json.error || 'Invalid response');

            const binary = atob(json.base64);
            const bytes = new Uint8Array(binary.length);
            for (let i = 0; i < binary.length; i++) bytes[i] = binary.charCodeAt(i);
            const blob = new Blob([bytes], {type: 'application/pdf'});
            const blobUrl = URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = blobUrl;
            a.download = json.filename || 'receipt.pdf';
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();

            setTimeout(() => {
                try { a.remove(); } catch(e) {}
                URL.revokeObjectURL(blobUrl);
            }, 100);

            btn.disabled = false;
            btn.innerHTML = original;

            // Close modal + reload
            setTimeout(() => {
                try { ensureModal().hide(); } catch(e) {}
                setTimeout(() => location.reload(), 400);
            }, 800);

        } catch (err) {
            console.error('Download failed:', err);
            alert('Download failed: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = original;
        }
    }

    function closeAndReload(){
        try { ensureModal().hide(); } catch(e) {}
        setTimeout(() => location.reload(), 300);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const dlBtn = document.getElementById('receiptDownloadBtn');
        if (dlBtn) dlBtn.addEventListener('click', download);

        const prBtn = document.getElementById('receiptPrintBtn');
        if (prBtn) prBtn.addEventListener('click', printReceipt);

        const clBtn = document.getElementById('receiptCloseBtn');
        if (clBtn) clBtn.addEventListener('click', closeAndReload);
    });

    return { show };
})();
</script>