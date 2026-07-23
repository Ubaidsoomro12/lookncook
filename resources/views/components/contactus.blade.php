<section class="contact-page-section py-5">
    <div class="container py-lg-4">
        <div
            class="row g-0 align-items-stretch position-relative contact-relative-row shadow-lg rounded-4 overflow-hidden bg-white">

            <div class="col-1023-image contact-image-side position-relative">
                <img src="{{ asset('images/contact-banner.png') }}" class="contact-main-image" alt="Look N Cook">
                <div class="image-overlay-gradient"></div>
            </div>

            <div class="col-1023-form contact-form-side d-flex align-items-center">
                <div class="contact-form-box w-100 animate-fade-in">

                    <div class="text-center mb-4">
                        <span class="badge rounded-pill px-3 py-2 mb-2"
                            style="background: rgba(255, 45, 122, 0.1); color: #ff2d7a; font-weight: 700; letter-spacing: 1px; font-size: 12px; text-transform: uppercase;">Get
                            In Touch</span>
                        <h2 class="contact-title mb-2">Contact Us</h2>
                        <p class="text-muted small px-3">Have questions about our recipes, catering, or table bookings?
                            Drop us a message!</p>
                    </div>
                    @if(session('success'))
                        <div id="success-alert" class="alert alert-success">
                            {{ session('success') }}
                        </div>

                        <script>
                            setTimeout(function () {
                                const alert = document.getElementById('success-alert');
                                if (alert) {
                                    alert.style.transition = 'opacity 0.5s ease';
                                    alert.style.opacity = '0';

                                    setTimeout(function () {
                                        alert.remove();
                                    }, 500);
                                }
                            }, 3000); // 3000 milliseconds = 3 seconds
                        </script>
                    @endif




                    <form action="{{ route('contacts.store') }}" method="POST" autocomplete="off">

                        @csrf

                        <div class="form-floating mb-2">
                            <input type="text" name="name"
                                class="form-control contact-input @error('name') is-invalid @enderror" id="floatingName"
                                placeholder="Your Name" value="{{ old('name') }}">

                            <label for="floatingName">
                                <i class="bi bi-person me-2"></i>Full Name
                            </label>
                        </div>

                        @error('name')
                            <div class="text-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="form-floating mb-2">
                            <input type="tel" name="phone"
                                class="form-control contact-input @error('phone') is-invalid @enderror"
                                id="floatingPhone" placeholder="Phone Number" value="{{ old('phone') }}">

                            <label for="floatingPhone">
                                <i class="bi bi-telephone me-2"></i>Phone Number
                            </label>
                        </div>

                        @error('phone')
                            <div class="text-danger mb-3">{{ $message }}</div>
                        @enderror


                        <div class="form-floating mb-2">
                            <select name="profession"
                                class="form-select contact-input @error('profession') is-invalid @enderror"
                                id="floatingProfession">

                                <option value="">Select your profession</option>
                                <option value="Home Cook / Homemaker" {{ old('profession') == 'Home Cook / Homemaker' ? 'selected' : '' }}>Home Cook / Homemaker</option>
                                <option value="Professional Chef" {{ old('profession') == 'Professional Chef' ? 'selected' : '' }}>Professional Chef / Baker</option>
                                <option value="Food Blogger / Content Creator" {{ old('profession') == 'Food Blogger / Content Creator' ? 'selected' : '' }}>Food Blogger / Content Creator</option>
                                <option value="Restaurant Owner / Manager" {{ old('profession') == 'Restaurant Owner / Manager' ? 'selected' : '' }}>Restaurant Owner / Manager</option>
                                <option value="Caterer / Event Planner" {{ old('profession') == 'Caterer / Event Planner' ? 'selected' : '' }}>Caterer / Event Planner</option>
                                <option value="Working Professional / Corporate Employee" {{ old('profession') == 'Working Professional / Corporate Employee' ? 'selected' : '' }}>Working Professional /
                                    Corporate Employee</option>
                                <option value="Student" {{ old('profession') == 'Student' ? 'selected' : '' }}>Student
                                </option>
                                <option value="Other" {{ old('profession') == 'Other' ? 'selected' : '' }}>Other
                                    Profession</option>

                            </select>

                            <label for="floatingProfession">
                                <i class="bi bi-briefcase me-2"></i>Profession
                            </label>
                        </div>

                        @error('profession')
                            <div class="text-danger mb-3">{{ $message }}</div>
                        @enderror


                        <div class="form-floating mb-2">
                            <textarea name="message"
                                class="form-control contact-textarea @error('message') is-invalid @enderror"
                                id="floatingMessage" style="height:120px"
                                placeholder="Message">{{ old('message') }}</textarea>

                            <label for="floatingMessage">
                                <i class="bi bi-chat-left-text me-2"></i>Your Message
                            </label>
                        </div>

                        @error('message')
                            <div class="text-danger mb-3">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="contact-submit-btn w-100 py-3 rounded-3">
                            Send Message
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

