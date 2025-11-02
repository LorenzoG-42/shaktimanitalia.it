# Technical Sheets Plugin

Un plugin WordPress completo per la gestione delle schede tecniche con custom post type, campi personalizzabili, categorie e tassonomie.

## Caratteristiche Principali

- **Custom Post Type**: Schede tecniche con gestione completa
- **Tassonomie**: Tipo e Marca per categorizzare le schede
- **4 Sezioni Layout**:
  - Sezione 1: Gallery immagini con zoom (50% larghezza)
  - Sezione 2: Tabella informazioni basilari personalizzabile (50% larghezza)
  - Sezione 3: Contenuto libero del post (50% larghezza)
  - Sezione 4: Accordion con titolo, immagine opzionale e testo (50% larghezza)
- **Gestione AJAX**: Aggiunta/rimozione dinamica di elementi
- **Export PDF**: Funzionalità di esportazione in PDF
- **Stampa ottimizzata**: Stili specifici per la stampa
- **Shortcode**: Integrazione tramite shortcode
- **Responsive Design**: Compatibile con tutti i dispositivi
- **Compatibilità Avada**: Ottimizzato per il tema Avada

## Installazione

1. Carica la cartella `technical-sheets` nella directory `/wp-content/plugins/`
2. Attiva il plugin dal pannello 'Plugins' di WordPress
3. Configura le impostazioni da 'Technical Sheets' > 'Settings'

## Utilizzo

### Creazione di una Scheda Tecnica

1. Vai su 'Technical Sheets' > 'Add New'
2. Inserisci il titolo della scheda
3. Aggiungi il contenuto principale nell'editor
4. Configura le 4 sezioni:
   - **Gallery**: Aggiungi immagini trascinabili
   - **Basic Information**: Crea coppie label-value personalizzate
   - **Accordion**: Aggiungi sezioni espandibili con titolo, immagine e testo
5. Assegna Tipo e Marca dalle rispettive tassonomie
6. Pubblica la scheda

### Shortcode

#### Archivio Schede Tecniche
```php
[technical_sheets posts_per_page="12" columns="3" show_excerpt="yes" show_meta="yes"]
```

**Parametri disponibili:**
- `posts_per_page`: Numero di schede da mostrare (default: 12)
- `type`: Filtra per tipo specifico (slug della tassonomia)
- `brand`: Filtra per marca specifica (slug della tassonomia)
- `orderby`: Ordina per (date, title, menu_order)
- `order`: Direzione ordinamento (ASC, DESC)
- `columns`: Numero di colonne (1-4, default: 3)
- `show_excerpt`: Mostra estratto (yes/no, default: yes)
- `show_meta`: Mostra meta informazioni (yes/no, default: yes)

#### Scheda Tecnica Singola
```php
[technical_sheet id="123" sections="all"]
```

**Parametri disponibili:**
- `id`: ID della scheda tecnica (obbligatorio)
- `sections`: Sezioni da mostrare (all, gallery, basic_info, content, accordion)

### Funzioni Template

#### Visualizzare la Gallery
```php
ts_display_gallery($post_id, $class = '');
```

#### Visualizzare Informazioni Basilari
```php
ts_display_basic_info($post_id, $class = '');
```

#### Visualizzare Accordion
```php
ts_display_accordion($post_id, $class = '');
```

#### Visualizzare Pulsante Export PDF
```php
ts_display_pdf_export_button($post_id, $class = '');
```

#### Visualizzare Pulsante Stampa
```php
ts_display_print_button($class = '');
```

#### Visualizzare Meta Informazioni
```php
ts_display_sheet_meta($post_id, $class = '');
```

### Funzioni Helper

#### Ottenere Dati Gallery
```php
$gallery_images = ts_get_gallery_images($post_id);
```

#### Ottenere Informazioni Basilari
```php
$basic_info = ts_get_basic_info($post_id);
```

#### Ottenere Sezioni Accordion
```php
$accordion_sections = ts_get_accordion_sections($post_id);
```

#### Ottenere URL Export PDF
```php
$pdf_url = ts_get_pdf_export_url($post_id);
```

## Personalizzazione

### Template Override

Puoi sovrascrivere i template copiando i file dalla cartella `templates/` del plugin alla cartella del tuo tema:

- `single-technical-sheet.php`: Template scheda singola
- `archive-technical-sheet.php`: Template archivio

### Stili CSS

Il plugin include CSS responsive. Puoi personalizzare gli stili aggiungendo CSS al tuo tema:

```css
.ts-single-sheet {
    /* I tuoi stili personalizzati */
}

.ts-gallery {
    /* Personalizza la gallery */
}

.ts-basic-info-table {
    /* Personalizza la tabella informazioni */
}

.ts-accordion {
    /* Personalizza l'accordion */
}
```

### Hook e Filtri

#### Actions
```php
// Dopo la creazione di una scheda tecnica
do_action('ts_after_create_sheet', $post_id);

// Prima dell'export PDF
do_action('ts_before_pdf_export', $post_id);

// After PDF export
do_action('ts_after_pdf_export', $post_id, $pdf_path);
```

#### Filters
```php
// Filtra il contenuto PDF
add_filter('ts_pdf_content', 'custom_pdf_content', 10, 2);

// Filtra le opzioni del plugin
add_filter('ts_plugin_options', 'custom_plugin_options');

// Filtra i campi meta box
add_filter('ts_meta_box_fields', 'custom_meta_fields');
```

## Impostazioni

### Impostazioni Generali
- **Posts per page in archive**: Numero di schede per pagina nell'archivio
- **Enable PDF Export**: Abilita/disabilita export PDF
- **Enable Print Styles**: Abilita/disabilita stili ottimizzati per la stampa

## Compatibilità

- **WordPress**: 5.0+
- **PHP**: 7.4+
- **Tema**: Avada (ottimizzato)
- **Browser**: Tutti i browser moderni

## Struttura File

```
technical-sheets/
├── technical-sheets.php (file principale)
├── includes/
│   ├── class-ts-post-type.php
│   ├── class-ts-taxonomies.php
│   ├── class-ts-meta-boxes.php
│   ├── class-ts-admin.php
│   ├── class-ts-frontend.php
│   ├── class-ts-shortcodes.php
│   ├── class-ts-pdf-export.php
│   └── ts-functions.php
├── templates/
│   ├── single-technical-sheet.php
│   └── archive-technical-sheet.php
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   └── frontend.css
│   └── js/
│       ├── admin.js
│       └── frontend.js
└── README.md
```

## Changelog

### 1.0.0
- Rilascio iniziale
- Custom post type per schede tecniche
- Tassonomie Tipo e Marca
- Sistema a 4 sezioni con layout 50/50
- Gestione AJAX per gallery, informazioni basilari e accordion
- Export PDF con TCPDF
- Stili ottimizzati per stampa
- Shortcode per integrazione
- Template responsive
- Compatibilità con tema Avada

## Supporto

Per supporto e domande, contattare lo sviluppatore.

## Licenza

GPLv2 or later
