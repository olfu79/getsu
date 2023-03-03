$("#form-login").validate({
    rules: {
      username: {
        required: true
      },
      password: {
          required: true
      }
    },
    messages: {
        username: {
            required: "To pole jest wymagane!"
          },
          password: {
              required: "To pole jest wymagane!"
          }
    },
    submitHandler: function(form) {
      form.submit();
    }
  });