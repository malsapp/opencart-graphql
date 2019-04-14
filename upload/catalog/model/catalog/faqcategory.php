<?php
class ModelCatalogFaqCategory extends Model {
	public function getFaqCategory($faqcategory_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faqcategory fq LEFT JOIN " . DB_PREFIX . "faqcategory_description fqd ON (fq.faqcategory_id = fqd.faqcategory_id) LEFT JOIN " . DB_PREFIX . "faqcategory_to_store fq2s ON (fq.faqcategory_id = fq2s.faqcategory_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND fq.faqcategory_id = '" . (int)$faqcategory_id . "' AND fq.status = '1' AND fq2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
	
		return $query->row;	
	}
	
	public function getFaqCategories($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "faqcategory m LEFT JOIN " . DB_PREFIX . "faqcategory_description fqd ON (m.faqcategory_id = fqd.faqcategory_id) LEFT JOIN " . DB_PREFIX . "faqcategory_to_store m2s ON (m.faqcategory_id = m2s.faqcategory_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND m.status = '1'";
			
			$sort_data = array(
				'title',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sort_order";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}				
					
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$faqcategory_data = $this->cache->get('faqcategory.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
		
			if (!$faqcategory_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faqcategory m LEFT JOIN " . DB_PREFIX . "faqcategory_description fqd ON (m.faqcategory_id = fqd.faqcategory_id) LEFT JOIN " . DB_PREFIX . "faqcategory_to_store m2s ON (m.faqcategory_id = m2s.faqcategory_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND m.status = '1' ORDER BY sort_order");
	
				$faqcategory_data = $query->rows;
			
				$this->cache->set('faqcategory.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $faqcategory_data);
			}
		 
			return $faqcategory_data;
		}	
	} 
}
?>