# language: ru

Функционал: Тест контроллера EventController
    Тестируем список событий, просмтотр события принятия участия и просмотр моих событий,
    оплата события

    Сценарий: Открыть страницу событий и убедиться в ее существовании
        Допустим я на странице "/events"
        Тогда код ответа сервера должен быть 200
        ### события
        И я должен видеть "отель \"Казацкий\", Киев" внутри элемента ".conf-info"
        И я должен видеть "Zend Framework Day посвящен популярному PHP фреймворку Zend Framework" внутри элемента ".conf-info p"

    Сценарий: Перейти на конкретное событие
        Допустим я на странице "/events"
        И кликаю по ссылке "Детальная информация"
        Тогда код ответа сервера должен быть 200
        И я должен быть на странице "/event/zend-framework-day-2011"
        И я должен видеть "среда, 18 апреля 2012" внутри элемента "header .event-head-text"
        И я должен видеть "Описание события" внутри элемента "article.about-event"
