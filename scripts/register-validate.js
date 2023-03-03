$("#form-register").validate({
    rules: {
      username: {
        required: true
      },
      password: {
          required: true,
          minlength: 6,
          maxlength: 20
      },
      repassword: {
        required: true,
        equalTo: ".password"
    },
      email: {
        required: true,
        email: true
    }
    },
    messages: {
        username: {
            required: "To pole jest wymagane!"
          },
          password: {
              required: "To pole jest wymagane!",
              minlength: "Hasło powinno zawierać przynajmniej 6 znaków!",
              maxlength: "Hasło nie może mieć więcej niż 99 znaków!"
          },
          repassword: {
            required: "To pole jest wymagane!",
            equalTo: "Hasła nie są takie same!"
        },
        email: {
          required: "To pole jest wymagane!",
          email: "Wprowadź prawidłowy adres email"
      }
    },
    submitHandler: function(form) {
      form.submit();
    }
  });