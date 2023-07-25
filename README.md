# smartbees-zadanie-rozszerzone
apache / php 7.3 / mysql

### 26.07 update (poprawki)
---

+ Połączenie z bazą danych teraz się znajduje w jednym miejscu - "connection.php"
+ Zostały usunięte console.logi w tym komentowane. Komentarze w pliku styles.css też
+ Odznaczanie comboboxa "Dostawa pod inny adres" teraz wraca dane użytkownika
+ Usunięta możliwość tworzenia kont o jednakowym loginem
+ Hasła teraz są przechowywane w bazie jako szyfrowane
+ Suma częściowa i łączna teraz działają poprawnie (jeśli w ogóle dobrze zrozumiałem jak to musi działać)

Zmiany poza uwagami:
+ Dodałem do tabeli "order_data" nullable kolumnę "discount_code" aby można było zrozumieć, z czego wynika taka cena.
   jeśli nie podamy kodu rabatowego lub będzie on nieaktywny, kolumna będzie miała wartość 0.
+ Zmieniłem sposób tworzenia numeru zamówienia. Teraz podstawą numeru służy liczba 10000 + autoinkrementowane id tabeli "personal_data"
+ Dodatkowo trochę zrefaktoryzowałem kod, nic globalnego lub ciekawego

 Pod pytaniem się został tylko problem z niewypełnieniem tabeli "order_data" przy zamówieniu. W moim przypadku, po wyświetleniu okna z kodem zamówienia
 do bazy przypisują się dane (działało nawet przed wszystkimi poprawkami). Robiłem zamówienie jako użytkownik niezalogowany, jako
 nizalogowany + tworzenie konta oraz jako zalogowany (podejrzewam, że może to zależeć od wprowadzonych danych, że niby walidują się dobrze,
 tylko nie uwzględniłem to w typach i/lub w rozmiarach typów danych kolumn tabeli "order_data"). Poniżej dołączam screena z bazy:


 ![order_data](/images/order_data.png)

### 24.07 update 
---

1.) Zrealizowałem funkcjonalność logowania, teraz można utworzyć konto przy wysyłaniu formularza, i przy następnych wypełnieniach wchodzić
do swojego konta

2.) Zrealizowałem funkcjonalność kodów rabatowych, teraz przy naciśnięciu na przycisk "Kod rabatowy" wyskakuje input z buttonem, jak
wpiszemy działający kod, to dostamy komunikat i rabat o wielkości procentu, wskazanego w tabeli z kodami rabatowymi, jeśli kod 
nie aktywny, dostaniemy komunikat, że nie jest aktywny

3.) Teraz dynamicznie się ładują metody dostawy i produkty, stworzyłem kilka itemów i trzy standardowe metody dostawy, wszystkie dane pobierają się
z bazy danych. Co do produktów, to możemy wpisać w pliku js_scripts.js id oraz ilość zamawianych produktów

Co do kodu, to w sumie prawie przestałem się korzystać z "wanilowego" javascripta, używałem prawie wszędzie jquery. Trochę(!) polepszyłem kod css.
Z PHPem też było trochę refaktoringu, dodałem różne pliki, które odpowiadają za różne czynności ten główny (w którym robimy zamówienie), nazywa się
process.php. Dodałem węcej OOP, chociaż nadal nie do końca wiem, czy miało to sens, i czy nie zepsuło czytelność kodu (niby ona wcześniej była :D) trochę
za późno zauważyłem, ale mam straszną mieszankę cudzyslowów ("cos", 'cos'), odrazu przepraszam za to, oczywiście, nie jest to jedyny problem. Mam nadzieję, że 
Pan\Pani który\która będzie to czytał\czytała wytszyma to. Dziękuję!

Również zamieszczam prosty diagram, który mniej więcej może pokazać, jak wiążą się dane (tym razem znów bez kluczów obcych)

![diagram2](/images/diagram_2.png)

---
### ===== niżej zamieszczona informacja dotyczy wersji bazowej 21.07 =====

zamieściłem dump bazy, localnie u siebie w kodzie nazwałem bazę 'smartbees_zadanie_db'

Trochę opiszę co się dzieje w tym zadaniu. Strona klienta pisana była mieszanką javascripta/jquery, walidacja ze strony frontendu 
najpierw była zrobiona za pomocą html5 "patternów" ale potem zmieniłem na zwykły javascript i dodałem customowy popup, który wskazuje na błędy.
Od razu chciałbym powiedzieć, że to jest strasznie proszta validacja, niektóre pola się sprawdzają przez regexy, a niektóre po prostu przez właściwość długości.
Backend validacja to po prostu kopia klientowej, tylko przepisana w PHPie. Ten popup do walidacji przyjmuje powiadomienie i wyświetla go, powiadomienia nie są
rozbudowane aby użytkownik wiedział detale. Na przykład ile musi mieć długości pole Hasło, jakie litery jub znaki są dozwolone i t.p. Przepraszam za to.
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

Tutaj zamieściłem prosty diagram bazy dla zrozumienia.
![diagram](/images/diagram.png)
