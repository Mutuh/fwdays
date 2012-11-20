# language: ru
Функционал: Проверяем регистрацию и данные пользователя в профиле

    Сценарий: Зайти на страницу регистрации, заполнить все обязательные поля и увидеть сообщение об успешной регистрации
        Допустим я на странице регистрации
        Тогда я заполняю обязательные поля формы: имя - "Jack Smith", e-mail - "test@fwdays.com", пароль - "qwerty"
        Когда я нажимаю "Регистрация"
        И я должен быть на странице подтверждения имейла
        И я должен видеть сообщение, что пользователь успешно создан
        И я должен видеть сообщение, что на почту "test@fwdays.com" выслано письмо для подтверждения регистрации

    # В этому сценарии для тестирования оптравки письма используется профайлер симфони. Но еще нужно отключить для него редирект страниц
    # В последнем методе редирект включается обратно, чтоб срабатывали следующие тесты
    Сценарий: Протестировать отправку имейла после регистрации
        Допустим я на странице регистрации
        И редирект страниц отключен
        Тогда я заполняю обязательные поля формы: имя - "Jack Smith", e-mail - "test@fwdays.com", пароль - "qwerty"
        Когда я нажимаю "Регистрация"
        Тогда письмо для подтверждения регистрации должно быть выслано на e-mail "test@fwdays.com"

    Сценарий: Зайти на страницу регистрации, заполнить все обязательные и дополнительные поля и увидеть сообщение об успешной регистрации
        Допустим я на странице регистрации
        Тогда я заполняю обязательные поля формы: имя - "Jack Smith", e-mail - "test@fwdays.com", пароль - "qwerty"
        И я заполняю дополнительные поля формы: страна - "Укрина", город - "Хмельницкий", компания - "Stfalcon", должность - "Web Developer"
        Когда я нажимаю "Регистрация"
        Тогда я должен быть на странице подтверждения имейла
        И я должен видеть сообщение, что пользователь успешно создан
        И я должен видеть сообщение, что на почту "test@fwdays.com" выслано письмо для подтверждения регистрации

    Сценарий: Зайти на страницу логина и войти в свою учетную запись
        Допустим я на странице логина
        Когда я вхожу в учетную запись с именем "jordan@fwdays.com" и паролем "qwerty"
        Тогда I am on homepage
        И я должен видеть меню для пользователя "jordan@fwdays.com"

    Сценарий: Зайти в учетную запись, в которой заполнены только обязательные поля, проверить информацию в них на странице профиля,
        Допустим я вошел в учетную запись с именем "jordan@fwdays.com" и паролем "qwerty"
        Когда я перехожу на страницу редактирования профиля
        Тогда я должен видеть свой имейл "jordan@fwdays.com"
        И я должен видеть свое имя "Michael Jordan"
        И я должен видеть название своей страны "USA"
        И я должен видеть название своего города "Boston"
        И я должен видеть название своей компании "NBA"
        И я должен видеть название своей должности "Point Guard"
