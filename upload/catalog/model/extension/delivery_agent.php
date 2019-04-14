<?php
class ModelExtensionDeliveryAgent extends Model
{
    public function getDeliveryAgent($delivery_agent_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "delivery_agent WHERE delivery_agent_id = '" . (int) $delivery_agent_id . "'");

        return $query->row;
    }

    public function getDeliveryAgentByEmail($email)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "delivery_agent WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

        return $query->row;
    }

    public function getOrder($delivery_agent_id, $order_id)
    {
        $prefix = DB_PREFIX;
        $agent_id = intval($delivery_agent_id);
        $order_id = intval($order_id);

        $query = $this->db->query(
            "SELECT orders.* FROM {$prefix}delivery_agent_order as da_orders
            LEFT JOIN {$prefix}order as orders
            ON da_orders.order_id = orders.order_id
            WHERE delivery_agent_id = '$agent_id'
            AND orders.order_id = '$order_id'
			LIMIT 1");

        return $query->rows;
    }

    public function getOrders($delivery_agent_id, $options = array())
    {
        $default_options = array(
            'start' => 0,
            'limit' => 20,
            'order_by' => 'order_id',
            'status' => array(),
        );
        $options = array_merge($default_options, $options);

        $order_dir = strpos($options['order_by'], '-') === 0 ? "DESC" : "ASC";
        $options['order_by'] = ltrim($options['order_by'], '-');

        $prefix = DB_PREFIX;
        $agent_id = (int) $delivery_agent_id;
        $status_filter = "";
        if (!empty($options['status']) && is_array($options['status'])) {
            $status_filter = " AND orders.order_status_id IN ('" . implode("', '", $options['status']) . "') ";
        }

        $query = $this->db->query(
            "SELECT orders.* FROM {$prefix}delivery_agent_order as da_orders
            LEFT JOIN {$prefix}order as orders
            ON da_orders.order_id = orders.order_id
            WHERE delivery_agent_id = '$agent_id'
            $status_filter
            ORDER BY orders.{$options['order_by']} {$order_dir}
			LIMIT {$options['start']}, {$options['limit']}");

        return $query->rows;
    }
}
