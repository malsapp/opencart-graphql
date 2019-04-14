<?php
class ModelCatalogFaq extends Model {
	public function addFaq($data) {
			
		$this->db->query("INSERT INTO " . DB_PREFIX . "faq SET faqcategory_id = '" . (int)$data['faqcategory_id'] . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");
		
		$faq_id = $this->db->getLastId();
		
		foreach ($data['faq_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET faq_id = '" . (int)$faq_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (isset($data['qa_store'])) {
			foreach ($data['qa_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "faq_to_store SET faq_id = '" . (int)$faq_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
												
		$this->cache->delete('faq');
	}
	
	public function editFaq($faq_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "faq SET faqcategory_id = '" . (int)$data['faqcategory_id'] . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE faq_id = '" . (int)$faq_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");
		
		foreach ($data['faq_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "faq_description SET faq_id = '" . (int)$faq_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "faq_to_store WHERE faq_id = '" . (int)$faq_id . "'");

		if (isset($data['qa_store'])) {
			foreach ($data['qa_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "faq_to_store SET faq_id = '" . (int)$faq_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
						
		$this->cache->delete('faq');
	}
	
	public function deleteFaq($faq_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "faq WHERE faq_id = '" . (int)$faq_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "faq_to_store WHERE faq_id = '" . (int)$faq_id . "'");		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'faq_id=" . (int)$faq_id. "'");
		
		$this->cache->delete('faq');
	}
	
	public function getFaq($faq_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "faq fq LEFT JOIN " . DB_PREFIX . "faq_description fqd ON (fq.faq_id = fqd.faq_id) WHERE fq.faq_id = '" . (int)$faq_id . "' AND fqd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				
		return $query->row;
	}
	
	public function getFaqs($data = array()) {
		if ($data) {
			$sql = "SELECT fq.*, fqd.title, fqc.title AS category FROM " . DB_PREFIX . "faq fq LEFT JOIN " . DB_PREFIX . "faq_description fqd ON (fq.faq_id = fqd.faq_id) LEFT JOIN " . DB_PREFIX . "faqcategory_description fqc ON (fq.faqcategory_id = fqc.faqcategory_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND fqc.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
		
			if (isset($data['filter_title']) && !is_null($data['filter_title'])) {
				$sql .= " AND LCASE(fqd.title) LIKE '" . $this->db->escape(strtolower($data['filter_title'])) . "%'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND fq.status = '" . (int)$data['filter_status'] . "'";
			}

			$sort_data = array(
				'fqd.title',
				'fqc.title',
				'fq.status',
				'fq.sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY fqd.name";	
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
			$qa_data = $this->cache->get('faq.' . $this->config->get('config_language_id'));
		
			if (!$qa_data) {
				$query = $this->db->query("SELECT fq.*, fqd.title, fqc.title AS category FROM " . DB_PREFIX . "faq fq LEFT JOIN " . DB_PREFIX . "faq_description fqd ON (fq.faq_id = fqd.faq_id) LEFT JOIN " . DB_PREFIX . "faqcategory_description fqc ON (fq.faqcategory_id = fqc.faqcategory_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND fqc.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY fqd.name ASC");
	
				$qa_data = $query->rows;
			
				$this->cache->set('faq.' . $this->config->get('config_language_id'), $qa_data);
			}	
	
			return $qa_data;
		}
	}
	
	public function getFaqDescriptions($faq_id) {
		$faq_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_description WHERE faq_id = '" . (int)$faq_id . "'");
		
		foreach ($query->rows as $result) {
			$faq_description_data[$result['language_id']] = array(
				'title'             => $result['title'],
				'description'      => $result['description'],
			);
		}
		
		return $faq_description_data;
	}

	public function getFaqStores($faq_id) {
		$qa_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faq_to_store WHERE faq_id = '" . (int)$faq_id . "'");

		foreach ($query->rows as $result) {
			$qa_store_data[] = $result['store_id'];
		}
		
		return $qa_store_data;
	}
	
	public function geTotaltFaqs($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faq fq LEFT JOIN " . DB_PREFIX . "faq_description fqd ON (fq.faq_id = fqd.faq_id) WHERE fqd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['filter_title']) && !is_null($data['filter_title'])) {
			$sql .= " AND LCASE(fqd.title) LIKE '%" . $this->db->escape(strtolower($data['filter_title'])) . "%'";
		}
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND fq.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
	
	public function geTotaltFaqsByFaqCategoryId($faqcategory_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faq WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");

		return $query->row['total'];
	}	
	
}
?>