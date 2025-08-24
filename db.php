<?php
/**
 * Class plugins_advmulti_db
 */
class plugins_advmulti_db {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
    public function fetchData(array $config, array $params = []) {
		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'advmultis':
					$query = 'SELECT 
								id_advmulti,
								url_advmulti,
								icon_advmulti,
								title_advmulti,
								desc_advmulti
							FROM mc_advmulti ms
							LEFT JOIN mc_advmulti_content msc USING(id_advmulti)
							LEFT JOIN mc_lang ml USING(id_lang)
							WHERE ml.id_lang = :lang
							  AND ms.module_advmulti = :module
							  AND ms.id_module '.(empty($params['id_module']) ? 'IS NULL' : '= :id_module').'
							ORDER BY ms.order_advmulti';
					if(empty($params['id_module'])) unset($params['id_module']);
					break;
				case 'activeadvmultis':
					$query = 'SELECT 
								id_advmulti,
								url_advmulti,
								blank_advmulti,
								icon_advmulti,
								title_advmulti,
								desc_advmulti
							FROM mc_advmulti ms
							LEFT JOIN mc_advmulti_content msc USING(id_advmulti)
							LEFT JOIN mc_lang ml USING(id_lang)
							WHERE iso_lang = :lang
							  AND ms.module_advmulti = :module_advmulti
							  AND ms.id_module '.(empty($params['id_module']) ? 'IS NULL' : '= :id_module').'
							  AND published_advmulti = 1
							ORDER BY order_advmulti';
					if(empty($params['id_module'])) unset($params['id_module']);
					break;
				case 'advmultiContent':
					$query = 'SELECT ms.*, msc.*
							FROM mc_advmulti ms
							JOIN mc_advmulti_content msc USING(id_advmulti)
							JOIN mc_lang ml USING(id_lang)
							WHERE ms.id_advmulti = :id';
					break;
				case 'img':
					$query = 'SELECT ms.id_advmulti, ms.icon_advmulti FROM mc_advmulti ms';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'advmultiContent':
					$query = 'SELECT * FROM mc_advmulti_content WHERE id_advmulti = :id AND id_lang = :id_lang';
					break;
				case 'lastadvmulti':
					$query = 'SELECT * FROM mc_advmulti ORDER BY id_advmulti DESC LIMIT 0,1';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

    /**
     * @param array $config
     * @param array $params
	 * @return bool
     */
    public function insert(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'advmulti':
				$query = "INSERT INTO mc_advmulti(icon_advmulti, module_advmulti, id_module, order_advmulti) 
						SELECT :icon_advmulti, :module, :id_module, COUNT(id_advmulti) FROM mc_advmulti WHERE module_advmulti = '".$params['module']."'";
				break;
			case 'advmultiContent':
				$query = 'INSERT INTO mc_advmulti_content(id_advmulti, id_lang, title_advmulti, desc_advmulti, url_advmulti, blank_advmulti, published_advmulti)
						VALUES (:id_advmulti, :id_lang, :title_advmulti, :desc_advmulti, :url_advmulti, :blank_advmulti, :published_advmulti)';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
    public function update(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'advmultiContent':
				$query = 'UPDATE mc_advmulti_content
						SET 
							title_advmulti = :title_advmulti,
							desc_advmulti = :desc_advmulti,
							url_advmulti = :url_advmulti,
							blank_advmulti = :blank_advmulti,
							published_advmulti = :published_advmulti
						WHERE id_advmulti_content = :id 
						AND id_lang = :id_lang';
				break;
			case 'order':
				$query = 'UPDATE mc_advmulti 
						SET order_advmulti = :order_advmulti
						WHERE id_advmulti = :id_advmulti';
				break;
            case 'icon':
                $query = 'UPDATE mc_advmulti 
						SET icon_advmulti = :icon_advmulti
						WHERE id_advmulti = :id';
                break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	protected function delete(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'advmulti':
				$query = 'DELETE FROM mc_advmulti WHERE id_advmulti IN('.$params['id'].')';
				$params = [];
				break;
			default:
				return false;
		}
		
		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
	}
}