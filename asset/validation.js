$("document").ready(function () {
  let usr_inp = $("#username");
  let pass_inp = $("#password-input");
  let rpt_pass_inp = $("#repeat-password-input");
  let signup_msg = $("#signupMessage");
  let login_msg = $("#loginMessage");

  //NOTE - username
  function validateusrName($input) {
    const val = $input.val();
    const pattern = /^[a-zA-Z_][a-zA-Z0-9_]{5,29}$/;
    if (pattern.test(val)) {
      $input.removeClass("danger-inp").addClass("success-inp");
      signup_msg.html("Username is valid.");
      signup_msg
        .addClass("success-passMessage")
        .removeClass("danger-passMessage");
    } else {
      signup_msg.html(
        `at least:<br/>
        <i class="ri-error-warning-fill"></i>6 characters long<br/>
        <i class="ri-error-warning-fill"></i>Only letters (a–z, A–Z), numbers, and underscore (_) are allowed.<br/>
        <i class="ri-error-warning-fill"></i>Must not start with a number.<br/>
        <i class="ri-error-warning-fill"></i>Must not contain spaces or special characters like @, !, #, $.`
      );
      signup_msg
        .addClass("danger-passMessage")
        .removeClass("success-passMessage");
      $input.addClass("danger-inp").removeClass("success-inp");
    }
  }
  function validatePassword(password) {
    const pattern = /^(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,30}$/;
    return pattern.test(password);
  }

  //NOTE - password
  function validatePass() {
    let pass = pass_inp.val();
    if (validatePassword(pass)) {
      signup_msg.html("Password is valid.");
      signup_msg
        .addClass("success-passMessage")
        .removeClass("danger-passMessage");
      pass_inp.addClass("success-inp").removeClass("danger-inp");
    } else {
      signup_msg.html(
        `at least:<br/>
        <i class="ri-error-warning-fill"></i>8 characters long<br/>
        <i class="ri-error-warning-fill"></i>One number(0-9)<br/>
        <i class="ri-error-warning-fill"></i>One special character(!@#$%^&*_+)<br/>
        <i class="ri-error-warning-fill"></i>Must not contain spaces.`
      );
      signup_msg
        .addClass("danger-passMessage")
        .removeClass("success-passMessage");
      pass_inp.addClass("danger-inp").removeClass("success-inp");
    }
  }

  //NOTE - confirm password
  function validateConfirm() {
    let pass = pass_inp.val();
    let confirm = rpt_pass_inp.val();
    if (confirm !== "" && validatePassword(confirm) && pass === confirm) {
      signup_msg.html("Passwords are valid and match.");
      signup_msg
        .addClass("success-passMessage")
        .removeClass("danger-passMessage");
      rpt_pass_inp.addClass("success-inp").removeClass("danger-inp");
    } else {
      rpt_pass_inp.addClass("danger-inp").removeClass("success-inp");
      signup_msg.html("Passwords do not match.");
      signup_msg
        .addClass("danger-passMessage")
        .removeClass("success-passMessage");
    }
  }

  usr_inp.on("input", function () {
    validateusrName($(this));
  });

  pass_inp.on("input", function () {
    validatePass($(this));
  });

  rpt_pass_inp.on("input", function () {
    validateConfirm($(this));
  });

  $("#form").on("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const formType = formData.get("form_type");
    if (formType === "signup") {
      let pass = pass_inp.val();
      let confirm = rpt_pass_inp.val();
      let username = usr_inp.val();
      const pattern_usr = /^[a-zA-Z_][a-zA-Z0-9_]{5,29}$/;
      if (
        pattern_usr.test(username) &&
        validatePassword(pass) &&
        pass === confirm
      ) {
        console.log("signup");
        console.log("ok");
        setTimeout(() => this.submit(), 500);
      } else {
        console.log("err");

        signup_msg.html("The fields are not valid.");
        signup_msg
          .addClass("danger-passMessage")
          .removeClass("success-passMessage");

        if (!pattern_usr.test(username)) {
          usr_inp.addClass("danger-inp").removeClass("success-inp");
        }
        if (!validatePassword(pass)) {
          pass_inp.addClass("danger-inp").removeClass("success-inp");
        }
        if (pass !== confirm) {
          rpt_pass_inp.addClass("danger-inp").removeClass("success-inp");
        }
      }
      // ANCHOR - signup codes
    } else if (formType === "login") {
      let pass = pass_inp.val();
      let username = usr_inp.val();
      const pattern_usr = /^[a-zA-Z_][a-zA-Z0-9_]{5,29}$/;
      if (pattern_usr.test(username) && validatePassword(pass)) {
        login_msg.html("");
        console.log("login");
        console.log("ok");
        setTimeout(() => this.submit(), 500);
      } else {
        console.log("err");
        login_msg.html("The fields are not valid.");
        login_msg
          .addClass("danger-passMessage")
          .removeClass("success-passMessage");

        if (!pattern_usr.test(username)) {
          usr_inp.addClass("danger-inp").removeClass("success-inp");
        }
        if (!validatePassword(pass)) {
          pass_inp.addClass("danger-inp").removeClass("success-inp");
        }
      }
      // ANCHOR - login codes
    } else if (formType === "dashboard") {
      setTimeout(() => this.submit(), 500);
    } else {
      console.log("err");
    }

    /* const username = $('#username').val().trim();
    const password = $('#password').val();

    // اعتبارسنجی ساده
    if (username === '' || password.length < 6) {
      alert('لطفاً نام کاربری را وارد کنید و رمز عبور حداقل ۶ کاراکتر باشد.');
      return;
    } */
  });
});
