# Fix Paginazione - Carica sempre pagina 1

## Problema
La paginazione AJAX caricava sempre la pagina 1 indipendentemente dal numero cliccato.

## Cause Identificate

### 1. Mancanza parametro `paged` nella Query
**File**: `includes/class-query-builder.php`
- Il metodo `build_query_args()` non includeva il parametro `paged` negli argomenti di WP_Query
- WordPress necessita di questo parametro per sapere quale pagina caricare

### 2. Attributi `data-page` non generati correttamente
**File**: `includes/class-template-loader.php`
- La paginazione iniziale non aggiungeva attributi `data-page` ai link
- I link generati non erano compatibili con il sistema AJAX

### 3. Regex inefficace per estrarre il numero di pagina
**File**: `elementor-post-loop.php` e `assets/js/frontend.js`
- Le espressioni regolari per estrarre il numero di pagina dai link non funzionavano correttamente
- Non gestivano tutti i formati possibili di URL

## Soluzioni Implementate

### 1. Query Builder - Aggiunto supporto paginazione
```php
// Paginazione - IMPORTANTE per AJAX
if (!empty($settings['paged'])) {
    $args['paged'] = (int) $settings['paged'];
} else {
    $args['paged'] = max(1, get_query_var('paged', 1));
}
```

### 2. Template Loader - Generazione link con data-page
```php
foreach ($links as $link) {
    // Estrai il numero di pagina e aggiungi data-page attribute
    if (preg_match('/#page-(\d+)/', $link, $matches)) {
        $page_num = $matches[1];
        $link = preg_replace('/href=["\']#page-\d+["\']/', 'href="#" data-page="' . $page_num . '"', $link);
    }
    echo $link;
}
```

### 3. PHP - Migliorata generazione paginazione AJAX
```php
foreach ($links as $link) {
    // Estrai il numero di pagina usando regex robusta
    if (preg_match('/#page-(\d+)/', $link, $matches)) {
        $page_num = $matches[1];
        $link = preg_replace('/href=["\']#page-\d+["\']/', 'href="#" data-page="' . $page_num . '"', $link);
    }
    echo $link;
}
```

### 4. JavaScript - Logica migliorata estrazione pagina
```javascript
// Prima controlla se c'è un attributo data-page
if ($link.data('page')) {
    page = parseInt($link.data('page'));
} else {
    // Prova pattern multipli
    const patterns = [
        /#page-(\d+)/,           // #page-2
        /\/page\/(\d+)/,         // /page/2/
        /[?&]paged=(\d+)/        // ?paged=2
    ];
    
    for (let pattern of patterns) {
        const matches = href.match(pattern);
        if (matches) {
            page = parseInt(matches[1]);
            break;
        }
    }
}
```

### 5. Debug Logging
Aggiunti log PHP e JavaScript per tracciare:
- Numero di pagina ricevuto
- Settings paged
- Post trovati
- Ogni step del processo di estrazione pagina

## Test di Verifica

### Test 1: Paginazione Numerata
1. Crea widget con almeno 20 post
2. Imposta 6 post per pagina
3. Click su "2" nella paginazione
4. **Verifica**: Dovrebbero apparire i post 7-12
5. Click su "3"
6. **Verifica**: Dovrebbero apparire i post 13-18

### Test 2: Console Debug
Apri console e clicca su pagina 2:
```
Getting widget settings...
Settings loaded from JSON: {post_type: "post", ...}
Page from data-page: 2
Final page number: 2
AJAX Response: {success: true, data: {page: 2, ...}}
Post items found: 6
```

### Test 3: PHP Debug Log
Con WP_DEBUG abilitato, verifica nel file debug.log:
```
AJAX Load Posts - Page: 2, Type: pagination
Settings paged: 2
Found 6 posts
```

### Test 4: Verifica HTML
Ispeziona i link della paginazione:
```html
<a href="#" data-page="2" class="page-numbers">2</a>
<a href="#" data-page="3" class="page-numbers">3</a>
```

## Modifiche ai File

### File Modificati:
1. ✅ `includes/class-query-builder.php` - Aggiunto parametro `paged`
2. ✅ `includes/class-template-loader.php` - Generazione link con `data-page`
3. ✅ `elementor-post-loop.php` - Migliorata regex e debug
4. ✅ `assets/js/frontend.js` - Logica robusta estrazione pagina

## Checklist Finale

- [x] Parametro `paged` passato a WP_Query
- [x] Attributi `data-page` nei link
- [x] Regex funzionanti per tutti i formati
- [x] Debug logging attivo
- [x] Test su diverse pagine
- [x] Verifica responsive

## Note Tecniche

### Flusso Completo Paginazione:

1. **Click link pagina 2**
2. **JS**: Estrae `data-page="2"`
3. **JS**: Invia AJAX con `page: 2`
4. **PHP**: Riceve `$_POST['page'] = 2`
5. **PHP**: Aggiunge `$settings['paged'] = 2`
6. **PHP**: Query_Builder crea `$args['paged'] = 2`
7. **PHP**: WP_Query carica post 7-12 (se 6 per pagina)
8. **PHP**: Genera nuova paginazione con `current: 2`
9. **JS**: Riceve HTML e sostituisce contenuto
10. **JS**: Anima nuovi elementi

### Formato Link Supportati:
- `#page-2` (usato da plugin)
- `/page/2/` (WordPress standard)
- `?paged=2` (query string)
- `data-page="2"` (attributo diretto)

## Risoluzione Problemi

### Se carica sempre pagina 1:
1. Controlla console: cerca "Final page number"
2. Verifica PHP log: cerca "Settings paged"
3. Ispeziona HTML: verifica attributo `data-page`
4. Controlla che Query_Builder usi `$args['paged']`

### Se la paginazione non si aggiorna:
1. Verifica che `response.data.pagination` esista
2. Controlla che il container `.elementor-post-loop-pagination` esista
3. Verifica che i nuovi link abbiano `data-page`

---

**Versione**: 1.0.1
**Data**: Novembre 2024
**Status**: ✅ RISOLTO
