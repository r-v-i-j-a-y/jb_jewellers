var myApp = angular.module("myApp", []);

myApp.controller("MyController", [
  "$scope",
  function ($scope, jb, $timeout) {
    var jb = this;
    jb.name = "vijay";
    jb.formData = {};

    const nonEmptyRegex = /^(?!\s*$).+$/;
    const phoneRegex = /^\+?[1-9][0-9]{7,14}$/;
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    // const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    const passwordRegex = /^\+?[1-9][0-9]{7,15}$/;
    var $rootScope = angular
      .element(document.body)
      .injector()
      .get("$rootScope");

    //  Validation rules
    const validationRules = {
      name: { regex: nonEmptyRegex, message: "Name cannot be empty" },
      first_name: {
        regex: nonEmptyRegex,
        message: "First Name cannot be empty",
      },
      last_name: { regex: nonEmptyRegex, message: "Last Name cannot be empty" },
      user_id: { regex: nonEmptyRegex, message: "Select User" },
      dob: { regex: nonEmptyRegex, message: "Date of Birth cannot be empty" },
      address1: { regex: nonEmptyRegex, message: "Address cannot be empty" },
      city: { regex: nonEmptyRegex, message: "City cannot be empty" },
      state: { regex: nonEmptyRegex, message: "State cannot be empty" },
      state: { regex: nonEmptyRegex, message: "State cannot be empty" },
      pincode: { regex: nonEmptyRegex, message: "Pincode cannot be empty" },
      pan_number: {
        regex: nonEmptyRegex,
        message: "Pan Number cannot be empty",
      },
      aadhaar_number: {
        regex: nonEmptyRegex,
        message: "Nominee cannot be empty",
      },
      nominee_relation: {
        regex: nonEmptyRegex,
        message: "Nominee Relation cannot be empty",
      },
      nominee: {
        regex: nonEmptyRegex,
        message: "Aadhaar Number cannot be empty",
      },
      user_name: { regex: nonEmptyRegex, message: "Name cannot be empty" },
      mobile: { regex: phoneRegex, message: "Mobile Number cannot be empty" },
      email: { regex: emailRegex, message: "Invalid email format" },
      password: {
        regex: passwordRegex,
        message:
          "Password must be 8+ chars, include upper, lower, number & special char",
      },
      password_confirmation: {
        regex: nonEmptyRegex,
        message: "Confirm Password cannot be empty",
        matchField: "password",
        matchMessage: "Password and confirm password do not match",
      },
      agree: {
        validate: () => document.getElementById("agree").checked,
        message: "You must agree to the terms and conditions",
      },
    };

    function getInputElement(name) {
      return $("input[name='" + name + "']");
    }

    // display errors
    function showError(name, message) {
      const input = getInputElement(name);
      input.addClass("invalid").removeClass("valid");
      input.addClass("border-danger");
      input.next(".error-message").text(message);
    }

    function clearError(name) {
      const input = getInputElement(name);
      input.removeClass("invalid").addClass("valid");
      input.removeClass("border-danger");
      input.next(".error-message").text("");
    }

    jb.validate = function (fieldName) {
      const rule = validationRules[fieldName];
      const value = jb.formData[fieldName];

      if (fieldName === "agree") {
        if (!rule.validate()) {
          showError(fieldName, rule.message);
          return false;
        }
        clearError(fieldName);
        return true;
      }

      if (!rule) return true;

      if (fieldName === "password_confirmation") {
        if (value !== jb.formData.password) {
          showError(fieldName, rule.matchMessage);
          return false;
        }
      }

      if (!rule.regex.test(value || "")) {
        showError(fieldName, rule.message);
        return false;
      }

      clearError(fieldName);
      return true;
    };

    jb.submitForm = function () {
      let isValid = true;
      angular.forEach(validationRules, (rule, field) => {
        if (!jb.validate(field)) {
          isValid = false;
        }
      });

      if (isValid) {
        alert("Form submitted!");
        // process form
      } else {
        alert("Please correct the errors.");
      }
    };

    function form_validation(formId) {
      // Real-time validation
      $(
        "#" +
          formId +
          " input, #" +
          formId +
          " textarea, #" +
          formId +
          " select"
      ).on("input", function () {
        validateInput(this);
        if (this.name === "password_confirmation") {
          validatepassword_confirmation(formId);
        }
      });

      var isValid = true;

      $(
        "#" +
          formId +
          " input, #" +
          formId +
          " textarea, #" +
          formId +
          " select"
      ).each(function () {
        if (!validateInput(this)) isValid = false;
      });

      if (!validatepassword_confirmation(formId)) isValid = false;

      return isValid;
    }

    /************************* Validate Here And Error *****************************************/

    function validateInput(input) {
      var rule = validationRules[input.name];
      var errorMessage = $(input).next(".error-message");

      // Special case for checkbox validation
      if (input.type === "checkbox" && erp.checkBoxValidate) {
        if (!rule.validate()) {
          errorMessage.text(rule.message);
          $(input).addClass("invalid").removeClass("valid");
          return false;
        }
        errorMessage.text("");
        $(input).removeClass("invalid").addClass("valid");
        return true;
      }

      if (rule && !rule.regex.test($(input).val())) {
        errorMessage.text(rule.message);
        $(input).addClass("invalid").removeClass("valid");
        $(input).addClass("border-danger");
        return false;
      }
      errorMessage.text("");
      $(input).removeClass("invalid").addClass("valid");
      $(input).removeClass("border-danger");
      return true;
    }

    /************************* Function to validate password confirmation *****************************************/

    function validatepassword_confirmation(formId) {
      var password = $("#" + formId + " input[name='password']").val();
      var conf_password = $(
        "#" + formId + " input[name='password_confirmation']"
      ).val();

      // Check if confirm password field exists
      if (!conf_password) {
        // console.warn("Confirm password field not found. Skipping validation.");
        return true; // If the confirm password field doesn't exist, skip validation
      }

      var password_confirmation = $(
        "#" + formId + " input[name='password_confirmation']"
      ).val();
      var errorMessage = $(
        "#" + formId + " input[name='password_confirmation']"
      ).next(".error-message");

      if (password_confirmation !== password) {
        errorMessage.text("Passwords do not match");
        $("#" + formId + " input[name='password_confirmation']")
          .addClass("invalid")
          .removeClass("valid");
        return false;
      }
      errorMessage.text("");
      $("#" + formId + " input[name='password_confirmation']")
        .removeClass("invalid")
        .addClass("valid");
      return true;
    }

    /************************ Registration form  ************************/

    jb.registerSubmit = async (event, formId) => {
      event.preventDefault();

      jb.validate_status = form_validation(formId);

      var formData = new FormData($("#" + formId)[0]);

      console.log("asdasd", jb.validate_status, formData);

      if (!jb.validate_status) {
        return;
      }

      await $.ajax({
        type: "POST",
        url: "register/store",
        data: formData,
        processData: false, // Prevent jQuery from processing data
        contentType: false, // Prevent jQuery from setting content-type
        success: function (response) {
          const { status, message } = response;
          if (status == "success") {
            window.location = "/";
          }
        },
        error: function () {
          alert("error occured");
        },
      });
    };

    /************************ Login form  ************************/

    jb.loginSubmit = async (event, formId) => {
      event.preventDefault();

      jb.validate_status = form_validation(formId);

      var formData = new FormData($("#" + formId)[0]);

      if (!jb.validate_status) {
        return;
      }

      await $.ajax({
        type: "POST",
        url: "login/login",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          const { status, message } = response;
          if (status == "success") {
            window.location = "/";
          }
        },
        error: function () {
          alert("error occured");
        },
      });
    };
    /************************ Login form  ************************/

    jb.userDetailsUpdate = async (event, formId) => {
      event.preventDefault();

      jb.validate_status = form_validation(formId);

      var formData = new FormData($("#" + formId)[0]);

      if (!jb.validate_status) {
        return;
      }

      await $.ajax({
        type: "POST",
        url: "userdetails/update",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          const { status, message } = response;
          if (status == "success") {
            // window.location = "/";
          }
        },
        error: function () {
          alert("error occured");
        },
      });
    };

    /************************* Common Init Function ********************/
    jb.commonInit = (authData) => {
      jb.navList = [
        {
          title: "members details",
          list: [
            {
              link: "/",
              linkName: "purchase scheme",
            },
            {
              link: "/payscheme",
              linkName: "pay scheme",
            },
            {
              link: "/viewusers",
              linkName: "view users",
            },
            {
              link: "/chitdetails",
              linkName: "chit details",
            },
          ],
        },
        {
          title: "transaction details",
          list: [
            {
              link: "/monthwisepayment",
              linkName: "month wise payment",
            },
            {
              link: "/chitwisepayment",
              linkName: "chit wise payment",
            },
            {
              link: "/pendingpayment",
              linkName: "pending payment",
            },
            {
              link: "/changestatus",
              linkName: "change status",
            },
            {
              link: "/closescheme",
              linkName: "close scheme",
            },
          ],
        },
      ];
      jb.currentPath = window.location.pathname;
      jb.authData = authData;

      if (!$rootScope.$$phase) {
        $rootScope.$apply(); // only if no digest is running
      }
    };

    /************************* User Details Init Function ********************/
    jb.userDetailsInit = async (usersList) => {
      jb.allUserList = await usersList;

      $(document).ready(() => {
        if (jb.authData["role_id"] == 2) {
          $("#selectUserId").val(jb.authData["id"]);
          $("#selectUserId").css("pointer-events", "none");
        }
      });

      if (!$rootScope.$$phase) {
        $rootScope.$apply(); // only if no digest is running
      }
    };
  },
]);

// ng-options="user.id as (user.mobile + ' - ' + user.user_name) for user in jb.allUserList" <?php if ($this->auth_user_role_id == 1) {
//                         $this->auth_user_id ?>disabled <?php } ?>
