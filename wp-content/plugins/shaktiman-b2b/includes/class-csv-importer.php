<?php
/**
 * CSV Importer per Mezzi Agricoli B2B
 * 
 * Gestisce l'importazione CSV per mezzi agricoli
 */

class Shaktiman_B2B_CSV_Importer {
    
    /**
     * Initialize the importer
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_import_page'), 20);
        add_action('admin_post_b2b_import_csv', array($this, 'handle_csv_import'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add import page to admin menu
     */
    public function add_import_page() {
        add_submenu_page(
            'edit.php?post_type=mezzo_agricolo',
            'Importa CSV',
            'Importa CSV',
            'manage_options',
            'b2b-import-csv',
            array($this, 'render_import_page')
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'mezzo_agricolo_page_b2b-import-csv') {
            return;
        }
        
        wp_enqueue_style(
            'b2b-importer-admin',
            plugin_dir_url(dirname(__FILE__)) . 'assets/css/importer-admin.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Render import page
     */
    public function render_import_page() {
        ?>
        <div class="wrap b2b-import-page">
            <h1>Importa Mezzi Agricoli da CSV</h1>
            
            <div class="b2b-import-instructions">
                <h2>Istruzioni</h2>
                <p>Il file CSV deve contenere le seguenti colonne nell'ordine indicato:</p>
                <ol>
                    <li><strong>TITOLO</strong> - Titolo del mezzo (obbligatorio) ✅</li>
                    <li><strong>CONTENUTO</strong> - Descrizione del mezzo (opzionale)</li>
                    <li><strong>CATEGORIA</strong> - Categoria mezzo (es. "Trattori", "Attrezzi") (opzionale)</li>
                    <li><strong>MODELLO</strong> - Modello del mezzo (es. "MT Series") (opzionale)</li>
                    <li><strong>VERSIONE</strong> - Versione (es. "225", "2024") (opzionale)</li>
                    <li><strong>UBICAZIONE</strong> - Dove si trova il mezzo (es. "Magazzino A") (opzionale)</li>
                    <li><strong>STATO</strong> - Stato disponibilità: "disponibile", "riservato" o "venduto" (opzionale)</li>
                    <li><strong>NOME_CLIENTE</strong> - Nome cliente (se riservato/venduto) (opzionale)</li>
                    <li><strong>NUMERO_CONTRATTO</strong> - Numero contratto (opzionale)</li>
                    <li><strong>RAGIONE_SOCIALE</strong> - Ragione sociale cliente (opzionale)</li>
                    <li><strong>DATA_CONTRATTO</strong> - Data contratto formato YYYY-MM-DD (opzionale)</li>
                </ol>
                
                <h3>Formato CSV</h3>
                <ul>
                    <li>Separatore: <code>,</code> (virgola) o <code>;</code> (punto e virgola)</li>
                    <li>Codifica: UTF-8 (consigliato)</li>
                    <li>Prima riga: intestazioni colonne</li>
                    <li>Testo tra virgolette se contiene separatori o a capo</li>
                </ul>
                
                <h3>Esempio CSV</h3>
                <pre>TITOLO,CONTENUTO,CATEGORIA,MODELLO,VERSIONE,UBICAZIONE,STATO,NOME_CLIENTE,NUMERO_CONTRATTO,RAGIONE_SOCIALE,DATA_CONTRATTO
"Trattore MT 225","Trattore agricolo 22CV","Trattori","MT Series","225","Magazzino A","disponibile","","","",""
"Trattore MT 244 - Riservato","Trattore 24CV","Trattori","MT Series","244","Magazzino B","riservato","Mario Rossi","","",""
"Aratro Reversibile","Aratro professionale","Attrezzi","Aratri","200","Magazzino A","venduto","Luigi Verdi","C2024001","Azienda Agricola Verdi SRL","2024-11-01"</pre>
            </div>
            
            <?php if (isset($_GET['import_result'])): ?>
                <div class="notice notice-<?php echo esc_attr($_GET['import_result']); ?> is-dismissible">
                    <p><?php echo isset($_GET['message']) ? esc_html(urldecode($_GET['message'])) : ''; ?></p>
                    <?php if (isset($_GET['imported']) || isset($_GET['updated']) || isset($_GET['errors'])): ?>
                        <ul>
                            <?php if (isset($_GET['imported']) && intval($_GET['imported']) > 0): ?>
                                <li><strong>Nuovi mezzi:</strong> <?php echo intval($_GET['imported']); ?></li>
                            <?php endif; ?>
                            <?php if (isset($_GET['updated']) && intval($_GET['updated']) > 0): ?>
                                <li><strong>Mezzi aggiornati:</strong> <?php echo intval($_GET['updated']); ?></li>
                            <?php endif; ?>
                            <?php if (isset($_GET['errors']) && intval($_GET['errors']) > 0): ?>
                                <li><strong>Errori:</strong> <?php echo intval($_GET['errors']); ?> righe</li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="b2b-import-form-wrapper">
                <h2>Carica File CSV</h2>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data" class="b2b-import-form">
                    <?php wp_nonce_field('b2b_import_csv', 'b2b_import_nonce'); ?>
                    <input type="hidden" name="action" value="b2b_import_csv">
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="csv_file">File CSV</label>
                            </th>
                            <td>
                                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                                <p class="description">Seleziona il file CSV da importare (max 10MB)</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="csv_delimiter">Separatore</label>
                            </th>
                            <td>
                                <select name="csv_delimiter" id="csv_delimiter">
                                    <option value=",">Virgola (,)</option>
                                    <option value=";">Punto e virgola (;)</option>
                                    <option value="\t">Tab</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="post_status">Stato Post</label>
                            </th>
                            <td>
                                <select name="post_status" id="post_status">
                                    <option value="publish">Pubblicato</option>
                                    <option value="draft">Bozza</option>
                                </select>
                                <p class="description">Stato iniziale dei mezzi importati</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="update_existing">Aggiorna esistenti</label>
                            </th>
                            <td>
                                <label>
                                    <input type="checkbox" name="update_existing" id="update_existing" value="1">
                                    Aggiorna i mezzi esistenti con lo stesso titolo
                                </label>
                                <p class="description">Se disabilitato, i mezzi con titolo duplicato verranno saltati</p>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Importa CSV">
                    </p>
                </form>
            </div>
            
            <div class="b2b-import-tips">
                <h2>Suggerimenti</h2>
                <ul>
                    <li><strong>Backup:</strong> Fai sempre un backup del database prima di importare</li>
                    <li><strong>Test:</strong> Prova con pochi record prima di caricare file grandi</li>
                    <li><strong>Stati validi:</strong> Usa solo "disponibile", "riservato" o "venduto" nella colonna STATO</li>
                    <li><strong>Tassonomie:</strong> Categorie, modelli, versioni e ubicazioni vengono create automaticamente</li>
                    <li><strong>Cliente:</strong> Se lo stato è "riservato" o "venduto", inserisci il NOME_CLIENTE</li>
                    <li><strong>Contratto:</strong> I dati contratto sono opzionali</li>
                    <li><strong>Date:</strong> Usa formato YYYY-MM-DD (es. 2024-11-12)</li>
                </ul>
            </div>
        </div>
        <?php
    }
    
    /**
     * Handle CSV import
     */
    public function handle_csv_import() {
        // Check nonce
        if (!isset($_POST['b2b_import_nonce']) || !wp_verify_nonce($_POST['b2b_import_nonce'], 'b2b_import_csv')) {
            wp_die('Nonce verification failed');
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die('You do not have permission to import data');
        }
        
        // Check if file was uploaded
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $this->redirect_with_message('error', 'Errore durante il caricamento del file');
            return;
        }
        
        // Get file info
        $file = $_FILES['csv_file'];
        $delimiter = isset($_POST['csv_delimiter']) ? stripslashes($_POST['csv_delimiter']) : ',';
        $post_status = isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : 'publish';
        $update_existing = isset($_POST['update_existing']) && $_POST['update_existing'] === '1';
        
        // Validate file size (max 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            $this->redirect_with_message('error', 'Il file è troppo grande (max 10MB)');
            return;
        }
        
        // Validate file type
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_ext !== 'csv') {
            $this->redirect_with_message('error', 'Il file deve essere in formato CSV');
            return;
        }
        
