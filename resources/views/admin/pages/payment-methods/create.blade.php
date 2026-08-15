@extends('admin.layouts.master')
@section('title', 'Create | Payments')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.payment-methods.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Add Payment Method</h1>
    </div>

    @if($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
        <form action="{{ route('admin.payment-methods.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
            @csrf

            <!-- ====== TYPE SELECTION (ALWAYS VISIBLE, DEFAULT EMPTY) ====== -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Select Payment Type <span class="text-red-500">*</span>
                </label>
                <select name="type" id="typeSelect" required
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('type') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all">
                    <option value="">-- Choose a type --</option>
                    <option value="cod">Cash on Delivery</option>
                    <option value="mobile_wallet">Mobile Wallet</option>
                    <option value="bank">Bank Transfer</option>
                </select>
                @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- ====== ALL OTHER FIELDS – HIDDEN INITIALLY ====== -->
            <div id="additionalFields" style="display: none;">

                <!-- Name (this will be the bank name for bank type) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                       Bank Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all"
                           placeholder="e.g. EasyPaisa, Meezan Bank">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- ====== LOGO UPLOAD ====== -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Logo <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <div class="flex items-center gap-4">
                        <div id="logoPreviewWrap" class="w-16 h-16 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shrink-0">
                            <img id="logoPreviewImg" src="" alt="Logo preview" class="w-full h-full object-contain hidden">
                            <i id="logoPreviewIcon" class="fa-solid fa-image text-gray-300 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="logo" id="logoInput" accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-[#334155] file:text-white hover:file:bg-[#1e293b] file:cursor-pointer cursor-pointer border {{ $errors->has('logo') ? 'border-red-300' : 'border-gray-200' }} rounded-xl">
                            <p class="text-xs text-gray-400 mt-1.5">PNG, JPG, WEBP or SVG. Square images look best. Max 2MB.</p>
                        </div>
                    </div>
                    @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Conditional type-specific fields -->
                <div id="conditionalFields">
                    
                    <!-- BANK FIELDS (no separate Bank Name – we use the main Name) -->
                    <div id="bankFields" style="display: none;">
                        <hr class="border-gray-200 my-4">
                        <h3 class="text-sm font-semibold text-gray-700">Bank Details</h3>
                        <div class="space-y-4 mt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Account Holder</label>
                                    <input type="text" name="account_title" value="{{ old('account_title') }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all"
                                           placeholder="e.g. Look n Cook">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Account Number</label>
                                    <input type="text" name="account_number" value="{{ old('account_number') }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all"
                                           placeholder="e.g. 03493568403">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">IBAN <span class="text-gray-400 font-normal">(optional)</span></label>
                                    <input type="text" name="iban" value="{{ old('iban') }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all"
                                           placeholder="e.g. PK48MEZNO0003OO112913065">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- WALLET FIELDS -->
                    <div id="walletFields" style="display: none;">
                        <hr class="border-gray-200 my-4">
                        <h3 class="text-sm font-semibold text-gray-700">Mobile Wallet Details</h3>
                        <div class="space-y-4 mt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Account Holder</label>
                                    <input type="text" name="account_title" value="{{ old('account_title') }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all"
                                           placeholder="e.g. Tasliman">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Account Number</label>
                                    <input type="text" name="account_number" value="{{ old('account_number') }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all"
                                           placeholder="e.g. 03493568403">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deep Link <span class="text-gray-400 font-normal">(optional)</span></label>
                                    <input type="url" name="deep_link" value="{{ old('deep_link') }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#334155]/20 focus:border-[#334155] transition-all"
                                           placeholder="https://jazzcash.com.pk/pay/...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- COD has no extra fields -->
                </div>

                <!-- Active -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-[#334155] border-gray-300 rounded focus:ring-[#334155]">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-[#1e293b] to-[#334155] text-white font-medium px-6 py-2.5 rounded-xl shadow-md hover:opacity-90 transition-all">
                        <i class="fa-solid fa-check"></i> Save Method
                    </button>
                    <a href="{{ route('admin.payment-methods.index') }}"
                       class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('typeSelect');
        const additionalFields = document.getElementById('additionalFields');
        const bankFields = document.getElementById('bankFields');
        const walletFields = document.getElementById('walletFields');

        function toggleFields() {
            const type = typeSelect.value;

            if (type) {
                additionalFields.style.display = 'block';
            } else {
                additionalFields.style.display = 'none';
                return;
            }

            bankFields.style.display = (type === 'bank') ? 'block' : 'none';
            walletFields.style.display = (type === 'mobile_wallet') ? 'block' : 'none';
        }

        typeSelect.addEventListener('change', toggleFields);
        // Default state: empty, all hidden
        toggleFields();

        // ====== LOGO LIVE PREVIEW ======
        const logoInput = document.getElementById('logoInput');
        const logoPreviewImg = document.getElementById('logoPreviewImg');
        const logoPreviewIcon = document.getElementById('logoPreviewIcon');

        logoInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) {
                logoPreviewImg.classList.add('hidden');
                logoPreviewIcon.classList.remove('hidden');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreviewImg.src = e.target.result;
                logoPreviewImg.classList.remove('hidden');
                logoPreviewIcon.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection