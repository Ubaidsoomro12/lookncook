{{-- resources/views/admin/pages/gallery/index.blade.php --}}
@extends('admin.layouts.master')

@section('content')
<style>
    /* ============================================
       GALLERY MANAGEMENT STYLES
    ============================================ */
    .gallery-container {
        padding: 20px 25px;
    }

    .gallery-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .gallery-header h2 {
        font-size: 26px;
        font-weight: 700;
        color: #000000;
        margin: 0;
    }

    .gallery-header h2 small {
        font-size: 14px;
        font-weight: 400;
        color: #6b7280;
        display: block;
        margin-top: 4px;
    }

    .form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .form-card .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #000000;
        margin-bottom: 8px;
    }

    .form-card .card-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 25px;
    }

    .form-card .card-subtitle i {
        color: #ff2d7a;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 14px;
        color: #1f2937;
        display: block;
        margin-bottom: 6px;
    }

    .form-group label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        color: #1f2937;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .form-control:focus {
        border-color: #ff2d7a;
        box-shadow: 0 0 0 3px rgba(255, 45, 122, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
        font-family: inherit;
    }

    .grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .grid-9 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .image-card {
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 16px;
        transition: all 0.2s ease;
    }

    .image-card:hover {
        border-color: #ff2d7a;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .image-card .image-preview-sm {
        width: 100%;
        height: 150px;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-card .image-preview-sm img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-card .image-preview-sm .no-image-sm {
        color: #9ca3af;
        font-size: 11px;
        text-align: center;
    }

    .image-card .image-preview-sm .no-image-sm i {
        font-size: 28px;
        display: block;
        margin-bottom: 4px;
    }

    .image-card .form-group {
        margin-top: 10px;
        margin-bottom: 0;
    }

    .image-card .form-control {
        padding: 8px 12px;
        font-size: 13px;
    }

    .section-divider {
        border: none;
        border-top: 2px dashed #e5e7eb;
        margin: 30px 0;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #ff2d7a;
    }

    .btn-submit {
        background: #ff2d7a;
        color: #ffffff;
        border: none;
        padding: 14px 40px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-submit:hover {
        background: #e01a66;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 45, 122, 0.3);
    }

    .btn-submit i {
        font-size: 16px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        flex-wrap: wrap;
    }

    /* Alert Messages */
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        padding: 14px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success i {
        font-size: 20px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .grid-3, .grid-9 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .gallery-container {
            padding: 12px 14px;
        }

        .form-card {
            padding: 18px;
        }

        .grid-3, .grid-9 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="gallery-container">
    <!-- Header -->
    <div class="gallery-header">
        <div>
            <h2>
              Gallery Management
                <small>Manage all gallery content in one place</small>
            </h2>
        </div>
    </div>

   <!-- Success Message -->
@if(session('success'))
    <div class="alert-success" id="success-alert">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000); // 3 seconds
    </script>
@endif

    <!-- ============================================ -->
    <!-- SINGLE FORM - ALL FIELDS                     -->
    <!-- ============================================ -->
    <div class="form-card">
        <h3 class="card-title">Gallery Settings</h3>
        <p class="card-subtitle">
            <i class="fas fa-info-circle"></i>
            Update all gallery content including hero section, featured images, and gallery grid
        </p>

        <form action="{{ route('admin.gallery.update', $gallery->id ?? 1) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- ========================================== -->
            <!-- SECTION 1: HERO SECTION                    -->
            <!-- ========================================== -->
            <div class="section-title">
                <i class="fas fa-crown"></i> Hero Section
            </div>

            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" 
                       placeholder="Enter gallery title" 
                       value="{{ old('title', $gallery->title ?? 'OUR GALLERY') }}">
            </div>

            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" name="subtitle" class="form-control" 
                       placeholder="Enter subtitle" 
                       value="{{ old('subtitle', $gallery->subtitle ?? 'Moments That Inspire') }}">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" 
                          placeholder="Enter description">{{ old('description', $gallery->description ?? 'Experience the artistry behind every event. From intimate gatherings to grand celebrations, we capture the essence of premium catering and unforgettable dining experiences.') }}</textarea>
            </div>

            <hr class="section-divider">

            <!-- ========================================== -->
            <!-- SECTION 2: FEATURED IMAGES (3)            -->
            <!-- ========================================== -->
            <div class="section-title">
                <i class="fas fa-star"></i> Featured Images (3)
            </div>

            <div class="grid-3">
                <!-- Image 1 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->image1)
                            <img src="{{ asset($gallery->image1) }}" alt="Image 1">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 1</label>
                        <input type="file" name="image1" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 1 Title</label>
                        <input type="text" name="image1_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('image1_title', $gallery->image1_title ?? 'Gourmet Artistry') }}">
                    </div>
                </div>

                <!-- Image 2 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->image2)
                            <img src="{{ asset($gallery->image2) }}" alt="Image 2">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 2</label>
                        <input type="file" name="image2" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 2 Title</label>
                        <input type="text" name="image2_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('image2_title', $gallery->image2_title ?? 'Elegant Dining') }}">
                    </div>
                </div>

                <!-- Image 3 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->image3)
                            <img src="{{ asset($gallery->image3) }}" alt="Image 3">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 3</label>
                        <input type="file" name="image3" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 3 Title</label>
                        <input type="text" name="image3_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('image3_title', $gallery->image3_title ?? 'Premium Events') }}">
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- ========================================== -->
            <!-- SECTION 3: GALLERY GRID (9 IMAGES)        -->
            <!-- ========================================== -->
            <div class="section-title">
                <i class="fas fa-th"></i> Gallery Grid (9 Images)
            </div>

            <div class="grid-9">
                <!-- Gallery Image 1 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img1)
                            <img src="{{ asset($gallery->gallery_img1) }}" alt="Gallery Image 1">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 1</label>
                        <input type="file" name="gallery_img1" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img1_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img1_title', $gallery->gallery_img1_title ?? 'Exquisite Cuisine') }}">
                    </div>
                </div>

                <!-- Gallery Image 2 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img2)
                            <img src="{{ asset($gallery->gallery_img2) }}" alt="Gallery Image 2">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 2</label>
                        <input type="file" name="gallery_img2" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img2_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img2_title', $gallery->gallery_img2_title ?? 'Chef Special') }}">
                    </div>
                </div>

                <!-- Gallery Image 3 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img3)
                            <img src="{{ asset($gallery->gallery_img3) }}" alt="Gallery Image 3">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 3</label>
                        <input type="file" name="gallery_img3" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img3_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img3_title', $gallery->gallery_img3_title ?? 'Luxury Setting') }}">
                    </div>
                </div>

                <!-- Gallery Image 4 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img4)
                            <img src="{{ asset($gallery->gallery_img4) }}" alt="Gallery Image 4">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 4</label>
                        <input type="file" name="gallery_img4" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img4_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img4_title', $gallery->gallery_img4_title ?? 'Fresh Ingredients') }}">
                    </div>
                </div>

                <!-- Gallery Image 5 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img5)
                            <img src="{{ asset($gallery->gallery_img5) }}" alt="Gallery Image 5">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 5</label>
                        <input type="file" name="gallery_img5" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img5_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img5_title', $gallery->gallery_img5_title ?? 'Signature Dish') }}">
                    </div>
                </div>

                <!-- Gallery Image 6 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img6)
                            <img src="{{ asset($gallery->gallery_img6) }}" alt="Gallery Image 6">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 6</label>
                        <input type="file" name="gallery_img6" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img6_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img6_title', $gallery->gallery_img6_title ?? 'Perfect Plating') }}">
                    </div>
                </div>

                <!-- Gallery Image 7 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img7)
                            <img src="{{ asset($gallery->gallery_img7) }}" alt="Gallery Image 7">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 7</label>
                        <input type="file" name="gallery_img7" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img7_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img7_title', $gallery->gallery_img7_title ?? 'Wine Pairing') }}">
                    </div>
                </div>

                <!-- Gallery Image 8 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img8)
                            <img src="{{ asset($gallery->gallery_img8) }}" alt="Gallery Image 8">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 8</label>
                        <input type="file" name="gallery_img8" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img8_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img8_title', $gallery->gallery_img8_title ?? 'Dessert Delight') }}">
                    </div>
                </div>

                <!-- Gallery Image 9 -->
                <div class="image-card">
                    <div class="image-preview-sm">
                        @if($gallery && $gallery->gallery_img9)
                            <img src="{{ asset($gallery->gallery_img9) }}" alt="Gallery Image 9">
                        @else
                            <div class="no-image-sm">
                                <i class="fas fa-image"></i>
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Image 9</label>
                        <input type="file" name="gallery_img9" class="form-control" style="padding: 4px;">
                    </div>
                    <div class="form-group">
                        <label style="font-size: 13px;">Title</label>
                        <input type="text" name="gallery_img9_title" class="form-control" 
                               placeholder="Enter title" 
                               value="{{ old('gallery_img9_title', $gallery->gallery_img9_title ?? 'Memorable Moments') }}">
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- SUBMIT BUTTON                             -->
            <!-- ========================================== -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Gallery
                </button>
            </div>
        </form>
    </div>
</div>
@endsection