# Guida al Sistema di Tracciamento - Shaktiman B2B

## 📋 Panoramica

Il sistema di tracciamento registra automaticamente tutte le attività sui mezzi agricoli e fornisce statistiche dettagliate per i rivenditori.

---

## 🗄️ Struttura Database

### Tabella: `wp_shaktiman_mezzo_log`
Log completo di tutte le attività sui mezzi.

**Campi:**
- `id` - ID univoco del log
- `mezzo_id` - ID del mezzo
- `user_id` - ID dell'utente che ha effettuato l'azione
- `user_name` - Nome dell'utente
- `action_type` - Tipo di azione (riserva, venduto, libera, cambio_ubicazione, contratto)
- `old_value` - Valore precedente (serializzato)
- `new_value` - Nuovo valore (serializzato)
- `nome_cliente` - Nome del cliente
- `note` - Note aggiuntive
- `created_at` - Data e ora dell'azione

### Tabella: `wp_shaktiman_rivenditore_stats`
Statistiche aggregate per rivenditore.

**Campi:**
- `id` - ID univoco
- `user_id` - ID del rivenditore
- `mezzo_id` - ID del mezzo
- `action_type` - Tipo di azione
- `stato` - Stato corrente (disponibile, riservato, venduto)
- `nome_cliente` - Nome del cliente
- `has_contratto` - Booleano: ha contratto (1) o no (0)
- `numero_contratto` - Numero del contratto
- `created_at` - Data di creazione
- `updated_at` - Data di ultimo aggiornamento

---

## 🔍 Storico Mezzo

### Frontend - Bottone Storico

**Posizione:** Visibile nella pagina del singolo mezzo per tutti gli utenti loggati.

**Funzionalità:**
- Mostra tutte le attività effettuate sul mezzo
- Visualizzazione cronologica (dal più recente)
- Dettagli: Data/Ora, Utente, Azione, Cliente, Cambiamenti

**Come usarlo:**
1. Apri la pagina di un mezzo agricolo
2. Clicca sul bottone **"📋 STORICO"**
3. Visualizza la tabella completa delle attività

**Informazioni visualizzate:**
- Chi ha riservato/venduto il mezzo
- Quando è stata effettuata l'azione
- Cambiamenti di stato o ubicazione
- Generazione contratti

---

## 📊 Statistiche Rivenditori

### 1. Shortcode per le Pagine

**Shortcode:** `[rivenditore_stats]`

