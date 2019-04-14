<?php
class ModelExtensionPhotoGallery extends Model {
	public function addPhoto($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "photo SET image = '" . $this->db->escape($data['image']) . "', date_added = NOW(), status = '" . (int)$data['status'] . "'");
		
		$photo_id = $this->db->getLastId();
		
		foreach ($data['photo'] as $key => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX ."photo_description SET photo_id = '" . (int)$photo_id . "', language_id = '" . (int)$key . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', short_description = '" . $this->db->escape($value['short_description']) . "'");
		}
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'photo_id=" . (int)$photo_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	}
	
	public function editPhoto($photo_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "photo SET image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE photo_id = '" . (int)$photo_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "photo_description WHERE photo_id = '" . (int)$photo_id. "'");
		
		foreach ($data['photo'] as $key => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX ."photo_description SET photo_id = '" . (int)$photo_id . "', language_id = '" . (int)$key . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', short_description = '" . $this->db->escape($value['short_description']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'photo_id=" . (int)$photo_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'photo_id=" . (int)$photo_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
	}
	
	public function getPhoto($photo_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'photo_id=" . (int)$photo_id . "') AS keyword FROM " . DB_PREFIX . "photo WHERE photo_id = '" . (int)$photo_id . "'"); 
 
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
   
	public function getPhotoDescription($photo_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "photo_description WHERE photo_id = '" . (int)$photo_id . "'"); 
		
		foreach ($query->rows as $result) {
			$photo_description[$result['language_id']] = array(
				'title'       			=> $result['title'],
				'short_description'		=> $result['short_description'],
				'description' 			=> $result['description']
			);
		}
		
		return $photo_description;
	}
 
	public function getAllPhotos($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "photo n LEFT JOIN " . DB_PREFIX . "photo_description nd ON n.photo_id = nd.photo_id WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY date_added DESC";
		
		if (isset($data['start']) && isset($data['limit'])) {
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
	}
   
	public function deletePhoto($photo_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "photo WHERE photo_id = '" . (int)$photo_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "photo_description WHERE photo_id = '" . (int)$photo_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'photo_id=" . (int)$photo_id. "'");
	}
   
	public function getTotalPhotos() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "photo");
	
		return $query->row['total'];
	}

}
?>