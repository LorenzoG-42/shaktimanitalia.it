# Importazione CSV - Schede Tecniche

## Come usare l'importatore CSV

### 1. Accesso alla pagina di importazione
Nel menu WordPress, vai su:
**Schede Tecniche > Importa CSV**

### 2. Formato del file CSV

Il file CSV deve contenere **esattamente** queste 5 colonne nell'ordine indicato:

| Colonna | Descrizione | Obbligatorio |
|---------|-------------|--------------|
| TITOLO | Titolo della scheda tecnica | ✅ Sì |
| CONTENUTO | Descrizione completa | No |
| CATEGORIA | Nome categoria (es. "Trattori") | No |
| MODELLO | Nome modello (es. "MT Series") | No |
| VERSIONE | Nome versione (es. "225") | No |

### 3. Regole di formattazione

- **Separatore**: virgola (,) o punto e virgola (;)
- **Codifica**: UTF-8 (consigliato)
- **Prima riga**: DEVE contenere le intestazioni (TITOLO, CONTENUTO, ecc.)
- **Testo con virgole**: racchiudere tra virgolette doppie `"Testo, con virgola"`
- **A capo nel contenuto**: racchiudere tra virgolette doppie

### 4. Esempio di file CSV

```csv
TITOLO,CONTENUTO,CATEGORIA,MODELLO,VERSIONE
"Trattore Shakti MT 225","Trattore agricolo da 22 CV","Trattori","MT Series","225"
"Aratro AR 200","Aratro reversibile professionale","Attrezzi","Aratri","200 cm"
```

### 5. Opzioni di importazione

#### Separatore
Scegli il separatore usato nel tuo file CSV:
- Virgola (,) - predefinito
- Punto e virgola (;)
- Tab

#### Stato Post
Scegli lo stato iniziale delle schede importate:
- **Pubblicato**: visibili immediatamente
- **Bozza**: da revisionare prima della pubblicazione
- **In revisione**: da approvare

#### Aggiorna esistenti
- ✅ **Abilitato**: le schede con lo stesso titolo vengono aggiornate
- ❌ **Disabilitato**: le schede duplicate vengono saltate

### 6. Processo di importazione

1. Prepara il file CSV seguendo il formato
2. Accedi a **Schede Tecniche > Importa CSV**
3. Carica il file
4. Seleziona le opzioni desiderate
5. Clicca su **Importa CSV**
6. Controlla il risultato (schede importate e eventuali errori)

### 7. Gestione delle tassonomie

Le **categorie**, **modelli** e **versioni** vengono create automaticamente se non esistono:
- Se "Trattori" non esiste, verrà creata come nuova categoria
- Se "MT Series" non esiste, verrà creato come nuovo modello
- I termini esistenti vengono riutilizzati

### 8. Suggerimenti importanti

✅ **Fai sempre un backup del database** prima di importare
✅ **Testa con pochi record** prima di file grandi
✅ **Usa lo stato "Bozza"** per verificare i risultati
✅ **Verifica la codifica UTF-8** per caratteri speciali
✅ **Controlla il separatore** se l'importazione fallisce

### 9. Limitazioni

- Dimensione massima file: **10 MB**
- Formato accettato: solo **.csv**
- Immagini: non supportate (vanno caricate manualmente)
- Custom fields: non supportati (solo contenuto base)

### 10. Risoluzione problemi

#### "Colonne mancanti"
Verifica che la prima riga contenga ESATTAMENTE:
```
TITOLO,CONTENUTO,CATEGORIA,MODELLO,VERSIONE
```

#### "Errore creazione post"
- Controlla i permessi utente (serve ruolo Amministratore)
- Verifica che il titolo non sia vuoto
- Controlla caratteri speciali nel CSV

#### "File troppo grande"
- Dividi il file in più parti
- Rimuovi colonne non necessarie
- Comprimi il contenuto

#### Caratteri strani (à, è, ì)
- Salva il CSV con codifica UTF-8
- In Excel: "Salva con nome" > "CSV UTF-8"
- In Google Sheets: "File" > "Scarica" > "CSV"

### 11. File di esempio

Un file di esempio è disponibile nella cartella del plugin:
```
wp-content/plugins/technical-sheets/esempio-import.csv
```

Puoi usarlo come modello per creare il tuo file di importazione.

---

## Domande frequenti

**Q: Posso importare immagini?**
A: No, le immagini vanno caricate manualmente dopo l'importazione.

**Q: Cosa succede se una scheda esiste già?**
A: Dipende dall'opzione "Aggiorna esistenti":
- Se abilitata: la scheda viene aggiornata
- Se disabilitata: la scheda viene saltata e segnalata come errore

**Q: Posso annullare un'importazione?**
A: No, l'importazione è irreversibile. Fai sempre un backup prima.

**Q: Quante schede posso importare alla volta?**
A: Il limite è dato dalla dimensione del file (10 MB). Tipicamente 500-1000 schede.

**Q: L'importazione è sicura?**
A: Sì, vengono effettuati tutti i controlli di sicurezza e validazione WordPress.