**Uso base (per l'utente corrente):**
```
[rivenditore_stats]
```

**Uso avanzato (per un utente specifico - solo admin):**
```
[rivenditore_stats user_id="123"]
```

**Dove inserirlo:**
- Crea una pagina WordPress (es. "Le Mie Statistiche")
- Aggiungi lo shortcode nel contenuto della pagina
- Pubblica la pagina
- I rivenditori vedranno le loro statistiche
- Gli admin possono specificare un user_id per vedere le stats di altri

### 2. Dashboard Widget (Solo Admin)

**Posizione:** Dashboard WordPress > Widget "Statistiche B2B - Rivenditori"

**Funzionalità:**
- Mostra i Top 5 rivenditori
- Ordinati per mezzi venduti
- Visualizza: Venduti, Riservati, Con Contratto
- Link al report completo

### 3. Visualizzazione Statistiche

**Cards Riassuntive:**
- 📦 **Mezzi Riservati** - Totale mezzi attualmente riservati
- ✅ **Mezzi Venduti** - Totale mezzi venduti
- 📄 **Con Contratto** - Venduti con contratto generato
- ⚠️ **Senza Contratto** - Venduti senza contratto (da completare)

**Filtri Disponibili:**
- **Tutti** - Mostra tutti i mezzi
- **Solo Riservati** - Solo mezzi riservati
- **Solo Venduti** - Solo mezzi venduti
- **Solo con Contratto** - Solo venduti con contratto
- **Solo senza Contratto** - Solo venduti senza contratto

**Tabella Dettagliata:**
- Nome del mezzo (con link)
- Stato attuale
- Nome cliente
- Stato contratto
- Data ultimo aggiornamento
- Bottone "Visualizza" per aprire il mezzo

---

## 🔄 Logging Automatico

Il sistema registra automaticamente le seguenti azioni:

### 1. Riserva Mezzo
**Quando:** Un utente riserva un mezzo disponibile
**Dati salvati:**
- Utente che ha riservato
- Nome cliente
- Cambio stato: disponibile → riservato
- Timestamp

### 2. Vendita Mezzo
**Quando:** Un utente marca un mezzo come venduto
**Dati salvati:**
- Utente che ha venduto
- Nome cliente
- Cambio stato: disponibile/riservato → venduto
- Timestamp

### 3. Liberazione Mezzo
**Quando:** Un mezzo viene liberato (riportato a disponibile)
**Dati salvati:**
- Utente che ha liberato
- Cambio stato: riservato/venduto → disponibile
- Timestamp
- **Nota:** Rimuove anche i dati da rivenditore_stats

### 4. Cambio Ubicazione
**Quando:** Un admin cambia l'ubicazione di un mezzo
**Dati salvati:**
- Utente che ha effettuato il cambio
- Ubicazione precedente
- Nuova ubicazione
- Timestamp

### 5. Generazione Contratto
**Quando:** Un admin genera/aggiorna un contratto
**Dati salvati:**
- Utente che ha generato il contratto
- Numero contratto
- Ragione sociale (se presente)
- Timestamp

---

## 👨‍💼 Gestione Admin vs Rivenditori

### Admin (edit_others_posts capability)

**Può fare:**
- ✅ Visualizzare storico di tutti i mezzi
- ✅ Visualizzare statistiche di tutti i rivenditori
- ✅ Registrare ordini per conto di altri utenti
- ✅ Vedere il widget Dashboard
- ✅ Liberare qualsiasi mezzo
- ✅ Visualizzare report completo

### Rivenditori (rivenditore, reparto_vendite roles)

**Può fare:**
- ✅ Visualizzare storico dei mezzi
- ✅ Visualizzare solo le proprie statistiche
- ✅ Liberare solo i propri mezzi riservati/venduti
- ✅ Liberare solo se NON c'è un contratto
- ❌ Non può vedere stats di altri rivenditori
- ❌ Non può accedere al widget Dashboard

---

## 🚀 Come Utilizzare le Statistiche

### Per i Rivenditori

1. **Accedi alla pagina statistiche** (creata dall'admin con lo shortcode)
2. **Visualizza le card riassuntive** per un colpo d'occhio
3. **Usa i filtri** per trovare:
   - Mezzi ancora da completare (senza contratto)
   - Tutti i mezzi venduti
   - Mezzi riservati in attesa
4. **Clicca su "Visualizza"** per aprire il mezzo e vedere i dettagli

### Per gli Admin

1. **Dashboard WordPress** - Vedi subito i top performer
2. **Crea pagine dedicate** con shortcode per ogni rivenditore:
   ```
   [rivenditore_stats user_id="123"]
   ```
3. **Monitora i contratti mancanti** usando il filtro "Senza Contratto"
4. **Analizza le performance** confrontando i numeri tra rivenditori

---

## 📱 Responsive

Tutte le interfacce sono completamente responsive:
- Cards si adattano su mobile (1 colonna)
- Tabelle scrollabili orizzontalmente su schermi piccoli
- Filtri in colonna su mobile
- Modal ottimizzati per touch

---

## 🔧 Personalizzazioni Possibili

### Aggiungere nuovi tipi di azione
Modifica `class-logger.php` e aggiungi il tipo nella funzione `log_action()`.

### Personalizzare i filtri
Modifica `class-stats.php` nella funzione `render_stats_template()`.

### Esportare dati
Usa direttamente le query SQL sulle tabelle `wp_shaktiman_mezzo_log` e `wp_shaktiman_rivenditore_stats`.

---

## 📞 Note Tecniche

- **Performance:** Le tabelle usano indici per query veloci anche con migliaia di record
- **Pulizia:** La liberazione di un mezzo rimuove automaticamente i record da `rivenditore_stats`
- **Integrità:** Tutti i log sono permanenti (solo `rivenditore_stats` viene aggiornato)
- **AJAX:** Tutte le chiamate usano nonce per sicurezza

---

## ✅ Checklist Attivazione

1. ✅ Plugin attivato
2. ✅ Tabelle create automaticamente
3. ✅ Crea una pagina "Statistiche" con shortcode `[rivenditore_stats]`
4. ✅ Verifica che i rivenditori possano accedere alla pagina
5. ✅ Testa il bottone "Storico" su un mezzo
6. ✅ Effettua alcune azioni (riserva, vendita) per popolare i dati
7. ✅ Verifica i filtri nella pagina statistiche
8. ✅ (Admin) Controlla il widget nella Dashboard

---

**Ultima Revisione:** 7 Novembre 2025
**Versione Plugin:** 1.0.0
