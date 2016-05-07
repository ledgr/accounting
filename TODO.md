1. AccountSet har ersatt ChartOfAccounts. Planen är att AccountPlan ska ärva AccountSet och
   lägga till metoder för gruppering vid rapportskrivning osv..
   ```php
   class EUBAS97 extends AccountSet implements AccountPlan {...}
   ```

1. Ingående balans; krävs alltid i bokföringen; endast för 1-2000-konton. Kan sparas
   direkt i kontoplan eftersom kontoplan måste vara knutet till bokföringsår!!??
   Kontoplan; varje konto anger vilken typ det är, detta anges ju egentligen av
   vilket tusental det är, onödigt med bokstav?? Typ borde även representeras av
   konstant i interface istället för vanlig bokstav...

## Huvudbok

består av kontosummeringar (subklass av Account?) för varje konto: . ingående
balans (vid årets början, från "ingående balans")
. ingående saldo (vid periodens början, om huvudbok inte skrivs ut för hela året)
. poster (Transactions) som berör kontot (beräknas från verifikationer) (+ verifikationsbeskrivning...)
. utgående saldo (beräknas)
eller kan vi använda den vanliga account-klassen för detta???
```php
$l = new Ledger($verAdapter, $accountsAdapter, $organizationData)
// $verAdapter plats där ver läses, SieAdapter, PdoAdapter, osv...
// accountsAdapter på samma sätt... Kan ersätta ChartOfAccounts??
// behövs organization data i Ledger? Eller bara skicka vidare till Report?
// i så fall organization bättre skickas direkt till report... eller formatter...
```

### VERIFIKATIONSLISTA
```php
foreach($l->getVerificationIterator(...) as $verNr => $verification);
```

### HUVUDBOK
```php
foreach($l->getAccountIterator(...) as $accountNr => $accountSummary);
// returnerar alltig i nummerordning, minst först!
// för effektivitet bör skapandet av AccountIterator kräva endast ett pass över VerificationIterator..
// .. account summarys kan ju också cachas på något sätt...
// ... i argumentlistorna betyder att vilka konton/verifikationer mm som ska ingå kan styras med argument.
```

### Rapporter
$rapport = new BalanceReport($l); // anropar $l->getAccountIterator()
echo new JsonFormatter($rapport);
//alternativt
$jsonFormatter = new JsonFormatter($rapport);
echo $jsonFormatter->format();

// BalanceReport kan ärva CustomReport, men en array-definition av hur rapporten ska se ut...
// VerificationReport, IncomingBalance, LedgerReport, ChartOfAccounts, mm är egna report-klasser

// kan sedan skrivas till olika format (pdf formatter kan helt enkelt vara snappy...)
echo new PdfFormatter(new HtmlFormatter($rapport));
echo $rapport->format();

// vissa format har inte stöd för alla rapporter...
echo new SieFormatter($verificationReport);
echo new SieFormatter($chartOfAccounts);

Andra typer av rapporter

Värden hämtas från huvudbok

Olika rapporter är sedan grupperingar av kontosummeringar
- kan nestas flera gånger
- kan innehålla flera perioder i olika kolumner (perioden, hela året, föregående år osv...)

Balansrapport
Tillgångar (fodringar + kassa/bank; 1000-konton)

skulder (eget kapital + skulder; 2000-konton)
Resultatrapport
Intäkter (3000-konton)

utgifter (4-9000-konton, grupperat efter namn)
Kodförslag (Kräver php 5.5)