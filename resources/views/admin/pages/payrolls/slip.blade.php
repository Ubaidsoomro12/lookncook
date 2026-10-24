<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Salary Slip — {{ $payroll->staff?->name }} — {{ $payroll->period_label }}</title>
<style>
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Segoe UI',sans-serif;background:#f3f4f6;padding:24px;color:#111827;}
    .slip{max-width:820px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 12px 30px rgba(0,0,0,.1);}
    .head{background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;padding:24px 32px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;}
    .head h1{font-size:24px;font-weight:800;letter-spacing:1px;margin-bottom:4px;}
    .head small{opacity:.9;font-size:11px;letter-spacing:2px;text-transform:uppercase;}
    .title{text-align:center;font-weight:800;letter-spacing:6px;font-size:14px;padding:14px;background:#fff5f8;border-bottom:1px dashed #ffd6e5;text-transform:uppercase;}
    .body{padding:24px 32px;}

    .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px 24px;padding-bottom:16px;border-bottom:1px dashed #e5e7eb;margin-bottom:20px;}
    .grid .l{font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:1px;font-weight:700;margin-bottom:2px;}
    .grid .v{font-size:13px;color:#111827;font-weight:700;}

    .cols{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:20px;}
    .col h4{font-size:11px;text-transform:uppercase;letter-spacing:1.5px;color:#6b7280;margin-bottom:10px;font-weight:800;}
    table{width:100%;border-collapse:collapse;font-size:13px;}
    table tr td{padding:6px 0;border-bottom:1px dashed #f3f4f6;}
    table tr:last-child td{border-bottom:none;}
    table tr td:last-child{text-align:right;font-weight:700;}
    .section-total td{border-top:1px solid #e5e7eb;border-bottom:none;padding-top:10px;font-weight:800;}
    .section-total-red td{color:#ef4444;}

    .net{background:linear-gradient(135deg,#111827,#1f2937);color:#fff;padding:24px 32px;text-align:center;}
    .net .label{font-size:11px;letter-spacing:2px;opacity:.7;text-transform:uppercase;margin-bottom:6px;}
    .net .amount{font-size:36px;font-weight:800;color:#ff2d7a;}

    .foot{padding:18px 32px;text-align:center;font-size:10px;color:#9ca3af;letter-spacing:1px;background:#fff5f8;border-top:1px dashed #ffd6e5;}
    .foot .ty{font-size:13px;font-weight:800;color:#ff2d7a;letter-spacing:2px;margin-bottom:4px;}

    .actions{max-width:820px;margin:20px auto 0;display:flex;gap:10px;justify-content:center;}
    .actions button{padding:12px 24px;border:none;border-radius:10px;font-weight:700;cursor:pointer;font-size:13px;}
    .btn-print{background:linear-gradient(135deg,#ff2d7a,#ff4b91);color:#fff;}
    .btn-back{background:#fff;color:#374151;border:1.5px solid #e5e7eb;}

    @media print{
        body{background:#fff;padding:0;}
        .slip{box-shadow:none;border-radius:0;}
        .actions{display:none;}
    }
    @media (max-width:600px){
        .grid,.cols{grid-template-columns:1fr;}
    }
</style>
</head>
<body>

<div class="slip">

    <div class="head">
        <div>
            <h1>Look n Cook</h1>
            <small>Fine Dining Restaurant</small>
        </div>
        <div style="text-align:right;">
            <small>Salary Slip</small>
            <div style="font-size:15px;font-weight:700;margin-top:4px;">{{ $payroll->period_label }}</div>
        </div>
    </div>

    <div class="title">Employee Salary Slip</div>

    <div class="body">

        {{-- STAFF INFO --}}
        <div class="grid">
            <div><div class="l">Employee Name</div><div class="v">{{ $payroll->staff?->name ?? '—' }}</div></div>
            <div><div class="l">Employee ID</div><div class="v">{{ $payroll->staff?->employee_id ?? '—' }}</div></div>
            <div><div class="l">Designation</div><div class="v">{{ $payroll->staff?->designation ?? '—' }}</div></div>
            <div><div class="l">Department</div><div class="v">{{ $payroll->staff?->department ?? '—' }}</div></div>
        </div>

        {{-- ALLOWANCES & DEDUCTIONS --}}
        <div class="cols">

            {{-- ALLOWANCES — no bottom total, just itemized list --}}
            <div class="col">
                <h4>Allowances</h4>
                <table>
                    @forelse($payroll->allowances as $entry)
                        <tr>
                            <td>{{ $entry->type?->name ?? '—' }}</td>
                            <td>{{ number_format($entry->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" style="color:#9ca3af;font-size:12px;text-align:center;">No allowances</td></tr>
                    @endforelse
                </table>
            </div>

            {{-- DEDUCTIONS — total row stays --}}
            <div class="col">
                <h4>Deductions</h4>
                <table>
                    @forelse($payroll->deductions as $entry)
                        <tr>
                            <td>{{ $entry->type?->name ?? '—' }}</td>
                            <td>{{ number_format($entry->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" style="color:#9ca3af;font-size:12px;text-align:center;">No deductions</td></tr>
                    @endforelse
                    <tr class="section-total section-total-red">
                        <td>Total Deductions</td>
                        <td>{{ number_format($payroll->total_deductions, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- PAYMENT INFO --}}
        <div class="grid" style="border-bottom:none;margin-bottom:0;padding-bottom:0;">
            <div><div class="l">Payment Status</div><div class="v">{{ ucfirst($payroll->payment_status) }}</div></div>
            <div><div class="l">Payment Method</div><div class="v">{{ $payroll->payment_method ?? '—' }}</div></div>
            <div><div class="l">Payment Date</div><div class="v">{{ $payroll->payment_date?->format('d M Y') ?? '—' }}</div></div>
            <div><div class="l">Issued On</div><div class="v">{{ now()->format('d M Y') }}</div></div>
        </div>
    </div>

    {{-- BOTTOM: Total Allowances only (salary payment handled separately) --}}
    <div class="net">
        <div class="label">Total Allowances</div>
        <div class="amount">Rs {{ number_format($payroll->total_allowances, 2) }}</div>
    </div>

    <div class="foot">
        <div class="ty">THANK YOU</div>
        <div>&mdash;&mdash;&mdash; Powered by Look n Cook POS &mdash;&mdash;&mdash;</div>
    </div>

</div>

<div class="actions">
    <button class="btn-print" onclick="window.print()">
        <i class="fa-solid fa-print" style="margin-right:6px;"></i>Print Slip
    </button>
    <button class="btn-back" onclick="history.back()">Back</button>
</div>

</body>
</html>