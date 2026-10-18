@extends('layouts.master') 

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 text-center">
                    <h2 class="fw-bold" style="color: #ff2d7a;">Staff & Customer Review</h2>
                    <p class="text-muted">Please fill out this form to submit your review.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Success/Error Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('staff.reviews.store') }}" method="POST">
                        @csrf
                        
                        <!-- Staff Name (Auto-filled & Read-only) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Staff Name</label>
                            <input type="text" class="form-control form-control-lg bg-light" 
                                   name="name" value="{{ Auth::user()->name ?? 'Guest User' }}" readonly>
                        </div>

                        <!-- ✅ Table Dropdown (Fetching Table ID) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Table</label>
                            <select class="form-select form-select-lg" name="table_id" required>
                                <option value="" disabled selected>Choose a table...</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}">
                                        {{ $table->table_name }} ({{ $table->table_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Message</label>
                            <textarea class="form-control form-control-lg" 
                                      name="message" rows="4" placeholder="Write your feedback here..." required></textarea>
                        </div>

                        <!-- Ratings (Bootstrap 5 Star Style) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block">Ratings</label>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="btn-group" role="group" aria-label="Rating Selection">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}" required>
                                        <label class="btn btn-outline-warning btn-lg" for="rating{{ $i }}">
                                            {{ $i }} <i class="fa-solid fa-star text-warning"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg fw-bold text-white" 
                                    style="background-color: #ff2d7a; border-radius: 12px;">
                                Submit Review
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection