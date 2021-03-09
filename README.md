Projekt miał na celu stworzenie strony wykorzystujacej potencjał systemu kolejkowego RabbitMQ. System taki może działać na niezależych serwerach, a nawet jego częśći mogą być napisane w różnych językach programowania.

W projekcie zostały wykorzystane 3 kolejki. Dwie z nich oparte są na exchange typu direct, który rozdziela wiadomości na podstawie słowa kluczowego, pozostała to typ fanout.

Dodatkowo próbując imitować obsługą zewnętrznych serwisów, dwie kolejki posiadają własny serializer, jednak z kolejek korzysta z domyślnego serializera symfony.

Opis działa: [tutaj](https://kamilborek.pl/project/proces-zamowien)
