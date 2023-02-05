$("#form-addSeries").validate({
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
      altname: {
        required: true,
        minlength: 2
      },
      fullname: {
        required: true,
        minlength: 3
      },
      season: {
        required: true,
        min: 0,
        step: 1,
        digits: true
      },
      epcount: {
        required: true,
        min: 0,
        step: 1,
        digits: true
      },
      brdtype: {
        required: true,
      },
      brdstart: {
        required: true,
        date: true
      },
      desc: {
        required: true,
        maxlength: 1024
      },
      poster: {
        required: true
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
          altname: {
            required: "To pole jest wymagane",
            minlength: "Nazwa jest za krótka"
          },
          fullname: {
            required: "To pole jest wymagane",
            minlength: "Pełna nazwa jest za krótka"
          },
          season: {
            required: "To pole jest wymagane",
            number: "Wprowadź prawidłową liczbę",
            min: "Sezon nie może być mniejszy niż 0",
            step: "Sezon musi być liczbą całkowitą nieujemną",
            digits: "Sezon musi być liczbą całkowitą nieujemną"
          },
          epcount: {
            required: "To pole jest wymagane",
            number: "Wprowadź prawidłową liczbę",
            min: "Liczba odcinków musi być liczbą całkowitą nieujemną",
            step: "Liczba odcinków  musi być liczbą całkowitą nieujemną",
            digits: "Liczba odcinków  musi być liczbą całkowitą nieujemną"
          },
          brdtype: {
            required: "To pole jest wymagane",
          },
          brdstart: {
            required: "To pole jest wymagane",
            date: "Zły format daty"
          },
          desc: {
            required: "To pole jest wymagane",
            maxlength: "Opis nie może być dłużysz niż 1024 znaki"
          },
          poster: {
            required: "To pole jest wymagane"
          }
    },
    submitHandler: function(form) {
      form.submit();
    }
  });