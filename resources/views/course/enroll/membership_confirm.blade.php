@extends('layouts.master')

@section('content')

<div class="container py-5"> 
    <div class="row justify-content-center"> 
        <div class="col-md-7 col-lg-6"> 
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden"> 

```
            <!-- Header Banner --> 
            <div class="card-header bg-gradient bg-primary text-white p-4 text-center border-0"> 
                <span class="badge bg-white text-primary fw-semibold px-3 py-2 rounded-pill mb-2 text-uppercase tracking-wide" style="font-size: 0.75rem;"> 
                    Course Enrollment 
                </span> 
                <h4 class="fw-bold mb-1">{{ $course->title }}</h4> 
                <p class="mb-0 text-white-50 fs-6">Confirm your details to continue</p> 
            </div> 

            <!-- Body Content --> 
            <div class="card-body p-4 p-md-5 bg-white"> 

                <!-- User Welcome Greeting --> 
                <div class="d-flex align-items-center p-3 mb-4 rounded-3 bg-light border"> 
                    <div class="avatar-circle bg-primary text-white me-3 d-flex align-items-center justify-content-center rounded-circle fw-bold fs-5" style="width: 48px; height: 48px;"> 
                        {{ strtoupper(substr($user->name, 0, 1)) }} 
                    </div> 
                    <div> 
                        <div class="text-muted small">Logged in as</div> 
                        <div class="fw-bold text-dark fs-6">{{ $user->name }}</div> 
                    </div> 
                </div> 

                <form action="{{ route('enroll.store', $course->id) }}" method="POST"> 
                    @csrf 
                     
                    <div class="mb-4"> 
                        <label class="form-label fw-semibold text-dark mb-2"> 
                            Select Membership Status 
                        </label> 

                        <!-- Member --> 
                        <div class="form-check custom-radio-card mb-2 p-0"> 
                            <input 
                                class="btn-check" 
                                type="radio" 
                                name="membership_status" 
                                id="status_member" 
                                value="member" 
                                {{ $user->studentProfile->membership_status === 'member' ? 'checked' : '' }} 
                                required
                            > 
                            <label class="btn btn-outline-primary w-100 text-start p-3 rounded-3 d-flex align-items-center justify-content-between" for="status_member"> 
                                <div class="d-flex align-items-center"> 
                                    <i class="bi bi-shield-check fs-4 me-3"></i> 
                                    <div> 
                                        <div class="fw-bold">Member</div> 
                                        <div class="small opacity-75">Access member discounted rate</div> 
                                    </div> 
                                </div> 
                                <i class="bi bi-check-circle-fill check-icon"></i> 
                            </label> 
                        </div>

                        <!-- Member Code -->
                        <div id="memberCodeContainer" class="mt-3" style="display: none;">
                            <label for="member_code" class="form-label fw-semibold">
                                Membership Code
                            </label>

                            <input
                                type="text"
                                class="form-control form-control-lg rounded-3"
                                id="member_code"
                                name="member_code"
                                value="{{ old('member_code') }}"
                                placeholder="Enter your membership code"
                            >

                            @error('member_code')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Non-Member --> 
                        <div class="form-check custom-radio-card mt-2 p-0"> 
                            <input 
                                class="btn-check" 
                                type="radio" 
                                name="membership_status" 
                                id="status_non_member" 
                                value="non-member" 
                                {{ $user->studentProfile->membership_status === 'non-member' ? 'checked' : '' }} 
                                required
                            > 
                            <label class="btn btn-outline-primary w-100 text-start p-3 rounded-3 d-flex align-items-center justify-content-between" for="status_non_member"> 
                                <div class="d-flex align-items-center"> 
                                    <i class="bi bi-person fs-4 me-3"></i> 
                                    <div> 
                                        <div class="fw-bold">Non-Member</div> 
                                        <div class="small opacity-75">Standard course fee applies</div> 
                                    </div> 
                                </div> 
                                <i class="bi bi-check-circle-fill check-icon"></i> 
                            </label> 
                        </div>
                         
                        @error('membership_status') 
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div> 
                        @enderror 
                    </div> 

                    <!-- Action Button --> 
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm py-3"> 
                        Proceed to Payment <i class="bi bi-arrow-right ms-2"></i> 
                    </button> 
                </form> 
            </div> 
        </div> 
    </div> 
</div> 
```

</div> 

<style> 
    .btn-check:not(:checked) + .btn-outline-primary { 
        background-color: #f8f9fa; 
        border-color: #dee2e6; 
        color: #212529; 
    } 

    .btn-check:not(:checked) + .btn-outline-primary .check-icon { 
        display: none; 
    } 

    .btn-check:checked + .btn-outline-primary { 
        background-color: #f0f7ff; 
        border-color: #0d6efd; 
        color: #0d6efd; 
    } 
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const memberRadio = document.getElementById('status_member');
    const nonMemberRadio = document.getElementById('status_non_member');
    const memberCodeContainer = document.getElementById('memberCodeContainer');
    const memberCodeInput = document.getElementById('member_code');

    function updateMemberCode() {

        if (memberRadio.checked) {
            memberCodeContainer.style.display = 'block';
            memberCodeInput.required = true;
        } else {
            memberCodeContainer.style.display = 'none';
            memberCodeInput.required = false;
            memberCodeInput.value = '';
        }
    }

    memberRadio.addEventListener('change', updateMemberCode);
    nonMemberRadio.addEventListener('change', updateMemberCode);

    // Handle previously selected Member after validation error/page reload
    updateMemberCode();
});
</script>

@endsection
