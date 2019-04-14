<?php
class ModelCatalogFaqCategory extends Model {
	public function addCategory($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "faqcategory SET sort_order = '" . (int)$this->request->post['sort_order'] . "', status = '" . (int)$data['status'] . "'");

		$faqcategory_id = $this->db->getLastId(); 
			
		foreach ($data['faqcategory_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "faqcategory_description SET faqcategory_id = '" . (int)$faqcategory_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (isset($data['faqcategory_store'])) {
			foreach ($data['faqcategory_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "faqcategory_to_store SET faqcategory_id = '" . (int)$faqcategory_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['faqcategory_layout'])) {
			foreach ($data['faqcategory_layout'] as $store_id => $layout) {
				if ($layout) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "faqcategory_to_layout SET faqcategory_id = '" . (int)$faqcategory_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
				
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'faqcategory_id=" . (int)$faqcategory_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('faqcategory');
	}
	
	public function editCategory($faqcategory_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "faqcategory SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "faqcategory_description WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");
					
		foreach ($data['faqcategory_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "faqcategory_description SET faqcategory_id = '" . (int)$faqcategory_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "faqcategory_to_store WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");
		
		if (isset($data['faqcategory_store'])) {
			foreach ($data['faqcategory_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "faqcategory_to_store SET faqcategory_id = '" . (int)$faqcategory_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "faqcategory_to_layout WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");

		if (isset($data['faqcategory_layout'])) {
			foreach ($data['faqcategory_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "faqcategory_to_layout SET faqcategory_id = '" . (int)$faqcategory_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'faqcategory_id=" . (int)$faqcategory_id . "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'faqcategory_id=" . (int)$faqcategory_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('faqcategory');
	}
	
	public function deleteCategory($faqcategory_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "faqcategory WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "faqcategory_description WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "faqcategory_to_store WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "faqcategory_to_layout WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'faqcategory_id=" . (int)$faqcategory_id . "'");

		$this->cache->delete('faqcategory');
	}	
	
	public function getCategory($faqcategory_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'faqcategory_id=" . (int)$faqcategory_id . "') AS keyword FROM " . DB_PREFIX . "faqcategory WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");
		
		return $query->row;
	}
	
	public function getCategorys($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "faqcategory fc LEFT JOIN " . DB_PREFIX . "faqcategory_description fcd ON (fc.faqcategory_id = fcd.faqcategory_id) WHERE fcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'fcd.title',
				'fc.sort_order'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY fcd.title";	
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
			$faqcategory_data = $this->cache->get('faqcategory.' . $this->config->get('config_language_id'));
		
			if (!$faqcategory_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faqcategory fc LEFT JOIN " . DB_PREFIX . "faqcategory_description fcd ON (fc.faqcategory_id = fcd.faqcategory_id) WHERE fcd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY fcd.title");
	
				$faqcategory_data = $query->rows;
			
				$this->cache->set('faqcategory.' . $this->config->get('config_language_id'), $faqcategory_data);
			}	
	
			return $faqcategory_data;			
		}
	}

	public function getFaqCategoryDescriptions($faqcategory_id) {
		$faqcategory_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faqcategory_description WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");

		foreach ($query->rows as $result) {
			$faqcategory_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'description' => $result['description']
			);
		}
		
		return $faqcategory_description_data;
	}


	public function getCategoryStores($faqcategory_id) {
		$faqcategory_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faqcategory_to_store WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");

		foreach ($query->rows as $result) {
			$faqcategory_store_data[] = $result['store_id'];
		}
		
		return $faqcategory_store_data;
	}

	public function getFaqCategoryLayouts($faqcategory_id) {
		$faqcategory_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "faqcategory_to_layout WHERE faqcategory_id = '" . (int)$faqcategory_id . "'");
		
		foreach ($query->rows as $result) {
			$faqcategory_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $faqcategory_layout_data;
	}

	public function getTotalCategorys() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faqcategory");
		
		return $query->row['total'];
	}
	
	public function getTotalFaqCategorysByLayoutId($layout_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "faqcategory_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
?>