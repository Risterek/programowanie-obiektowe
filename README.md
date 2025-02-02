# Budżet osobisty - aplikacja do zarządzania wydatkami
JanKinal1_MaksymilianRichter2_I_USM_Inf_NW_1_BudżetDomowy

## Demo online
Działającą wersję aplikacji można podejrzeć pod adresem: https://budzet.kinal.pl

Dane do logowania demo:
- Login: Jurek
- Hasło: abc123

## O projekcie
Aplikacja została zaprojektowana w oparciu o paradygmat programowania obiektowego (OOP), wykorzystując PHP do implementacji backendu oraz JavaScript do interaktywnych funkcjonalności po stronie klienta. Cała architektura opiera się na klasach, które zapewniają separację logiki biznesowej, dostępu do danych i prezentacji.

### Kluczowe cechy
- Podstawowe operacje na wydatkach (dodawanie i podgląd)
- Wizualizacja danych w czasie rzeczywistym
- Responsywny interfejs użytkownika
- Bezpieczna autentykacja użytkowników
- Dynamiczna nawigacja po datach (kalendarz) + możliwość zmieniania lat

## Struktura projektu
Projekt wykorzystuje następujący stack technologiczny:

### Backend (PHP 7.4+)
- Klasa `Database` - zarządzanie połączeniem z bazą danych
- Klasa `User` - obsługa autentykacji i zarządzania użytkownikami
- Klasa `Expense` - logika biznesowa związana z wydatkami

### Frontend
- Vanilla JavaScript z klasami do obsługi kalendarza i nawigacji
- CSS z flexboxem do layoutu
- SVG do elementów graficznych