<style>
    /* Google Fonts Import for clean modern aesthetics */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

    .contact-page-section {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f8f9fa;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    /* Core Layout Configurations */
    .contact-image-side {
        width: 100%;
    }

    .contact-main-image {
        width: 100%;
        height: 700px;
        object-fit: cover;
    }

    .image-overlay-gradient {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.0));
        pointer-events: none;
    }

    /* Premium Form Styling */
    .contact-form-box {
        background: #ffffff;
        padding: 2.5rem;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .contact-title {
        font-size: 32px;
        font-weight: 800;
        background: linear-gradient(135deg, #e54b9d 0%, #ff2d7a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Floating Inputs Optimization */
    .contact-input,
    .contact-textarea {
        border: 1.5px solid #eaeaea !important;
        border-radius: 12px !important;
        background-color: #fcfcfc !important;
        font-size: 15px;
        color: #333 !important;
        transition: all 0.25s ease-in-out !important;
    }

    /* Dropdown native arrow custom position to sit beautifully with floating labels */
    .form-select.contact-input {
        background-position: right 1rem center !important;
    }

    .contact-input:focus,
    .contact-textarea:focus {
        border-color: #ff2d7a !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(255, 45, 122, 0.15) !important;
    }

    /* Modern Gradient Submit Button */
    .contact-submit-btn {
        border: none;
        background: linear-gradient(135deg, #ff4da0 0%, #ff2d7a 100%);
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 20px rgba(255, 45, 122, 0.3);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .contact-submit-btn:hover {
        background: linear-gradient(135deg, #ff2d7a 0%, #d6125e 100%);
        box-shadow: 0 10px 24px rgba(255, 45, 122, 0.4);
        transform: translateY(-2px);
    }

    .contact-submit-btn:active {
        transform: translateY(1px);
    }

    /* Animation */
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.98);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* =======================================================
       1023px Breakpoint Rule: Image 75% Right, Form Leftmost Overlay
    ======================================================= */
    @media(min-width: 1023px) {
        .contact-relative-row {
            position: relative;
            min-height: 700px;
        }

        /* Image shifts right, takes exactly 75% width */
        .contact-image-side {
            width: 75% !important;
            margin-left: auto !important;
            margin-right: 0 !important;
            z-index: 1;
        }

        /* Form centers vertically on the Leftmost side overlaying the image */
        .contact-form-side {
            position: absolute;
            top: 50%;
            left: 4% !important;
            /* Kept slight spacing from screen edge */
            right: auto !important;
            transform: translateY(-50%);
            z-index: 10;
            width: 100%;
            max-width: 480px;
            /* Perfectly sized standard form box width */
        }
    }

    /* Responsive adjustments for Mobile & Tablets */
    @media(max-width: 1022px) {
        .contact-form-side {
            margin-top: -80px;
            position: relative;
            z-index: 12;
            padding: 0 1.5rem;
        }

        .contact-main-image {
            height: 400px;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
        }

        .contact-form-box {
            padding: 2rem;
        }
    }

    @media(max-width: 576px) {
        .contact-title {
            font-size: 26px;
        }

        .contact-form-box {
            padding: 1.5rem 1.25rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Target all the error message divs
        const errorMessages = document.querySelectorAll('.text-danger');

        // Wait 5 seconds (5000 milliseconds), then hide them
        setTimeout(function () {
            errorMessages.forEach(function (msg) {
                // Smoothly fade out
                msg.style.transition = "opacity 0.5s ease";
                msg.style.opacity = "0";

                // Remove from layout after fade out completes
                setTimeout(() => msg.style.display = "none", 500);
            });
        }, 5000);
    });
</script>