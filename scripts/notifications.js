$(document).ready(function() {
    $(document).on('click', '.download', function() {
        var noty = new Noty({
            text: 'Pobieranie rozpocznie się za chwilę...',
            type: 'info',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        noty.show();
    });
    $(document).on('click', '.share', function() {
        var noty = new Noty({
            text: 'Skopiowano link do filmu',
            type: 'info',
            theme: 'relax',
            layout: 'topRight',
            timeout: 1500,
        });
        noty.show();
    });
    $(document).on('click', '.series-added', function() {
        var noty = new Noty({
            text: 'Usunięto serię z listy do obejrzenia',
            type: 'info',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        noty.show();
    });
    $(document).on('click', '.series-toadd', function() {
        var noty = new Noty({
            text: 'Dodano serię do obejrzenia',
            type: 'info',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        noty.show();
    });
    var queryString = window.location.search;
    var searchParams = new URLSearchParams(queryString);
    if(eParam = searchParams.get('e')){
        var noty = new Noty({
            text: '',
            type: 'error',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        if (eParam === 'error') {
            noty.setText('Nieznany błąd!', true);
            noty.show();
        }
        if (eParam === 'sdatamissing') {
            noty.setText('Brak danych serii, nie dodano do bazy!', true);
            noty.show();
        }
        if (eParam === 'edatamissing') {
            noty.setText('Brak danych odcinka, nie dodano do bazy!', true);
            noty.show();
        }
        if (eParam === 'eexist') {
            noty.setText('Odcinek już istnieje!', true);
            noty.show();
        }
        if (eParam === 'cantbanadm') {
            noty.setText('Nie można banować administracji!', true);
            noty.show();
        }
        if (eParam === 'seriesnotexist') {
            noty.setText('Nie ma takiej serii!', true);
            noty.show();
        }
        if (eParam === 'episodenotexist') {
            noty.setText('Nie ma takiego odcinka!', true);
            noty.show();
        }
        if (eParam === 'usernotexist') {
            noty.setText('Nie ma takiego użytkownika! Przekierowano na Twój profil.', true);
            noty.setTimeout(4500);
            noty.show();
        }
        if (eParam === 'wrngpass') {
            noty.setText('Zły login lub hasło!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'missingdata') {
            noty.setText('Uzupełnij wszystkie dane w formularzu!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'invalidemail') {
            noty.setText('Nieprawidłowy adres email!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'invalidusername') {
            noty.setText('Nieprawidłowa nazwa użytkownika!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'passwordwrnglength') {
            noty.setText('Hasło musi mieć minimum 6 znaków a maximum 20!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'usernameexist') {
            noty.setText('Hasło musi mieć minimum 6 znaków a maximum 20!', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'passwordwrnglength') {
            noty.setText('Ta nazwa użytkownika jest już zajęta! Wybierz inną.', true);
            noty.setTimeout(false);
            noty.show();
        }
        if (eParam === 'emailexist') {
            noty.setText('Ten email już istnieje.', true);
            noty.setTimeout(false);
            noty.show();
        }
    }
    if(eParam = searchParams.get('s')){
        var noty = new Noty({
            text: '',
            type: 'success',
            theme: 'relax',
            layout: 'topRight',
            timeout: 3000,
        });
        if (eParam === 'success') {
            noty.setText('Wykonano pomyślnie!', true);
            noty.show();
        }
        if (eParam === 'supdatedprofile') {
            noty.setText('Pomyślnie zaktualizowano profil!', true);
            noty.show();
        }
        if (eParam === 'sadded') {
            noty.setText('Pomyślnie dodano serię!', true);
            noty.show();
        }
        if (eParam === 'eadded') {
            noty.setText('Pomyślnie dodano odcinek!', true);
            noty.show();
        }
        if (eParam === 'banned') {
            noty.setText('Pomyślnie zbanowano użytkownika!', true);
            noty.show();
        }
        if (eParam === 'unbanned') {
            noty.setText('Pomyślnie odbanowano użytkownika!', true);
            noty.show();
        }
        if (eParam === 'delcom') {
            noty.setText('Pomyślnie usunięto komentarz!', true);
            noty.show();
        }
        if (eParam === 'delrep') {
            noty.setText('Pomyślnie usunięto zgłoszenie!', true);
            noty.show();
        }
        if (eParam === 'shidden') {
            noty.setText('Pomyślnie ukryto serię!', true);
            noty.show();
        }
        if (eParam === 'sshown') {
            noty.setText('Pomyślnie uwidoczniono serię!', true);
            noty.show();
        }
        if (eParam === 'sdeleted') {
            noty.setText('Pomyślnie usunięto serię!', true);
            noty.show();
        }
        if (eParam === 'ehidden') {
            noty.setText('Pomyślnie ukryto odcinek!', true);
            noty.show();
        }
        if (eParam === 'eshown') {
            noty.setText('Pomyślnie uwidoczniono odcinek!', true);
            noty.show();
        }
        if (eParam === 'edeleted') {
            noty.setText('Pomyślnie usunięto odcinek!', true);
            noty.show();
        }
        if (eParam === 'eupdated') {
            noty.setText('Pomyślnie uaktualniono odcinek!', true);
            noty.show();
        }
        if (eParam === 'supdated') {
            noty.setText('Pomyślnie uaktualniono serię!', true);
            noty.show();
        }
        if (eParam === 'sresetrequest') {
            noty.setText('Pomyślnie zresetowano hasło użytkownika!', true);
            noty.show();
        }
        if (eParam === 'sdeluser') {
            noty.setText('Pomyślnie usunięto użytkownika!', true);
            noty.show();
        }
        if (eParam === 'sreg') {
            noty.setText('Pomyślnie stworzono konto! Zaloguj się używając wcześniej podanych danych', true);
            noty.setTimeout(false);
            noty.show();
        }
    }
  });
  