<?php
/**
 * CSV Importer Class
 * 
 * Handles CSV import for technical sheets
 */

class TS_CSV_Importer {
    
    /**
     * Initialize the importer
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_import_page'));
        add_action('admin_post_ts_import_csv', array($this, 'handle_csv_import'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add import page to admin menu
     */
    public function add_import_page() {
        add_submenu_page(
            'edit.php?post_type=technical_sheet',
            'Importa CSV',
            'Importa CSV',
            'manage_options',
            'ts-import-csv',
            array($this, 'render_import_page')
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'technical_sheet_page_ts-import-csv') {
            return;
        }
        
        wp_enqueue_style(
            'ts-importer-admin',
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
        <div class="wrap ts-import-page">
            <h1>Importa Schede Tecniche da CSV</h1>
            
            <div class="ts-import-instructions">
                <h2>Istruzioni</h2>
                <p>Il file CSV deve contenere le seguenti colonne nell'ordine indicato:</p>
                <ol>
                    <li><strong>TITOLO</strong> - Titolo della scheda tecnica (obbligatorio)</li>
                    <li><strong>CONTENUTO</strong> - Contenuto/descrizione della scheda</li>
                    <li><strong>CATEGORIA</strong> - Nome della categoria (verrà creata se non esiste)</li>
                    <li><strong>MODELLO</strong> - Nome del modello (verrà creato se non esiste)</li>
                    <li><strong>VERSIONE</strong> - Nome della versione (verrà creata se non esiste)</li>
                </ol>
                
                <h3>Formato CSV</h3>
                <ul>
                    <li>Separatore: <code>,</code> (virgola) o <code>;</code> (punto e virgola)</li>
                    <li>Codifica: UTF-8 (consigliato)</li>
                    <li>Prima riga: intestazioni colonne</li>
                    <li>Testo tra virgolette se contiene separatori o a capo</li>
                </ul>
                
                <h3>Esempio CSV</h3>
                <pre>TITOLO,CONTENUTO,CATEGORIA,MODELLO,VERSIONE
"Trattore XYZ 100","Descrizione del trattore con caratteristiche tecniche","Trattori","XYZ","100 HP"
"Aratro ABC 200","Descrizione dell'aratro","Attrezzi","ABC","200 cm"</pre>
            </div>
            
            <?php if (isset($_GET['import_result'])): ?>
                <div class="notice notice-<?php echo esc_attr($_GET['import_result']); ?> is-dismissible">
                    <p><?php echo esc_html(urldecode($_GET['message'])); ?></p>
                    <?php if (isset($_GET['imported']) || isset($_GET['errors'])): ?>
                        <ul>
                            <?php if (isset($_GET['imported'])): ?>
                                <li>Importate: <?php echo intval($_GET['imported']); ?> schede</li>
                            <?php endif; ?>
                            <?php if (isset($_GET['errors'])): ?>
                                <li>Errori: <?php echo intval($_GET['errors']); ?> righe</li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="ts-import-form-wrapper">
                <h2>Carica File CSV</h2>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data" class="ts-import-form">
                    <?php wp_nonce_field('ts_import_csv', 'ts_import_nonce'); ?>
                    <input type="hidden" name="action" value="ts_import_csv">
                    
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
                                    <option value="pending">In revisione</option>
                                </select>
                                <p class="description">Stato iniziale delle schede importate</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="update_existing">Aggiorna esistenti</label>
                            </th>
                            <td>
                                <label>
                                    <input type="checkbox" name="update_existing" id="update_existing" value="1">
                                    Aggiorna le schede esistenti con lo stesso titolo
                                </label>
                                <p class="description">Se disabilitato, le schede con titolo duplicato verranno saltate</p>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Importa CSV">
                    </p>
                </form>
            </div>
            
            <div class="ts-import-tips">
                <h2>Suggerimenti</h2>
                <ul>
                    <li>Fai sempre un backup del database prima di importare</li>
                    <li>Testa l'importazione con pochi record prima di caricare file grandi</li>
                    <li>Usa lo stato "Bozza" per controllare i risultati prima della pubblicazione</li>
                    <li>Le tassonomie (categoria, modello, versione) vengono create automaticamente se non esistono</li>
                    <li>Se il titolo contiene virgole, racchiudilo tra virgolette doppie</li>
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
        if (!isset($_POST['ts_import_nonce']) || !wp_verify_nonce($_POST['ts_import_nonce'], 'ts_import_csv')) {
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
            $result['errors']
        );
    }
    
    /**
     * Process CSV file
     */
    private function process_csv($file_path, $delimiter, $post_status, $update_existing) {
        $imported = 0;
        $errors = 0;
        $error_messages = array();
        
        // Open file
        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            return array(
                'success' => false,
                'message' => 'Impossibile aprire il file CSV',
                'imported' => 0,
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
                'errors' => 0
            );
        }
        
        // Normalize headers (trim and uppercase)
        $headers = array_map(function($h) {
            return strtoupper(trim($h));
        }, $headers);
        
        // Validate headers
        $required_headers = array('TITOLO', 'CONTENUTO', 'CATEGORIA', 'MODELLO', 'VERSIONE');
        $missing_headers = array_diff($required_headers, $headers);
        if (!empty($missing_headers)) {
            fclose($handle);
            return array(
                'success' => false,
                'message' => 'Colonne mancanti: ' . implode(', ', $missing_headers),
                'imported' => 0,
                'errors' => 0
            );
        }
        
        // Map column indexes
        $col_map = array(
            'titolo' => array_search('TITOLO', $headers),
            'contenuto' => array_search('CONTENUTO', $headers),
            'categoria' => array_search('CATEGORIA', $headers),
            'modello' => array_search('MODELLO', $headers),
            'versione' => array_search('VERSIONE', $headers)
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
            $titolo = isset($row[$col_map['titolo']]) ? trim($row[$col_map['titolo']]) : '';
            $contenuto = isset($row[$col_map['contenuto']]) ? trim($row[$col_map['contenuto']]) : '';
            $categoria = isset($row[$col_map['categoria']]) ? trim($row[$col_map['categoria']]) : '';
            $modello = isset($row[$col_map['modello']]) ? trim($row[$col_map['modello']]) : '';
            $versione = isset($row[$col_map['versione']]) ? trim($row[$col_map['versione']]) : '';
            
            // Validate required fields
            if (empty($titolo)) {
                $errors++;
                $error_messages[] = "Riga $row_number: Titolo mancante";
                continue;
            }
            
            // Check if post exists
            $existing_post = get_page_by_title($titolo, OBJECT, 'technical_sheet');
            
            if ($existing_post && !$update_existing) {
                $errors++;
                $error_messages[] = "Riga $row_number: Scheda '$titolo' già esistente (saltata)";
                continue;
            }
            
            // Create or update post
            $post_data = array(
                'post_title'   => $titolo,
                'post_content' => $contenuto,
                'post_status'  => $post_status,
                'post_type'    => 'technical_sheet'
            );
            
            if ($existing_post && $update_existing) {
                $post_data['ID'] = $existing_post->ID;
                $post_id = wp_update_post($post_data);
            } else {
                $post_id = wp_insert_post($post_data);
            }
            
            if (is_wp_error($post_id)) {
                $errors++;
                $error_messages[] = "Riga $row_number: Errore creazione post - " . $post_id->get_error_message();
                continue;
            }
            
            // Assign taxonomies
            if (!empty($categoria)) {
                $this->assign_term($post_id, $categoria, 'technical_sheet_category');
            }
            
            if (!empty($modello)) {
                $this->assign_term($post_id, $modello, 'technical_sheet_model');
            }
            
            if (!empty($versione)) {
                $this->assign_term($post_id, $versione, 'technical_sheet_version');
            }
            
            $imported++;
        }
        
        fclose($handle);
        
        // Prepare result message
        $message = "Importazione completata: $imported schede importate";
        if ($errors > 0) {
            $message .= ", $errors errori";
        }
        
        return array(
            'success' => true,
            'message' => $message,
            'imported' => $imported,
            'errors' => $errors,
            'error_messages' => $error_messages
        );
    }
    
    /**
     * Assign term to post (create if doesn't exist)
     */
    private function assign_term($post_id, $term_name, $taxonomy) {
        if (empty($term_name)) {
            return;
        }
        
        // Check if term exists
        $term = get_term_by('name', $term_name, $taxonomy);
        
        if (!$term) {
            // Create term
            $result = wp_insert_term($term_name, $taxonomy);
            if (is_wp_error($result)) {
                return;
            }
            $term_id = $result['term_id'];
        } else {
            $term_id = $term->term_id;
        }
        
        // Assign term to post
        wp_set_object_terms($post_id, $term_id, $taxonomy, false);
    }
    
    /**
     * Redirect with message
     */
    private function redirect_with_message($type, $message, $imported = 0, $errors = 0) {
        $redirect_url = add_query_arg(
            array(
                'page' => 'ts-import-csv',
                'import_result' => $type,
                'message' => urlencode($message),
                'imported' => $imported,
                'errors' => $errors
            ),
            admin_url('edit.php?post_type=technical_sheet')
        );
        
        wp_redirect($redirect_url);
        exit;
    }
}

// Initialize
new TS_CSV_Importer();