        // Process CSV
        $result = $this->process_csv($file['tmp_name'], $delimiter, $post_status, $update_existing);
        
        // Redirect with result
        $this->redirect_with_message(
            $result['success'] ? 'success' : 'error',
            $result['message'],
            $result['imported'],
            $result['updated'],
            $result['errors']
        );
    }
    
    /**
     * Process CSV file
     */
    private function process_csv($file_path, $delimiter, $post_status, $update_existing) {
        $imported = 0;
        $updated = 0;
        $errors = 0;
        $error_messages = array();
        
        // Open file
        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            return array(
                'success' => false,
                'message' => 'Impossibile aprire il file CSV',
                'imported' => 0,
                'updated' => 0,
                'errors' => 0
            );
        }
        
        // Set locale for proper CSV parsing
        setlocale(LC_ALL, 'it_IT.UTF-8');
        
        // Read header row
        $headers = fgetcsv($handle, 0, $delimiter);
        if ($headers === false) {
            fclose($handle);
            return array(
                'success' => false,
                'message' => 'Il file CSV è vuoto o non valido',
                'imported' => 0,
                'updated' => 0,
                'errors' => 0
            );
        }
        
        // Normalize headers (trim, remove BOM, uppercase)
        $headers = array_map(function($h) {
            // Remove BOM if present
            $h = str_replace("\xEF\xBB\xBF", '', $h);
            return strtoupper(trim($h));
        }, $headers);
        
        // Validate required headers (solo TITOLO è obbligatorio per il funzionamento base)
        $required_headers = array('TITOLO');
        $missing_headers = array_diff($required_headers, $headers);
        if (!empty($missing_headers)) {
            fclose($handle);
            return array(
                'success' => false,
                'message' => 'Colonne obbligatorie mancanti: ' . implode(', ', $missing_headers),
                'imported' => 0,
                'updated' => 0,
                'errors' => 0
            );
        }
        
        // Map column indexes
        $col_map = array(
            'titolo' => array_search('TITOLO', $headers),
            'contenuto' => array_search('CONTENUTO', $headers),
            'categoria' => array_search('CATEGORIA', $headers),
            'modello' => array_search('MODELLO', $headers),
            'versione' => array_search('VERSIONE', $headers),
            'ubicazione' => array_search('UBICAZIONE', $headers),
            'stato' => array_search('STATO', $headers),
            'nome_cliente' => array_search('NOME_CLIENTE', $headers),
            'numero_contratto' => array_search('NUMERO_CONTRATTO', $headers),
            'ragione_sociale' => array_search('RAGIONE_SOCIALE', $headers),
            'data_contratto' => array_search('DATA_CONTRATTO', $headers)
        );
        
        // Process rows
        $row_number = 1; // Header is row 0
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $row_number++;
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            // Get data
            $titolo = isset($row[$col_map['titolo']]) && $col_map['titolo'] !== false ? trim($row[$col_map['titolo']]) : '';
            $contenuto = isset($row[$col_map['contenuto']]) && $col_map['contenuto'] !== false ? trim($row[$col_map['contenuto']]) : '';
            $categoria = isset($row[$col_map['categoria']]) && $col_map['categoria'] !== false ? trim($row[$col_map['categoria']]) : '';
            $modello = isset($row[$col_map['modello']]) && $col_map['modello'] !== false ? trim($row[$col_map['modello']]) : '';
            $versione = isset($row[$col_map['versione']]) && $col_map['versione'] !== false ? trim($row[$col_map['versione']]) : '';
            $ubicazione = isset($row[$col_map['ubicazione']]) && $col_map['ubicazione'] !== false ? trim($row[$col_map['ubicazione']]) : '';
            $stato = isset($row[$col_map['stato']]) && $col_map['stato'] !== false ? strtolower(trim($row[$col_map['stato']])) : '';
            $nome_cliente = isset($row[$col_map['nome_cliente']]) && $col_map['nome_cliente'] !== false ? trim($row[$col_map['nome_cliente']]) : '';
            $numero_contratto = isset($row[$col_map['numero_contratto']]) && $col_map['numero_contratto'] !== false ? trim($row[$col_map['numero_contratto']]) : '';
            $ragione_sociale = isset($row[$col_map['ragione_sociale']]) && $col_map['ragione_sociale'] !== false ? trim($row[$col_map['ragione_sociale']]) : '';
            $data_contratto = isset($row[$col_map['data_contratto']]) && $col_map['data_contratto'] !== false ? trim($row[$col_map['data_contratto']]) : '';
            
            // Validate required fields
            if (empty($titolo)) {
                $errors++;
                $error_messages[] = "Riga $row_number: Titolo mancante";
                continue;
            }
            
            // Validate stato
            $stati_validi = array('disponibile', 'riservato', 'venduto');
            if (!empty($stato) && !in_array($stato, $stati_validi)) {
                $errors++;
                $error_messages[] = "Riga $row_number: Stato non valido '$stato'. Usa: disponibile, riservato, venduto";
                continue;
            }
            
            // Check if post exists - metodo migliorato (get_page_by_title è deprecato)
            $existing_post = $this->find_post_by_title($titolo, 'mezzo_agricolo');
            
            if ($existing_post && !$update_existing) {
                $errors++;
                $error_messages[] = "Riga $row_number: Mezzo '$titolo' già esistente (saltato)";
                continue;
            }
            
            // Create or update post
            $post_data = array(
                'post_title'   => $titolo,
                'post_content' => $contenuto,
                'post_status'  => $post_status,
                'post_type'    => 'mezzo_agricolo'
            );
            
            $is_update = false;
            if ($existing_post && $update_existing) {
                $post_data['ID'] = $existing_post->ID;
                $post_id = wp_update_post($post_data);
                $is_update = true;
                
                // wp_update_post può restituire 0 in caso di errore (non WP_Error)
                // In tal caso, usa l'ID del post esistente
                if ($post_id === 0) {
                    $post_id = $existing_post->ID;
                }
            } else {
                $post_id = wp_insert_post($post_data);
            }
            
            if (is_wp_error($post_id)) {
                $errors++;
                $error_messages[] = "Riga $row_number: Errore creazione post - " . $post_id->get_error_message();
                continue;
            }
            
            // Se è un aggiornamento, pulisci i meta fields vecchi prima di aggiornare
            if ($is_update) {
                $this->clean_post_meta($post_id);
                $updated++;
            } else {
                $imported++;
            }
            
            // Assign taxonomies - pulisce e assegna
            $this->assign_term($post_id, $categoria, 'categoria_mezzo', true);
            $this->assign_term($post_id, $modello, 'modello', true);
            $this->assign_term($post_id, $versione, 'versione', true);
            $this->assign_term($post_id, $ubicazione, 'ubicazione', true);
            $this->assign_term($post_id, $stato, 'disponibilita', true);
            
            // IMPORTANTE: flush cache tassonomie per questo post
            clean_object_term_cache($post_id, 'mezzo_agricolo');
            
            // Save meta fields - salva solo se non vuoti
            if (!empty($nome_cliente)) {
                update_post_meta($post_id, '_nome_cliente', sanitize_text_field($nome_cliente));
            }
            
            if (!empty($numero_contratto)) {
                update_post_meta($post_id, '_numero_contratto', sanitize_text_field($numero_contratto));
            }
            
            if (!empty($ragione_sociale)) {
                update_post_meta($post_id, '_ragione_sociale', sanitize_text_field($ragione_sociale));
            }
            
            if (!empty($data_contratto)) {
                // Validate date format
                $date = DateTime::createFromFormat('Y-m-d', $data_contratto);
                if ($date && $date->format('Y-m-d') === $data_contratto) {
                    update_post_meta($post_id, '_data_contratto', $data_contratto);
                }
            }
            
            // Gestione date automatiche per stato riservato/venduto
            if ($stato === 'riservato') {
                // Se è un update e lo stato è cambiato, aggiorna la data
                if ($is_update) {
                    $old_stato = wp_get_object_terms($existing_post->ID, 'disponibilita', array('fields' => 'names'));
                    if (empty($old_stato) || !in_array('riservato', $old_stato)) {
                        update_post_meta($post_id, '_data_riservato', current_time('mysql'));
                    }
                } else {
                    // Nuovo post
                    update_post_meta($post_id, '_data_riservato', current_time('mysql'));
                }
            }
            
            if ($stato === 'venduto') {
                // Se è un update e lo stato è cambiato, aggiorna la data
                if ($is_update) {
                    $old_stato = wp_get_object_terms($existing_post->ID, 'disponibilita', array('fields' => 'names'));
                    if (empty($old_stato) || !in_array('venduto', $old_stato)) {
                        update_post_meta($post_id, '_data_venduto', current_time('mysql'));
                    }
                } else {
                    // Nuovo post
                    update_post_meta($post_id, '_data_venduto', current_time('mysql'));
                }
            }
        }
        
        fclose($handle);
        
        // Prepare result message
        $message = "Importazione completata: ";
        $parts = array();
        
        if ($imported > 0) {
            $parts[] = "$imported nuovi mezzi";
        }
        if ($updated > 0) {
            $parts[] = "$updated mezzi aggiornati";
        }
        if ($errors > 0) {
            $parts[] = "$errors errori";
        }
        
        if (empty($parts)) {
            $message = "Nessuna operazione eseguita";
        } else {
            $message .= implode(', ', $parts);
        }
        
        return array(
            'success' => true,
            'message' => $message,
            'imported' => $imported,
            'updated' => $updated,
            'errors' => $errors,
            'error_messages' => $error_messages
        );
    }
    
    /**
     * Find post by title (alternativa moderna a get_page_by_title deprecato)
     */
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
        
        if ($post) {
            return get_post($post->ID);
        }
        
        return null;
    }
    
    /**
     * Clean post meta fields before update
     */
    private function clean_post_meta($post_id) {
        // Lista dei meta fields gestiti dall'import
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
    
    /**
     * Assign term to post (create if doesn't exist)
     */
    private function assign_term($post_id, $term_name, $taxonomy, $replace = false) {
        // Trim per sicurezza
        $term_name = trim($term_name);
        
        if (empty($term_name)) {
            // Se term_name è vuoto e replace è true, rimuovi tutti i termini
            if ($replace) {
                wp_set_object_terms($post_id, array(), $taxonomy, false);
            }
            return;
        }
        
        // Verifica se la tassonomia esiste
        if (!taxonomy_exists($taxonomy)) {
            return;
        }
        
        // Check if term exists - prova prima per slug, poi per name
        $term = term_exists($term_name, $taxonomy);
        
        if (!$term) {
            // Il termine non esiste, crealo
            // Genera uno slug dal nome
            $slug = sanitize_title($term_name);
            
            $result = wp_insert_term(
                $term_name,  // name
                $taxonomy,   // taxonomy
                array(
                    'slug' => $slug
                )
            );
            
            if (is_wp_error($result)) {
                // Potrebbe essere un errore di duplicato, proviamo a recuperarlo
                if ($result->get_error_code() === 'term_exists') {
                    $term_id = $result->get_error_data();
                } else {
                    return;
                }
            } else {
                $term_id = $result['term_id'];
            }
        } else {
            // Se term_exists restituisce un array, prendi term_id, altrimenti è già il term_id
            $term_id = is_array($term) ? $term['term_id'] : $term;
        }
        
        // Assign term to post - false = sostituisce, true = aggiunge
        wp_set_object_terms($post_id, intval($term_id), $taxonomy, false);
    }
    
    /**
     * Redirect with message
     */
    private function redirect_with_message($type, $message, $imported = 0, $updated = 0, $errors = 0) {
        $redirect_url = add_query_arg(
            array(
                'page' => 'b2b-import-csv',
                'import_result' => $type,
                'message' => urlencode($message),
                'imported' => $imported,
                'updated' => $updated,
                'errors' => $errors
            ),
            admin_url('edit.php?post_type=mezzo_agricolo')
        );
        
        wp_redirect($redirect_url);
        exit;
    }
}

// Initialize
new Shaktiman_B2B_CSV_Importer();
