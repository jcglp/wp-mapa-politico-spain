<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Mapa_Politico_Settings {

	/**
	 * The single instance of WP_Mapa_Politico_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'wpmps_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );

	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_options_page( __( 'WP Mapa Politico', WPMPS_TEXTDOMAIN ) , __( 'WP Mapa Politico', WPMPS_TEXTDOMAIN ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );		

		wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.5.0' );
		wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Configuración', WPMPS_TEXTDOMAIN ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {


		$settings['inicio'] = array(
				'title'					=> __('Inicio', WPMPS_TEXTDOMAIN ),
				'description'			=> __( 'Bienvenido al WP Mapa Político España, plugin WordPress que te permitirá mostrar un mapa político de España con áreas seleccionables.', WPMPS_TEXTDOMAIN ),
				'fields'				=> array(
						array(
								'id' 			=> 'inicio',
								'label'			=> '',
								'description'	=> '',
								'type'			=> 'inicio',
								'default'		=> '0',

						),
				)
		);


		$settings['mapa'] = array(
				'title'					=> __( 'Mapa', WPMPS_TEXTDOMAIN ),
				'description'			=> __( 'Define los enlaces para cada una de las provincias. # es un enlace vacío, a usar sino se quiere definir enlace.', WPMPS_TEXTDOMAIN ),
				'fields'				=> array(
												array(
														'id' 			=> 'mapa',
														'label'			=> '',
														'description'	=> '',
														'type'			=> 'mapa',
														'default'		=> '0',

												),
										)
								);

		$settings['configuracion'] = array(
				'title'					=> __( 'Configuración', WPMPS_TEXTDOMAIN ),
				'description'			=> __( 'Configuración aspectos del plugin.', WPMPS_TEXTDOMAIN ),
				'fields'				=> array(

												array(
														'id' 			=> 'show_border',
														'label'			=> __( '¿Mostrar borde del mapa?', WPMPS_TEXTDOMAIN ),
														'description'	=> false,
														'type'			=> 'radio',
														'default'		=> 'S',
														'options' => array('S' => 'Mostrar',
																							'N' => 'Ocultar'),
												),
												array(
														'id' 			=> 'border_color',
														'label'			=> __( 'Color del borde', WPMPS_TEXTDOMAIN ),
														'description'	=> __( 'Seleccione el color del borde.', WPMPS_TEXTDOMAIN ),
														'type'			=> 'color',
														'default'		=> '#dddddd'
												),

												array(
														'id' 			=> 'separador1',
														'label'			=> false,
														'description'	=> false,
														'type'			=> 'separador',
														'default'		=> false
												),

												array(
														'id' 			=> 'background_color',
														'label'			=> __( 'Color del fondo del mapa', WPMPS_TEXTDOMAIN ),
														'description'	=> __( 'Seleccione el color del fondo del mapa.', WPMPS_TEXTDOMAIN ),
														'type'			=> 'color',
														'default'		=> '#dde6da'
												),
												array(
														'id' 			=> 'background_provincia_color',
														'label'			=> __( 'Color del fondo defecto provincias', WPMPS_TEXTDOMAIN ),
														'description'	=> __( 'Seleccione el color del fondo de las provincias.', WPMPS_TEXTDOMAIN ),
														'type'			=> 'color',
														'default'		=> '#8098a8'
												),
												array(
														'id' 			=> 'hover_provincia_color',
														'label'			=> __( 'Color provincia activa', WPMPS_TEXTDOMAIN ),
														'description'	=> __( 'Seleccione el color de activación de las provincias.', WPMPS_TEXTDOMAIN ),
														'type'			=> 'color',
														'default'		=> '#265a82'
												),

												array(
														'id' 			=> 'separador2',
														'label'			=> false,
														'description'	=> false,
														'type'			=> 'separador',
														'default'		=> false
												),

												array(
														'id' 			=> 'rellenar_provincias_con_enlace',
														'label'			=> __( '¿Colorear provincias que tienen enlace?', WPMPS_TEXTDOMAIN ),
														'description'	=> 'Para pintar las provincias que tienen enlace con un color personalizado.',
														'type'			=> 'radio',
														'default'		=> 'N',
														'options' => array('S' => 'Si',
																							'N' => 'No'),
												),

												array(
														'id' 			=> 'has_link_provincia_color',
														'label'			=> __( 'Color provincia con enlace', WPMPS_TEXTDOMAIN ),
														'description'	=> __( 'Seleccione el color de relleno en las provincias con enlace.', WPMPS_TEXTDOMAIN ),
														'type'			=> 'color',
														'default'		=> '#989090'
												),

												array(
														'id' 			=> 'separador3',
														'label'			=> false,
														'description'	=> false,
														'type'			=> 'separador',
														'default'		=> false
												),

												array(
														'id' 			=> 'metodo_recuperar_svg',
														'label'			=> __( 'Forma de recuperar la imagen', WPMPS_TEXTDOMAIN ),
														'description'	=> 'Metodo interno para recuperar la imagen que muestra el mapa.',
														'type'			=> 'radio',
														'default'		=> 'curl',
														'options' => array('curl' => 'CURL',
																							'file' => 'FILE'),
												),

											)
								);





		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
		$html .= '<h2>' . __( 'WP Mapa Politico España' , WPMPS_TEXTDOMAIN ) . '</h2>' . "\n";
		$html .= '<div>' .  "\n";

		$tab = '';
		if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
			$tab .= $_GET['tab'];
		}

		// Show page tabs
		if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

			$html .= '<h2 class="nav-tab-wrapper">' . "\n";

			$c = 0;
			foreach ( $this->settings as $section => $data ) {

				// Set tab class
				$class = 'nav-tab';
				if ( ! isset( $_GET['tab'] ) ) {
					if ( 0 == $c ) {
						$class .= ' nav-tab-active';
					}
				} else {
					if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
						$class .= ' nav-tab-active';
					}
				}

				// Set tab link
				$tab_link = add_query_arg( array( 'tab' => $section ) );
				if ( isset( $_GET['settings-updated'] ) ) {
					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Output tab
				$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

				++$c;
			}

			$html .= '</h2>' . "\n";
		}

		if ($tab=='inicio' || $tab=='') {

			// Get settings fields
			ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			$this->do_settings_sections_wmps( $this->parent->_token . '_settings' );

			$html .= ob_get_clean();

		} else if (substr($tab, 0, 4)==='mapa') {

			if (isset($_POST['Submit'])) {

				$wpmps_mapas =  get_option( 'wpmps_plugin_mapas' );

				foreach ($_POST as $key => $valor){
					$v = explode( '-', $key);
					if (4 == count($v) ) {
						if ( isset( $wpmps_mapas[$v[0]][$v[1]][$v[2]][$v[3]] ) ){
							$wpmps_mapas[$v[0]][$v[1]][$v[2]][$v[3]] = $valor;

						}

					}

				}
				update_option( 'wpmps_plugin_mapas', $wpmps_mapas );

				echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">'
						.'<p>'. __( 'Datos guardados correctamente' , WPMPS_TEXTDOMAIN ) .'</p>'
						.'<button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button>'
					.'</div>';
			}

			// Formulario para capturar la informacion del mapa y guararlo como una única opcion
			// $bnn_action = '../wp-content/plugins/'.plugin_basename( __DIR__ ).'/lib/grabar_coordenadas.php';
			$bnn_action = "";
			$html .= '<form method="post" action="'.$bnn_action.'" enctype="multipart/form-data">' . "\n";



			// Get settings fields
			ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			$this->do_settings_sections_wmps( $this->parent->_token . '_settings' );

			$html .= ob_get_clean();

			$html .= '<p class="submit">' . "\n";
			$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
			$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Guardar cambios' , WPMPS_TEXTDOMAIN ) ) . '" />' . "\n";
			$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";


		} else {

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

			// Get settings fields
			ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			$this->do_settings_sections_wmps( $this->parent->_token . '_settings' );
			$html .= ob_get_clean();

			$html .= '<p class="submit">' . "\n";
			$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
			$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Guardar cambios' , WPMPS_TEXTDOMAIN ) ) . '" />' . "\n";
			$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";

		}

		$html .= '</div>' . "\n";

		echo $html;
	}


	function do_settings_sections_wmps( $page ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[$page] ) )
			return;

		foreach ( (array) $wp_settings_sections[$page] as $section ) {
			if ( $section['title'] )
				echo "<h3>{$section['title']}</h3>\n";

			if ( $section['callback'] )
				call_user_func( $section['callback'], $section );

			if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
				continue;

			if ('inicio' != $section['id']):
				echo '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Guardar cambios' , WPMPS_TEXTDOMAIN ) ) . '" />' . "\n";
			endif;

			echo '<table class="wpmps-form-table">';
			$this->do_settings_fields_wpms( $page, $section['id'] );
			echo '</table>';
		}
	}


	function do_settings_fields_wpms($page, $section) {
		global $wp_settings_fields;

		if ( ! isset( $wp_settings_fields[$page][$section] ) )
			return;



		foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
			$class = '';

			if ( ! empty( $field['args']['class'] ) ) {
				$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
			}


			echo "<tr{$class}>";

			if ( ! empty( $field['args']['label_for'] ) ) {
				echo '<th scope="col"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></th>';

			} else {
				echo '<th scope="col">' . $field['title'] . '</th>';

			}

			echo '</tr>';
			echo "<tr{$class}>";
			echo '<td>';
			call_user_func($field['callback'], $field['args']);
			echo '</td>';
			echo '</tr>';
		}
	}

	/**
	 * Main WP_Mapa_Politico_Settings Instance
	 *
	 * Ensures only one instance of WP_Mapa_Politico_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WP_Mapa_Politico()
	 * @return Main WP_Mapa_Politico_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
