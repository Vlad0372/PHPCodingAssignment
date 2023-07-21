# smartbees-zadanie
apache / php 7.3 / mysql

zamieściłem dump bazy, localnie u siebie w kodzie nazwałem bazę 'smartbees_zadanie_db'

Trochę opiszę co się dzieje w tym zadaniu. Strona klienta pisana była mieszanką javascripta/jquery, validacja ze strony frontendu 
najpierw była zrobiona za pomocą html5 "patternów" ale potem zmieniłem na zwykły javascript i dodałem customowy popup, który wskazuje na błędy.
Od razu chciałbym powiedzieć, że to jest strasznie proszta validacja, niektóre pola się sprawdzają przez regexy, a niektóre po prostu przez właściwość długości.
Backend validacja to po prostu kopia clientowej, tylko przepisana w phpie. Ten popup do walidacji przyjmuje powiadomienie i wyświetla go, powiadomienia nie są
rozbudowane aby użytkownik wiedział detale. Na przykład ile musi mieć długości pole Hasło, jakie litery jub znaki są dozwolone i t.p. Przepraszam za to :)
W css trochę musiałem pokombinować wraperami, aby zrealizować logikę składania formularza taką, jak chciałem, więc trochę nie logicznych rzeczy tam może 
wystąpić. Również może być dużo niewykorzystywalnych idków/classów. W javascripcie w sumie wszystko jest zrozumiałe beż wyjaśnienia oprócz pierwszej funkcji,
jest to właśnie cała logika związana z requestem ajax i opracowywaniem odpowiedzi. W skrócie: 1.) pobieramy wszystkie dane z inputów 2.) róbmy walidację
3.) jeśli walidacja była zakończona pomyślnie -> róbmy requesta, gdzie przekazujemy nasze wartości, inaczej -> ignorowanie requestu.
Co do php`a: cały kod jest strasznie skomplikowany, powiem szczerze jest tak ponieważ starałem się wykonać jeden z wymaganych punktów zadania - użycia OOP.
Użyłem, tylko to trochę zepsuło i bez tego nie najlepszy kod :). Niczego bardziej ciekawszego, aniż zwykły transfer danych 
pomiędzy $_POST[]->object field->sql request ja nie zmogłem wymyślić :(. Otóż, zaczyna się wszystko od includowania klas, dalej mamy wypełnienie danych 
do zwrotu do klienta "pustymi wartościami" użycie funkcji validate, do której właśnie przekazujemy przez referencję dane do zwrotu, w środku tej funkcji one się
wypełniają (tutaj tylko status walidacji i powiadomienie z błędem). Ukryłem całą walidację tylko po to, aby wyglądało lepiej, żadnej idej poza tym nie było.
Po walidacji właśnie mamy tworzenie 4 obiektów klas (3, jeśli użytkownik nie chce tworzyć konta) i wysyłanie 4(3) sql zapytań do bazy. Właśnie tak to wszystko działa.
Przy błędzie walidacji, mamy powiadomienie błędu, przy udanej walidacji, zwracamy kod zamówienia.

P.s. Do bazy nie dodałem tylko kluczów obcych, wiem tylko, że muszą one tam być
