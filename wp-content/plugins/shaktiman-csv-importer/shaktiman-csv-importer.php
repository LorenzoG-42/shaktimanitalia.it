<?php
/**
 * Plugin Name: Shaktiman CSV Importer
 * Description: Importa prodotti da file CSV nel CPT 'service' usando post 13401 come template Elementor. Menu admin dedicato.
 * Version: 2.0
 * Author: Generated
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Shaktiman_CSV_Importer {
    public function __construct() {
        add_action( 'init', array( $this, 'register_vehicle_cpt' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_post_shaktiman_csv_import', array( $this, 'handle_csv_import' ) );
        add_action( 'admin_post_shaktiman_retry_images', array( $this, 'handle_retry_images' ) );
        add_action( 'admin_post_shaktiman_cleanup', array( $this, 'handle_cleanup' ) );
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    public function register_vehicle_cpt() {
        // Non registriamo più un CPT separato, usiamo il CPT 'service' esistente
        // che ha la stessa struttura del post template 13401
        return;
    }

    public function admin_menu() {
        add_management_page( 'Shaktiman CSV Import', 'Shaktiman CSV Import', 'manage_options', 'shaktiman-csv-importer', array( $this, 'admin_page' ) );
        add_management_page( 'Shaktiman Cleanup', 'Shaktiman Cleanup', 'manage_options', 'shaktiman-cleanup', array( $this, 'cleanup_page' ) );
    }

    public function admin_notices() {
        if ( isset( $_GET['shaktiman_csv_msg'] ) ) {
            $msg = sanitize_text_field( $_GET['shaktiman_csv_msg'] );
            if ( strpos( $msg, 'imported_' ) === 0 ) {
                // Parse the complex message: imported_X_skipped_Y_template_only
                $parts = explode( '_', $msg );
                $imported = isset( $parts[1] ) ? intval( $parts[1] ) : 0;
                $skipped = 0;
                $template_only = false;
                
                // Look for skipped count
                if ( in_array( 'skipped', $parts ) ) {
                    $skipped_index = array_search( 'skipped', $parts );
                    if ( isset( $parts[$skipped_index + 1] ) ) {
                        $skipped = intval( $parts[$skipped_index + 1] );
                    }
                }
                
                // Look for template_only flag
                if ( in_array( 'template', $parts ) && in_array( 'only', $parts ) ) {
                    $template_only = true;
                }
                
                $success_msg = 'Import CSV completato! ';
                if ( $template_only ) {
                    $success_msg .= 'Aggiornato template Elementor per ' . $imported . ' prodotti (titolo e immagini mantenuti).';
                } else {
                    $success_msg .= 'Importati ' . $imported . ' prodotti nel CPT service con template dal post 13401.';
                }
                
                if ( $skipped > 0 ) {
                    $success_msg .= ' Saltati: ' . $skipped . '.';
                }
                
                echo '<div class="notice notice-success is-dismissible"><p>' . $success_msg . '</p></div>';
            } elseif ( strpos( $msg, 'retry_images_' ) === 0 ) {
                $count = intval( str_replace( 'retry_images_', '', $msg ) );
                echo '<div class="notice notice-success is-dismissible"><p>Retry immagini completato! Processati ' . $count . ' post.</p></div>';
            } elseif ( strpos( $msg, 'cleanup_' ) === 0 ) {
                $count = intval( str_replace( 'cleanup_', '', $msg ) );
                echo '<div class="notice notice-success is-dismissible"><p>Cleanup completato! Eliminati ' . $count . ' post Shaktiman e relative immagini.</p></div>';
            } else {
                $errors = array(
                    'no_file' => 'Nessun file CSV selezionato.',
                    'upload_error' => 'Errore durante l\'upload del file.',
                    'not_csv' => 'Il file deve essere in formato CSV.',
                    'empty_csv' => 'Il file CSV è vuoto o non ha righe valide.',
                    'invalid_format' => 'Formato CSV non valido. Deve avere almeno: url,title,content',
                    'no_bundled_csv' => 'File CSV bundled non trovato nel plugin.',
                    'cleanup_not_confirmed' => 'Cleanup annullato: devi confermare l\'eliminazione.',
                );
                $error_msg = isset( $errors[$msg] ) ? $errors[$msg] : 'Errore sconosciuto.';
                echo '<div class="notice notice-error is-dismissible"><p>Errore: ' . esc_html( $error_msg ) . '</p></div>';
            }
        }
    }

    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Shaktiman CSV Importer</h1>
            <p>Questo strumento importa prodotti da un file CSV generato dallo script Python nel CPT <code>vehicle</code>.</p>
            
            <div class="card">
                <h2>📋 Workflow</h2>
                <p><strong>🎉 Tutto pronto!</strong> Il plugin contiene già:</p>
                <ul>
                    <li>✅ <strong>144 prodotti</strong> nel file <code>shaktiman_products_v2.csv</code></li>
                    <li>✅ <strong>Tutte le immagini</strong> nella cartella <code>images/</code></li>
                    <li>✅ <strong>Permalinks personalizzati</strong> per URL SEO-friendly</li>
                </ul>
                <p><strong>Procedi con l'import usando il pulsante "📁 Usa CSV incluso" qui sotto!</strong></p>
                
                <p style="margin-top: 15px;">
                    <a href="<?php echo admin_url( 'tools.php?page=shaktiman-cleanup' ); ?>" class="button button-secondary">
                        🧹 Gestisci/Elimina post Shaktiman esistenti
                    </a>
                </p>
            </div>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
                <?php wp_nonce_field( 'shaktiman_csv_import' ); ?>
                <input type="hidden" name="action" value="shaktiman_csv_import">
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="csv_file">File CSV</label></th>
                        <td>
                            <input type="file" name="csv_file" id="csv_file" accept=".csv">
                            <p class="description">
                                Seleziona un file CSV da caricare, oppure usa il pulsante qui sotto per importare il CSV già presente nel plugin.<br>
                                <strong>Formato supportato:</strong> url,title,content,image_url,local_image_path,permalink
                            </p>
                            <?php if ( file_exists( plugin_dir_path( __FILE__ ) . 'shaktiman_products_v2.csv' ) ) : ?>
                                <button type="submit" name="use_bundled_csv" value="1" class="button button-secondary">
                                    📁 Usa CSV incluso nel plugin (shaktiman_products_v2.csv)
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="update_existing">Gestione duplicati</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="update_existing" id="update_existing" value="1">
                                Aggiorna post esistenti se URL già presente
                            </label>
                            <p class="description">Se deselezionato, salterà prodotti già importati.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="download_images">Download immagini</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="download_images" id="download_images" value="1" checked>
                                Scarica immagini e imposta come featured image
                            </label>
                            <p class="description">Consigliato: scarica le immagini nella media library di WordPress.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Aggiorna post esistenti</th>
                        <td>
                            <label>
                                <input type="checkbox" name="update_existing" value="1" checked>
                                Aggiorna post esistenti (basato su permalink CSV)
                            </label>
                            <p class="description">Se disabilitato, i post esistenti vengono saltati. Se abilitato, vengono aggiornati con nuovi dati e template.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Modalità aggiornamento</th>
                        <td>
                            <label>
                                <input type="radio" name="update_mode" value="full" checked>
                                <strong>Aggiornamento completo</strong> - titolo, contenuto, immagine e template
                            </label><br>
                            <label>
                                <input type="radio" name="update_mode" value="template_only">
                                <strong>Solo template Elementor</strong> - mantiene titolo e immagine esistenti
                            </label>
                            <p class="description">Template-only è utile per applicare modifiche al layout senza re-importare immagini.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="batch_size">Dimensione batch</label></th>
                        <td>
                            <select name="batch_size" id="batch_size">
                                <option value="0">Tutti i prodotti (144)</option>
                                <option value="20">20 prodotti alla volta</option>
                                <option value="50" selected>50 prodotti alla volta (raccomandato)</option>
                                <option value="100">100 prodotti alla volta</option>
                            </select>
                            <p class="description">Per server con limitazioni, usa batch più piccoli per evitare timeout.</p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button( 'Importa da CSV' ); ?>
            </form>

            <?php $this->show_debug_info(); ?>

            <div class="card">
                <h3>🔍 Formato CSV supportato</h3>
                <pre>url,title,content,image_url,local_image_path,permalink
https://global.shaktimanagro.com/champion/,Champion ES,Description...,https://...,champion.png,champion</pre>
                
                <h3>✅ Cosa fa l'import</h3>
                <ul>
                    <li>Crea post di tipo <code>service</code> usando post 13401 come template Elementor</li>
                    <li><strong>Controllo duplicati:</strong> usa permalink CSV come chiave primaria, fallback su URL sorgente</li>
                    <li><strong>Modalità aggiornamento:</strong> completo (tutto) o solo template (preserva titolo/immagini esistenti)</li>
                    <li><strong>Priorità immagini:</strong> usa immagini locali dalla cartella <code>images/</code></li>
                    <li><strong>Riutilizzo intelligente:</strong> evita duplicati riutilizzando immagini già presenti in WordPress</li>
                    <li>Imposta permalinks personalizzati per SEO</li>
                    <li>Copia struttura Elementor completa dal template</li>
                    <li>Salva meta <code>_shaktiman_source</code> con URL originale</li>
                    <li>Riusa immagini già presenti nella media library</li>
                </ul>
                
                <h3>🎨 Template Elementor (Post 13401)</h3>
                <ul>
                    <li><strong>Layout consistente:</strong> ogni prodotto usa la stessa struttura avanzata</li>
                    <li><strong>Design professionale:</strong> sezioni per features, specifiche, varianti, prezzi</li>
                    <li><strong>Personalizzazione:</strong> solo titolo e featured image vengono sostituiti</li>
                    <li><strong>Meta fields:</strong> copia completa di _elementor_data e configurazioni tema</li>
                    <li><strong>CSS regeneration:</strong> forza rigenerazione assets Elementor per compatibilità</li>
                </ul>
                
                <h3>⚡ Modalità di Aggiornamento</h3>
                <ul>
                    <li><strong>Completo:</strong> aggiorna titolo, contenuto, immagine e template Elementor</li>
                    <li><strong>Solo Template:</strong> applica modifiche di layout senza re-importare immagini (⚡ veloce)</li>
                    <li><strong>Caso d'uso template-only:</strong> modifichi il post 13401 e vuoi applicare le modifiche a tutti i prodotti</li>
                </ul>
                
                <h3>🚀 Vantaggi immagini locali</h3>
                <ul>
                    <li><strong>🏃‍♂️ Velocità:</strong> nessun download da server remoti</li>
                    <li><strong>🛡️ Affidabilità:</strong> nessun rischio di timeout o errori di rete</li>
                    <li><strong>♻️ Efficienza:</strong> riutilizzo automatico di immagini già presenti in WordPress</li>
                    <li><strong>💾 Ottimizzazione:</strong> evita duplicati nella media library</li>
                    <li><strong>🔍 Debug:</strong> info dettagliate su riutilizzo vs nuovo caricamento</li>
                </ul>
            </div>
        </div>
        <?php
    }

    public function cleanup_page() {
        // Get count of Shaktiman posts
        $shaktiman_posts = new WP_Query( array(
            'post_type' => 'vehicle',
            'meta_key' => '_shaktiman_source',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));

        $total_posts = $shaktiman_posts->found_posts;
        ?>
        <div class="wrap">
            <h1>🧹 Shaktiman Cleanup</h1>
            <p>Questa pagina permette di eliminare tutti i post del CPT <code>service</code> importati da Shaktiman per fare una pulizia completa.</p>
            
            <div class="card">
                <h2>📊 Stato attuale</h2>
                <p><strong>Post Shaktiman trovati:</strong> <?php echo $total_posts; ?></p>
                
                <?php if ( $total_posts > 0 ) : ?>
                    <h3>🔍 Anteprima post da eliminare:</h3>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titolo</th>
                                <th>Data creazione</th>
                                <th>Immagine featured</th>
                                <th>URL sorgente</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $preview_posts = new WP_Query( array(
                            'post_type' => 'vehicle',
                            'meta_key' => '_shaktiman_source',
                            'posts_per_page' => 10, // Show first 10
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        while ( $preview_posts->have_posts() ) : $preview_posts->the_post(); 
                            $thumbnail_id = get_post_thumbnail_id();
                            $source = get_post_meta( get_the_ID(), '_shaktiman_source', true );
                        ?>
                            <tr>
                                <td><?php echo get_the_ID(); ?></td>
                                <td><strong><?php the_title(); ?></strong></td>
                                <td><?php echo get_the_date( 'Y-m-d H:i:s' ); ?></td>
                                <td>
                                    <?php if ( $thumbnail_id ) : ?>
                                        ✅ ID: <?php echo $thumbnail_id; ?>
                                    <?php else : ?>
                                        ❌ Nessuna
                                    <?php endif; ?>
                                </td>
                                <td><small><?php echo esc_html( $source ); ?></small></td>
                            </tr>
                        <?php endwhile; wp_reset_postdata(); ?>
                        </tbody>
                    </table>
                    
                    <?php if ( $total_posts > 10 ) : ?>
                        <p><small>Mostrati primi 10 di <?php echo $total_posts; ?> totali.</small></p>
                    <?php endif; ?>
                    
                <?php else : ?>
                    <div class="notice notice-info inline">
                        <p>✅ Nessun post Shaktiman trovato. Il database è pulito!</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( $total_posts > 0 ) : ?>
            <div class="card">
                <h2>⚠️ Eliminazione completa</h2>
                <p><strong>Cosa verrà eliminato:</strong></p>
                <ul>
                    <li>🗂️ Tutti i <strong><?php echo $total_posts; ?> post</strong> di tipo <code>vehicle</code> con meta <code>_shaktiman_source</code></li>
                    <li>🖼️ Tutte le <strong>immagini featured</strong> associate dalla media library</li>
                    <li>📝 Tutti i <strong>metadati</strong> associati (_shaktiman_source, _shaktiman_image_debug, ecc.)</li>
                    <li>🗑️ <strong>Cancellazione permanente</strong> (non nel cestino)</li>
                </ul>
                
                <div class="notice notice-warning inline">
                    <p><strong>⚠️ ATTENZIONE:</strong> Questa operazione è <strong>irreversibile</strong>. Assicurati di voler eliminare definitivamente tutti i post Shaktiman.</p>
                </div>

                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('⚠️ SEI SICURO?\n\nStai per eliminare DEFINITIVAMENTE <?php echo $total_posts; ?> post Shaktiman e le relative immagini.\n\nQuesta operazione NON può essere annullata.\n\nVuoi continuare?');">
                    <?php wp_nonce_field( 'shaktiman_cleanup' ); ?>
                    <input type="hidden" name="action" value="shaktiman_cleanup">
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">Conferma eliminazione</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="delete_images" value="1" checked>
                                    Elimina anche le immagini dalla media library
                                </label>
                                <p class="description">Rimuove definitivamente i file immagine dal server</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Conferma finale</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="confirm_deletion" value="1" required>
                                    Confermo di voler eliminare TUTTI i <?php echo $total_posts; ?> post Shaktiman
                                </label>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-primary" style="background-color: #dc3232;">
                            🗑️ ELIMINA DEFINITIVAMENTE TUTTI I POST SHAKTIMAN
                        </button>
                        <a href="<?php echo admin_url( 'tools.php?page=shaktiman-csv-importer' ); ?>" class="button button-secondary">
                            ← Torna all'import
                        </a>
                    </p>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }
    

    public function handle_csv_import() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Permesso negato' );
        }

        check_admin_referer( 'shaktiman_csv_import' );

        // Increase memory and time limits for large imports
        ini_set( 'memory_limit', '512M' );
        set_time_limit( 300 ); // 5 minutes
        
        // Disable WordPress heartbeat during import
        wp_deregister_script( 'heartbeat' );

        $use_bundled = isset( $_POST['use_bundled_csv'] ) && $_POST['use_bundled_csv'] == '1';
        $update_existing = isset( $_POST['update_existing'] ) && $_POST['update_existing'] == '1';
        $update_mode = isset( $_POST['update_mode'] ) ? sanitize_text_field( $_POST['update_mode'] ) : 'full';
        $download_images = isset( $_POST['download_images'] ) && $_POST['download_images'] == '1';
        $batch_size = isset( $_POST['batch_size'] ) ? intval( $_POST['batch_size'] ) : 0;

        if ( $use_bundled ) {
            // Use bundled CSV from plugin directory
            $bundled_csv = plugin_dir_path( __FILE__ ) . 'shaktiman_products_v2.csv';
            if ( ! file_exists( $bundled_csv ) ) {
                wp_redirect( add_query_arg( 'shaktiman_csv_msg', 'no_bundled_csv', wp_get_referer() ) );
                exit;
            }
            $csv_content = file_get_contents( $bundled_csv );
        } else {
            // Use uploaded file
            if ( ! isset( $_FILES['csv_file'] ) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK ) {
                wp_redirect( add_query_arg( 'shaktiman_csv_msg', 'upload_error', wp_get_referer() ) );
                exit;
            }

            $file = $_FILES['csv_file'];
            $file_ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
            
            if ( $file_ext !== 'csv' ) {
                wp_redirect( add_query_arg( 'shaktiman_csv_msg', 'not_csv', wp_get_referer() ) );
                exit;
            }

            $csv_content = file_get_contents( $file['tmp_name'] );
        }
        if ( empty( $csv_content ) ) {
            wp_redirect( add_query_arg( 'shaktiman_csv_msg', 'empty_csv', wp_get_referer() ) );
            exit;
        }

        $lines = str_getcsv( $csv_content, "\n" );
        if ( count( $lines ) < 2 ) { // At least header + 1 data row
            wp_redirect( add_query_arg( 'shaktiman_csv_msg', 'empty_csv', wp_get_referer() ) );
            exit;
        }

        // Parse header
        $header = str_getcsv( array_shift( $lines ) );
        $required_columns = array( 'url', 'title', 'content' );
        $optional_columns = array( 'image_url', 'local_image_path', 'permalink' );
        
        // Check if we have required columns
        if ( array_diff( $required_columns, $header ) ) {
            wp_redirect( add_query_arg( 'shaktiman_csv_msg', 'invalid_format', wp_get_referer() ) );
            exit;
        }

        // Check if we have local image support
        $has_local_images = in_array( 'local_image_path', $header );
        $has_permalink = in_array( 'permalink', $header );

        $imported = 0;
        $skipped = 0;

        foreach ( $lines as $line ) {
            if ( empty( trim( $line ) ) ) continue;
            
            $data = str_getcsv( $line );
            if ( count( $data ) < 3 ) continue; // At least url, title, content

            // Ensure data array has same length as header array to prevent array_combine errors
            $header_count = count( $header );
            $data_count = count( $data );
            
            if ( $data_count > $header_count ) {
                // Truncate excess data
                $data = array_slice( $data, 0, $header_count );
            } elseif ( $data_count < $header_count ) {
                // Pad with empty strings
                $data = array_pad( $data, $header_count, '' );
            }
            
            // Now safely combine
            $row = array_combine( $header, $data );
            if ( $row === false ) {
                // Skip this row if array_combine still fails
                continue;
            }
            
            $url = esc_url_raw( $row['url'] );
            $title = sanitize_text_field( $row['title'] );
            $content = wp_kses_post( $row['content'] );
            $image_url = isset( $row['image_url'] ) ? esc_url_raw( $row['image_url'] ) : '';
            $local_image = isset( $row['local_image_path'] ) ? sanitize_file_name( $row['local_image_path'] ) : '';
            $permalink = isset( $row['permalink'] ) ? sanitize_title( $row['permalink'] ) : '';

            if ( empty( $url ) || empty( $title ) ) continue;

            // Check if already exists - prioritize permalink over source URL
            $existing = null;
            if ( ! empty( $permalink ) ) {
                $existing = $this->get_post_by_permalink( $permalink );
            }
            
            // Fallback to source URL if no permalink match
            if ( ! $existing ) {
                $existing = $this->get_post_by_source_url( $url );
            }
            
            if ( $existing && ! $update_existing ) {
                $skipped++;
                continue;
            }

            // Handle different update modes
            if ( $existing && $update_existing && $update_mode === 'template_only' ) {
                // Template-only mode: don't update title, content, or images
                $post_id = $existing->ID;
                
                // Copy Elementor template structure from post 13401
                $this->copy_elementor_template( 13401, $post_id );
                
                // Update source URL meta
                update_post_meta( $post_id, '_shaktiman_source', $url );
                
                $imported++;
            } else {
                // Full update mode or new post
                $postarr = array(
                    'post_title'   => $title,
                    'post_content' => $content,
                    'post_status'  => 'publish',
                    'post_type'    => 'service',
                );

                // Set custom permalink if provided
                if ( ! empty( $permalink ) ) {
                    $postarr['post_name'] = $permalink;
                }

                if ( $existing && $update_existing ) {
                    $postarr['ID'] = $existing->ID;
                    $post_id = wp_update_post( $postarr );
                } else {
                    $post_id = wp_insert_post( $postarr );
                }

                if ( is_wp_error( $post_id ) ) continue;

                // Copy Elementor template structure from post 13401
                $this->copy_elementor_template( 13401, $post_id );

                // Save source URL
                update_post_meta( $post_id, '_shaktiman_source', $url );

                // Handle featured image - priority to local images
                if ( $download_images ) {
                    if ( $has_local_images && ! empty( $local_image ) ) {
                        // Use local image from plugin directory
                        $image_result = $this->set_featured_image_from_local( $local_image, $post_id );
                    } elseif ( ! empty( $image_url ) ) {
                        // Fallback to remote image
                        $image_result = $this->set_featured_image_from_url( $image_url, $post_id );
                    }
                    
                    if ( isset( $image_result ) && ! $image_result ) {
                        update_post_meta( $post_id, '_shaktiman_image_error', $local_image ?: $image_url );
                    }
                }

                $imported++;
            }
            
            // Clear memory and prevent timeouts
            if ( $imported % 5 == 0 ) {
                // Clear WordPress object cache every 5 imports
                wp_cache_flush();
                
                // Small delay to prevent server overload
                usleep( 500000 ); // 0.5 seconds
                
                // Reset time limit
                set_time_limit( 300 );
            }
        }

        $msg = 'imported_' . $imported;
        if ( $skipped > 0 ) {
            $msg .= '_skipped_' . $skipped;
        }
        if ( $update_mode === 'template_only' ) {
            $msg .= '_template_only';
        }

        wp_redirect( add_query_arg( 'shaktiman_csv_msg', $msg, wp_get_referer() ) );
        exit;
    }

    private function get_post_by_source_url( $source_url ) {
        $query = new WP_Query( array(
            'post_type'  => 'service',
            'meta_key'   => '_shaktiman_source',
            'meta_value' => $source_url,
            'posts_per_page' => 1,
        ) );

        return $query->have_posts() ? $query->posts[0] : false;
    }

    private function get_post_by_permalink( $permalink ) {
        $query = new WP_Query( array(
            'post_type'  => 'service',
            'name'       => $permalink,
            'posts_per_page' => 1,
        ) );

        return $query->have_posts() ? $query->posts[0] : false;
    }

    private function set_featured_image_from_url( $image_url, $post_id ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // Skip if already has featured image and we're updating
        if ( has_post_thumbnail( $post_id ) ) {
            return true; // Consider this a success since image exists
        }

        // Make sure image URL is absolute
        if ( strpos( $image_url, 'http' ) !== 0 ) {
            $image_url = 'https://global.shaktimanagro.com' . ltrim( $image_url, '/' );
        }

        // Test if image URL is accessible
        $response = wp_remote_head( $image_url, array(
            'timeout' => 10,
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ));

        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            // Save error info for debugging
            update_post_meta( $post_id, '_shaktiman_image_debug', 'URL not accessible: ' . $image_url );
            return false;
        }

        // Try to find existing image by URL or filename before downloading
        $filename = basename( parse_url( $image_url, PHP_URL_PATH ) );
        if ( $filename ) {
            $existing_attachment = $this->get_attachment_by_filename( $filename );
            if ( $existing_attachment ) {
                set_post_thumbnail( $post_id, $existing_attachment->ID );
                update_post_meta( $post_id, '_shaktiman_image_debug', 'Reused existing remote attachment ID ' . $existing_attachment->ID . ': ' . $filename );
                return true;
            }
        }

        // Download and attach image
        $tmp = media_sideload_image( $image_url, $post_id, null, 'id' );
        
        if ( is_wp_error( $tmp ) ) {
            // Save detailed error for debugging
            update_post_meta( $post_id, '_shaktiman_image_debug', 'Sideload error: ' . $tmp->get_error_message() );
            return false;
        }

        if ( is_numeric( $tmp ) ) {
            set_post_thumbnail( $post_id, intval( $tmp ) );
            // Save success info
            update_post_meta( $post_id, '_shaktiman_image_debug', 'Success: ' . $image_url );
            return true;
        }

        update_post_meta( $post_id, '_shaktiman_image_debug', 'Unknown error with: ' . $image_url );
        return false;
    }

    private function set_featured_image_from_local( $local_image_filename, $post_id ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // Skip if already has featured image
        if ( has_post_thumbnail( $post_id ) ) {
            return true;
        }

        // Path to local image in plugin directory
        $plugin_dir = plugin_dir_path( __FILE__ );
        $local_image_path = $plugin_dir . 'images/' . $local_image_filename;

        // Check if local image exists
        if ( ! file_exists( $local_image_path ) ) {
            update_post_meta( $post_id, '_shaktiman_image_debug', 'Local image not found: ' . $local_image_filename );
            return false;
        }

        // Get file info
        $file_info = pathinfo( $local_image_path );
        $filename = sanitize_file_name( $file_info['basename'] );
        
        // Check if this image is already in media library
        $existing_attachment = $this->get_attachment_by_filename( $filename );
        if ( $existing_attachment ) {
            set_post_thumbnail( $post_id, $existing_attachment->ID );
            update_post_meta( $post_id, '_shaktiman_image_debug', 'Reused existing attachment ID ' . $existing_attachment->ID . ': ' . $filename );
            return true;
        }

        // Upload to WordPress media library
        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'] . '/' . $filename;
        $upload_url = $upload_dir['url'] . '/' . $filename;

        // Copy file to uploads directory
        if ( ! copy( $local_image_path, $upload_path ) ) {
            update_post_meta( $post_id, '_shaktiman_image_debug', 'Failed to copy: ' . $local_image_filename );
            return false;
        }

        // Check file type
        $filetype = wp_check_filetype( $filename, null );
        if ( ! $filetype['type'] ) {
            unlink( $upload_path ); // Clean up
            update_post_meta( $post_id, '_shaktiman_image_debug', 'Invalid file type: ' . $filename );
            return false;
        }

        // Create attachment
        $attachment = array(
            'guid'           => $upload_url,
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $attachment_id = wp_insert_attachment( $attachment, $upload_path, $post_id );
        
        if ( is_wp_error( $attachment_id ) ) {
            unlink( $upload_path ); // Clean up
            update_post_meta( $post_id, '_shaktiman_image_debug', 'Insert attachment error: ' . $attachment_id->get_error_message() );
            return false;
        }

        // Generate metadata and thumbnails
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_path );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );

        // Set as featured image
        set_post_thumbnail( $post_id, $attachment_id );
        update_post_meta( $post_id, '_shaktiman_image_debug', 'Success local: ' . $filename );
        
        return true;
    }

    private function get_attachment_by_filename( $filename ) {
        // First try exact match by post_title (without extension)
        $title = preg_replace( '/\.[^.]+$/', '', $filename );
        $query = new WP_Query( array(
            'post_type' => 'attachment',
            'post_status' => 'inherit', 
            'posts_per_page' => 1,
            'title' => $title
        ));
        
        if ( $query->have_posts() ) {
            return $query->posts[0];
        }
        
        // Fallback: search by attached file path
        $query = new WP_Query( array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => '_wp_attached_file',
                    'value' => $filename,
                    'compare' => 'LIKE'
                )
            )
        ));

        if ( $query->have_posts() ) {
            // Double-check the filename matches exactly to avoid false positives
            $attachment = $query->posts[0];
            $attached_file = get_post_meta( $attachment->ID, '_wp_attached_file', true );
            $attached_filename = basename( $attached_file );
            
            if ( $attached_filename === $filename ) {
                return $attachment;
            }
        }

        return false;
    }

    /**
     * Copy Elementor structure from template post to new post
     */
    private function copy_elementor_template( $template_post_id, $new_post_id ) {
        // Define Elementor and theme-specific meta keys to copy
        $meta_keys_to_copy = array(
            '_elementor_data',
            '_elementor_edit_mode', 
            '_elementor_version',
            '_elementor_template_type',
            '_elementor_page_assets',
            '_wp_page_template',
            'header_layout',
            'header_layout_sticky',
            'service_external_link',
            'service_excerpt',
            'service_icon_type',
            'service_icon_font',
            'service_icon_img',
            'content_spacing',
            'custom_service_number',
            'pt_mode',
            'ptitle_layout',
            'custom_ptitle'
        );
        
        foreach ( $meta_keys_to_copy as $meta_key ) {
            // Use get_post_meta with single=true to get the properly unserialized value
            $meta_value = get_post_meta( $template_post_id, $meta_key, true );
            
            if ( $meta_value !== '' && $meta_value !== false ) {
                // For _elementor_page_assets, ensure it's properly handled as array
                if ( $meta_key === '_elementor_page_assets' && is_string( $meta_value ) ) {
                    // Try to unserialize if it's a serialized string
                    $unserialized = maybe_unserialize( $meta_value );
                    if ( is_array( $unserialized ) ) {
                        $meta_value = $unserialized;
                    } else {
                        // If not a valid serialized array, skip this meta to avoid errors
                        continue;
                    }
                }
                
                update_post_meta( $new_post_id, $meta_key, $meta_value );
            }
        }
        
        // Force Elementor to regenerate CSS for this post
        if ( class_exists( '\Elementor\Plugin' ) ) {
            // Clear Elementor cache for this post
            \Elementor\Plugin::$instance->files_manager->clear_cache();
            
            // Delete the page assets meta to force regeneration
            delete_post_meta( $new_post_id, '_elementor_page_assets' );
        }
        
        return true;
    }

    private function show_debug_info() {
        // Show recent imports and their image status
        $recent_imports = new WP_Query( array(
            'post_type' => 'service',
            'meta_key' => '_shaktiman_source',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        if ( $recent_imports->have_posts() ) {
            ?>
            <div class="card">
                <h3>🔍 Debug: Ultimi 5 import</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Titolo</th>
                            <th>Immagine Featured</th>
                            <th>Debug Info</th>
                            <th>URL Sorgente</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ( $recent_imports->have_posts() ) : $recent_imports->the_post(); ?>
                        <tr>
                            <td><strong><?php the_title(); ?></strong></td>
                            <td>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    ✅ <small>ID: <?php echo get_post_thumbnail_id(); ?></small>
                                <?php else : ?>
                                    ❌ <small>Nessuna immagine</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $debug = get_post_meta( get_the_ID(), '_shaktiman_image_debug', true );
                                echo $debug ? '<small>' . esc_html( $debug ) . '</small>' : '<small>Nessun debug</small>';
                                ?>
                            </td>
                            <td>
                                <?php 
                                $source = get_post_meta( get_the_ID(), '_shaktiman_source', true );
                                echo $source ? '<small><a href="' . esc_url( $source ) . '" target="_blank">' . esc_html( $source ) . '</a></small>' : '-';
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <p><small>Se vedi "❌ Nessuna immagine", controlla la colonna "Debug Info" per il motivo.</small></p>
                
                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top: 10px;">
                    <?php wp_nonce_field( 'shaktiman_retry_images' ); ?>
                    <input type="hidden" name="action" value="shaktiman_retry_images">
                    <button type="submit" class="button button-secondary">🔄 Riprova download immagini per tutti i post senza featured image</button>
                </form>
            </div>
            <?php
        }
        wp_reset_postdata();
    }

    public function handle_retry_images() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Permesso negato' );
        }

        check_admin_referer( 'shaktiman_retry_images' );

        // Find all service posts without featured images but with shaktiman source
        $posts_to_retry = new WP_Query( array(
            'post_type' => 'service',
            'meta_key' => '_shaktiman_source',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'NOT EXISTS'
                )
            )
        ));

        $processed = 0;
        while ( $posts_to_retry->have_posts() ) {
            $posts_to_retry->the_post();
            $post_id = get_the_ID();
            
            // Try to extract image URL from debug info or CSV data
            $debug_info = get_post_meta( $post_id, '_shaktiman_image_debug', true );
            
            // Look for image URL in debug info
            if ( preg_match( '/with:\s*(.+)$/', $debug_info, $matches ) ) {
                $image_url = trim( $matches[1] );
                $this->set_featured_image_from_url( $image_url, $post_id );
                $processed++;
            }
        }

        wp_reset_postdata();
        wp_redirect( add_query_arg( 'shaktiman_csv_msg', 'retry_images_' . $processed, wp_get_referer() ) );
        exit;
    }

    public function handle_cleanup() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Permesso negato' );
        }

        check_admin_referer( 'shaktiman_cleanup' );

        $confirm_deletion = isset( $_POST['confirm_deletion'] ) && $_POST['confirm_deletion'] == '1';
        $delete_images = isset( $_POST['delete_images'] ) && $_POST['delete_images'] == '1';

        if ( ! $confirm_deletion ) {
            wp_redirect( add_query_arg( 'shaktiman_csv_msg', 'cleanup_not_confirmed', wp_get_referer() ) );
            exit;
        }

        // Get all Shaktiman posts
        $shaktiman_posts = new WP_Query( array(
            'post_type' => 'service',
            'meta_key' => '_shaktiman_source',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));

        $deleted_count = 0;
        $deleted_images = 0;

        foreach ( $shaktiman_posts->posts as $post_id ) {
            // Delete featured image if requested
            if ( $delete_images ) {
                $thumbnail_id = get_post_thumbnail_id( $post_id );
                if ( $thumbnail_id ) {
                    $deleted = wp_delete_attachment( $thumbnail_id, true ); // true = force delete
                    if ( $deleted ) {
                        $deleted_images++;
                    }
                }
            }

            // Delete the post (force delete, not trash)
            $deleted = wp_delete_post( $post_id, true );
            if ( $deleted ) {
                $deleted_count++;
            }
        }

        $msg = 'cleanup_' . $deleted_count;
        if ( $delete_images && $deleted_images > 0 ) {
            $msg .= '_images_' . $deleted_images;
        }

        wp_redirect( add_query_arg( 'shaktiman_csv_msg', $msg, admin_url( 'tools.php?page=shaktiman-cleanup' ) ) );
        exit;
    }
}

new Shaktiman_CSV_Importer();