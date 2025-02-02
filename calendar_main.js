class Calendar {
    constructor(monthYearElement, datesElement, prevButton, nextButton) {
        this.monthYearElement = monthYearElement;
        this.datesElement = datesElement;
        this.currentDate = new Date();
        this.initListeners(prevButton, nextButton);
        this.updateCalendar();
        
        // Inicjalizacja paska kategorii
        if (typeof categoryData !== 'undefined') {
            this.updateCategoryBar(categoryData);
        }
    }

    initListeners(prevButton, nextButton) {
        // Obsługa nawigacji kalendarza
        prevButton.addEventListener("click", () => this.changeMonth(-1));
        nextButton.addEventListener("click", () => this.changeMonth(1));

        // Obsługa kliknięć w daty
        this.datesElement.addEventListener('click', (event) => {
            const dateDiv = event.target.closest('.date');
            if (dateDiv && !dateDiv.classList.contains('inactive')) {
                const selectedDate = dateDiv.getAttribute('data-date');
                if (selectedDate) {
                    this.fetchDayExpenses(selectedDate);
                }
            }
        });
    }

    // Pobieranie wydatków dla wybranego dnia
    fetchDayExpenses(dateString) {
        fetch(`getExpenses.php?date=${dateString}`)
            .then(response => response.json())
            .then(expenses => this.updateDayExpenses(dateString, expenses))
            .catch(error => {
                console.error('Error fetching expenses:', error);
                document.getElementById('dayExpenses').innerHTML = 
                    '<p class="error">Wystąpił błąd podczas ładowania wydatków</p>';
            });
    }

    // Aktualizacja widoku wydatków dla wybranego dnia
    updateDayExpenses(dateString, expenses) {
        const [year, month, day] = dateString.split('-');
        const months = [
            'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca',
            'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia'
        ];

        // Aktualizacja nagłówka
        document.getElementById('dayTitle').textContent = 
            `Wydatki ${parseInt(day)} ${months[parseInt(month)-1]} ${year}`;

        const expensesContainer = document.getElementById('dayExpenses');
        
        // Obsługa braku wydatków
        if (expenses.length === 0) {
            expensesContainer.innerHTML = '<p class="no-expenses">Brak wydatków w tym dniu</p>';
            return;
        }

        // Mapowanie kategorii na polskie nazwy
        const categoryNames = {
            'food': 'Jedzenie',
            'clothes': 'Ubranie',
            'entertainment': 'Rozrywka',
            'bills': 'Rachunki',
            'other': 'Inne'
        };

        // Generowanie HTML dla listy wydatków
        const expensesHTML = expenses.map(expense => `
            <div class="expense-item">
                <span class="expense-category">${categoryNames[expense.category] || expense.category}</span>
                <span class="expense-amount">${parseFloat(expense.amount).toFixed(2)} zł</span>
            </div>
        `).join('');

        expensesContainer.innerHTML = expensesHTML;
    }

    // Zmiana miesiąca w kalendarzu
    changeMonth(offset) {
        this.currentDate.setMonth(this.currentDate.getMonth() + offset);
        this.updateCalendar();
    }

    // Aktualizacja widoku kalendarza
    updateCalendar() {
        const currentYear = this.currentDate.getFullYear();
        const currentMonth = this.currentDate.getMonth();

        // Aktualizacja nagłówka kalendarza
        this.monthYearElement.textContent = this.currentDate.toLocaleString('pl-PL', {
            month: 'long',
            year: 'numeric'
        });

        // Obliczenia dla kalendarza
        const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        let startDay = firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1;

        // Generowanie HTML kalendarza
        let datesHTML = "";

        // Puste komórki na początku miesiąca
        for (let i = 0; i < startDay; i++) {
            datesHTML += '<div class="date inactive"></div>';
        }

        // Dni miesiąca
        for (let day = 1; day <= daysInMonth; day++) {
            const dateObj = new Date(currentYear, currentMonth, day);
            const isToday = dateObj.toDateString() === new Date().toDateString();
            const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            datesHTML += `
                <div class="date${isToday ? ' active' : ''}" data-date="${dateString}">
                    ${day}
                </div>
            `;
        }

        this.datesElement.innerHTML = datesHTML;
    }

    // Aktualizacja paska kategorii wydatków
    updateCategoryBar(categoryData) {
        const total = Object.values(categoryData).reduce((sum, val) => sum + Number(val), 0);
        if (total === 0) return;

        const setWidth = (className, value) => {
            const element = document.querySelector(className);
            if (element && value) {
                const percentage = (Number(value) / total * 100).toFixed(2);
                element.style.width = `${percentage}%`;
                element.title = `${parseFloat(value).toFixed(2)} zł (${percentage}%)`;
            } else if (element) {
                element.style.width = '0';
            }
        };

        setWidth('.jedzenieGraph', categoryData.food);
        setWidth('.ubraniaGraph', categoryData.clothes);
        setWidth('.rozrywkaGraph', categoryData.entertainment);
        setWidth('.rachunkiGraph', categoryData.bills);
        setWidth('.inneGraph', categoryData.other);
    }
}

// Inicjalizacja po załadowaniu strony
document.addEventListener('DOMContentLoaded', () => {
    // Ustawienie domyślnego komunikatu
    document.getElementById('dayExpenses').innerHTML = 
        '<p class="no-expenses">Wybierz odpowiedni dzień z kalendarza po lewej stronie, aby zobaczyć szczegóły wydatków.</p>';

    // Inicjalizacja kalendarza
    new Calendar(
        document.getElementById('monthYear'),
        document.getElementById('dates'),
        document.getElementById('previesBtn'),
        document.getElementById('nextBtn')
    );
});