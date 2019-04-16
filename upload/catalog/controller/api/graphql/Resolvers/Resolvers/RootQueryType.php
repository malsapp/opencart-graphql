<?php
namespace GQL;

require_once realpath (__DIR__ . '/../Helpers.php');

trait RootQueryTypeResolver {

    public function RootQueryType_product ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        return $ctx->model_catalog_product->getProduct ($args['id']);
    }

    public function RootQueryType_products ($root, $args, &$ctx) {

        $key_mapper = [
            'name' => 'pd.name',
            'price' => 'p.price',
            'rating' => 'rating',
            'quantity' => 'p.model',
            'date_added' => 'p.date_added',
            'sort_order' => 'p.sort_order'
        ];

        $ctx->load->model ('catalog/product');
        $args['sort'] = in_array($key_mapper[$args['sort']], array_keys($key_mapper)) ? $key_mapper[$args['sort']] : '';
        return $ctx->model_catalog_product->getProducts ($args);
    }

    public function RootQueryType_compareProducts ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        $res = [];
        foreach ($args['ids'] as $id) {
            $product = $ctx->model_catalog_product->getProduct ($id);
            $res[] = $product;
        }
        return $res;
    }

    public function RootQueryType_bestsellerProducts ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        $limit = isset ($args['limit'])? $args['limit'] : 20;
        return $ctx->model_catalog_product->getBestSellerProducts ($limit);
    }

    public function RootQueryType_relatedProducts ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        return $ctx->model_catalog_product->getProductRelated ($args['id']);
    }

    public function RootQueryType_latestProducts ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        $limit = isset ($args['limit'])? $args['limit'] : 20;
        return $ctx->model_catalog_product->getLatestProducts ($limit);
    }

    public function RootQueryType_popularProducts ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        $limit = isset ($args['limit'])? $args['limit'] : 20;
        return $ctx->model_catalog_product->getPopularProducts ($limit);
    }

    public function RootQueryType_productSpecials ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/product');
        return $ctx->model_catalog_product->getProductSpecials ($args);
    }

    public function RootQueryType_menu ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/information');
        $informations = $ctx->model_catalog_information->getInformations();
        $res = [];

        foreach($informations as $key => $information) {
            $res[] = array(
                'item_id' => $information['information_id'] . ':' . -3,
                'object_id' => $information['information_id'] . ':' . -3,
                'object_type' => 'page',
                'url' => '',
                'title' => $information['title'],
                'order' => $key
            );
        }

        $res[] = array(
            'item_id' => '-1',
            'object_id' => '-1',
            'object_type' => 'category',
            'url' => '',
            'title' => 'الاسئلة الشائعة',
            'order' => count($res),
        );

        $res[] = array(
            'item_id' => '-2',
            'object_id' => '-2',
            'object_type' => 'category',
            'url' => '',
            'title' => 'الأخبار',
            'order' => count($res),
        );

        return $res;
    }

    public function RootQueryType_reviews ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/review');
        $start = isset ($args['start'])? $args['start'] : 0;
        $limit = isset ($args['limit'])? $args['limit'] : 20;
        return $ctx->model_catalog_review->getReviewsByProductId ($args['product_id'], $start, $limit);
    }

    public function RootQueryType_post ($root, $args, &$ctx) {
        $post_id = explode(':', $args['id'])[0];
        $category_id = explode(':', $args['id'])[1];

        if ($category_id == '-1') {
            $ctx->load->model ('catalog/faq');
            $faq = $ctx->model_catalog_faq->getFaq($post_id);

            if (isset($faq)) {
                return array(
                    'id' => $faq['faq_id'] . ':' . -1,
                    'title' => $faq['title'],
                    'content' => $faq['description'],
                    'date' => $faq['date_added']
                );
            }
        }

        else if($category_id == '-2') {
            $ctx->load->model ('extension/news');
            $news = $ctx->model_extension_news->getNews($post_id);

            if (isset($news)) {
                return array(
                    'id' => $news['news_id'] . ':' . -2,
                    'title' => $news['title'],
                    'content' => $news['description'],
                    'excerpt' => $news['short_description'],
                    'date' => $news['date_added']
                );
            }
        }

        else {
            $ctx->load->model ('catalog/information');
            $information = $ctx->model_catalog_information->getInformation($post_id);

            if (isset($information)) {
                return array(
                    'id' => $information['information_id'] . ':' . -3,
                    'title' => $information['title'],
                    'content' => $information['description']
                );
            }
        }
    }

    public function PostsCategoryType_posts ($root, $args, &$ctx) {
        return $root['posts'];
    }

    public function RootQueryType_posts_category ($root, $args, &$ctx) {
        if ($args['id'] == '-1') {
            $ctx->load->model ('catalog/faq');
            $faqs = $ctx->model_catalog_faq->getFaqs();

            return array(
                'id' => '-1',
                'name' => 'الاسئلة الشائعة',
                'count' => count($faqs),
                'posts' => array_map(function ($faq) {
                    return array(
                        'id' => $faq['faq_id'] . ':' . -1,
                        'title' => $faq['title'],
                        'content' => $faq['description'],
                        'date' => $faq['date_added']
                    );
                }, $faqs)
            );

        }
        else if ($args['id'] == '-2') {
            $ctx->load->model ('extension/news');
            $news = $ctx->model_extension_news->getAllNews();

            return array(
                'id' => '-2',
                'name' => 'الأخبار',
                'count' => count($news),
                'posts' => array_map(function ($news_item) {
                    return array(
                        'id' => $news_item['news_id'] . ':' . -2,
                        'title' => $news_item['title'],
                        'content' => $news_item['description'],
                        'excerpt' => $news_item['short_description'],
                        'date' => $news_item['date_added']
                    );
                }, $news)
            );
        }
        else {
            $ctx->load->model ('catalog/information');
            $informations = $ctx->model_catalog_information->getInformations();

            return array(
                'id' => '-3',
                'name' => 'المعلومات',
                'count' => count($informations),
                'posts' => array_map(function ($information) {
                    return array(
                        'id' => $information['information_id'] . ':' . -3,
                        'title' => $information['title'],
                        'content' => $information['description']
                    );
                }, $informations)
            );
        }
    }

    public function RootQueryType_categories ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/category');
        $ctx->load->model ('catalog/product');
        $parent = isset ($args['parent']) ? $args['parent'] : 0;
        $cats = $ctx->model_catalog_category->getCategories ($parent);
        foreach ($cats as &$cat) {
            $cat['products_count'] = $ctx->model_catalog_product->getTotalProducts ([
                'filter_category_id' => $cat['category_id'],
                'filter_sub_category' => true
            ]);
        }
        return $cats;
    }

    public function RootQueryType_category ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/category');
        $ctx->load->model ('catalog/product');
        $cat = $ctx->model_catalog_category->getCategory ($args['id']);
        $cat['products_count'] = $ctx->model_catalog_product->getTotalProducts ([
            'filter_category_id' => $args['id'],
            'filter_sub_category' => true
        ]);
        return $cat;
    }

    public function RootQueryType_manufacturers ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/manufacturer');
        return $ctx->model_catalog_manufacturer->getManufacturers ();
    }

    public function RootQueryType_manufacturer ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/manufacturer');
        return $ctx->model_catalog_manufacturer->getManufacturer ($args['id']);
    }

    public function RootQueryType_informations ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/information');
        return $ctx->model_catalog_information->getInformations ();
    }

    public function RootQueryType_information ($root, $args, &$ctx) {
        $ctx->load->model ('catalog/information');
        return $ctx->model_catalog_information->getInformation ($args['id']);
    }

    public function RootQueryType_session ($root, $args, &$ctx) {
        $id = isset ($ctx->sess) ? $ctx->sess : null;
        $id = isset ($args['id']) ? $args['id'] : $id;
        return [
            'id' => getSession ($ctx, $id)
        ];
    }

    public function RootQueryType_cart ($root, $args, &$ctx) {
        return getCartType ($ctx);
    }

    public function RootQueryType_address ($root, $args, &$ctx) {
        if (!$ctx->customer->isLogged ()) return null;
        $ctx->load->model ('account/address');
        return $ctx->model_account_address->getAddress($args['id']);
    }

    public function RootQueryType_addresses ($root, $args, &$ctx) {
        if (!$ctx->customer->isLogged ()) return null;
        $ctx->load->model ('account/address');
        return $ctx->model_account_address->getAddresses();
    }

    public function RootQueryType_customerGroup ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer_group');
        return $ctx->model_account_customer_group->getCustomerGroup ($args['id']);
    }

    public function RootQueryType_customerGroups ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer_group');
        return $ctx->model_account_customer_group->getCustomerGroups ();
    }

    public function RootQueryType_download ($root, $args, &$ctx) {
        $ctx->load->model ('account/download');
        return $ctx->model_account_download->getDownload ($args['id']);
    }

    public function RootQueryType_downloads ($root, $args, &$ctx) {
        $ctx->load->model ('account/download');
        return $ctx->model_account_download->getDownloads ($args);
    }

    public function RootQueryType_language ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/language');
        if (!isset($args['id'])) $id = $ctx->config->get ('config_language_id');
        else $id = $args['id'];
        return $ctx->model_localisation_language->getLanguage ($id);
    }

    public function RootQueryType_languages ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/language');
        return $ctx->model_localisation_language->getLanguages ($args);
    }

    public function RootQueryType_zone ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/zone');
        return $ctx->model_localisation_zone->getZone ($args['id']);
    }

    public function RootQueryType_zones ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/zone');
        return $ctx->model_localisation_zone->getZonesByCountryId ($args['country_id']);
    }

    public function RootQueryType_country ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/country');
        return $ctx->model_localisation_country->getCountry ($args['id']);
    }

    public function RootQueryType_countries ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/country');
        return $ctx->model_localisation_country->getCountries ($args);
    }

    public function RootQueryType_currency ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/currency');
        if (!isset ($args['code'])) {
            return $ctx->model_localisation_currency->getCurrencyByCode ($ctx->session->data['currency']);
        }
        return $ctx->model_localisation_currency->getCurrencyByCode ($args['code']);
    }

    public function RootQueryType_currencies ($root, $args, &$ctx) {
        $ctx->load->model ('localisation/currency');
        return $ctx->model_localisation_currency->getCurrencies ($args);
    }

    public function RootQueryType_banners ($root, $args, &$ctx) {
        $ctx->load->model ('design/banner');
        $ctx->load->model ('design/layout');
        $ctx->load->model ('extension/module');

        $layout_id = $ctx->model_design_layout->getLayout($args['layout']);
        $modules = $ctx->model_design_layout->getLayoutModules($layout_id, 'content_top');

        foreach ($modules as $module) {
            $parts = explode ('.', $module['code']);
            if ($parts[0] == 'banner') {
                $m = $ctx->model_extension_module->getModule($parts[1]);
                return $ctx->model_design_banner->getBanner ($m['banner_id']);
            }
        }

        return [];
    }

    public function RootQueryType_rewards ($root, $args, &$ctx) {
        $ctx->load->model ('account/reward');
        return $ctx->model_account_reward->getRewards ($args);
    }

    public function RootQueryType_transactions ($root, $args, &$ctx) {
        $ctx->load->model ('account/transaction');
        return $ctx->model_account_transaction->getTransactions ($args);
    }

    public function RootQueryType_wishlist ($root, $args, &$ctx) {
        $products = array ();
        $ids = array ();

        if (!$ctx->customer->isLogged () && isset ($ctx->session->data['wishlist']))
            $ids = $ctx->session->data['wishlist'];

        if ($ctx->customer->isLogged ()) {
            $ctx->load->model ('account/wishlist');
            $queryRes = $ctx->model_account_wishlist->getWishlist ();
            foreach ($queryRes as $row) $ids[] = $row['product_id'];
        }

        if (empty ($ids)) return null;

        $ctx->load->model ('catalog/product');
        foreach ($ids as $id) $products[] = $ctx->model_catalog_product->getProduct ($id);
        return $products;
    }

    public function RootQueryType_order ($root, $args, &$ctx) {
        $ctx->load->model ('account/order');
        return $ctx->model_account_order->getOrder ($args['id']);
    }

    public function RootQueryType_orders ($root, $args, &$ctx) {
        $ctx->load->model ('account/order');
        $ctx->load->model ('checkout/order');
        $start = isset ($args['start']) ? $args['start'] : 0;
        $limit = isset ($args['limit']) ? $args['limit'] : 20;
        $orders = $ctx->model_account_order->getOrders ($start, $limit);
        $res = array();
        foreach ($orders as $order) {
            $res[] = $ctx->model_checkout_order->getOrder ($order['order_id']);
        }
        return $res;
    }

    public function RootQueryType_orderProduct ($root, $args, &$ctx) {
        $ctx->load->model ('account/order');
        return $ctx->model_account_order->getOrderProduct ($args['order_id'], $args['order_product_id']);
    }

    public function RootQueryType_orderProducts ($root, $args, &$ctx) {
        $ctx->load->model ('account/order');
        return $ctx->model_account_order->getOrderProducts ($args['product_id']);
    }

    public function RootQueryType_orderOptions ($root, $args, &$ctx) {
        $ctx->load->model ('account/order');
        return $ctx->model_account_order->getOrderOptions ($args['order_id'], $args['order_product_id']);
    }

    public function RootQueryType_orderVouchers ($root, $args, &$ctx) {
        $ctx->load->model ('account/order');
        return $ctx->model_account_order->getOrderVouchers ($args['order_id']);
    }

    public function RootQueryType_getCustomField ($root, $args, &$ctx) { return null; }

    public function RootQueryType_getCustomFields ($root, $args, &$ctx) { return null; }

    public function RootQueryType_paymentAddress ($root, $args, &$ctx) {
        return getAddress ($ctx, 'payment_address');
    }

    public function RootQueryType_shippingAddress ($root, $args, &$ctx) {
        return getAddress ($ctx, 'shipping_address');
    }

    public function RootQueryType_paymentMethods ($root, $args, &$ctx) {
        return null;
    }

    public function RootQueryType_shippingMethods ($root, $args, &$ctx) {
        $res = getShippingMethods ($ctx);
        foreach ($res as &$item) {
            $item['quote'] = reset($item['quote']);
        }
        return $res;
    }

    public function RootQueryType_return ($root, $args, &$ctx) {
        $ctx->load->model ('account/return');
        return $ctx->model_account_return->getReturn ($args['id']);
    }

    public function RootQueryType_returns ($root, $args, &$ctx) {
        $ctx->load->model ('account/return');
        return $ctx->model_account_return->getReturns ($args);
    }

    public function RootQueryType_ReturnHistories ($root, $args, &$ctx) {
        $ctx->load->model ('account/return');
        return $ctx->model_account_return->getReturnHistories ($args['return_id']);
    }

    public function RootQueryType_loggedIn ($root, $args, &$ctx) {
        if ($ctx->customer->isLogged ()) {
            $ctx->load->model ('account/customer');
            return $ctx->model_account_customer->getCustomer ($ctx->customer->getId ());
        }
        return null;
    }

    public function RootQueryType_customer ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer');
        return $ctx->model_account_customer->getCustomer ($args['id']);
    }

    public function RootQueryType_customerByEmail ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer');
        return $ctx->model_account_customer->getCustomerByEmail ($args['email']);
    }

    public function RootQueryType_customerByCode ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer');
        return $ctx->model_account_customer->getCustomerByCode ($args['code']);
    }

    public function RootQueryType_customerByToken ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer');
        return $ctx->model_account_customer->getCustomerByToken ($args['token']);
    }

    public function RootQueryType_Ips ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer');
        return $ctx->model_account_customer->getIps ($args['id']);
    }

    public function RootQueryType_LoginAttempts ($root, $args, &$ctx) {
        $ctx->load->model ('account/customer');
        return $ctx->model_account_customer->getLoginAttempts ($args['email']);
    }

    public function RootQueryType_faq($root, $args, &$ctx){
        $ctx->load->model ('catalog/faq');
        return $ctx->model_catalog_faq->getFaq ($args['id']);
    }

    public function RootQueryType_faqs($root, $args, &$ctx){
         $ctx->load->model ('catalog/faq');
        return $ctx->model_catalog_faq->getFaqs ();
    }

    public function RootQueryType_faqCategory($root, $args, &$ctx){
        $ctx->load->model ('catalog/faqcategory');
        return $ctx->model_catalog_faqcategory->getFaqCategory ($args['id']);
    }

    public function RootQueryType_faqCategories($root, $args, &$ctx){
        $ctx->load->model ('catalog/faqcategory');
        return $ctx->model_catalog_faqcategory->getFaqCategories ();
    }

    public function RootQueryType_news($root, $args, &$ctx){
        $ctx->load->model ('extension/news');
        return $ctx->model_extension_news->getNews ($args['id']);
    }

    public function RootQueryType_allnews($root, $args, &$ctx){
        $ctx->load->model ('extension/news');
        return $ctx->model_extension_news->getAllNews (array());
    }

    public function RootQueryType_daLoggedIn($root, $args, &$ctx){
        if (!isset ($ctx->session->data['current_agent'])) return null;
        $ctx->load->model ('extension/delivery_agent');
        return $ctx->model_extension_delivery_agent->getDeliveryAgent ($ctx->session->data['current_agent']);
    }

    public function RootQueryType_daOrders ($root, $args, &$ctx){
        if (!isset ($ctx->session->data['current_agent'])) return array();
        $ctx->load->model ('extension/delivery_agent');
        $agent_id = $ctx->session->data['current_agent'];

        return $ctx->model_extension_delivery_agent->getOrders($agent_id, $args);
    }

    public function RootQueryType_daOrder ($root, $args, &$ctx) {
        if (!isset ($ctx->session->data['current_agent'])) return null;
        $ctx->load->model ('extension/delivery_agent');
        $agent_id = $ctx->session->data['current_agent'];

        return $ctx->model_extension_delivery_agent->getOrder($agent_id, $args['id']);
    }

    public function RootQueryType_orderStatuses ($root, $args, &$ctx) {
        return getOrderStatuses ($ctx);
    }

    public function RootQueryType_productVariationPrice ($root, $args, &$ctx) {
        return variationData ($args, $ctx);
    }

    public function RootQueryType_productVariationData ($root, $args, &$ctx) {
        return variationData ($args, $ctx);
    }

    public function RootQueryType_siteInfo ($root, $args, &$ctx) {
        if ($args['key'] != GQ_INTERNAL_KEY) return null;
        $mysqlversion = $ctx->db->query("select version();");

        return [
            'phpversion' => phpversion(),
            'phpinfo' => pinfo(),
            'mysqlversion' => $mysqlversion->rows[0]['version()'],
            'pluginversion' => GQ_PLUGIN_VERSION
        ];
    }

    // public function RootQueryType_photo($root, $args, &$ctx){
    //     $ctx->load->model ('extension/photo_gallery');
    //     return $ctx->model_catalog_faqcategory->getPhoto ($args['id']);
    // }

    // public function RootQueryType_photos($root, $args, &$ctx){
    //     $ctx->load->model ('extension/photo_gallery');
    //     return $ctx->model_catalog_faqcategory->getAllPhotos($args['id']);
    // }

}
?>
