<?php
class ModelExtensionDeliveryAgent extends Model {
	public function addDeliveryAgent($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_agent SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status']. "', date_added = NOW()");

		$delivery_agent_id = $this->db->getLastId();

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_agent_address SET delivery_agent_id = '" . (int)$delivery_agent_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");

				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE " . DB_PREFIX . "delivery_agent SET address_id = '" . (int)$address_id . "' WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");
				}
			}
		}
		
		return $delivery_agent_id;
	}

	public function editDeliveryAgent($delivery_agent_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "delivery_agent SET  firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']). "', status = '" . (int)$data['status'] . "' WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "delivery_agent SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_agent_address WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_agent_address SET address_id = '" . (int)$address['address_id'] . "', delivery_agent_id = '" . (int)$delivery_agent_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id']  . "'");

				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE " . DB_PREFIX . "delivery_agent SET address_id = '" . (int)$address_id . "' WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");
				}
			}
		}
	}

	public function editToken($delivery_agent_id, $token) {
		$this->db->query("UPDATE " . DB_PREFIX . "delivery_agent SET token = '" . $this->db->escape($token) . "' WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");
	}

	public function deleteDeliveryAgent($delivery_agent_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_agent WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_agent_address WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");
	}

	public function getDeliveryAgent($delivery_agent_id) {
		$query = $this->db->query("SELECT DISTINCT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "delivery_agent WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");

		return $query->row;
	}

	public function getDeliveryAgentByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "delivery_agent WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

		public function getDeliveryAgents($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . "delivery_agent c where 1=1 ";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'c.status',
			'c.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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
	}

	public function getTotalDeliveryAgent($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "delivery_agent";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}



	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_agent_address WHERE address_id = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			return array(
				'address_id'     => $address_query->row['address_id'],
				'delivery_agent_id'    => $address_query->row['delivery_agent_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
			);
		}
	}

	public function getAddresses($delivery_agent_id) {
		$address_data = array();

		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "delivery_agent_address WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}

	public function getOrders($delivery_agent_id){
		$orders=array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_agent_order WHERE delivery_agent_id = '" . (int)$delivery_agent_id . "'");
		foreach ($query->rows as $row) {
			$orders[]=$row['order_id'];
		}
		return $orders;
	}

	public function assignOrder($delivery_agent_id,$order_id){
		//delete the previously assigned delivery agents 
		$this->db->query("DELETE FROM oc_delivery_agent_order WHERE order_id=$order_id;");
		//assign order to a new delivery agent
		$this->db->query("INSERT INTO oc_delivery_agent_order SET delivery_agent_id =$delivery_agent_id , order_id=$order_id;");		
		return $this->db->getLastId();
	}

	public function unassignOrder($order_id){
		//delete the previously assigned delivery agents 
		$this->db->query("DELETE FROM oc_delivery_agent_order WHERE order_id=$order_id;");
	}

	public function getDeliveryAgentByOrderId($order_id){
		$result=$this->db->query("SELECT * FROM oc_delivery_agent_order WHERE order_id = $order_id");
		return $query->row;
	}
}
?>