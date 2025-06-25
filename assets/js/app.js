// Converted from AngularJS to plain JavaScript with jQuery

$(document).ready(function () {});
let formData = {};
let validate_status = null;

// ToastService using iziToast
const ToastService = {
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

// Example usage inside any function:
const nonEmptyRegex = /^(?!\s*$).+$/;
const phoneRegex = /^\+?[1-9][0-9]{7,14}$/;
const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
// const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
const passwordRegex = /^\+?[1-9][0-9]{7,15}$/;
// var $rootScope = angular.element(document.body).injector().get("$rootScope");

//  Validation rules
const validationRules = {
  name: { regex: nonEmptyRegex, message: "Name cannot be empty" },
  chit_amount: {
    regex: nonEmptyRegex,
    message: "Chit amount cannot be empty",
  },
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

function validate(fieldName) {
  const rule = validationRules[fieldName];
  const value = formData[fieldName];

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
    if (value !== formData.password) {
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
}

function submitForm() {
  let isValid = true;

  // Loop through each field in the validationRules object
  for (const field in validationRules) {
    if (!validateField(field)) {
      isValid = false;
    }
  }

  if (isValid) {
    alert("Form submitted!");
    // You can handle the form submission here (e.g., via AJAX)
  } else {
    alert("Please correct the errors.");
  }
}
function form_validation(formId) {
  // Real-time validation
  $(
    "#" + formId + " input, #" + formId + " textarea, #" + formId + " select"
  ).on("input", function () {
    validateInput(this);
    if (this.name === "password_confirmation") {
      validatepassword_confirmation(formId);
    }
  });

  var isValid = true;

  $(
    "#" + formId + " input, #" + formId + " textarea, #" + formId + " select"
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

function clearForm(formId) {
  // Clear model data

  // Reset all input, textarea, and select elements
  $(
    "#" + formId + " input, #" + formId + " textarea, #" + formId + " select"
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
}

/************************ Login form  ************************/

function loginSubmit(event, formId) {
  event.preventDefault();

  validate_status = form_validation(formId);
  formData = new FormData($("#" + formId)[0]);

  if (!validate_status) {
    return;
  }

  $.ajax({
    type: "POST",
    url: "post_request/loginRequest.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      const { status, message } = response;
      if (status == "success") {
        window.location = "index.php";
      } else {
        ToastService.error("Error", response.errors);
      }
    },
  });
}

/************************ Registration form  ************************/

function registerSubmit(event, formId) {
  event.preventDefault();

  validate_status = form_validation(formId);

  formData = new FormData($("#" + formId)[0]);

  if (!validate_status) {
    return;
  }

  $.ajax({
    type: "POST",
    url: "post_request/registerRequest.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        window.location = "index.php";
      } else {
        let firstKey = Object.keys(errors)[0];
        let firstErrorMessage = errors[firstKey][0];
        ToastService.error("Error", firstErrorMessage);
      }
    },
  });
}
/************************ User Details Update  ************************/

function userDetailsUpdate(event, formId) {
  event.preventDefault();

  validate_status = form_validation(formId);

  formData = new FormData($("#" + formId)[0]);

  if (!validate_status) {
    return;
  }

  $.ajax({
    type: "POST",
    url: "post_request/userDetailsRequest.php",
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
}

/************************ Scheme Create  ************************/

async function schemeCreate(event, formId) {
  event.preventDefault();
  validate_status = form_validation(formId);
  var formData = new FormData($("#" + formId)[0]);
  if (!validate_status) {
    return;
  }

  await $.ajax({
    type: "POST",
    url: "post_request/schemeCreateRequest.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);
        clearForm(formId);
      } else {
        ToastService.error("Error", message);
      }
    },
  });
}

/************************** Scheme Status Change *******************************/
let schemeData = null;
schemeStatusChangeModal = function (scheme) {
  schemeData = JSON.parse(scheme);
  const modal = bootstrap.Modal.getOrCreateInstance($("#schemeStatusModal")[0]);
  modal.show();
};
schemeStatusChangeModalClose = () => {
  let id = `#schemeStatusSwitch${schemeData.id}`;
  if ($(id).is(":checked")) {
    $(id).prop("checked", false);
  } else {
    $(id).prop("checked", true);
  }
};

async function confirmStatusChange() {
  await $.ajax({
    type: "POST",
    url: "post_request/schemeRequest.php",
    data: { id: schemeData.id },
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);
        // $(`#schemeStatusLable${schemeData.id}`).html(
        //   schemeData.scheme_status == "active" ? "Inactive" : "Active"
        // );
        // schemeData.scheme_status =
        //   schemeData.scheme_status == "active" ? "Inactive" : "Active";
        // const modal = bootstrap.Modal.getOrCreateInstance(
        //   $("#schemeStatusModal")[0]
        // );
        // modal.hide();
        // setTimeout(() => {
        // }, 1000);
        window.location.reload();
      } else {
        ToastService.error("Error", message);
      }
    },
  });
}

/************************* Chit Creat ****************************/
chitCreate = async (event, formId) => {
  event.preventDefault();

  validate_status = form_validation(formId);

  var formData = new FormData($("#" + formId)[0]);
  if (!validate_status) {
    return;
  }

  await $.ajax({
    type: "POST",
    url: "post_request/chitCreateRequest.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);
        clearForm(formId);
      } else {
        // let firstKey = Object.keys(errors)[0];
        // let firstErrorMessage = errors[firstKey][0];
        ToastService.error("Error", message);
      }
    },
  });
};

