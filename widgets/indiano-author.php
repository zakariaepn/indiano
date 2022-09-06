<?php
/**
 * EWA Elementor Heading Widget.
 *
 * Elementor widget that inserts heading into the page
 *
 * @since 1.0.0
 */
class Indiano_Author extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve heading  widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'indiano-author';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Indiano Author', 'indiano' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve heading  widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the heading widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'indiano_cat' ];
	}

	public function get_keywords() {
		return [ 'authorbox','indiano' ];
	}   

	/**
	 * Register heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		
		// start of the Content tab section
	   $this->start_controls_section(
	       'invato-author',
		    [
		        'label' => esc_html__('Content','indiano'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		   
		    ]
	    );
		
		$this->add_control(
			'author_designation',
			[
				'label' => esc_html__( 'Designation', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'WP developer', 'plugin-name' ),
				'placeholder' => esc_html__( 'Type your designation here', 'plugin-name' ),
				'label_block' => true,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'author_social_title', [
				'label' => esc_html__( 'Social Title', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Facebook' , 'plugin-name' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'author_social_media_link',
			[
				'label' => esc_html__( 'Link', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'plugin-name' ),
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'author_social_icon',
			[
				'label' => esc_html__( 'Icon', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'circle',
						'dot-circle',
						'square-full',
					],
					'fa-regular' => [
						'circle',
						'dot-circle',
						'square-full',
					],
				],
			]
		);

		$this->add_control(
			'author_social_lists',
			[
				'label' => esc_html__( 'Social List', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'author_social_media_link' => esc_url( 'https://www.facebook.com', 'plugin-name' ),
						'author_social_icon' =>  [
							'value' => 'fas fa-circle',
							'library' => 'fa-solid',
						]
					]
					
				],
				'title_field' => '{{{ author_social_title }}}',
			]
		);



		
		$this->end_controls_section();
		// end of the Content tab section
		
		

	}

	/**
	 * Render heading  widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		$author_designation = $settings['author_designation'];
		$author_social_lists = $settings['author_social_lists'];
		
	
		
		
		?>
		
<div class="place-author-card">
    <div class="place-author-img">
				<?php
					$user = wp_get_current_user();
			
					if ( $user ) :
					?>
					<img src="<?php echo esc_url( get_avatar_url( $user->ID ) ); ?>" />
				<?php endif; ?>
      
    </div>
    <div class="place-author-content">
        <div>
            <h3 class="author-name">
				<?php
						$current_user = wp_get_current_user();
						echo $current_user->nickname;
				?>
            </h3>
            <p class="place-mb">
                <?php if($author_designation){echo $author_designation;} ?>
            </p>
        </div>
        <ul class="place-author-social">
			<?php if($author_social_lists){
					foreach($author_social_lists as $list){
						
						?> 
								
							   <li>
									<a href="<?php echo $list['author_social_media_link']['url']; ?>">
									<i class="<?php echo $list['author_social_icon']['value']; ?>"></i>
									</a>
								</li>
						<?php 
					}
			} ?>
           
			
            
        </ul>
    </div>
</div>
		
		
       <?php
	}
}