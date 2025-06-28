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
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
// const pinCodeRegex = /^[A-Za-z0-9\- ]{3,10}$/;
const pinCodeRegex = /^[0-9]{4,6}$/;
const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
const aadhaarRegex = /^[2-9]{1}[0-9]{11}$/;

// const passwordRegex = /^\+?[1-9][0-9]{7,15}$/;
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
  pincode: { regex: pinCodeRegex, message: "Enter proper Pincode" },
  pan_number: {
    regex: panRegex,
    message: "Enter a valid PAN number (e.g. ABCDE1234F)",
  },
  aadhaar_number: {
    regex: aadhaarRegex,
    message: "Enter a valid 12-digit Aadhaar number",
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
/********************* Mobile number type validation ********************/
function mobileNumberType(e) {
  const allowedKeys = ["Backspace", "ArrowLeft", "ArrowRight", "Delete", "Tab"];
  const value = e.target.value;
  const key = e.key;

  // Allow control keys
  if (allowedKeys.includes(key)) return;

  // Prevent any non-digit and non-'+' key
  if (!/[\d+]/.test(key)) {
    e.preventDefault();
    return;
  }

  // Prevent more than one '+' or '+' not at the start
  if (key === "+") {
    if (value.includes("+") || input.selectionStart !== 0) {
      e.preventDefault();
      return;
    }
  }

  // Count digits (exclude '+')
  const digitsOnly = value.replace(/\D/g, "");
  if (digitsOnly.length >= 15 && /\d/.test(key)) {
    e.preventDefault(); // stop further input
  }
}

/**************************** Is number only ***************************/

function isNumberKey(event) {
  const charCode = event.which ? event.which : event.keyCode;

  // Allow only number keys (0-9)
  if (charCode < 48 || charCode > 57) {
    return false;
  }

  return true;
}
/**************************** Is String only ***************************/

function isLetterKey(event) {
  const charCode = event.which ? event.which : event.keyCode;

  // A–Z (uppercase) = 65–90
  // a–z (lowercase) = 97–122
  if (
    (charCode >= 65 && charCode <= 90) || // A–Z
    (charCode >= 97 && charCode <= 122) ||
    charCode === 32 // a–z
  ) {
    return true;
  }

  return false; // Block anything else (digits, space, etc.)
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
  button_loader(event);
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
      button_loader(event);
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
  button_loader(event);
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
      button_loader(event);
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
  button_loader(event);
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
      button_loader(event);
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
  button_loader(event);
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
      button_loader(event);
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

async function confirmStatusChange(event) {
  button_loader(event);

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
      button_loader(event);
    },
  });
}

/************************* Chit Creat ****************************/
async function chitCreate(event, formId) {
  event.preventDefault();

  validate_status = form_validation(formId);

  var formData = new FormData($("#" + formId)[0]);
  if (!validate_status) {
    return;
  }
  button_loader(event);

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
      button_loader(event);
    },
  });
}

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
  button_loader(event);

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
      button_loader(event);
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

async function confirmChitPurchase(event) {
  button_loader(event);
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
      button_loader(event);
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

async function confirmChangeStatus(event) {
  button_loader(event);

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
      button_loader(event);
    },
  });
}

function confirmChangeStatusClose() {
  window.location.reload();
}

/**************************** Pay Chit ***************************/

let user_id = null;
function paySchemeUserSelect() {
  user_id = $("#selectPayUserId").val();
  window.location = `pay-scheme.php?user_id=${user_id}`;
}

async function payChitModal(event, id, amount, scheme, chit_number) {
  const modal = bootstrap.Modal.getOrCreateInstance($("#payChitModal")[0]);
  // let details = `
  // <p>${}</p>
  // `;

  user_id = $("#selectPayUserId").val();

  data = {
    chit_id: id,
    amount,
    user_id,
  };
  button_loader(event);

  await $.ajax({
    type: "POST",
    url: "post_request/getPaymentDetails.php",
    data,
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        const modal = bootstrap.Modal.getOrCreateInstance(
          $("#payChitModal")[0]
        );
        modal.show();
        let details = `
        <h4 calss="text-center">Are you sure to pay</h4>
        <div calss=>
        <p>Scheme - ${scheme}</p>
        <p>Chit Number - ${chit_number}</p>
        <p>Month - ${response.data["month"]}</p>
        <p>Year - ${response.data["year"]}</p>
        <p>Amount - ${amount} ₹</p>
        </div>
        `;
        data.month = response.data["month"];
        data.year = response.data["year"];
        data.payment_id = response.data["payment_id"];
        $("#paymentModalContent").html(details);
      } else {
        // const modal = bootstrap.Modal.getOrCreateInstance(
        //   $("#payChitModal")[0]
        // );
        // modal.hide();
        ToastService.error("Error", message);
      }
      button_loader(event);
    },
  });
}

