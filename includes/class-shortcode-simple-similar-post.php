<?php
/**
 * Simple Shortcode Similar Post class
 */
class SSSPost {

	/**
	 * short code name (use replace)
	 * @var string
	 */
	public $sssp_shortcode_name = "[ssspost]";

	/**
	 * shortcode counter
	 * @var integer
	 */
	private $sssp_shortcode_count = 0;
	
	/**
	 * lavenshtein asc array
	 * @var array[int laven,post post]
	 */
	private $sssp_laven_post_array;

	/**
	 * constructor get instance
	 */
	public function __construct()
	{
		add_filter( 'get_my_sssp_instance', [ $this, 'get_sssp_instance' ] );
	}

	/**
	 * get instance
	 * @return instance
	 */
	public function get_sssp_instance()
	{
		return $this; 
	}

	/**
	 * wordpress shortcode description
	 * @param  $atts
	 * @return html element
	 */
	public  function sssp_shortcode($atts) {
		extract(shortcode_atts(array(
			'post_num' => 1,
		), $atts));
		$before_count = $this->sssp_shortcode_count;
		$this->sssp_shortcode_count += $post_num; 


		$sssp_post_array = $this->sssp_laven_post_array;
		$ret_html = "";
		if(count($sssp_post_array) > ($before_count) && isset($sssp_post_array[$before_count])){ 
			$ret_html .= '<div class="sssp-box">';
			$ret_html .= '<p class="sssp-box-title">' . __("Related Post","simple-shortcode-similar-post") .'</p>';
			$ret_html .= '<ul class="sssp-list sssp-list-'.$post_num.'">';
			for($i = 1;$i <= $post_num;$i++){
				if(isset($sssp_post_array[$before_count + $i - 1])){
					$post = $sssp_post_array[$before_count + $i - 1]['post'];
					$url = get_permalink($post->ID); 
					$post_title = $post->post_title;
					$ret_html .= '<li class="sssp-list-item">';
					$ret_html .= '<h5 class="sssp-similar-post"><a href="'.$url.'">'.$post_title.'</a></h5>';
					$ret_html .= '</li>';
				}
			}
			$ret_html .= '</ul></div>';
		}
		$after_count = $this->sssp_shortcode_count;
		return $ret_html;
	}

	/**
	 * get category similar post
	 * @return boolean
	 */
	public function sssp_get_category_posts(){
		$post_id = get_the_ID();
		$categories = wp_get_post_terms($post_id,'category',array('fields' => 'ids'));
		$title = get_the_title();
		$args = array('category' => $categories,'exclude' => $post_id );
		$all_posts = get_posts( $args );
		$posts_leven_array = [];

		foreach($all_posts as $post){
			$data = [
				'post' => $post,
				'leven' => $this->levenshtein_utf8($title,$post->post_title),
			];
			$posts_leven_array[] = $data;
		}
		foreach ((array) $posts_leven_array as $key => $value) {
			$sort[$key] = $value['leven'];
		}
		if(!is_array($sort)){
			return false;
		}

		array_multisort($sort, SORT_ASC, $posts_leven_array);
		$this->sssp_laven_post_array = $posts_leven_array;
		return true;
	}
	public function levenshtein_utf8($s1, $s2, $cost_ins = 1, $cost_rep = 1, $cost_del = 1) {
		$s1 = preg_split('//u', $s1, -1, PREG_SPLIT_NO_EMPTY);
		$s2 = preg_split('//u', $s2, -1, PREG_SPLIT_NO_EMPTY);
		$l1 = count($s1);
		$l2 = count($s2);
		if (!$l1) {
			return $l2 * $cost_ins;
		}
		if (!$l2) {
			return $l1 * $cost_del;
		}
		$p1 = array_fill(0, $l2 + 1, 0);
		$p2 = array_fill(0, $l2 + 1, 0);
		for ($i2 = 0; $i2 <= $l2; ++$i2) {
			$p1[$i2] = $i2 * $cost_ins;
		}
		for ($i1 = 0; $i1 < $l1; ++$i1) {
			$p2[0] = $p1[0] + $cost_ins;
			for ($i2 = 0; $i2 < $l2; ++$i2) {
				$c0 = $p1[$i2] + ($s1[$i1] === $s2[$i2] ? 0 : $cost_rep);
				$c1 = $p1[$i2 + 1] + $cost_del;
				if ($c1 < $c0) {
					$c0 = $c1;
				}
				$c2 = $p2[$i2] + $cost_ins;
				if ($c2 < $c0) {
					$c0 = $c2;
				}
				$p2[$i2 + 1] = $c0;
			}
			$tmp = $p1;
			$p1 = $p2;
			$p2 = $tmp;
		}
		return $p1[$l2];
	}
}
