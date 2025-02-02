class YearNavigation {
    constructor() {
        this.currentYear = new Date().getFullYear();
        this.yearSpan = document.getElementById('currentYear');
        this.spendingSpan = document.getElementById('Spending');
        
        document.getElementById('prevYearBtn').addEventListener('click', () => this.changeYear(-1));
        document.getElementById('nextYearBtn').addEventListener('click', () => this.changeYear(1));
        
        this.updateYearDisplay();
    }

    changeYear(offset) {
        this.currentYear += offset;
        this.updateYearDisplay();
        this.fetchYearlySum();
    }

    updateYearDisplay() {
        this.yearSpan.textContent = `W roku ${this.currentYear}`;
    }

    fetchYearlySum() {
        fetch(`getYearlySum.php?year=${this.currentYear}`)
            .then(response => response.json())
            .then(data => {
                this.spendingSpan.textContent = Math.round(data.sum);
            })
            .catch(error => console.error('Error:', error));
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new YearNavigation();
});