async function confirmChitPayment(event, razerpay_key, razerpay_secret) {
  button_loader(event);

  let method = $('input[name="paymentMethoSelect"]:checked').val();
  if (method == "upi") {
    await $.ajax({
      type: "POST",
      url: "post_request/razerPayCreateOrder.php",
      data,
      success: function (response) {
        let payment_id = "";
        const data = JSON.parse(response);
        const options = {
          key: razerpay_key, // Replace with your key
          amount: data.amount,
          currency: data.currency,
          name: "Your Company Name",
          description: "Test Transaction",
          order_id: data.order_id,
          handler: function (response) {
            // payment_id = JSON.parse(response).payment_id;
            $.ajax({
              type: "POST",
              url: "post_request/razerPayStatusRequest.php",
              data: {
                payment_id: response.razorpay_payment_id,
                status: "success",
              },
              success: function (response) {
                ToastService.success("Success", response.message);
              },
            });
            // fetch("verify_payment.php", {
            //   method: "POST",
            //   headers: { "Content-Type": "application/json" },
            //   body: JSON.stringify({ payment_id: response.razorpay_payment_id }),
            // });
            // alert(
            //   "Payment Successful!\nPayment ID: " + response.razorpay_payment_id
            // );
          },
          modal: {
            ondismiss: function () {
              data.status = "failed";
              $.ajax({
                type: "POST",
                url: "post_request/razerPayStatusRequest.php",
                data: data,
                success: function (response) {
                  ToastService.error("Error", response.message);
                },
              });
            },
          },
          prefill: {
            name: "Vijay",
            email: "vijay@example.com",
            contact: "9999999999",
          },
          theme: {
            color: "#3399cc",
          },
        };

        const rzp1 = new Razorpay(options);
        rzp1.open();
        button_loader(event);
      },
    });
  } else {
    const modal = bootstrap.Modal.getOrCreateInstance($("#payChitModal")[0]);
    modal.hide();
    const otpModal = bootstrap.Modal.getOrCreateInstance($("#otpModal")[0]);
    otpModal.show();
  }

  // await $.ajax({
  //   type: "POST",
  //   url: "post_request/chitPaymentRequest.php",
  //   data,
  //   success: function (response) {
  //     const { status, message, errors } = response;
  //     if (status == "success") {
  //       ToastService.success("Success", message);

  //       const modal = bootstrap.Modal.getOrCreateInstance(
  //         $("#payChitModal")[0]
  //       );
  //       modal.hide();
  //       window.location.reload();
  //     } else {
  //       const modal = bootstrap.Modal.getOrCreateInstance(
  //         $("#payChitModal")[0]
  //       );
  //       modal.hide();
  //       ToastService.error("Error", message);
  //     }
  //     button_loader(event);
  //   },
  // });
}

/************************* Send Otp  ************************/
async function sendOtpToUser(event, mobile) {
  button_loader(event);

  await $.ajax({
    type: "POST",
    url: "post_request/otpGenerateRequest.php",
    data: { mobile },
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);
        const modal = bootstrap.Modal.getOrCreateInstance($("#otpModal")[0]);
        modal.show();
      } else {
        ToastService.error("Error", message);
      }
      button_loader(event);
    },
  });
  // const modal = bootstrap.Modal.getOrCreateInstance($("#otpModal")[0]);
  // modal.show();
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

/************************ Select picker ***************/
$(".selectpicker").selectpicker();

