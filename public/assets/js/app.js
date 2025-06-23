var myApp = angular.module("myApp", []);

// // Custom directive to trigger DataTable after ng-repeat
// myApp.directive("datatable", function () {
//   return {
//     restrict: "A",
//     link: function (scope, element) {
//       scope.$watch(
//         function () {
//           return scope.$last;
//         },
//         function (isLast) {
//           if (isLast) {
//             setTimeout(function () {
//               $(element.closest("table")).DataTable();
//             }, 0);
//           }
//         }
//       );
//     },
//   };
// });
// For ajax

// $http.get('/api/users').then(function (response) {
//   $scope.users = response.data;
//   $timeout(function () {
//     $('#userTable').DataTable();
//   }, 0);
// });

// ToastService wrapping iziToast
myApp.factory("ToastService", function () {
  return {
    success: function (title, message) {
      iziToast.success({
        title: title || "Success",
        message: message || "Operation successful",
        position: "topRight",
      });
    },
    error: function (title, message) {
      iziToast.error({
        title: title || "Error",
        message: message || "Something went wrong",
        position: "topRight",
      });
    },
    warning: function (title, message) {
      iziToast.warning({
        title: title || "Warning",
        message: message || "Be careful",
        position: "topRight",
      });
    },
    info: function (title, message) {
      iziToast.info({
        title: title || "Info",
        message: message || "FYI",
        position: "topRight",
      });
    },
  };
});

