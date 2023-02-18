<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\Addons\Departments\Profile;

use Tygh\Application;
use Tygh\Enum\ObjectStatuses;
use Tygh\Enum\SiteArea;
use Tygh\Languages\Languages;
use Tygh\Registry;

/**
 * Class Departments
 *
 * @package Departments
 */
class Departments
{
    /** @var array */
    protected $db = [];

    /** @var area */
    protected $area;

    /** @var lang_code */
    protected $lang_code;

    /**
     * Departments constructor
     *
     * @param Application $app
     * @param $area
     * @param $lang_code
     */
    public function __construct(Application $app, $area = AREA, $lang_code = CART_LANGUAGE)
    {
        $this->db = $app['db'];
        $this->area = $area;
        $this->lang_code = $lang_code;
    }

    /**
     * Get departments list
     *
     * @param array $params
     * @return array
     */
    public function getList($params)
    {
        $default_params = array(
            'page' => 1,
            'items_per_page' => 0,
            'lang_code' => $this->lang_code
        );

        $params = array_merge($default_params, $params);
        $cache_key = __FUNCTION__ . md5(serialize($params));

        Registry::registerCache(
            $cache_key,
            ['departments', 'department_descriptions'],
            Registry::cacheLevel('locale_auth'),
            true
        );

        $cache = Registry::get($cache_key);

        if (!empty($cache)) {
            $departments = $cache;
        } else {
            $fields = array(
                '?:departments.*',
                '?:department_descriptions.department',
                '?:department_descriptions.description',
            );

            $sortings = array(
                'timestamp' => '?:departments.timestamp',
                'name' => '?:department_descriptions.department',
                'status' => '?:departments.status',
            );

            $condition = $limit = $join = '';

            if (!empty($params['limit'])) {
                $limit = $this->db->quote(' LIMIT 0, ?i', $params['limit']);
            }

            $sorting = db_sort($params, $sortings, 'name', 'asc');

            if (!empty($params['item_ids'])) {
                $condition .= $this->db->quote(' AND ?:departments.department_id IN (?n)', explode(',', $params['item_ids']));
            }

            if (!empty($params['department_id'])) {
                $condition .= $this->db->quote(' AND ?:departments.department_id = ?i', $params['department_id']);
            }

            if (!empty($params['name'])) {
                $condition .= $this->db->quote(' AND ?:department_descriptions.department LIKE ?l', '%' . trim($params['name']) . '%');
            }

            if (!empty($params['director_id'])) {
                $condition .= $this->db->quote(' AND ?:departments.director_id = ?i', $params['director_id']);
            }

            if ($this->area == SiteArea::STOREFRONT) {
                $condition .= $this->db->quote(' AND ?:departments.status = ?s', ObjectStatuses::ACTIVE);
            } elseif (!empty($params['status'])) {
                $condition .= $this->db->quote(' AND ?:departments.status = ?s', $params['status']);
            }

            if (!empty($params['users'])) {
                $condition .= $this->db->quote(' AND ?:departments.users = ?s', $params['users']);
            }

            $join .= $this->db->quote(' LEFT JOIN ?:department_descriptions ON ?:department_descriptions.department_id = ?:departments.department_id AND ?:department_descriptions.lang_code = ?s', $this->lang_code);

            if (!empty($params['items_per_page'])) {
                $params['total_items'] = $this->db->getField("SELECT COUNT(*) FROM ?:departments $join WHERE 1 $condition");
                $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
            }

            $departments = $this->db->getHash(
                "SELECT ?p FROM ?:departments " .
                $join .
                "WHERE 1 ?p ?p ?p",
                'department_id', implode(', ', $fields), $condition, $sorting, $limit
            );

            $department_image_ids = array_keys($departments);
            $images = fn_get_image_pairs($department_image_ids, 'department', 'M', true, false, $this->lang_code);

            foreach ($departments as $department_id => $department) {
                $departments[$department_id]['main_pair'] = !empty($images[$department_id]) ? reset($images[$department_id]) : array();
            }

            if (!empty($departments)) {
                Registry::set($cache_key, $departments);
            }
        }

        return array($departments, $params);
    }

    /**
     * Get departments info by id
     *
     * @param $department_id
     * @return array
     */
    public function getById($department_id)
    {
        $department = [];
        if ($department_id) {
            list($departments) = $this->getList([
                'department_id' => $department_id,
                'items_per_page' => 1,

            ]);
            if ($departments) {
                $department = reset($departments);
            }
        }
        return $department;
    }

    /**
     * Update/Insert department
     *
     * @param $data
     * @param $department_id
     * @return int
     */
    public function upsert($data, $department_id = 0)
    {
        if (isset($data['timestamp'])) {
            $data['timestamp'] = fn_parse_date($data['timestamp']);
        }

        if ($department_id) {
            $this->db->query("UPDATE ?:departments SET ?u WHERE department_id = ?i", $data, $department_id);
            $this->db->query("UPDATE ?:department_descriptions SET ?u WHERE department_id = ?i AND lang_code = ?s", $data, $department_id, $this->lang_code);

        } else {
            $department_id = $data['department_id'] = $this->db->replaceInto('departments', $data);

            foreach (Languages::getAll() as $data['lang_code'] => $v) {
                $this->db->replaceInto('department_descriptions', $data);
            }
        }
        if ($department_id) {
            fn_attach_image_pairs('department', 'department', $department_id, $lang_code);
        }

        if (empty($data['users'])) {
            $data['users'] = '';
        }

        return $department_id;
    }

    /**
     * Delete department
     *
     * @param $department_id
     * @return bool
     */
    public function delete($department_id)
    {
        $result = false;
        if ($department_id) {
            $result = $this->db->query('DELETE FROM ?:departments WHERE department_id = ?i', $department_id);
            $this->db->query('DELETE FROM ?:department_descriptions WHERE department_id = ?i', $department_id);
        }

        return $result;
    }
}