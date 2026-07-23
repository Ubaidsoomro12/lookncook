<!-- =========================================
     REVIEW FORM SECTION
========================================= -->


<section class="py-5" style="background:#fff7fb;">

    <div class="container">

        <div class="text-center mb-5">

            <span class="fw-semibold"
                  style="color:#ff2d7a;letter-spacing:2px;">

                CUSTOMER REVIEW

            </span>

            <h2 class="fw-bold mt-2">

                Leave a Review

            </h2>

            <p class="text-muted">

                We value your feedback. Share your experience with us!

            </p>

        </div>

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-lg p-4"
                     style="border-radius:20px;">

                    <form method="POST" action="#">

                        @csrf

                        <div class="row g-3">

                            <!-- Name -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Name
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control py-2"
                                       value="{{ old('name') }}"
                                       required>

                            </div>

                            <!-- Email -->
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control py-2"
                                       value="{{ old('email') }}"
                                       required>

                            </div>

                            <!-- Profession -->
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Profession
                                </label>

                                <input type="text"
                                       name="profession"
                                       class="form-control py-2"
                                       value="{{ old('profession') }}"
                                       required>

                            </div>

                            <!-- Message -->
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Message
                                </label>

                                <textarea name="message"
                                          rows="5"
                                          class="form-control"
                                          required>{{ old('message') }}</textarea>

                            </div>

                            <!-- Rating -->
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Rating
                                </label>

                                <div class="d-flex gap-3 mt-2">

                                    @for($i = 1; $i <= 5; $i++)

                                        <label class="fs-4 text-warning">

                                            <input type="radio"
                                                   name="rating"
                                                   value="{{ $i }}"
                                                   required>

                                            ⭐

                                        </label>

                                    @endfor

                                </div>

                            </div>

                            <!-- Button -->
                            <div class="col-12 text-center mt-4">

                                <button type="submit"
                                        class="btn text-white px-5 py-2 fw-semibold"
                                        style="background:#ff2d7a;border-radius:30px;">

                                    Submit Review

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

<style>

input[type="radio"]{
    margin-right:5px;
}

.form-control:focus{
    border-color:#ff2d7a;
    box-shadow:0 0 0 .15rem rgba(255,45,122,.15);
}

</style>