myApp.controller("MyController", [
  "$scope",
  "$timeout",
  "ToastService",
  function ($scope, $timeout, ToastService) {
    var jb = this;

    jb.formData = {};

    // Example usage inside any function:
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
      chit_amount: { regex: nonEmptyRegex, message: "Chit amount cannot be empty" },
      first_name: {
        regex: nonEmptyRegex,
        message: "First Name cannot be empty",
      },
      last_name: { regex: nonEmptyRegex, message: "Last Name cannot be empty" },
      scheme_name: {
        regex: nonEmptyRegex,
        message: "Scheme Name cannot be empty",
      },
      scheme_tenure: {
        regex: nonEmptyRegex,
        message: "Scheme Tenure cannot be empty",
      },
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

    /************************* Clear Form  *****************************************/

    jb.clearForm = function (formId) {
      // Clear model data
      jb.formData = {};

      // Reset all input, textarea, and select elements
      $(
        "#" +
          formId +
          " input, #" +
          formId +
          " textarea, #" +
          formId +
          " select"
      ).each(function () {
        const type = $(this).attr("type");

        if (type === "checkbox" || type === "radio") {
          $(this).prop("checked", false);
        } else {
          $(this).val("");
        }

        // Remove validation classes and error messages
        $(this).removeClass("valid invalid border-danger");
        $(this).next(".error-message").text("");
      });

      // Also uncheck agreement if it exists
      if ($("#agree").length) {
        $("#agree").prop("checked", false);
      }
    };

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
        url: "register",
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
        url: "login",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          const { status, message } = response;
          if (status == "success") {
            window.location = "/";
          } else {
            ToastService.error("Error", response.errors);
          }
        },
      });
    };
    /************************ User Details Update  ************************/

    jb.userDetailsUpdate = async (event, formId) => {
      event.preventDefault();

      jb.validate_status = form_validation(formId);

      var formData = new FormData($("#" + formId)[0]);

      if (!jb.validate_status) {
        return;
      }

      await $.ajax({
        type: "POST",
        url: "user-details",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          const { status, message, errors } = response;
          if (status == "success") {
            ToastService.success("Success", message);
          } else {
            let firstKey = Object.keys(errors)[0];
            let firstErrorMessage = errors[firstKey][0];
            ToastService.error("Error", firstErrorMessage);
          }
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
              linkName: "Dashboard",
            },
            {
              link: "/purchase-scheme",
              linkName: "purchase scheme",
            },
            {
              link: "/pays-cheme",
              linkName: "pay scheme",
            },
            {
              link: "/view-users",
              linkName: "view users",
            },
            {
              link: "/schemes",
              linkName: "schemes",
            },
            {
              link: "/chit-details",
              linkName: "chit details",
            },
          ],
        },
        {
          title: "transaction details",
          list: [
            {
              link: "/month-wise-payment",
              linkName: "month wise payment",
            },
            {
              link: "/chit-wise-payment",
              linkName: "chit wise payment",
            },
            {
              link: "/pending-payment",
              linkName: "pending payment",
            },
            {
              link: "/change-status",
              linkName: "change status",
            },
            {
              link: "/close-scheme",
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
    jb.userDetailsInit = async (user) => {
      jb.selectUserDetilas = await user;
      jb.selectUserDetilas.dob = new Date(jb.selectUserDetilas.dob);
      jb.selectUserDetilas.anniversary = new Date(
        jb.selectUserDetilas.anniversary
      );

      if (!$rootScope.$$phase) {
        $rootScope.$apply(); // only if no digest is running
      }
    };
    /************************* Users Init Function ********************/
    jb.usersInit = async (usersDetails) => {
      jb.allUserList = await usersDetails;
      if (!$rootScope.$$phase) {
        $rootScope.$apply(); // only if no digest is running
      }
    };
    /************************* Schemes Function ********************/
    jb.schemesInit = async (schemesData) => {
      jb.schemesData = await schemesData;
      if (!$rootScope.$$phase) {
        $rootScope.$apply(); // only if no digest is running
      }
    };
    /************************ Scheme Create  ************************/

    jb.schemeCreate = async (event, formId) => {
      event.preventDefault();

      jb.validate_status = form_validation(formId);

      var formData = new FormData($("#" + formId)[0]);
      if (!jb.validate_status) {
        return;
      }

      await $.ajax({
        type: "POST",
        url: "scheme-create",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          const { status, message, errors } = response;
          if (status == "success") {
            ToastService.success("Success", message);
            jb.clearForm(formId);
          } else {
            // let firstKey = Object.keys(errors)[0];
            // let firstErrorMessage = errors[firstKey][0];
            ToastService.error("Error", message);
          }
        },
      });
    };

    jb.schemeStatusChangeModal = function (scheme) {
      jb.schemeData = scheme;
    };
    jb.schemeStatusChangeModalClose = () => {
      let id = `#schemeStatusSwitch${jb.schemeData.id}`;
      if ($(id).is(":checked")) {
        $(id).prop("checked", false);
      } else {
        $(id).prop("checked", true);
      }
    };

    jb.confirmStatusChange = async function () {
      await $.ajax({
        type: "POST",
        url: "scheme-status-update",
        data: { id: jb.schemeData.id },
        success: function (response) {
          const { status, message, errors } = response;
          if (status == "success") {
            ToastService.success("Success", message);

            jb.schemeData.scheme_status =
              jb.schemeData.scheme_status == "active" ? "inactive" : "active";
            const modal = bootstrap.Modal.getOrCreateInstance(
              $("#schemeStatusModal")[0]
            );
            modal.hide();
          } else {
            ToastService.error("Error", message);
          }
        },
      });
      if (!$rootScope.$$phase) {
        $rootScope.$apply(); // only if no digest is running
      }
    };

    /************************* Chit Init Function ********************/
    jb.chitsInit = async (chitData) => {
      jb.chitData = await chitData;
      if (!$rootScope.$$phase) {
        $rootScope.$apply(); // only if no digest is running
      }
    };

    jb.chitCreate = async (event, formId) => {
      event.preventDefault();

      jb.validate_status = form_validation(formId);

      var formData = new FormData($("#" + formId)[0]);
      if (!jb.validate_status) {
        return;
      }

      await $.ajax({
        type: "POST",
        url: "chit-create",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          const { status, message, errors } = response;
          if (status == "success") {
            ToastService.success("Success", message);
            jb.clearForm(formId);
          } else {
            // let firstKey = Object.keys(errors)[0];
            // let firstErrorMessage = errors[firstKey][0];
            ToastService.error("Error", message);
          }
        },
      });
    };
    jb.chitStatusChangeModal = function (chit) {
      jb.chitSelected = chit;
    };
    jb.chitStatusChangeModalClose = () => {
      let id = `#chitStatusSwitch${jb.chitSelected.id}`;
      if ($(id).is(":checked")) {
        $(id).prop("checked", false);
      } else {
        $(id).prop("checked", true);
      }
    };

    jb.confirmChitStatusChange = async function () {
      await $.ajax({
        type: "POST",
        url: "chit-status-update",
        data: { id: jb.chitSelected.id },
        success: function (response) {
          const { status, message, errors } = response;
          if (status == "success") {
            ToastService.success("Success", message);

            jb.chitSelected.status =
              jb.chitSelected.status == "active" ? "inactive" : "active";
            const modal = bootstrap.Modal.getOrCreateInstance(
              $("#chitStatusModal")[0]
            );
            modal.hide();
          } else {
            ToastService.error("Error", message);
          }
        },
      });
      if (!$rootScope.$$phase) {
        $rootScope.$apply(); // only if no digest is running
      }
    };
  },
]);

// Directive to run new DataTable after ng-repeat

myApp.directive("datatable", function () {
  return {
    restrict: "A",
    link: function (scope, element, attrs) {
      scope.$watch(
        function () {
          return scope.jb?.allUserList?.length;
        },
        function (newVal) {
          if (newVal > 0) {
            setTimeout(function () {
              new DataTable("#userTable", {
                dom:
                  "<'row mb-2'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + // top: length, buttons, search
                  "<'row'<'col-sm-12'tr>>" + // table
                  "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>", // bottom: info + pagination
                buttons: [
                  {
                    extend: "excel",
                    text: "Export Excel",
                  },
                  {
                    extend: "pdf",
                    text: "Export PDF",
                  },
                ],
                pageLength: 10,
                lengthMenu: [
                  [10, 20, 50, 100],
                  ["10", "20", "50", "100"],
                ],
              });
            }, 0);
          }
        }
      );
    },
  };
});

// ng-options="user.id as (user.mobile + ' - ' + user.user_name) for user in jb.allUserList" <?php if ($this->auth_user_role_id == 1) {
//                         $this->auth_user_id ?>disabled <?php } ?>
