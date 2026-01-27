# Changelog - Ottimizzazione Import CSV

**Data:** 27 Gennaio 2026

## Problemi Risolti

### 1. Funzione Deprecata `get_page_by_title()`
**Problema:** La funzione `get_page_by_title()` è deprecata da WordPress 6.2+ e poteva causare problemi di rilevamento dei post esistenti.

**Soluzione:** Implementata nuova funzione `find_post_by_title()` che usa query dirette al database tramite `$wpdb`:
```php
private function find_post_by_title($title, $post_type = 'post') {
    global $wpdb;
    
    $post = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT ID, post_title FROM $wpdb->posts 
            WHERE post_title = %s 
            AND post_type = %s 
            AND post_status != 'trash'
            LIMIT 1",
            $title,
            $post_type
        )
    );
    
    return $post ? get_post($post->ID) : null;
}
```

### 2. Meta Fields Non Puliti Durante Aggiornamento
**Problema:** Durante l'aggiornamento di un mezzo esistente, i vecchi meta fields non venivano rimossi, causando dati residui (es. nome cliente vecchio che rimaneva).

**Soluzione:** Implementata funzione `clean_post_meta()` che elimina i meta fields prima di salvare i nuovi valori:
```php
private function clean_post_meta($post_id) {
    $meta_fields = array(
        '_nome_cliente',
        '_numero_contratto',
        '_ragione_sociale',
        '_data_contratto'
        // NON eliminiamo _data_riservato e _data_venduto per preservare lo storico
    );
    
    foreach ($meta_fields as $meta_key) {
        delete_post_meta($post_id, $meta_key);
    }
}
```

### 3. Tassonomie Non Sostituite
**Problema:** Durante l'aggiornamento, le tassonomie (categoria, modello, versione, ubicazione, stato) non venivano sostituite ma aggiunte, causando accumulo di termini.

**Soluzione:** Modificata la funzione `assign_term()` con parametro `$replace`:
- `$replace = true`: Sostituisce completamente i termini esistenti (usato durante import)
- `$replace = false`: Aggiunge ai termini esistenti
- Se `$term_name` è vuoto e `$replace = true`, rimuove tutti i termini

```php
private function assign_term($post_id, $term_name, $taxonomy, $replace = false) {
    if (empty($term_name)) {
        if ($replace) {
            wp_set_object_terms($post_id, array(), $taxonomy, false);
        }
        return;
    }
    
    // ... codice per creare/trovare il termine ...
    
    // $replace determina se sostituire (false) o aggiungere (true)
    wp_set_object_terms($post_id, $term_id, $taxonomy, !$replace);
}
```

### 4. Gestione Date Riservato/Venduto
**Problema:** Le date di riservazione/vendita venivano aggiornate anche quando non necessario.

**Soluzione:** Implementata logica intelligente che:
- Imposta la data solo quando lo stato cambia effettivamente
- Preserva le date esistenti se lo stato non cambia
- Crea automaticamente le date per nuovi mezzi con stato riservato/venduto

### 5. Logging Migliorato
**Problema:** Nessun feedback dettagliato sulle operazioni eseguite, difficile debugging.

**Soluzione:** 
- Aggiunto conteggio separato per mezzi nuovi e mezzi aggiornati
- Logging opzionale quando `WP_DEBUG` è attivo
- Messaggi di feedback più dettagliati nell'interfaccia admin
- Distinzione chiara tra importazioni e aggiornamenti

## Modifiche all'Interfaccia

### Messaggio di Risultato Migliorato
Prima:
```
Importazione completata: 10 mezzi importati, 2 errori
```

Dopo:
```
Importazione completata: 7 nuovi mezzi, 3 mezzi aggiornati, 2 errori

• Nuovi mezzi: 7
• Mezzi aggiornati: 3
• Errori: 2 righe
```

## Comportamento Aggiornato

### Modalità "Aggiorna Esistenti" Attivata
1. Cerca mezzo esistente per titolo (metodo affidabile)
2. Se trovato:
   - Aggiorna titolo e contenuto
   - **PULISCE** tutti i meta fields gestiti
   - **SOSTITUISCE** tutte le tassonomie
   - Salva nuovi valori dal CSV
   - Gestisce intelligentemente le date di stato
   - Incrementa contatore "aggiornati"
3. Se non trovato:
   - Crea nuovo mezzo
   - Incrementa contatore "nuovi"

### Modalità "Aggiorna Esistenti" Disattivata
1. Cerca mezzo esistente per titolo
2. Se trovato:
   - **SALTA** la riga
   - Segnala errore
3. Se non trovato:
   - Crea nuovo mezzo

## Test Consigliati

1. **Test Import Nuovo:**
   - Importare file CSV con mezzi nuovi
   - Verificare che tutti i campi siano popolati correttamente

2. **Test Aggiornamento:**
   - Importare CSV con mezzi esistenti (checkbox "Aggiorna esistenti" attiva)
   - Modificare alcuni campi nel CSV
   - Re-importare
   - Verificare che i campi siano aggiornati correttamente
   - Verificare che non ci siano dati residui

3. **Test Cambio Stato:**
   - Creare mezzo con stato "disponibile"
   - Aggiornare CSV con stato "riservato"
   - Verificare che venga creata `_data_riservato`
   - Aggiornare CSV con stato "venduto"
   - Verificare che venga creata `_data_venduto`

4. **Test Pulizia Campi:**
   - Creare mezzo con nome_cliente = "Mario Rossi"
   - Aggiornare CSV lasciando nome_cliente vuoto
   - Verificare che il campo sia effettivamente vuoto dopo l'import

## Prestazioni

Le modifiche implementate migliorano le prestazioni:
- Query database più efficiente per trovare post esistenti
- Pulizia preventiva dei meta evita accumulo dati
- Logging condizionale (solo in debug mode)

## Retrocompatibilità

Tutte le modifiche sono retrocompatibili:
- File CSV esistenti funzionano senza modifiche
- L'interfaccia mantiene le stesse opzioni
- Nessuna modifica richiesta alla struttura database
