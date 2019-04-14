<?php
class ModelCatalogFaq extends Model {
	public function getFaq($faq_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq m LEFT JOIN " . DB_PREFIX . "faq_description fqd ON (m.faq_id = fqd.faq_id) LEFT JOIN " . DB_PREFIX . "faq_to_store m2s ON (m.faq_id = m2s.faq_id) WHERE m.faq_id = '" . (int)$faq_id . "' AND fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
	
		return $query->row;	
	}
	
	public function getFaqs($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "faq m LEFT JOIN " . DB_PREFIX . "faq_description fqd ON (m.faq_id = fqd.faq_id) LEFT JOIN " . DB_PREFIX . "faq_to_store m2s ON (m.faq_id = m2s.faq_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND m.status = '1'";

			if (isset($data['faqcategory_id'])) {
				$sql .= " AND m.faqcategory_id = '" . (int)$data['faqcategory_id'] . "'";
			}
			
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
			$faq_data = $this->cache->get('faq.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'));
		
			if (!$faq_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq m LEFT JOIN " . DB_PREFIX . "faq_description fqd ON (m.faq_id = fqd.faq_id) LEFT JOIN " . DB_PREFIX . "faq_to_store m2s ON (m.faq_id = m2s.faq_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND m.status = '1' ORDER BY title");
	
				$faq_data = $query->rows;
			
				$this->cache->set('faq.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $faq_data);
			}
		 
			return $faq_data;
		}	
	} 

	public function getTotalFaqs($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faq fq LEFT JOIN " . DB_PREFIX . "faq_description fqd ON (fq.faq_id = fqd.faq_id) LEFT JOIN " . DB_PREFIX . "faq_to_store fq2s ON (fq.faq_id = fq2s.faq_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND fq.status = '1' AND fq2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (isset($data['faqcategory_id'])) {
			$sql .= " AND fq.faqcategory_id = '" . (int)$data['faqcategory_id'] . "'";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

}
?>