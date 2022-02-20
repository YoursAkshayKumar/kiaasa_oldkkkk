<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function getProduct_post() {
        $method = $this->_detect_method();
        if (!$method == 'GET') {
            $this->response(['status' => 400, 'messsage'=>'error', 'description' => 'Bad request.'], REST_Controller::HTTP_BAD_REQUEST);
            exit();
        }
        else{

            $this->db->select(
                'p.id,
                 p.product_name,
                 p.product_barcode,
                 p.product_sku,
                 p.vendor_product_sku,
                 p.category_id,
                 p.subcategory_id,
                 p.product_description,
                 p.base_price,
                 p.sale_price,
                 p.size_id,
                 p.color_id,
                 p.push_demand_booked,
                 p.sale_category,
                 p.story_id,
                 p.season_id,
                 p.product_type,
                 p.hsn_code,
                 p.user_id,
                 p.gst_inclusive,
                 p.custom_product,
                 p.arnon_product,
                 p.supplier_name,
                 p.product_rate_updated,
                 p.status,
                 p.is_deleted,
                 p.created_at
                '
            );
    
            $this->db->from('pos_product_master as p');

            if($this->input->post('store_id')){
                $productIds =  $this->product_inventory_m->getProductByStoreId($this->input->post('store_id'));
                var_dump($this->input->post('store_id')); exit;
                // $productIds = [1];

                $this->db->where_in('p.id', $productIds);
            }




            $this->db->limit(5);
            $this->db->order_by('p.id DESC');
    
            $products = $this->db->get()->result_array();
    
            foreach ($products as $key => $p) {
                $this->db->select(
                    'i.image_name,
                     i.image_title,
                     i.image_type 
                    '
                );
                $this->db->from('pos_product_images as i');
                $this->db->where('i.product_id =', $p["id"]);
                $image = $this->db->get()->result_array();
                $products[$key]['image'] =  $image;
            }
    
    
            if (empty($products)) {
                $response = ['status' => 200, 'message' => 'success', 'description' => 'There is no product'];
            } else {
                $response = ['status' => 200, 'message' => 'success', 'description' => 'Product fetch successfully.', 'data' => $products];
            }
    

            
            
            $this->response($response, REST_Controller::HTTP_OK);
            exit();
        }
    }

    





//CLASS ENDS
}