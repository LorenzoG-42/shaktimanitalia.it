# Test Import CSV - Diagnosi e Risoluzione

## Problema Riscontrato

Il file CSV caricato ha una struttura **ridotta** rispetto al formato completo:

### Colonne presenti nel CSV:
```
TITOLO, CONTENUTO, CATEGORIA, MODELLO, VERSIONE, STATO
```

### Colonne gestite dall'importer:
```
TITOLO, CONTENUTO, CATEGORIA, MODELLO, VERSIONE, UBICAZIONE, STATO, 
NOME_CLIENTE, NUMERO_CONTRATTO, RAGIONE_SOCIALE, DATA_CONTRATTO
```

**Nota:** La colonna `UBICAZIONE` non è presente nel CSV caricato, ma questo non dovrebbe essere un problema dato che tutte le colonne tranne `TITOLO` sono opzionali.

## Modifiche Implementate

### 1. Sistema di Debug Automatico
- Ora l'importer crea un file di log dettagliato: `wp-content/uploads/csv-import-debug.log`
- Il log contiene:
  - Headers rilevati dal CSV
  - Numero di colonne
  - Mapping delle colonne
  - Dettaglio di ogni operazione (INSERT/UPDATE/ERRORE)

### 2. Gestione BOM UTF-8
- Rimozione automatica del BOM (Byte Order Mark) che può causare problemi di parsing
- Normalizzazione degli header con trim avanzato

### 3. Logging Dettagliato
- Mostra le prime 3 righe elaborate per verificare il parsing
- Traccia ogni inserimento/aggiornamento/errore
- Salva informazioni anche senza WP_DEBUG attivo

## Come Testare

### 1. Verifica il CSV
Assicurati che il file CSV:
- Sia salvato in UTF-8 (preferibilmente UTF-8 senza BOM)
- Usi la virgola come separatore
- Abbia il carattere € scritto correttamente (non �)

### 2. Carica il CSV
1. Vai su **Mezzi Agricoli > Importa CSV**
2. Seleziona il file CSV
3. **IMPORTANTE**: Spunta "Aggiorna esistenti" se vuoi aggiornare mezzi già presenti
4. Clicca su "Importa CSV"

### 3. Controlla il File di Log
Dopo l'import, scarica e controlla il file:
```
wp-content/uploads/csv-import-debug.log
```

Il file conterrà informazioni come:
```
Inizio import: 2026-01-27 14:30:00
Headers CSV: TITOLO | CONTENUTO | CATEGORIA | MODELLO | VERSIONE | STATO
Numero colonne: 6
Colonne mappate: titolo=0, contenuto=1, categoria=2, modello=3, versione=4, stato=5
Prima riga dati: TRINCIA AFM 160 SH.25.0746 | Descrizione: ARM FLAIL MOWER... | TRINCIA | AFM | 160 | disponibile
Numero campi prima riga: 6
Riga 2 - Titolo: TRINCIA AFM 160 SH.25.0746, Stato: disponibile, Categoria: TRINCIA
INSERT: TRINCIA AFM 160 SH.25.0746
...
Fine import: 2026-01-27 14:30:05
Totali - Nuovi: 165, Aggiornati: 0, Errori: 0
```

### 4. Problemi Comuni e Soluzioni

#### Problema: "Nessuna operazione eseguita"
**Causa:** Tutti i mezzi già esistono e checkbox "Aggiorna esistenti" non è spuntata
**Soluzione:** Spunta "Aggiorna esistenti" prima di importare

#### Problema: Caratteri strani (�) al posto di €
**Causa:** Encoding del file non corretto
**Soluzione:** 
1. Apri il CSV con un editor di testo (Notepad++, VS Code)
2. Salva come UTF-8 (senza BOM)
3. Ricarica il file

#### Problema: "Colonne obbligatorie mancanti: TITOLO"
**Causa:** Header non riconosciuto
**Soluzione:** Verifica che la prima riga contenga esattamente `TITOLO` (maiuscolo/minuscolo non importa)

#### Problema: Stati non validi
**Causa:** Il campo STATO contiene valori diversi da: disponibile, riservato, venduto
**Soluzione:** Assicurati che tutti i valori nella colonna STATO siano uno di questi tre (minuscolo)

## Formato CSV Consigliato

### Minimo (solo TITOLO obbligatorio):
```csv
TITOLO
Trattore MT 225
```

### Completo:
```csv
TITOLO,CONTENUTO,CATEGORIA,MODELLO,VERSIONE,UBICAZIONE,STATO,NOME_CLIENTE,NUMERO_CONTRATTO,RAGIONE_SOCIALE,DATA_CONTRATTO
Trattore MT 225,Descrizione completa,Trattori,MT Series,225,Magazzino A,disponibile,,,,
```

### Il tuo formato (compatibile):
```csv
TITOLO,CONTENUTO,CATEGORIA,MODELLO,VERSIONE,STATO
TRINCIA AFM 160,Descrizione,TRINCIA,AFM,160,disponibile
```

## Prossimi Passi

1. Carica il CSV con checkbox "Aggiorna esistenti" attivo
2. Scarica e leggi il file `csv-import-debug.log`
3. Se ci sono errori, condividi il contenuto del log per diagnosi più approfondita
4. Verifica che i mezzi siano stati creati in **Mezzi Agricoli > Tutti i mezzi**
