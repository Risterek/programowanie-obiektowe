class Calendar {
    constructor(monthYearElement, datesElement, prevButton, nextButton) {
        this.monthYearElement = monthYearElement;
        this.datesElement = datesElement;
        this.currentDate = new Date();
        this.initListeners(prevButton, nextButton);
        this.updateCalendar();
    }

    initListeners(prevButton, nextButton) {
        prevButton.addEventListener("click", () => {
            this.changeMonth(-1);
        });
        nextButton.addEventListener("click", () => {
            this.changeMonth(1);
        });
    }

    changeMonth(offset) {
        this.currentDate.setMonth(this.currentDate.getMonth() + offset);
        this.updateCalendar();
    }

    updateCalendar() {
        const currentYear = this.currentDate.getFullYear();
        const currentMonth = this.currentDate.getMonth();

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const lastDay = new Date(currentYear, currentMonth + 1, 0).getDate();

        this.monthYearElement.textContent = this.currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });

        let datesHTML = "";

        // Poprzedni miesiąc
        for (let i = firstDay - 1; i >= 0; i--) {
            const prevDate = new Date(currentYear, currentMonth, -i);
            datesHTML += `<div class="date inactive">${prevDate.getDate()}</div>`;
        }

        // Obecny miesiąc
        for (let i = 1; i <= lastDay; i++) {
            const date = new Date(currentYear, currentMonth, i);
            const activeClass = date.toDateString() === new Date().toDateString() ? 'active' : '';
            datesHTML += `<div class="date ${activeClass}">${i}</div>`;
        }

        // Następny miesiąc
        const remainingDays = 7 - (new Date(currentYear, currentMonth + 1, 0).getDay() + 1);
        for (let i = 1; i <= remainingDays; i++) {
            const nextDate = new Date(currentYear, currentMonth + 1, i);
            datesHTML += `<div class="date inactive">${nextDate.getDate()}</div>`;
        }

        this.datesElement.innerHTML = datesHTML;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const calendar = new Calendar(
        document.getElementById('monthYear'),
        document.getElementById('dates'),
        document.getElementById('previesBtn'),
        document.getElementById('nextBtn')
    );
});

/*Kod trzyma się zasad paradygmatu obiektowego, ponieważ wykorzystuje hermetyzację, zamykając dane i logikę wewnątrz klasy Calendar, co ogranicza dostęp do właściwości i metod spoza obiektu. Dzięki abstrakcji użytkownik korzysta z wysokopoziomowych metod, takich jak updateCalendar() czy changeMonth(), bez znajomości szczegółów implementacji. Struktura kodu wspiera dziedziczenie, umożliwiając rozszerzenie funkcjonalności poprzez tworzenie klas pochodnych, np. dodanie świąt w rozszerzonej klasie AdvancedCalendar. Potencjał polimorfizmu pozwala na nadpisywanie metod w klasach dziedziczących, co zwiększa elastyczność projektu. Kod jest modularny, wielokrotnego użytku i łatwy do testowania, co czyni go zgodnym z głównymi filarami OOP: hermetyzacją, abstrakcją, dziedziczeniem i polimorfizmem.*/