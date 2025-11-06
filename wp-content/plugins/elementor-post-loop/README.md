# Elementor Post Loop

Un widget Elementor avanzato per creare loop di post personalizzati con supporto completo per tutti i tipi di post, pagine e Custom Post Types.

## Caratteristiche

- ✅ **Supporto completo per tutti i tipi di post** (post, pagine, CPT)
- ✅ **Query builder avanzato** per filtrare per tassonomie, meta fields, autori, date
- ✅ **Layout multipli** (griglia, lista, masonry)
- ✅ **Template personalizzabili** (card, overlay, minimal)
- ✅ **Controlli di stile completi** con Elementor
- ✅ **Paginazione avanzata** (numeri, prev/next, load more)
- ✅ **Anteprima live nell'editor** Elementor
- ✅ **Responsive design**
- ✅ **Supporto AJAX** per caricamento dinamico
- ✅ **Template override** dal tema
- ✅ **Schema markup** per SEO
- ✅ **Traduzione ready**

## Installazione

1. Carica la cartella del plugin in `/wp-content/plugins/`
2. Attiva il plugin dal pannello di amministrazione
3. Assicurati che Elementor sia installato e attivo
4. Il widget "Post Loop Avanzato" sarà disponibile nella categoria "General" di Elementor

## Requisiti

- WordPress 5.0+
- Elementor 3.0+
- PHP 7.4+

## Utilizzo

### 1. Aggiungere il Widget

1. Apri una pagina con Elementor
2. Cerca "Post Loop Avanzato" nella barra di ricerca dei widget
3. Trascina il widget nella tua pagina
4. **L'anteprima sarà immediatamente visibile** con post di esempio

### Anteprima Live

Il widget mostra un'anteprima live **con dati reali** durante la modifica:
- **Post effettivi del database** che riflettono le impostazioni scelte
- **Aggiornamento in tempo reale** quando modifichi i controlli
- **Indicatori visivi** per distinguere dall'anteprima dal frontend
- **Badge informativi** che mostrano il tipo di post e ID
- **Limitazione automatica** a 8 post per non appesantire l'editor
- **Link disabilitati** per evitare navigazione accidentale
- **Anteprima paginazione** quando abilitata

#### Caratteristiche Anteprima:
- 🔍 **Indicatore "Dati Reali"** nell'angolo del container
- 🏷️ **Badge su ogni post** con tipo e ID
- 📊 **Statistiche live** sul numero di post trovati
- 🎨 **Stili applicati in tempo reale**
- ⚡ **Performance ottimizzata** per l'editor

### 2. Configurazione Query

#### Tipo di Post
- Seleziona il tipo di post da visualizzare
- Supporta tutti i post types pubblici inclusi i Custom Post Types

#### Filtraggio
- **Numero di post**: Imposta quanti post mostrare (-1 per tutti)
- **Offset**: Salta i primi N post
- **Ordinamento**: Per data, titolo, ordine menu, autore, etc.
- **Tassonomie**: Filtra per categorie, tag o tassonomie personalizzate
- **Meta Fields**: Filtra per campi personalizzati
- **Autore**: Includi/escludi autori specifici
- **Date**: Filtra per periodo temporale

### 3. Configurazione Layout

#### Layout disponibili:
- **Griglia**: Layout a griglia responsive (1-6 colonne)
- **Lista**: Layout a lista orizzontale
- **Masonry**: Layout masonry (richiede JavaScript)

#### Stili Template:
- **Card**: Stile carta con bordi
- **Overlay**: Contenuto sovrapposto all'immagine
- **Minimal**: Stile minimale senza bordi

### 4. Configurazione Contenuto

#### Elementi configurabili:
- **Immagine in evidenza**: Mostra/nascondi, dimensioni, link
- **Titolo**: Tag HTML, lunghezza massima, link
- **Meta informazioni**: Data, autore, commenti, categorie, tag
- **Excerpt**: Lunghezza personalizzabile
- **Link "Leggi di più"**: Testo personalizzabile

### 5. Paginazione e AJAX

#### Tipi di paginazione:
- **Numeri**: Paginazione numerata classica con caricamento AJAX
- **Precedente/Successivo**: Navigazione semplice
- **Carica Altri**: Caricamento AJAX dinamico con pulsante

#### Funzionalità AJAX (v1.0.1+):

✨ **Caratteristiche**:
- ⚡ Caricamento veloce senza refresh della pagina
- 🎨 Animazioni smooth fade-in
- 📊 Feedback visivo con overlay di loading
- 🔄 Aggiornamento automatico della paginazione
- 📱 Completamente responsive
- 🎯 Scroll automatico ai contenuti caricati

**Paginazione Numerata AJAX**:
```
1. Click su numero pagina (es. "2")
2. Appare overlay di loading
3. Contenuti esistenti sfumano
4. Nuovi post vengono caricati
5. Animazione fade-in staggered (scaglionata)
6. Paginazione si aggiorna
7. Scroll automatico al widget
```

**Load More AJAX**:
```
1. Click su "Carica altri"
2. Pulsante mostra "Caricamento..."
3. Nuovi post appaiono sotto quelli esistenti
4. Animazione fade-in uno per uno
5. Pulsante si aggiorna o scompare all'ultima pagina
```

#### Debug AJAX:

