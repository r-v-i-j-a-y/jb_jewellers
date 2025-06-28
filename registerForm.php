<form id="registerForm">
    <div class="mb-3">
        <input type="text" name="user_name" class="form-control rounded-pill" placeholder="Your Name"
            autocomplete="user_name">
        <p class="m-1 text-danger error-message"></p>
    </div>
    <div class="mb-3">
        <input type="text" name="email" class="form-control rounded-pill" placeholder="Email Address"
            onkeypress="return event.charCode != 32" autocomplete="email">
        <p class="m-1 text-danger error-message"></p>
    </div>
    <div class="mb-3">
        <input type="text" name="mobile" class="form-control rounded-pill" placeholder="Mobile Number"
            autocomplete="mobile">
        <p class="m-1 text-danger error-message"></p>
    </div>
    <div class="mb-3 position-relative">
        <input type="password" name="password" class="form-control rounded-pill" placeholder="Password"
            onkeypress="return event.charCode != 32" autocomplete="off">
        <p class="m-1 text-danger error-message"></p>
        <i class="fas fa-eye-slash fa-lg text-primary cursor-pointer position-absolute toggle-password top-50"
            onclick="showPassword(event)"></i>
    </div>
    <div class="mb-3">
        <input type="password" name="password_confirmation" class="form-control rounded-pill"
            placeholder="Confirm Password" onkeypress="return event.charCode != 32" autocomplete="off">
        <p class="m-1 text-danger error-message"></p>
    </div>


    <button class="btn btn-primary w-100 rounded-pill mt-3" onclick="registerSubmit(event,'registerForm')">
        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
        <span class="d-flex justify-content-center align-items-center" role="status"> <i
                class="fa-solid fa-address-card me-2"></i>Register</span>
    </button>
</form>