/*************************** Chit Status Change  *******************************/
let chitSelected = null;
function chitStatusChangeModal(chit) {
  //   debugger;
  chitSelected = chit;
  const modal = bootstrap.Modal.getOrCreateInstance($("#chitStatusModal")[0]);
  modal.show();
}
function chitStatusChangeModalClose() {
  let id = `#chitStatusSwitch${chitSelected.id}`;
  if ($(id).is(":checked")) {
    $(id).prop("checked", false);
  } else {
    $(id).prop("checked", true);
  }
}

async function confirmChitStatusChange() {
  await $.ajax({
    type: "POST",
    url: "post_request/chitRequest.php",
    data: { id: chitSelected.id },
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);

        // chitSelected.status =
        //   chitSelected.status == "active" ? "inactive" : "active";
        // const modal = bootstrap.Modal.getOrCreateInstance(
        //   $("#chitStatusModal")[0]
        // );
        // modal.hide();
        window.location.reload();
      } else {
        ToastService.error("Error", message);
      }
    },
  });
}

/********************** Purchase Chit  *********************/
let purchaseChitId = null;
let purchaseSchemeId = null;
let schemeChitNumber = null;

function purchaseChitModal(chitId, schemeId, chitNumber) {
  purchaseChitId = chitId;
  purchaseSchemeId = schemeId;
  schemeChitNumber = chitNumber;
  if (!$("#selectUserId").val()) {
    $("#selectUserIdError").html("Select user");
    return;
  }

  $("#selectUserIdError").html("");
  const modal = bootstrap.Modal.getOrCreateInstance($("#chitPurchaseModal")[0]);
  modal.show();
}

async function confirmChitPurchase() {
  await $.ajax({
    type: "POST",
    url: "post_request/chitPurchase.php",
    data: {
      chit_id: purchaseChitId,
      scheme_id: purchaseSchemeId,
      chit_number: schemeChitNumber,
      user_id: $("#selectUserId").val(),
    },
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);

        const modal = bootstrap.Modal.getOrCreateInstance(
          $("#chitPurchaseModal")[0]
        );
        modal.hide();
        // window.location.reload();
      } else {
        ToastService.error("Error", message);
      }
    },
  });
}

/**************************** Chit status change ***************************/
let data = null;
async function chitStatusChange(id) {
  $chitStatus = $(`#chitStatus${id}`).val();

  data = {
    chit_id: id,
    status: $chitStatus,
  };
  $("#selectUserIdError").html("");
  const modal = bootstrap.Modal.getOrCreateInstance($("#chaneStatusModal")[0]);
  modal.show();
}

async function confirmChangeStatus() {
  await $.ajax({
    type: "POST",
    url: "post_request/changeStatus.php",
    data,
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);

        const modal = bootstrap.Modal.getOrCreateInstance(
          $("#chaneStatusModal")[0]
        );
        modal.hide();
        window.location.reload();
      } else {
        ToastService.error("Error", message);
      }
    },
  });
}

function confirmChangeStatusClose() {
  window.location.reload();
}

/**************************** Pay Chit ***************************/
function payChitModal(id, amount) {
  debugger;
  const modal = bootstrap.Modal.getOrCreateInstance($("#payChitModal")[0]);
  modal.show();

  data = {
    chit_id: id,
    amount,
  };
}

async function confirmChitPayment() {
  await $.ajax({
    type: "POST",
    url: "post_request/chitPaymentRequest.php",
    data,
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);

        const modal = bootstrap.Modal.getOrCreateInstance(
          $("#chaneStatusModal")[0]
        );
        modal.hide();
        window.location.reload();
      } else {
        ToastService.error("Error", message);
      }
    },
  });
}

/********************* Data Table  *************************/

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
new DataTable("#statusTable", {
  pageLength: 10,
  lengthMenu: [
    [10, 20, 50, 100],
    ["10", "20", "50", "100"],
  ],
});

/*********************** Pay scheme user select **********************/
function paySchemeUserSelect() {
  let user_id = $("#selectUserId").val();
  window.location = `pay-scheme.php?user_id=${user_id}`;
}

//  <?php
//                                   // Create a DatePeriod to iterate through each month
//                                   $startDate = new DateTime($userChit['chit']['start_date']);
//                                   $endDate = new DateTime($userChit['chit']['end_date']);
//                                   $period = new DatePeriod($startDate, $interval, $endDate);
//                                   foreach ($period as $index => $date):
//                                       ?>
//                                       <!-- // Format and display the month and year -->
//                                       <div class=" d-flex justify-content-between text-muted ">
//                                           <p><?= $date->format('F Y') ?></p>
//                                           <p>pending</p>
//                                       </div>
//                                       <!-- echo $date->format('F Y') . "\n"; -->

//                                       <!-- // If this is the first month, also show the start day
//                                       // if ($date == $startDate) {
//                                       // echo "Starts on: " . $startDate->format('jS F Y') . "\n";
//                                       // }

//                                       // If this is the last month, also show the end day
//                                       $nextMonth = clone $date;
//                                       $nextMonth->add($interval);

//                                       // if ($nextMonth > $endDate) {
//                                       // echo "Ends on: " . $endDate->format('jS F Y') . "\n";
//                                       // } -->

//                                       <!-- echo "\n"; -->
//                                       <?php
//                                   endforeach
//                                   ?>