/************************** Transaction filter ***************/
function transactionFilter() {
  let today = new Date();

  let year = today.getFullYear();
  let month = String(today.getMonth() + 1).padStart(2, "0"); // Month is 0-based
  let day = String(today.getDate()).padStart(2, "0");

  let formattedDate = `${year}-${month}-${day}`;

  let schemeFilter = $("#schemeFilter").val();
  let chitFilter = $("#chitFilter").val();
  let userFilter = $("#userFilter").val();
  let paymentStatusFilter = $("#paymentStatusFilter").val();
  let chitStatusFilter = $("#chitStatusFilter").val();
  let startDate = $("#startDate").val();
  let endDate = $("#endDate").val();

  let params = "";
  if (schemeFilter) {
    params +=
      params == "" ? `scheme_id=${schemeFilter}` : `&scheme_id=${schemeFilter}`;
  }
  if (chitFilter) {
    params +=
      params == "" ? `chit_amount=${chitFilter}` : `&chit_amount=${chitFilter}`;
  }
  if (userFilter) {
    params += params == "" ? `user_id=${userFilter}` : `&user_id=${userFilter}`;
  }
  if (paymentStatusFilter) {
    params +=
      params == ""
        ? `pay_status=${paymentStatusFilter}`
        : `&pay_status=${paymentStatusFilter}`;
  }
  if (chitStatusFilter) {
    params +=
      params == ""
        ? `chit_status=${chitStatusFilter}`
        : `&chit_status=${chitStatusFilter}`;
  }

  if (!startDate && endDate) {
    ToastService.warning("Error", "Select Start date");
    return;
  }
  if (startDate && !endDate) {
    ToastService.warning("Error", "Select End date");
    return;
  }
  if (startDate && endDate) {
    params += params == "" ? `start_at=${startDate}` : `&start_at=${startDate}`;
    params += params == "" ? `end_at=${endDate}` : `&end_at=${endDate}`;
  }

  if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
    ToastService.error("Error", "End date is earlier than start date.");
  } else {
    window.location = `month-wise-payment.php?${params}`;
  }
}

/********************** Change status filter ***********************/
function changeStatusFilter() {
  let chitStatusFilter = $("#chitStatusFilter").val();
  let params = "";

  if (chitStatusFilter) {
    params +=
      params == ""
        ? `chit_status=${chitStatusFilter}`
        : `&chit_status=${chitStatusFilter}`;
  }
  window.location = `change-status.php?${params}`;
}

///////////////////// Button Loader Function
function button_loader(event) {
  if (event.target.nodeName == "BUTTON") {
    loader_element = event.target;
  } else {
    loader_element = event.target.parentElement;
  }

  /////////////////////// Add loader in button
  $loader_element = loader_element;
  $loader_span_1 = loader_element.firstElementChild;
  $loader_span_2 = loader_element.lastElementChild;

  $loader_element.classList.toggle("disabled");
  $loader_span_1.classList.toggle("d-none");
  $loader_span_2.classList.toggle("d-none");
}

/******************** SHOW PASSWORD ******************/
function showPassword(event) {
  event.preventDefault();
  let inputElement;
  inputElement = event.target.previousElementSibling.previousElementSibling;
  event.target.classList.toggle("fa-eye");
  event.target.classList.toggle("fa-eye-slash");
  inputElement.type = inputElement.type === "password" ? "text" : "password";
}

/************************** Forgot Password ******************/

async function forgotPassword(event, formId) {
  event.preventDefault();

  validate_status = form_validation(formId);

  var formData = new FormData($("#" + formId)[0]);
  if (!validate_status) {
    return;
  }
  button_loader(event);
  await $.ajax({
    type: "POST",
    url: "post_request/forgotPasswordRequest.php",
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
      button_loader(event);
    },
  });
}

/************************** reset Password ******************/

async function resetPassword(event, formId) {
  event.preventDefault();

  validate_status = form_validation(formId);

  var formData = new FormData($("#" + formId)[0]);
  if (!validate_status) {
    return;
  }
  button_loader(event);
  await $.ajax({
    type: "POST",
    url: "post_request/resetPasswordRequest.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      const { status, message, errors } = response;
      if (status == "success") {
        ToastService.success("Success", message);
        clearForm(formId);
        setTimeout(() => {
          window.location = "login.php";
        }, 1000);
      } else {
        // let firstKey = Object.keys(errors)[0];
        // let firstErrorMessage = errors[firstKey][0];
        ToastService.error("Error", message);
      }
      button_loader(event);
    },
  });
}

/*********************** Pay scheme user select **********************/

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
