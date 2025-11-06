# Shaktiman B2B - Plugin WordPress

Plugin per la gestione della sezione B2B dedicata ai rivenditori di mezzi agricoli.

## Descrizione

Questo plugin crea una sezione completa B2B per la gestione dei mezzi agricoli con accesso riservato ai rivenditori. Include:

- Custom Post Type per mezzi agricoli
- Tassonomie per categorizzazione e filtraggio
- Sistema di gestione stati (Disponibile, Riservato, Venduto)
- Ruoli personalizzati con permessi differenziati
- Interfaccia frontend con griglia stile WooCommerce
- Sistema di filtri avanzati
- Gestione contratti per utenti autorizzati

## Caratteristiche Principali

### Ruoli Utente

1. **Rivenditore**
   - Può visualizzare tutti i mezzi agricoli
   - Può modificare lo stato dei mezzi (disponibile, riservato, venduto)
   - Può inserire il nome del cliente quando riserva o vende un mezzo
   - Non può creare o eliminare mezzi

2. **Reparto Vendite**
   - Tutti i permessi del rivenditore
   - Può creare nuovi mezzi agricoli
   - Può inserire e modificare i dati del contratto
   - Può eliminare mezzi

3. **Amministratore**
   - Controllo completo su tutte le funzionalità

### Custom Post Type: Mezzi Agricoli

- **Slug**: `mezzo_agricolo`
- **Archivio**: `/mezzi-agricoli-b2b/`
- **Singolo**: `/mezzo-agricolo/{slug}/`

**Campi supportati**:
- Titolo (modello del mezzo)
- Descrizione completa
- Immagine in evidenza
- Excerpt
- Custom fields per dati aggiuntivi

### Tassonomie

1. **Disponibilità** (`disponibilita`)
   - Disponibile (default)
   - Riservato
   - Venduto

2. **Categoria** (`categoria_mezzo`)
   - Gerarchica
   - Personalizzabile

3. **Marchio** (`marchio`)
   - Non gerarchica
   - Tag-style

4. **Ubicazione** (`ubicazione`)
   - Non gerarchica
   - Per localizzazione geografica

5. **Stato Magazzino** (`stato_magazzino`)
   - In Arrivo
   - In Magazzino

### Meta Fields

**Informazioni Cliente** (tutti gli utenti autorizzati):
- Nome Cliente (obbligatorio per stati riservato/venduto)
- Data Prenotazione (automatica al cambio stato)
- Data Vendita (automatica al cambio stato)

**Informazioni Contratto** (solo Reparto Vendite e Admin):
- Numero Contratto (alfanumerico)
- Ragione Sociale
- Data Contratto

## Utilizzo

### Installazione

1. Carica la cartella `shaktiman-b2b` in `/wp-content/plugins/`
2. Attiva il plugin dal menu "Plugin" di WordPress
3. I ruoli personalizzati e le tassonomie verranno create automaticamente
4. Vai su Impostazioni → Permalink e salva per aggiornare le rewrite rules

### Creazione Mezzi Agricoli

1. Accedi all'admin di WordPress
2. Vai su "Mezzi Agricoli B2B" → "Aggiungi Nuovo"
3. Compila i campi richiesti
4. Assegna le tassonomie appropriate
5. Pubblica

### Accesso Frontend

**Archivio automatico**:
```
https://tuosito.it/mezzi-agricoli-b2b/
```

**Shortcode per inserire la griglia in una pagina**:
```
[mezzi_agricoli_grid per_page="12" columns="3" orderby="date" order="DESC"]
```

Parametri shortcode:
- `per_page`: Numero di mezzi per pagina (default: 12)
- `columns`: Numero di colonne nella griglia (2, 3, 4 - default: 3)
- `orderby`: Ordina per (date, title, etc. - default: date)
- `order`: Direzione ordinamento (ASC, DESC - default: DESC)

### Filtri Frontend

I filtri sono disponibili automaticamente nella pagina archivio e includono:

- Ricerca per keyword
- Filtro per Disponibilità
- Filtro per Categoria
- Filtro per Marchio
- Filtro per Ubicazione
- Filtro per Stato Magazzino

I filtri funzionano in AJAX per un'esperienza utente fluida.

### Gestione Stati

#### Disponibile → Riservato
1. Modifica il mezzo
2. Cambia la tassonomia "Disponibilità" a "Riservato"
3. Inserisci il nome del cliente
4. La data di prenotazione viene salvata automaticamente
5. Salva

#### Riservato → Venduto
1. Modifica il mezzo
2. Cambia la tassonomia "Disponibilità" a "Venduto"
3. Aggiorna il nome del cliente se necessario
4. La data di vendita viene salvata automaticamente
5. **Solo Reparto Vendite/Admin**: Compila i dati del contratto
6. Salva

#### Riservato/Venduto → Disponibile
1. Modifica il mezzo
2. Cambia la tassonomia "Disponibilità" a "Disponibile"
3. Il nome cliente e le date vengono cancellati automaticamente
4. Salva

## Sicurezza e Permessi

- L'archivio e i singoli mezzi sono accessibili solo agli utenti autenticati con ruolo rivenditore o superiore
- I controlli di accesso sono implementati a livello di template e capabilities
- I dati del contratto sono visibili e modificabili solo da Reparto Vendite e Amministratori
- Tutti i dati vengono sanitizzati prima del salvataggio
- Nonce e verifiche di sicurezza su tutte le operazioni AJAX

## Personalizzazione Template

Puoi sovrascrivere i template del plugin creando una cartella `shaktiman-b2b` nel tuo tema e copiandovi i template:

```
your-theme/
└── shaktiman-b2b/
    ├── archive-mezzo_agricolo.php
    ├── single-mezzo_agricolo.php
    ├── content-mezzo.php
    └── mezzi-grid.php
```

## Hooks e Filtri

Il plugin è estensibile tramite hooks e filtri WordPress standard.

### Actions disponibili
- Durante l'attivazione vengono registrati i ruoli e le tassonomie
- Durante il salvataggio vengono aggiornati automaticamente i meta fields

### Filtri disponibili
- Tutti i filtri standard di WordPress per post types e tassonomie

## Compatibilità

- WordPress 5.8+
- PHP 7.4+
- Compatibile con tutti i temi moderni
- Responsive design

## Supporto

Per supporto e segnalazione bug, contatta l'amministratore del sistema.

## Changelog

### 1.0.0 (2 Novembre 2025)
- Release iniziale
- Custom Post Type Mezzi Agricoli
- 5 Tassonomie personalizzate
- 2 Ruoli utente personalizzati
- Sistema di gestione stati
- Meta boxes per cliente e contratto
- Template frontend con griglia
- Sistema filtri AJAX
- Controlli di accesso completi
- Stili CSS responsive
- JavaScript per interazioni

## Credits

Sviluppato per Shaktiman Italia