Per verificare il funzionamento AJAX, apri la Console del browser (F12):

```javascript
// Log automatici durante navigazione AJAX:
Getting widget settings...
Settings loaded from JSON: {...}
Page from data-page: 2
Final page number: 2
AJAX Response: {success: true, data: {...}}
Post items found: 6
HTML replaced
```

Se hai problemi con AJAX, consulta `AJAX_FIX_NOTES.md` e `PAGINATION_FIX.md` per diagnostica completa.

## Personalizzazione Template

### Override dal Tema

Puoi personalizzare i template copiando i file dalla cartella `templates/` del plugin nella cartella `elementor-post-loop/` del tuo tema:

```
/wp-content/themes/your-theme/elementor-post-loop/
├── post-item.php
└── [altri template]
```

### Template Personalizzati

#### Esempio di template personalizzato:

```php
<?php
// File: wp-content/themes/your-theme/elementor-post-loop/custom-post-item.php

global $post;
?>

<article class="my-custom-post-item">
    <h2><?php the_title(); ?></h2>
    <div class="my-meta">
        <?php echo get_the_date(); ?> - <?php the_author(); ?>
    </div>
    <div class="my-content">
        <?php the_excerpt(); ?>
    </div>
</article>
```

## Styling CSS

### Classi CSS principali:

```css
.elementor-post-loop-container      /* Container principale */
.elementor-post-loop-grid           /* Layout griglia */
.elementor-post-loop-list           /* Layout lista */
.elementor-post-loop-masonry        /* Layout masonry */
.elementor-post-loop-item           /* Singolo item */
.elementor-post-loop-thumbnail      /* Container immagine */
.elementor-post-loop-content        /* Container contenuto */
.elementor-post-loop-title          /* Titolo */
.elementor-post-loop-meta           /* Meta informazioni */
.elementor-post-loop-excerpt        /* Excerpt */
.elementor-post-loop-read-more      /* Link "Leggi di più" */
.elementor-post-loop-pagination     /* Paginazione */
```

### Esempio di personalizzazione CSS:

```css
.elementor-post-loop-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.elementor-post-loop-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.elementor-post-loop-title a {
    color: #333;
    text-decoration: none;
}

.elementor-post-loop-title a:hover {
    color: #007cba;
}
```

## Hook e Filtri

### Filtri disponibili:

```php
// Modifica gli argomenti della query
add_filter('elementor_post_loop_query_args', function($args, $settings) {
    // Modifica $args
    return $args;
}, 10, 2);

// Modifica il contenuto renderizzato
add_filter('elementor_post_loop_render_content', function($content, $settings) {
    // Modifica $content
    return $content;
}, 10, 2);
```

### Azioni disponibili:

```php
// Prima del rendering del loop
add_action('elementor_post_loop_before_render', function($settings) {
    // Il tuo codice
});

// Dopo il rendering del loop
add_action('elementor_post_loop_after_render', function($settings) {
    // Il tuo codice
});
```

## Custom Post Types

Il plugin supporta automaticamente tutti i Custom Post Types registrati correttamente. Per ogni CPT vengono rilevate automaticamente:

- Tutte le tassonomie associate
- I meta fields personalizzati
- Le gerarchie (se supportate)

### Esempio di registrazione CPT compatibile:

```php
register_post_type('prodotto', [
    'public' => true,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
    'taxonomies' => ['categoria_prodotto', 'tag_prodotto'],
    'show_ui' => true,
    'show_in_menu' => true,
]);
```

## Troubleshooting

### Problemi comuni:

1. **Widget non visibile**: Verifica che Elementor sia attivo e aggiornato
2. **Immagini non caricate**: Controlla le dimensioni immagine e i permessi
3. **Masonry non funziona**: Assicurati che JavaScript sia abilitato
4. **AJAX non funziona**: Verifica la configurazione del server e i nonce

### Debug:

Abilita il debug WordPress per vedere eventuali errori:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Supporto

Per supporto e segnalazione bug, contatta [supporto@shaktimanitalia.it](mailto:supporto@shaktimanitalia.it)

## Changelog

### 1.0.1 (6 Novembre 2024)
- 🐛 **FIX**: Risolto problema animazioni Load More
- 🐛 **FIX**: Risolto problema visualizzazione paginazione numerata
- 🐛 **FIX**: Risolto problema paginazione che caricava sempre pagina 1
- ✨ **NEW**: Aggiunte animazioni smooth per caricamenti AJAX
- ✨ **NEW**: Overlay di loading durante caricamenti
- ✨ **NEW**: Debug logging dettagliato per troubleshooting
- 📚 **DOCS**: Aggiunti file AJAX_FIX_NOTES.md e PAGINATION_FIX.md
- 🧪 **TEST**: Aggiunto file test-ajax.html per test standalone
- Vedi `CHANGELOG.md` per dettagli completi

### 1.0.0 (Precedente)
- Prima release
- Supporto completo per tutti i post types
- Layout multipli (grid, list, masonry)
- Template personalizzabili
- Paginazione avanzata
- Supporto AJAX
- Controlli Elementor completi

## Licenza

GPL v2 or later

## Crediti

Sviluppato da Shaktiman Italia
Website: [https://shaktimanitalia.it](https://shaktimanitalia.it)