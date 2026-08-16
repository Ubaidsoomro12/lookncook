{{-- FILE: resources/views/admin/pages/riders/create.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Create | Riders')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.riders.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-pink-50 text-[#ff2d7a] hover:bg-pink-100 transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Add Rider</h1>
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

    <div class="bg-white border border-pink-100 rounded-2xl shadow-sm p-6">
        <form action="{{ route('admin.riders.store') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
            @csrf

            <!-- Photo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Rider Photo <span class="text-gray-400 font-normal">(optional)</span></label>
                <div class="flex items-center gap-4">
                    <div id="imagePreviewWrap" class="w-20 h-20 rounded-full border-2 border-pink-100 bg-pink-50 flex items-center justify-center overflow-hidden shrink-0">
                        <img id="imagePreviewImg" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        <i id="imagePreviewIcon" class="fa-solid fa-user text-pink-200 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="image" id="imageInput" accept="image/png,image/jpeg,image/jpg,image/webp"
                               class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-[#ff2d7a] file:text-white hover:file:bg-[#e01d65] file:cursor-pointer cursor-pointer border {{ $errors->has('image') ? 'border-red-300' : 'border-gray-200' }} rounded-xl">
                        <p class="text-xs text-gray-400 mt-1.5">PNG, JPG or WEBP. Square photo looks best. Max 2MB.</p>
                    </div>
                </div>
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <hr class="border-pink-50">
            <h3 class="text-sm font-semibold text-[#ff2d7a] uppercase tracking-wide">Basic Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. Ahmed Khan">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('phone') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. 03001234567">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. ahmed@example.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. Karachi">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" required rows="2"
                              class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('address') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all resize-none"
                              placeholder="Enter full address">{{ old('address') }}</textarea>
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <hr class="border-pink-50">
            <h3 class="text-sm font-semibold text-[#ff2d7a] uppercase tracking-wide">Vehicle Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Vehicle Type <span class="text-red-500">*</span></label>
                    <select name="vehicle_type" required
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('vehicle_type') ? 'border-red-300' : 'border-gray-200' }} text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all">
                        <option value="bike" {{ old('vehicle_type') === 'bike' ? 'selected' : '' }}>🏍️ Bike</option>
                        <option value="car" {{ old('vehicle_type') === 'car' ? 'selected' : '' }}>🚗 Car</option>
                        <option value="van" {{ old('vehicle_type') === 'van' ? 'selected' : '' }}>🚐 Van</option>
                        <option value="bicycle" {{ old('vehicle_type') === 'bicycle' ? 'selected' : '' }}>🚲 Bicycle</option>
                    </select>
                    @error('vehicle_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Vehicle Number / Plate</label>
                    <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. KHI-1234">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">License Number</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                           placeholder="Driving license #">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">CNIC</label>
                    <input type="text" name="cnic" value="{{ old('cnic') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                           placeholder="e.g. 42101-1234567-1">
                </div>
            </div>

            <hr class="border-pink-50">
            <h3 class="text-sm font-semibold text-[#ff2d7a] uppercase tracking-wide">Other Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Emergency Contact</label>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all"
                           placeholder="Alternate contact number">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Joining Date</label>
                    <input type="date" name="joining_date" value="{{ old('joining_date') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff2d7a]/20 focus:border-[#ff2d7a] transition-all resize-none"
                              placeholder="Any additional notes about this rider">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-[#ff2d7a] border-gray-300 rounded focus:ring-[#ff2d7a]">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#ff2d7a] to-[#ff6fa5] text-white font-medium px-6 py-2.5 rounded-xl shadow-md shadow-pink-200 hover:opacity-90 transition-all">
                    <i class="fa-solid fa-check"></i> Save Rider
                </button>
                <a href="{{ route('admin.riders.index') }}"
                   class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const imagePreviewImg = document.getElementById('imagePreviewImg');
        const imagePreviewIcon = document.getElementById('imagePreviewIcon');

        imageInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) {
                imagePreviewImg.classList.add('hidden');
                imagePreviewIcon.classList.remove('hidden');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewImg.src = e.target.result;
                imagePreviewImg.classList.remove('hidden');
                imagePreviewIcon.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection