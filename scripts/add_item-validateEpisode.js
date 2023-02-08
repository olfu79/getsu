$("#form-addEpisode").validate({
    rules: {
      id: {
        required: true,
        digits: true,
        step: 1,
        minlength: 9,
        maxlength: 9,
        min: 100000000,
        max: 999999999
      },
      series: {
          required: true
      },
      title: {
        required: true,
        minlength: 2
      },
      number: {
        required: true,
        min: 0,
        step: 1,
        digits: true
      },
      url: {
        required: true,
        url: true
      },
      poster: {
        required: true,
        url: true
      },
      desc: {
        maxlength: 2048
      }
    },
    messages: {
      id: {
        required: "To pole jest wymagane",
        digits: "Zły format ID",
        step: "Zły format ID",
        minlength: "Zły format ID",
        maxlength: "Zły format ID",
        min: "Zły format ID",
        max: "Zły format ID"
      },
      series:{
        required: "To pole jest wymagane"
      },
      title: {
        required: "To pole jest wymagane",
        minlength: "Nazwa jest za krótka"
      },
      number: {
        required: "To pole jest wymagane",
        number: "Wprowadź prawidłową liczbę",
        min: "Numer odcinka musi być liczbą całkowitą nieujemną",
        step: "Numer odcinka musi być liczbą całkowitą nieujemną",
        digits: "Numer odcinka musi być liczbą całkowitą nieujemną"
      },
      url: {
        required: "To pole jest wymagane",
        url: "Wprowadź poprawny adres URL"
      },
      poster: {
        required: "To pole jest wymagane",
        url: "Wprowadź poprawny adres URL"
      },
      desc: {
        maxlength: "Opis nie może być dłużysz niż 2048 znaki"
      }
    },
    submitHandler: function(form) {
      form.submit();
    }
  });