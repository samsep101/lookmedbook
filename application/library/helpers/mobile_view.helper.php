<?php
/**
 * Mobile
 *
 */
class MobileViewHelper {
	
	/* json-category list */
	public function categories($categories,$level = 1)
	{
		$arr = array();
		foreach($categories as $category){
			$arr[] = array('category' => array('id'=> $category['id'], 'name'=> $category['name'], 'level' => $level)); 	
		}
		return json_encode($arr);
	}
	
	/* json-products list */
	public function products($products){
		$arr = array();
		foreach($products as $product){
			$arr[] = array(
						'product' => array(
							'id'=> $product['id'], 
							'name'=> $product['name'], 
							'html' => $product['brief'], 
							'price' => $product['price'], 
							'image' => '/media/files/products/'.$product['image_id']
						)
					); 	
		}
		return json_encode($arr);
	}
	
	/* json-product */
	public function product($product){
		$arr = array();
		$arr[] = array(
			'product' => array(
					'id'=> $product['id'], 
					'name'=> $product['name'], 
					'html' => $product['content'], 
					'price' => $product['price'], 
					'image' => '/media/files/products/'.$product['image_id'],
					'images' => array(
						array('image' => '/media/files/products/'.$product['image_id']),
						array('image' => '/media/files/products/'.$product['img2']),
						array('image' => '/media/files/products/'.$product['img3']),
						array('image' => '/media/files/products/'.$product['img4']),
						array('image' => '/media/files/products/'.$product['img5']),
					)
				)
			); 	
		return json_encode($arr);
	}
}