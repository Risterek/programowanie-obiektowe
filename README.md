1. Warstwy aplikacji
Stosuj zasadę rozdzielania odpowiedzialności (Separation of Concerns) i podziel aplikację na warstwy:

Warstwa prezentacji (Frontend): Twoje strony HTML/CSS oraz JavaScript do interakcji z backendem.
Warstwa logiki biznesowej (Backend): Klasy C# realizujące zasady programowania obiektowego.
Warstwa dostępu do danych (Database Layer): Klasy C# obsługujące komunikację z bazą danych.
2. Baza danych
Wybierz system baz danych, np. SQL Server lub SQLite. Struktura bazy danych może wyglądać następująco:

Tabela: Users

Id (PK)	Username	PasswordHash
1	user1	hashed_password_1
2	user2	hashed_password_2
Tabela: Expenses

Id (PK)	UserId (FK)	Amount	Category	Date
1	1	50.00	Food	2025-01-16
2	1	200.00	Credit	2025-01-15
3. Backend w C#
Stwórz aplikację w C# jako projekt ASP.NET Core MVC lub Web API:

Klasy modelu (Encapsulacja danych):

public class User
{
    public int Id { get; set; }
    public string Username { get; set; }
    public string PasswordHash { get; set; }
}

public class Expense
{
    public int Id { get; set; }
    public int UserId { get; set; }
    public decimal Amount { get; set; }
    public string Category { get; set; }
    public DateTime Date { get; set; }
}
Warstwa dostępu do danych:

Użyj Entity Framework Core do mapowania danych na klasy:

public class BudgetContext : DbContext
{
    public DbSet<User> Users { get; set; }
    public DbSet<Expense> Expenses { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder options)
        => options.UseSqlServer("your_connection_string");
}
Warstwa logiki biznesowej:

Klasa odpowiedzialna za operacje na wydatkach:

public class ExpenseService
{
    private readonly BudgetContext _context;

    public ExpenseService(BudgetContext context)
    {
        _context = context;
    }

    public void AddExpense(int userId, decimal amount, string category, DateTime date)
    {
        var expense = new Expense
        {
            UserId = userId,
            Amount = amount,
            Category = category,
            Date = date
        };
        _context.Expenses.Add(expense);
        _context.SaveChanges();
    }

    public IEnumerable<Expense> GetExpensesByUser(int userId, DateTime month)
    {
        return _context.Expenses
            .Where(e => e.UserId == userId && e.Date.Month == month.Month && e.Date.Year == month.Year)
            .ToList();
    }
}
4. API do komunikacji frontend-backend
Stwórz kontrolery w ASP.NET Core Web API, np.:

Kontroler logowania:

[ApiController]
[Route("api/auth")]
public class AuthController : ControllerBase
{
    private readonly BudgetContext _context;

    public AuthController(BudgetContext context)
    {
        _context = context;
    }

    [HttpPost("login")]
    public IActionResult Login(string username, string password)
    {
        var user = _context.Users.SingleOrDefault(u => u.Username == username);
        if (user == null || !VerifyPassword(password, user.PasswordHash))
        {
            return Unauthorized();
        }
        return Ok(new { user.Id, user.Username });
    }

    private bool VerifyPassword(string password, string hash)
    {
        // Implementacja weryfikacji hasła
        return true; // dla uproszczenia
    }
}
Kontroler wydatków:

[ApiController]
[Route("api/expenses")]
public class ExpensesController : ControllerBase
{
    private readonly ExpenseService _expenseService;

    public ExpensesController(ExpenseService expenseService)
    {
        _expenseService = expenseService;
    }

    [HttpPost]
    public IActionResult AddExpense(int userId, decimal amount, string category, DateTime date)
    {
        _expenseService.AddExpense(userId, amount, category, date);
        return Ok();
    }

    [HttpGet("{userId}")]
    public IActionResult GetExpenses(int userId, DateTime month)
    {
        var expenses = _expenseService.GetExpensesByUser(userId, month);
        return Ok(expenses);
    }
}
5. Frontend – komunikacja z backendem
Dodaj skrypty JavaScript do wysyłania żądań do backendu:

const login = async (username, password) => {
    const response = await fetch('api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    });
    if (response.ok) {
        const data = await response.json();
        console.log('Logged in:', data);
    } else {
        console.error('Login failed');
    }
};

const addExpense = async (userId, amount, category, date) => {
    const response = await fetch('api/expenses', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ userId, amount, category, date })
    });
    if (response.ok) {
        console.log('Expense added');
    } else {
        console.error('Failed to add expense');
    }
};
6. Testowanie i wdrożenie
Przetestuj aplikację lokalnie.
Wdróż aplikację na serwer, np. Azure lub IIS.
Wdrożenie projektu w ten sposób pozwoli Ci stworzyć skalowalną aplikację z przestrzeganiem zasad programowania obiektowego i separacji odpowiedzialności. Czy chcesz rozwinąć konkretny element?
